<?php

namespace App\Mail;

use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventRegistrationSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;

    public function __construct(EventRegistration $registration)
    {
        $this->registration = $registration;
    }

    public function build()
    {
        return $this->subject('Registration Confirmed: ' . $this->registration->event->title)
                    ->view('emails.event_registration_success');
    }
} 