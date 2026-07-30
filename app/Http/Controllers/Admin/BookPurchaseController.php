<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookPurchase;

class BookPurchaseController extends Controller
{
    public function index()
    {
        return view('admin.book-purchases.index');
    }

    public function list()
    {
        $purchases = BookPurchase::with(['user', 'book.subject'])
            ->where('status', 'completed')
            ->latest('purchased_at')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'student_name' => $p->user->name,
                    'student_email' => $p->user->email,
                    'book_title' => $p->book->title,
                    'subject' => $p->book->subject->name ?? 'General',
                    'amount' => $p->amount,
                    'purchased_at' => $p->purchased_at?->format('M d, Y h:i A'),
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $purchases,
        ]);
    }
}
