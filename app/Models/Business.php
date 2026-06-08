<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Business extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'business_name',
        'slug',
        'phone',
        'email',
        'website',
        'street_address',
        'latitude',
        'longitude',
        'zipcode',
        'subcategory_id',
        'area_id',
        'claimed_by',
        'status',
        'logo_path',
        'description',
        'whatsapp_number',
        'facebook_link',
        'instagram_link',
        'twitter_link',
        'pinterest_link',
        // Business Hours
        'monday_open', 'monday_close', 'monday_closed',
        'tuesday_open', 'tuesday_close', 'tuesday_closed',
        'wednesday_open', 'wednesday_close', 'wednesday_closed',
        'thursday_open', 'thursday_close', 'thursday_closed',
        'friday_open', 'friday_close', 'friday_closed',
        'saturday_open', 'saturday_close', 'saturday_closed',
        'sunday_open', 'sunday_close', 'sunday_closed',
    ];
    
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($business) {
            if (empty($business->slug)) {
                $business->slug = Str::slug($business->business_name);
            }
        });

        static::updating(function ($business) {
            if ($business->isDirty('business_name') && empty($business->slug)) {
                $business->slug = Str::slug($business->business_name);
            }
        });
    }
    
    /**
     * Get the subcategory that the business belongs to.
     */
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
    
    /**
     * Get the area that the business belongs to.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    
    /**
     * Get the user who claimed the business.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'claimed_by');
    }
    
    /**
     * Scope a query to only include businesses from a specific subcategory.
     */
    public function scopeInSubcategory($query, $subcategoryId)
    {
        return $query->where('subcategory_id', $subcategoryId);
    }
    
    /**
     * Scope a query to only include businesses from a specific area.
     */
    public function scopeInArea($query, $areaId)
    {
        return $query->where('area_id', $areaId);
    }
    
    /**
     * Scope a query to search businesses by name.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('business_name', 'like', "%{$search}%");
    }
    
    /**
     * Scope to filter businesses within a certain distance from coordinates.
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
        $earthRadius = 6371; // Earth's radius in kilometers
        $fixedRadius = 5; // Fixed 5 km radius regardless of input

        return $query->whereRaw("
            ($earthRadius * acos(
                cos(radians(?)) * 
                cos(radians(businesses.latitude)) * 
                cos(radians(businesses.longitude) - radians(?)) + 
                sin(radians(?)) * 
                sin(radians(businesses.latitude))
            )) <= ?
        ", [$latitude, $longitude, $latitude, $fixedRadius])
        ->whereNotNull('businesses.latitude')
        ->whereNotNull('businesses.longitude');
    }

    /**
     * Scope to get businesses with location data for mapping.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithLocationData($query)
    {
        return $query->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->where('latitude', '!=', 0)
                    ->where('longitude', '!=', 0);
    }

    /**
     * Scope to sort businesses by location priority based on user's location.
     * Priority: 0 (same area) > 1 (same city) > 2 (same state) > 3 (other)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $userLocation - should contain area_id, city_id, state_id, zipcode
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByLocationPriority($query, $userLocation = null)
    {
        if (!$userLocation) {
            return $query->orderBy('business_name', 'asc');
        }

        $userAreaId = $userLocation['area_id'] ?? null;
        $userCityId = $userLocation['city_id'] ?? null;
        $userStateId = $userLocation['state_id'] ?? null;
        $userZipcode = $userLocation['zipcode'] ?? null;

        // Build the priority case statement
        $priorityCase = "CASE ";
        
        // Priority 0: Same zipcode (highest priority)
        if ($userZipcode) {
            $priorityCase .= "WHEN businesses.zipcode = '{$userZipcode}' THEN 0 ";
        }
        
        // Priority 1: Same area
        if ($userAreaId) {
            $priorityCase .= "WHEN businesses.area_id = {$userAreaId} THEN 1 ";
        }
        
        // Priority 2: Same city (via area relationship)
        if ($userCityId) {
            $priorityCase .= "WHEN areas.city_id = {$userCityId} THEN 2 ";
        }
        
        // Priority 3: Same state (via area->city relationship)
        if ($userStateId) {
            $priorityCase .= "WHEN cities.state_id = {$userStateId} THEN 3 ";
        }
        
        // Priority 4: Everything else
        $priorityCase .= "ELSE 4 END as location_priority";

        return $query
            ->leftJoin('areas', 'businesses.area_id', '=', 'areas.id')
            ->leftJoin('cities', 'areas.city_id', '=', 'cities.id')
            ->selectRaw("businesses.*, {$priorityCase}")
            ->orderByRaw('location_priority ASC')
            ->orderBy('business_name', 'asc'); // Secondary sort by name
    }

    /**
     * Scope to sort businesses by zipcode proximity and location priority.
     * This method includes distance calculation for businesses within same zipcode.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $userLocation - should contain latitude, longitude, zipcode, area_id, city_id, state_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByZipcodePriority($query, $userLocation = null)
    {
        if (!$userLocation) {
            return $query->orderBy('business_name', 'asc');
        }

        $userLat = $userLocation['latitude'] ?? null;
        $userLng = $userLocation['longitude'] ?? null;
        $userZipcode = $userLocation['zipcode'] ?? null;
        $userAreaId = $userLocation['area_id'] ?? null;
        $userCityId = $userLocation['city_id'] ?? null;
        $userStateId = $userLocation['state_id'] ?? null;

        $priorityParts = [];
        $bindings = [];

        // Priority 0: Business's Area is associated with the User's Zipcode
        if ($userZipcode) {
            $priorityParts[] = "WHEN EXISTS (SELECT 1 FROM area_zipcode az JOIN zipcodes z ON az.zipcode_id = z.id WHERE az.area_id = businesses.area_id AND z.code = ?) THEN 0";
            $bindings[] = $userZipcode;
        }
        
        // Priority 1: Same area_id
        if ($userAreaId) {
            $priorityParts[] = "WHEN businesses.area_id = ? THEN 1";
            $bindings[] = $userAreaId;
        }
        
        // Priority 2: Same city_id (via business's area)
        // The leftJoin on 'areas' later in the query makes areas.city_id available.
        if ($userCityId) {
            $priorityParts[] = "WHEN areas.city_id = ? THEN 2";
            $bindings[] = $userCityId;
        }
        
        // Priority 3: Same state_id (via business's area's city)
        // The leftJoin on 'cities' later in the query makes cities.state_id available.
        if ($userStateId) {
            $priorityParts[] = "WHEN cities.state_id = ? THEN 3";
            $bindings[] = $userStateId;
        }
        
        if (empty($priorityParts)) {
            // No location criteria to sort by, default to alphabetical
            return $query->orderBy('business_name', 'asc');
        }

        $priorityCase = "CASE " . implode(" ", $priorityParts) . " ELSE 4 END AS location_priority";

        return $query
            ->leftJoin('areas', 'businesses.area_id', '=', 'areas.id')
            ->leftJoin('cities', 'areas.city_id', '=', 'cities.id')
            ->selectRaw("businesses.*, {$priorityCase}", $bindings)
            ->orderByRaw('location_priority ASC')
            ->orderBy('businesses.business_name', 'asc'); // Secondary sort by name
    }
    
    /**
     * Scope to sort businesses by actual distance (GPS coordinates) when available,
     * with fallback to hierarchical location priority for businesses without coordinates.
     * This is the PROPER way to sort businesses by proximity to user's live location.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $userLocation - should contain latitude, longitude, zipcode, area_id, city_id, state_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDistancePriority($query, $userLocation = null)
    {
        if (!$userLocation) {
            return $query->orderBy('business_name', 'asc');
        }

        $userLat = $userLocation['latitude'] ?? null;
        $userLng = $userLocation['longitude'] ?? null;
        $userZipcode = $userLocation['zipcode'] ?? null;
        $userAreaId = $userLocation['area_id'] ?? null;
        $userCityId = $userLocation['city_id'] ?? null;
        $userStateId = $userLocation['state_id'] ?? null;

        // If we have user's GPS coordinates, use distance-based sorting
        if ($userLat && $userLng) {
            $earthRadius = 6371; // Earth's radius in kilometers

            // Build the priority case statement for businesses without coordinates
            $priorityParts = [];
            $bindings = [];

            // Priority 0: Business's Area is associated with the User's Zipcode
            if ($userZipcode) {
                $priorityParts[] = "WHEN EXISTS (SELECT 1 FROM area_zipcode az JOIN zipcodes z ON az.zipcode_id = z.id WHERE az.area_id = businesses.area_id AND z.code = ?) THEN 0";
                $bindings[] = $userZipcode;
            }
            
            // Priority 1: Same area_id
            if ($userAreaId) {
                $priorityParts[] = "WHEN businesses.area_id = ? THEN 1";
                $bindings[] = $userAreaId;
            }
            
            // Priority 2: Same city_id (via business's area)
            if ($userCityId) {
                $priorityParts[] = "WHEN areas.city_id = ? THEN 2";
                $bindings[] = $userCityId;
            }
            
            // Priority 3: Same state_id (via business's area's city)
            if ($userStateId) {
                $priorityParts[] = "WHEN cities.state_id = ? THEN 3";
                $bindings[] = $userStateId;
            }
            
            $priorityCase = !empty($priorityParts) ? 
                "CASE " . implode(" ", $priorityParts) . " ELSE 4 END" : "4";

            return $query
                ->leftJoin('areas', 'businesses.area_id', '=', 'areas.id')
                ->leftJoin('cities', 'areas.city_id', '=', 'cities.id')
                ->selectRaw("
                    businesses.*, 
                    CASE 
                        WHEN businesses.latitude IS NOT NULL AND businesses.longitude IS NOT NULL THEN
                            ($earthRadius * acos(
                                cos(radians(?)) * 
                                cos(radians(businesses.latitude)) * 
                                cos(radians(businesses.longitude) - radians(?)) + 
                                sin(radians(?)) * 
                                sin(radians(businesses.latitude))
                            ))
                        ELSE NULL 
                    END AS actual_distance,
                    CASE 
                        WHEN businesses.latitude IS NOT NULL AND businesses.longitude IS NOT NULL THEN 0
                        ELSE {$priorityCase}
                    END AS location_priority
                ", array_merge([$userLat, $userLng, $userLat], $bindings))
                ->orderByRaw('
                    CASE 
                        WHEN businesses.latitude IS NOT NULL AND businesses.longitude IS NOT NULL THEN actual_distance
                        ELSE location_priority + 1000
                    END ASC
                ')
                ->orderBy('businesses.business_name', 'asc'); // Tertiary sort by name
        }

        // Fallback to hierarchical sorting if no GPS coordinates available
        return $this->scopeByZipcodePriority($query, $userLocation);
    }
    
    /**
     * Get the category through the subcategory.
     */
    public function category()
    {
        return $this->hasOneThrough(
            Category::class, 
            Subcategory::class,
            'id', // Foreign key on subcategories table
            'id', // Foreign key on categories table
            'subcategory_id', // Local key on businesses table
            'category_id' // Local key on subcategories table
        );
    }
    
    /**
     * Get city and state through the area.
     */
    public function city()
    {
        return $this->hasOneThrough(
            City::class,
            Area::class,
            'id', // Foreign key on areas table
            'id', // Foreign key on cities table
            'area_id', // Local key on businesses table
            'city_id' // Local key on areas table
        );
    }

    /**
     * Get state through area and city relationships.
     */
    public function state()
    {
        return $this->hasOneThrough(
            State::class,
            Area::class,
            'id', // Foreign key on areas table  
            'id', // Foreign key on states table
            'area_id', // Local key on businesses table
            'state_id' // Local key through cities table
        )->join('cities', 'areas.city_id', '=', 'cities.id');
    }
    
    /**
     * Get all claims for this business.
     */
    public function claims()
    {
        return $this->hasMany(BusinessClaim::class);
    }
    
    /**
     * Check if the business is claimed.
     */
    public function isClaimed()
    {
        return $this->claimed_by !== null;
    }
    
    /**
     * Check if the business has a pending claim.
     */
    public function hasPendingClaim()
    {
        return $this->claims()->pending()->exists();
    }
    
    /**
     * Get the reviews for the business.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    /**
     * Get the images for the business.
     */
    public function images()
    {
        return $this->hasMany(BusinessImage::class);
    }
    
    /**
     * Get the primary image for the business.
     */
    public function primaryImage()
    {
        return $this->hasOne(BusinessImage::class)->where('is_primary', true);
    }
    
    /**
     * Get average rating for the business.
     * 
     * @return float
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
    
    /**
     * Get total reviews count.
     * 
     * @return int
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }
    
    /**
     * Get formatted address string.
     * 
     * @return string
     */
    public function getFormattedAddressAttribute()
    {
        $address = $this->street_address ?? '';
        
        if ($this->area) {
            $address .= $address ? ', ' . $this->area->name : $this->area->name;
            
            if ($this->area->city) {
                $address .= ', ' . $this->area->city->name;
                
                if ($this->area->city->state) {
                    $address .= ', ' . $this->area->city->state->name;
                }
            }
        }
        
        return $address;
    }

    /**
     * Get the zipcode model for this business.
     */
    public function zipcodeModel()
    {
        return $this->belongsTo(Zipcode::class, 'zipcode', 'code');
    }
}
