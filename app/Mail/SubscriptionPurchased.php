<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionPurchased extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subscription;

    /**
     * Create a new message instance.
     *
     * @param Subscription $subscription
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Subscription Activated - ' . $this->subscription->plan->name)
                    ->view('emails.subscription-purchased')
                    ->with([
                        'subscription' => $this->subscription,
                        'user' => $this->subscription->user,
                        'plan' => $this->subscription->plan,
                    ]);
    }
} 