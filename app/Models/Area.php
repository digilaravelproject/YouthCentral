<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = ['city_id', 'name'];

    /**
     * Get the city that owns the area.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get all businesses in this area.
     */
    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

    /**
     * Get all zipcodes associated with this area.
     * Many-to-many relationship.
     */
    public function zipcodes()
    {
        return $this->belongsToMany(Zipcode::class, 'area_zipcode');
    }

    /**
     * Get the primary zipcode for this area (first one).
     */
    public function primaryZipcode()
    {
        return $this->zipcodes()->first();
    }

    /**
     * Check if this area has a specific zipcode.
     */
    public function hasZipcode($zipcodeCode)
    {
        return $this->zipcodes()->where('code', $zipcodeCode)->exists();
    }
}
