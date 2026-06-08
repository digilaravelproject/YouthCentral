<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'duration_type',
        'duration_value',
        'max_businesses',
        'max_images',
        'featured_listing',
        'is_active',
        'description',
        'priority',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'featured_listing' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the subscriptions for the plan.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get active plans only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }
    
    /**
     * Get formatted duration.
     */
    public function getFormattedDurationAttribute()
    {
        if ($this->duration_type === 'one-time') {
            return 'One-time';
        }
        
        $suffix = $this->duration_type === 'monthly' ? ' month' : ' year';
        if ($this->duration_value > 1) {
            $suffix .= 's';
        }
        
        return $this->duration_value . $suffix;
    }
} 