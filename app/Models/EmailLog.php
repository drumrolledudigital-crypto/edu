<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient',
        'subject',
        'type',
        'status',
        'retry_count',
        'error_message',
        'appointment_id',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'retry_count' => 'integer',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
