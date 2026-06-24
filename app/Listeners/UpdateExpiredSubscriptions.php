<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Subscription;

class UpdateExpiredSubscriptions
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        Subscription::updateExpiredSubscriptions();
    }
}
