@extends('layouts.student-app')

@section('title', 'My Books | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-secondary">My Books</h1>
            <p class="text-gray-500 mt-1">{{ $purchases->count() }} book{{ $purchases->count() !== 1 ? 's' : '' }} purchased</p>
        </div>
        <a href="{{ route('books.index') }}" class="text-primary hover:text-primary/80 font-bold text-sm flex items-center gap-2">
            <i class="fas fa-plus"></i> Browse Books
        </a>
    </div>

    @if($purchases->isEmpty())
        <div class="text-center py-20">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-book-open text-4xl text-gray-300"></i>
            </div>
            <h2 class="text-2xl font-black text-secondary mb-2">No books purchased yet</h2>
            <p class="text-gray-500 mb-8">Browse our collection and purchase your first book.</p>
            <a href="{{ route('books.index') }}" class="inline-block bg-primary text-white font-bold py-4 px-10 rounded-full shadow-lg hover:bg-primary/90 transition-all">
                Browse Books
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($purchases as $purchase)
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition-all">
                <div class="p-6">
                    <div class="w-full h-40 bg-primary/5 rounded-xl flex items-center justify-center mb-4 overflow-hidden">
                        @if($purchase->book->cover_image)
                            <img src="{{ asset('storage/' . $purchase->book->cover_image) }}" alt="{{ $purchase->book->title }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-book text-5xl text-primary/30"></i>
                        @endif
                    </div>
                    <span class="inline-block px-3 py-1 bg-primary/10 text-primary rounded-full text-[10px] font-black uppercase tracking-wider mb-3">
                        {{ $purchase->book->subject->name ?? 'General' }}
                    </span>
                    <h3 class="text-lg font-black text-secondary mb-2">{{ $purchase->book->title }}</h3>
                    <p class="text-xs text-gray-400 mb-4">Purchased {{ $purchase->purchased_at->format('M d, Y') }}</p>
                    <div class="flex gap-2">
                        @if($purchase->book->pdf_file)
                            <a href="{{ asset('storage/' . $purchase->book->pdf_file) }}" target="_blank" class="flex-1 bg-primary text-white text-center py-3 rounded-full font-bold text-sm hover:bg-primary/90 transition-all">
                                <i class="fas fa-download mr-1"></i> Download
                            </a>
                        @endif
                        <a href="{{ route('books.show', $purchase->book->slug) }}" class="flex-1 bg-gray-100 text-secondary text-center py-3 rounded-full font-bold text-sm hover:bg-gray-200 transition-all">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
