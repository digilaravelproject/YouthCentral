<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressActivity extends Model
{
    use HasFactory;

    protected $table = 'progress_activities';

    protected $fillable = [
        'activity_type',
        'title',
        'percentage',
        'max_limit',
    ];
}
