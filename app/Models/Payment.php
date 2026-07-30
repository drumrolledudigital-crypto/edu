<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'appointment_id',
        'stripe_payment_id',
        'transaction_id',
        'amount',
        'currency',
        'payment_status',
        'payment_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }
}
