@extends('layouts.student-app')

@section('title', 'My Cart | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    @php $cart = session()->get('book_cart', []); $total = array_sum(array_column($cart, 'price')); @endphp

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-secondary">My Cart</h1>
            <p class="text-gray-500 mt-1">{{ count($cart) }} item{{ count($cart) !== 1 ? 's' : '' }}</p>
        </div>
        <a href="{{ route('books.index') }}" class="text-primary hover:text-primary/80 font-bold text-sm flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Continue Shopping
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm font-semibold">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm font-semibold">{{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl mb-6 text-sm font-semibold">{{ session('info') }}</div>
    @endif

    @if(empty($cart))
        <div class="text-center py-20">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-shopping-cart text-4xl text-gray-300"></i>
            </div>
            <h2 class="text-2xl font-black text-secondary mb-2">Your cart is empty</h2>
            <p class="text-gray-500 mb-8">Browse our collection and add books to your cart.</p>
            <a href="{{ route('books.index') }}" class="inline-block bg-primary text-white font-bold py-4 px-10 rounded-full shadow-lg hover:bg-primary/90 transition-all">
                Browse Books
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($cart as $item)
            <div class="bg-white rounded-2xl p-4 flex items-center gap-4 shadow-sm border border-gray-100">
                <div class="w-16 h-20 bg-primary/5 rounded-xl flex items-center justify-center shrink-0 overflow-hidden">
                    @if($item['cover_image'])
                        <img src="{{ asset('storage/' . $item['cover_image']) }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-book text-2xl text-primary/30"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-secondary text-sm truncate">{{ $item['title'] }}</h3>
                    <p class="text-xs text-gray-400">{{ $item['subject_name'] }}</p>
                </div>
                <div class="text-right shrink-0">
                    <p class="font-black text-primary">${{ number_format($item['price'], 2) }}</p>
                </div>
                <form method="POST" action="{{ route('student.cart.remove', $item['book_id']) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-9 h-9 rounded-full bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>

        <div class="mt-8 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <span class="font-bold text-secondary">Total</span>
                <span class="font-black text-2xl text-primary">${{ number_format($total, 2) }}</span>
            </div>
            <form method="POST" action="{{ route('student.cart.checkout') }}">
                @csrf
                <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-black py-4 px-8 rounded-full shadow-lg transition-all text-center">
                    Proceed to Checkout
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
