<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusinessPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        // Any authenticated vendor can view their businesses
        return $user->role === 'vendor';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Business $business)
    {
        // Admin can view any business, vendor can only view their claimed businesses
        if ($user->role === 'admin') {
            return true;
        }
        
        return $user->role === 'vendor' && $business->claimed_by === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // Any authenticated vendor can create businesses
        return $user->role === 'vendor';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Business $business)
    {
        // Admin can update any business, vendor can only update their claimed businesses
        if ($user->role === 'admin') {
            return true;
        }
        
        return $user->role === 'vendor' && $business->claimed_by === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Business $business)
    {
        // Admin can delete any business, vendor can only delete their claimed businesses
        if ($user->role === 'admin') {
            return true;
        }
        
        return $user->role === 'vendor' && $business->claimed_by === $user->id;
    }
} 