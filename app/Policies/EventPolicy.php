<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the event.
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function view(User $user, Event $event)
    {
        // Users can view their own events
        return $user->id === $event->created_by;
    }

    /**
     * Determine whether the user can create events.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        // Users and vendors can create general events
        return in_array($user->role, ['user', 'vendor']);
    }

    /**
     * Determine whether the user can update the event.
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function update(User $user, Event $event)
    {
        // Users can only edit their own general events that are pending or approved
        return $user->id === $event->created_by 
            && $event->category === 'general'
            && in_array($event->status, ['pending', 'approved']);
    }

    /**
     * Determine whether the user can delete the event.
     * NEW RULE: Users/vendors can only delete events AFTER approval
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function delete(User $user, Event $event)
    {
        // Users can delete their own general events that are pending or approved
        return $user->id === $event->created_by 
            && $event->category === 'general'
            && in_array($event->status, ['pending', 'approved']);
    }

    /**
     * Determine whether the user can restore the event.
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function restore(User $user, Event $event)
    {
        return $user->id === $event->created_by;
    }

    /**
     * Determine whether the user can permanently delete the event.
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function forceDelete(User $user, Event $event)
    {
        // Only admins can force delete
        return $user->role === 'admin';
    }
} 