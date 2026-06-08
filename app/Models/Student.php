<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'phone',
        'name',
        'otp_code',
        'otp_expires_at',
        'event_registration_id',
        'student_class',
        'login_streak',
        'last_login_date',
    ];

    public function eventRegistration()
    {
        return $this->belongsTo(EventRegistration::class, 'event_registration_id');
    }

    public function studyMaterials()
    {
        return $this->belongsToMany(StudyMaterial::class, 'study_material_assignments');
    }

    public function activities()
    {
        return $this->hasMany(StudentActivity::class, 'student_id');
    }
}
