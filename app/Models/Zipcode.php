<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zipcode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'city_name',
        'state_name',
        'country_code',
        'latitude',
        'longitude',
        'is_active'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean'
    ];

    /**
     * Get all areas associated with this zipcode.
     * Many-to-many relationship.
     */
    public function areas()
    {
        return $this->belongsToMany(Area::class, 'area_zipcode');
    }

    /**
     * Get all businesses in this zipcode.
     */
    public function businesses()
    {
        return $this->hasMany(Business::class, 'zipcode', 'code');
    }

    /**
     * Scope to get active zipcodes only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Find zipcode by coordinates using reverse geocoding.
     * This will be used by the location detection service.
     */
    public static function findByCoordinates($latitude, $longitude, $radiusKm = 10)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        return static::selectRaw("
            *, (
                $earthRadius * acos(
                    cos(radians(?)) * 
                    cos(radians(latitude)) * 
                    cos(radians(longitude) - radians(?)) + 
                    sin(radians(?)) * 
                    sin(radians(latitude))
                )
            ) AS distance
        ", [$latitude, $longitude, $latitude])
        ->where('is_active', true)
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->havingRaw('distance <= ?', [$radiusKm])
        ->orderByRaw('distance ASC')
        ->first();
    }

    /**
     * Get the formatted zipcode display name.
     */
    public function getDisplayNameAttribute()
    {
        $parts = array_filter([$this->code, $this->city_name, $this->state_name]);
        return implode(', ', $parts);
    }
} 