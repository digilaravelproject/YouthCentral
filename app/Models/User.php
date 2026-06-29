<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'location',
        // 'latitude',
        // 'longitude',
        'about_me',
        'status',
        'business_name',
        'business_address',
        'gst_number',
        'otp_code',
        'otp_expires_at',
        'phone_verified',
        'phone_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'phone_verified' => 'boolean',
    ];
    
    /**
     * Check if user has the specified role
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }
    
    /**
     * Check if user is admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }
    
    /**
     * Check if user is vendor
     *
     * @return bool
     */
    public function isVendor()
    {
        return $this->hasRole('vendor');
    }
    
    /**
     * Check if user is regular user
     *
     * @return bool
     */
    public function isUser()
    {
        return $this->hasRole('user');
    }
    
    /**
     * Check if vendor is approved
     * 
     * @return bool
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }
    
    /**
     * Check if vendor is pending approval
     * 
     * @return bool
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function businesses()
    {
        return $this->hasMany(Business::class, 'claimed_by');
    }
    
    /**
     * Get the user's subscriptions.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    
    /**
     * Get the user's active subscription.
     */
    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            })
            ->latest()
            ->first();
    }
    
    /**
     * Check if user has an active subscription.
     */
    public function hasActiveSubscription()
    {
        return $this->activeSubscription() !== null;
    }
    
    /**
     * Check if user can add more businesses based on their plan.
     */
    public function canAddMoreBusinesses()
    {
        $subscription = $this->activeSubscription();
        
        if (!$subscription) {
            return true;
        }
        
        $currentBusinessCount = $this->businesses()->count();
        $maxBusinesses = $subscription->plan->max_businesses;
        
        return $currentBusinessCount < $maxBusinesses;
    }

    /**
     * Get the reviews written by the user.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    // Add this method to your app/Models/User.php file
    public function hasRegisteredForEvent($eventId)
    {
        return $this->eventRegistrations()
            ->where('event_id', $eventId)
            ->where('payment_status', 'paid')
            ->exists();
    }

    // Make sure you have the relationship defined in User.php as well:
    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
