<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyMaterial extends Model
{
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
        return $this->belongsToMany(EventRegistration::class, 'study_material_assignments', 'study_material_id', 'event_registration_id');
    }
}
