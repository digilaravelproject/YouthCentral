<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'started_at',
        'ends_at',
        'status',
        'transaction_id',
        'amount_paid',
        'payment_method',
        'payment_details',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'ends_at' => 'datetime',
        'amount_paid' => 'decimal:2',
        'payment_details' => 'array',
    ];

    /**
     * Get the plan that the subscription belongs to.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the user that the subscription belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if subscription is active.
     */
    public function isActive()
    {
        return $this->status === 'active' && ($this->ends_at === null || $this->ends_at->isFuture());
    }

    /**
     * Check if subscription has expired.
     */
    public function hasExpired()
    {
        return $this->ends_at !== null && $this->ends_at->isPast();
    }

    /**
     * Get active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            });
    }

    /**
     * Cancel this subscription.
     */
    public function cancel()
    {
        $this->status = 'cancelled';
        $this->save();
        
        return $this;
    }

    /**
     * Update expired subscriptions for users and vendors.
     *
     * @return void
     */
    public static function updateExpiredSubscriptions()
    {
        self::whereHas('user', function ($query) {
                $query->whereIn('role', ['user', 'vendor']);
            })
            ->where('ends_at', '<', now())
            ->where('status', '!=', 'expired')
            ->update(['status' => 'expired']);
    }
} 