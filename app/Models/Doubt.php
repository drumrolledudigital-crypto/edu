<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doubt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'topic_name',
        'title',
        'description',
        'attachment',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
        'title' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }
}
