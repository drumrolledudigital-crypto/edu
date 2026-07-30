<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'amount',
        'stripe_session_id',
        'stripe_payment_intent',
        'status',
        'purchased_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'purchased_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
