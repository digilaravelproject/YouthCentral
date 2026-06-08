<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelQuestionPaper extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'file_path',
        'video_link',
        'status',
        'subject',
        'month',
        'topic',
    ];

    public function students()
    {
        return $this->belongsToMany(EventRegistration::class, 'model_question_paper_assignments', 'model_question_paper_id', 'event_registration_id')->withPivot('completed_at')->withTimestamps();
    }
}
