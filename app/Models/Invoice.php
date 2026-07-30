<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'student_id',
        'appointment_id',
        'payment_id',
        'amount',
        'currency',
        'status',
        'invoice_date',
        'pdf_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'invoice_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
