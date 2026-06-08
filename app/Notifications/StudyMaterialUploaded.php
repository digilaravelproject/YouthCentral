<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class StudyMaterialUploaded extends Notification
{
    use Queueable;

    protected $material;

    public function __construct($material)
    {
        $this->material = $material;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Study Material',
            'message' => 'A new study material "' . $this->material->title . '" has been added.',
            'material_id' => $this->material->id,
            'url' => route('student.study-materials.index')
        ];
    }
}
