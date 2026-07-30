@extends('layouts.app')

@section('title', $book->title . ' | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Breadcrumb + Hero -->
<section class="relative pt-20 pb-16 px-4 md:px-12 bg-white overflow-hidden">
    <div class="absolute top-0 right-0 w-1/3 h-full bg-accent/5 rounded-br-[100px] -z-10"></div>
    <div class="max-w-5xl mx-auto">
        <div class="fade-up">
            <div class="flex items-center justify-start gap-3 text-sm font-bold text-gray-400 mb-8">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <a href="{{ route('books.index') }}" class="hover:text-primary transition-colors">Books</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-primary">{{ Str::limit($book->title, 40) }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                <!-- Cover Image -->
                <div class="relative">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full rounded-2xl shadow-lg border border-gray-100">
                    @else
                        <div class="w-full aspect-[3/4] bg-primary/5 rounded-2xl flex items-center justify-center border border-gray-100">
                            <i class="fas fa-book text-6xl text-primary/20"></i>
                        </div>
                    @endif
                </div>

                <!-- Book Info -->
                <div>
                    <span class="inline-block py-1.5 px-4 rounded-full bg-primary/10 text-primary font-bold text-xs mb-4 tracking-wide uppercase">
                        {{ $book->subject->name ?? 'General' }}
                    </span>
                    <h1 class="text-3xl lg:text-4xl font-black text-secondary leading-tight mb-6">{{ $book->title }}</h1>

                    @if($book->short_description)
                        <p class="text-gray-600 text-base leading-relaxed mb-8">{{ $book->short_description }}</p>
                    @endif

                    @if($book->price)
                        <div class="mb-6">
                            <span class="text-4xl font-black text-primary">${{ number_format($book->price, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex items-center gap-3 mb-8 text-sm text-gray-400">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-primary"></i>
                            {{ $book->created_at->format('M d, Y') }}
                        </span>
                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                        <span class="flex items-center gap-2">
                            <i class="fas fa-tag text-primary"></i>
                            {{ $book->subject->name ?? 'General' }}
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            <form method="POST" action="{{ route('student.cart.add', $book) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-white font-black py-4 px-8 rounded-full shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </form>
                        @endauth
                        @if($book->pdf_file)
                            <a href="{{ asset('storage/' . $book->pdf_file) }}" target="_blank" class="inline-flex items-center justify-center gap-2 bg-secondary hover:bg-secondary/90 text-white font-bold py-4 px-8 rounded-full shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                        @endif
                        <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center gap-2 bg-white border-2 border-secondary/10 hover:border-primary text-secondary hover:text-primary font-bold py-4 px-8 rounded-full transition-all duration-300">
                            <i class="fas fa-arrow-left"></i> Back to Books
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 px-4 md:px-12 bg-white">
    <div class="max-w-5xl mx-auto bg-secondary rounded-[3rem] p-10 md:p-16 text-center relative overflow-hidden shadow-2xl fade-up">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

        <div class="relative z-10">
            <h2 class="text-3xl md:text-5xl font-black text-white mb-6">Enjoyed This Book?</h2>
            <p class="text-white/80 text-lg md:text-xl mb-10 max-w-2xl mx-auto font-medium">Explore more books or book a personalized session with our expert educators.</p>

            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ route('books.index') }}" class="w-full sm:w-auto bg-primary hover:bg-white hover:text-primary text-white font-black py-4 px-10 rounded-full shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    Browse More Books
                </a>
                <a href="{{ route('student.booking.create') }}" class="w-full sm:w-auto bg-transparent border-2 border-white/30 text-white hover:bg-white/10 font-bold py-4 px-10 rounded-full transition-all duration-300">
                    Book a Session
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
