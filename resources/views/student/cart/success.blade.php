@extends('layouts.student-app')

@section('title', 'Payment Successful | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')
<div class="max-w-lg mx-auto px-4 py-20 text-center">
    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fas fa-check text-3xl text-green-600"></i>
    </div>
    <h1 class="text-3xl font-black text-secondary mb-2">Payment Successful!</h1>
    <p class="text-gray-500 mb-2">You purchased {{ $count }} book(s).</p>
    <p class="text-gray-400 text-sm mb-8">You can now access your books from My Books.</p>

    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('student.books.index') }}" class="bg-primary text-white font-bold py-4 px-10 rounded-full shadow-lg hover:bg-primary/90 transition-all">
            View My Books
        </a>
        <a href="{{ route('books.index') }}" class="bg-white border-2 border-gray-200 text-secondary font-bold py-4 px-10 rounded-full hover:border-primary transition-all">
            Browse More Books
        </a>
    </div>
</div>
@endsection
