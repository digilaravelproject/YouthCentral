<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomeSliderItem extends Model
{
    protected $table = 'home_slider_items';

    protected $fillable = [
        'title',
        'subtitle',
        'icon_class',
        'color',
        'image_path',
        'link_url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): string
    {
        $path = (string) ($this->image_path ?? '');
        if ($path === '') {
            return asset('assets_public/images/backgrounds/slideshow/1.jpg');
        }

        // If it's an app asset path, use asset() directly.
        if (str_starts_with($path, 'assets_') || str_starts_with($path, 'assets/')) {
            return asset($path);
        }

        // Otherwise assume it's stored in public disk.
        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        // Fallback
        return asset($path);
    }
}

