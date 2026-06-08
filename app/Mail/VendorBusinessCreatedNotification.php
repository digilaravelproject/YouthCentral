<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VendorBusinessCreatedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $subjectLine;
    public array $data;

    public function __construct(string $subjectLine, array $data)
    {
        $this->subjectLine = $subjectLine;
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.vendor-business-created')
            ->with(['data' => $this->data]);
    }
}
