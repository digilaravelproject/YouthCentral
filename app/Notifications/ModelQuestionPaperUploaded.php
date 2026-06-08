<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ModelQuestionPaperUploaded extends Notification
{
    use Queueable;

    protected $paper;

    public function __construct($paper)
    {
        $this->paper = $paper;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Model Question Paper',
            'message' => 'A new model question paper "' . $this->paper->title . '" has been added.',
            'paper_id' => $this->paper->id,
            'url' => route('student.model-question-papers.index')
        ];
    }
}
