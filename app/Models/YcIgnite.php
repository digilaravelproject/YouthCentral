<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YcIgnite extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'mobile',
        'dob',
        'age_category',
        'address',
        'school',
        'grade',
        'sport_event',
        'parent_consent',
        'terms_conditions',
    ];

    protected $casts = [
        'parent_consent'   => 'boolean',
        'terms_conditions' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
