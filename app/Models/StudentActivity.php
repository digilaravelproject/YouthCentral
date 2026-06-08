<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentActivity extends Model
{
    use HasFactory;

    protected $table = 'student_activities';

    protected $fillable = [
        'student_id',
        'activity_type',
        'reference_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
