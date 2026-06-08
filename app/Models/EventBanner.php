<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventBanner extends Model
{
    protected $fillable = [
        'event_id',
        'image_path',
        'is_primary',
        'display_order'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'display_order' => 'integer'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
} 