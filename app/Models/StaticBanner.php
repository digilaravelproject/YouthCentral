<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticBanner extends Model
{
    protected $table = 'static_banners';

    protected $fillable = [
        'key',
        'image_path',
    ];

    /**
     * Get the events listing featured banner (single record)
     */
    public static function getEventsListingBanner(): ?StaticBanner
    {
        return static::where('key', 'events_listing')->first();
    }
}
