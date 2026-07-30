@extends('layouts.app')

@section('title', 'Our Books | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Hero Section -->
<section class="relative pt-20 pb-32 px-4 md:px-12 bg-white overflow-hidden">
    <div class="absolute top-0 right-0 w-1/3 h-full bg-accent/5 rounded-br-[100px] -z-10"></div>
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="fade-up z-10 text-center lg:text-left">
            <div class="flex items-center justify-center lg:justify-start gap-3 text-sm font-bold text-gray-400 mb-6">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-primary">Books</span>
            </div>

            <span class="inline-block py-1.5 px-4 rounded-full bg-primary/10 text-primary font-bold text-xs mb-6 tracking-wide uppercase">
                Our Library
            </span>
            <h1 class="text-4xl lg:text-6xl font-black text-secondary leading-tight mb-6">
                Explore Our <span class="text-primary">Book</span> Collection
            </h1>
            <p class="text-lg text-gray-600 mb-8 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                Access curated books and study materials designed to strengthen concepts and boost academic performance.
            </p>
        </div>
        <div class="relative fade-up hidden lg:block" style="transition-delay: 0.2s;">
            <div class="relative w-full max-w-md mx-auto">
                <div class="absolute inset-0 bg-primary/10 rounded-full blur-3xl"></div>
                <svg class="relative z-10 w-full animate-float" viewBox="0 0 400 350" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="200" cy="175" r="140" fill="#FF4D8D" opacity="0.08"/>
                    <!-- Book stack -->
                    <rect x="130" y="200" width="140" height="20" rx="4" fill="#1A2B48"/>
                    <rect x="125" y="180" width="150" height="20" rx="4" fill="#FF4D8D"/>
                    <rect x="120" y="160" width="160" height="20" rx="4" fill="#FFD166"/>
                    <!-- Open book on top -->
                    <rect x="140" y="100" width="120" height="50" rx="6" fill="white" stroke="#FF4D8D" stroke-width="2"/>
                    <line x1="200" y1="100" x2="200" y2="150" stroke="#FF4D8D" stroke-width="1.5"/>
                    <rect x="150" y="112" width="40" height="4" rx="2" fill="#FF4D8D" opacity="0.3"/>
                    <rect x="150" y="122" width="30" height="4" rx="2" fill="#FF4D8D" opacity="0.2"/>
                    <rect x="210" y="112" width="40" height="4" rx="2" fill="#FF4D8D" opacity="0.3"/>
                    <rect x="210" y="122" width="30" height="4" rx="2" fill="#FF4D8D" opacity="0.2"/>
                    <!-- Bookmark -->
                    <polygon points="260,80 260,110 270,102 280,110 280,80" fill="#FF4D8D"/>
                    <!-- Decorative elements -->
                    <circle cx="100" cy="120" r="5" fill="#FFD166"/>
                    <circle cx="310" cy="150" r="4" fill="#FF4D8D"/>
                    <circle cx="320" cy="220" r="3" fill="#FF4D8D" opacity="0.4"/>
                    <circle cx="90" cy="230" r="3" fill="#FFD166" opacity="0.4"/>
                </svg>
            </div>
        </div>
    </div>
</section>

<!-- Books Grid -->
<section class="py-20 px-4 md:px-12 bg-light min-h-[60vh]">
    <div class="max-w-7xl mx-auto">

        <!-- Grid Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="book-grid">
            @forelse($books as $index => $book)
            <div class="book-card group bg-white rounded-card shadow-soft hover:shadow-hover border border-gray-50 p-8 transition-all duration-500 fade-up"
                 style="transition-delay: {{ ($index % 3) * 0.1 }}s;">

                <div class="relative mb-6">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-48 object-cover rounded-xl border border-gray-100 group-hover:shadow-md transition-all">
                    @else
                        <div class="w-full h-48 bg-primary/5 rounded-xl flex items-center justify-center border border-gray-100">
                            <i class="fas fa-book text-4xl text-primary/30"></i>
                        </div>
                    @endif
                    <span class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur-sm text-primary rounded-full text-[10px] font-black uppercase tracking-wider border border-primary/10 shadow-sm">
                        {{ $book->subject->name ?? 'General' }}
                    </span>
                </div>

                <div class="flex items-start justify-between mb-1">
                    <h3 class="text-xl font-black text-secondary group-hover:text-primary transition-colors line-clamp-2 flex-1">{{ $book->title }}</h3>
                    @if($book->price)
                        <span class="text-lg font-black text-primary shrink-0 ml-2">${{ number_format($book->price, 2) }}</span>
                    @endif
                </div>
                <p class="text-gray-500 text-sm leading-relaxed mb-6 line-clamp-3">
                    {{ $book->short_description ?? 'No description available.' }}
                </p>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('books.show', $book->slug) }}" class="w-full bg-secondary hover:bg-primary text-white text-center py-3.5 rounded-full font-bold text-sm transition-all shadow-md hover:shadow-xl flex items-center justify-center gap-2 group/btn">
                        View Details <i class="fas fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                    </a>
                    @if($book->pdf_file)
                        <a href="{{ asset('storage/' . $book->pdf_file) }}" target="_blank" class="w-full bg-white border-2 border-secondary/10 hover:border-primary text-secondary hover:text-primary text-center py-3.5 rounded-full font-bold text-sm transition-all flex items-center justify-center gap-2">
                            Download PDF <i class="fas fa-download text-xs"></i>
                        </a>
                    @endif
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="col-span-full py-20 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl text-gray-300">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 class="text-2xl font-black text-secondary mb-2">No books available yet</h3>
                <p class="text-gray-500 mb-8">We are currently updating our library. Please check back soon!</p>
                <a href="{{ route('contact') }}" class="inline-block bg-primary text-white font-bold py-4 px-10 rounded-full shadow-lg">
                    Contact Us for Updates
                </a>
            </div>
            @endforelse
        </div>

    </div>
</section>

<!-- CTA Section -->
<section class="py-24 px-4 md:px-12 bg-white">
    <div class="max-w-5xl mx-auto bg-secondary rounded-[3rem] p-10 md:p-16 text-center relative overflow-hidden shadow-2xl fade-up">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

        <div class="relative z-10">
            <h2 class="text-3xl md:text-5xl font-black text-white mb-6">Need Help Finding the Right Book?</h2>
            <p class="text-white/80 text-lg md:text-xl mb-10 max-w-2xl mx-auto font-medium">Contact our educators for personalized book recommendations tailored to your child's learning needs.</p>

            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ route('contact') }}" class="w-full sm:w-auto bg-primary hover:bg-white hover:text-primary text-white font-black py-4 px-10 rounded-full shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    Contact Us
                </a>
                <a href="{{ route('subjects.index') }}" class="w-full sm:w-auto bg-transparent border-2 border-white/30 text-white hover:bg-white/10 font-bold py-4 px-10 rounded-full transition-all duration-300">
                    Browse Subjects
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
</script>
@endpush
