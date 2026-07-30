<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'class_range_from',
        'class_range_to',
        'session_duration',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function doubts()
    {
        return $this->hasMany(Doubt::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
