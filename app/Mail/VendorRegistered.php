<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VendorRegistered extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $vendor;

    /**
     * Create a new message instance.
     *
     * @param User $vendor
     */
    public function __construct(User $vendor)
    {
        $this->vendor = $vendor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to Youth Central - Vendor Account Created')
                    ->view('emails.vendor-registered')
                    ->with([
                        'vendor' => $this->vendor,
                    ]);
    }
} 