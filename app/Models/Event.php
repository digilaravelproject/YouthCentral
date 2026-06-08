<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'long_description',
        'venue',
        'state_id',
        'city_id',
        'area_id',
        'latitude',
        'longitude',
        'start_date',
        'end_date',
        'registration_amount',
        'seat_limit',
        'status',
        'created_by',
        'banner_image',
        'featured_banner',
        'additional_images',
        'event_schedule',
        'terms_and_conditions',
        'approved_by',
        'rejection_reason',
        'category'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'additional_images' => 'array',
        'event_schedule' => 'array',
        'registration_amount' => 'decimal:2',
        'seat_limit' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    public function banners(): HasMany
    {
        return $this->hasMany(EventBanner::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function primaryBanner(): HasMany
    {
        return $this->banners()->where('is_primary', true);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get the state that the event belongs to.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the city that the event belongs to.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the area that the event belongs to.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function approve(int $adminId): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $adminId,
            'rejection_reason' => null
        ]);
    }

    public function reject(int $adminId, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $adminId,
            'rejection_reason' => $reason
        ]);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Scope to filter events within a certain distance from coordinates.
     * Uses Haversine formula for distance calculation.
     * Radius is fixed at 5 km for all queries.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $latitude
     * @param float $longitude
     * @param int $radiusKm (ignored, fixed at 5 km)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithinDistance($query, $latitude, $longitude, $radiusKm = null)
    {
        // Check if columns exist before using them
        if (!Schema::hasColumn('events', 'latitude') || !Schema::hasColumn('events', 'longitude')) {
            return $query; // Return original query if columns don't exist
        }

        $earthRadius = 6371; // Earth's radius in kilometers
        $fixedRadius = 5; // Fixed 5 km radius regardless of input

        return $query->whereRaw("
            ($earthRadius * acos(
                cos(radians(?)) * 
                cos(radians(events.latitude)) * 
                cos(radians(events.longitude) - radians(?)) + 
                sin(radians(?)) * 
                sin(radians(events.latitude))
            )) <= ?
        ", [$latitude, $longitude, $latitude, $fixedRadius])
        ->whereNotNull('events.latitude')
        ->whereNotNull('events.longitude');
    }

    /**
     * Scope to get events with location data for mapping.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithLocationData($query)
    {
        // Check if columns exist before using them
        if (!Schema::hasColumn('events', 'latitude') || !Schema::hasColumn('events', 'longitude')) {
            return $query->whereRaw('1 = 0'); // Return empty result if columns don't exist
        }

        return $query->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->where('latitude', '!=', 0)
                    ->where('longitude', '!=', 0);
    }

    /**
     * Scope to sort events by location priority based on user's location.
     * Priority: 0 (same area) > 1 (same city) > 2 (same state) > 3 (other)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $userLocation - should contain area_id, city_id, state_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByLocationPriority($query, $userLocation = null)
    {
        if (!$userLocation) {
            return $query->orderBy('start_date', 'asc');
        }

        $userAreaId = $userLocation['area_id'] ?? null;
        $userCityId = $userLocation['city_id'] ?? null;
        $userStateId = $userLocation['state_id'] ?? null;

        // Build the priority case statement
        $priorityCase = "CASE ";
        
        // Priority 1: Same area
        if ($userAreaId) {
            $priorityCase .= "WHEN events.area_id = {$userAreaId} THEN 1 ";
        }
        
        // Priority 2: Same city
        if ($userCityId) {
            $priorityCase .= "WHEN events.city_id = {$userCityId} THEN 2 ";
        }
        
        // Priority 3: Same state
        if ($userStateId) {
            $priorityCase .= "WHEN events.state_id = {$userStateId} THEN 3 ";
        }
        
        // Priority 4: Everything else
        $priorityCase .= "ELSE 4 END as location_priority";

        return $query
            ->selectRaw("events.*, {$priorityCase}")
            ->orderByRaw('location_priority ASC')
            ->orderBy('events.start_date', 'asc'); // Secondary sort by start date
    }

    /**
     * Scope to sort events by actual distance (GPS coordinates) when available,
     * with fallback to hierarchical location priority for events without coordinates.
     * This is the PROPER way to sort events by proximity to user's live location.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $userLocation - should contain latitude, longitude, area_id, city_id, state_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDistancePriority($query, $userLocation = null)
    {
        if (!$userLocation) {
            return $query->orderBy('start_date', 'asc');
        }

        $userLat = $userLocation['latitude'] ?? null;
        $userLng = $userLocation['longitude'] ?? null;
        $userAreaId = $userLocation['area_id'] ?? null;
        $userCityId = $userLocation['city_id'] ?? null;
        $userStateId = $userLocation['state_id'] ?? null;

        // If we have user's GPS coordinates, use distance-based sorting
        if ($userLat && $userLng) {
            // Check if coordinate columns exist before using GPS-based sorting
            if (!Schema::hasColumn('events', 'latitude') || !Schema::hasColumn('events', 'longitude')) {
                // Fallback to hierarchical sorting if coordinate columns don't exist
                return $this->scopeByLocationPriority($query, $userLocation);
            }

            $earthRadius = 6371; // Earth's radius in kilometers

            // Build the priority case statement for events without coordinates
            $priorityParts = [];
            $bindings = [];

            // Priority 1: Same area_id
            if ($userAreaId) {
                $priorityParts[] = "WHEN events.area_id = ? THEN 1";
                $bindings[] = $userAreaId;
            }
            
            // Priority 2: Same city_id
            if ($userCityId) {
                $priorityParts[] = "WHEN events.city_id = ? THEN 2";
                $bindings[] = $userCityId;
            }
            
            // Priority 3: Same state_id
            if ($userStateId) {
                $priorityParts[] = "WHEN events.state_id = ? THEN 3";
                $bindings[] = $userStateId;
            }
            
            $priorityCase = !empty($priorityParts) ? 
                "CASE " . implode(" ", $priorityParts) . " ELSE 4 END" : "4";

            return $query
                ->selectRaw("
                    events.*, 
                    CASE 
                        WHEN events.latitude IS NOT NULL AND events.longitude IS NOT NULL THEN
                            ($earthRadius * acos(
                                cos(radians(?)) * 
                                cos(radians(events.latitude)) * 
                                cos(radians(events.longitude) - radians(?)) + 
                                sin(radians(?)) * 
                                sin(radians(events.latitude))
                            ))
                        ELSE NULL 
                    END AS actual_distance,
                    CASE 
                        WHEN events.latitude IS NOT NULL AND events.longitude IS NOT NULL THEN 0
                        ELSE {$priorityCase}
                    END AS location_priority
                ", array_merge([$userLat, $userLng, $userLat], $bindings))
                ->orderByRaw('
                    CASE 
                        WHEN events.latitude IS NOT NULL AND events.longitude IS NOT NULL THEN actual_distance
                        ELSE location_priority + 1000
                    END ASC
                ')
                ->orderBy('events.start_date', 'asc'); // Tertiary sort by start date
        }

        // Fallback to hierarchical sorting if no GPS coordinates available
        return $this->scopeByLocationPriority($query, $userLocation);
    }

    /**
     * Boot the model and automatically generate slug on creation/update.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = $model->generateSlug();
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('title')) {
                $model->slug = $model->generateSlug();
            }
        });
    }

    /**
     * Generate a unique slug from the event title.
     * Removes all spaces and special characters, keeps only alphanumeric characters.
     * 
     * @return string
     */
    public function generateSlug()
    {
        $slug = $this->title;
        // Remove all spaces and special characters, keep only alphanumeric
        $slug = preg_replace('/[^a-zA-Z0-9]/', '', $slug);
        // Convert to lowercase
        $slug = strtolower($slug);
        
        // Ensure uniqueness by appending a number if necessary
        $originalSlug = $slug;
        $count = 1;
        while (self::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $originalSlug . $count;
            $count++;
        }
        
        return $slug;
    }

    /**
     * Get the route key name.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Resolve route binding - include soft-deleted records for model binding.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Convert 202616 to 2027 for backward compatibility display
        $originalValue = $value;
        $modifiedValue = str_replace('2026', '2027', $value);
        
        return $this->withTrashed()
            ->where($field ?? $this->getRouteKeyName(), $modifiedValue)
            ->first();
    }
} 