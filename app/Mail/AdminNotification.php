<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject_line;
    public $notification_data;
    public $template;

    /**
     * Create a new message instance.
     *
     * @param string $subject_line
     * @param array $notification_data
     * @param string $template
     */
    public function __construct($subject_line, $notification_data, $template = 'admin-notification')
    {
        $this->subject_line = $subject_line;
        $this->notification_data = $notification_data;
        $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject_line)
                    ->view('emails.' . $this->template)
                    ->with($this->notification_data);
    }
} 