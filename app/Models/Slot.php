<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
