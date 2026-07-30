<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'title',
        'slug',
        'short_description',
        'cover_image',
        'pdf_file',
        'status',
        'price',
    ];

    protected $casts = [
        'status' => 'string',
        'price' => 'decimal:2',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
