<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['state_id', 'name', 'slug'];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($city) {
            if (empty($city->slug)) {
                $city->slug = Str::slug($city->name);
            }
        });

        static::updating(function ($city) {
            if ($city->isDirty('name') && empty($city->slug)) {
                $city->slug = Str::slug($city->name);
            }
        });
    }

    /**
     * Get the state that owns the city.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the areas for the city.
     */
    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function businesses()
    {
        return $this->hasManyThrough(Business::class, Area::class);
    }
}
