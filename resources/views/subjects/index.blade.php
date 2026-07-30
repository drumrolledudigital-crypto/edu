@extends('layouts.app')

@section('title', 'Our Subjects | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- 1. Hero Section -->
<section class="relative pt-20 pb-32 px-4 md:px-12 bg-white overflow-hidden">
    <div class="absolute top-0 right-0 w-1/3 h-full bg-accent/5 rounded-br-[100px] -z-10"></div>
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="fade-up z-10 text-center lg:text-left">
            <div class="flex items-center justify-center lg:justify-start gap-3 text-sm font-bold text-gray-400 mb-6">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-primary">Subjects</span>
            </div>
            
            <span class="inline-block py-1.5 px-4 rounded-full bg-primary/10 text-primary font-bold text-xs mb-6 tracking-wide uppercase">
                Our Subjects
            </span>
            <h1 class="text-4xl lg:text-6xl font-black text-secondary leading-tight mb-6">
                Choose the Right <span class="text-primary">Subject</span> for Your Child
            </h1>
            <p class="text-lg text-gray-600 mb-8 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                Expert-led personalized learning sessions designed to build strong foundations and academic confidence.
            </p>
        </div>
        <div class="relative fade-up hidden lg:block" style="transition-delay: 0.2s;">
            <div class="relative w-full max-w-md mx-auto">
                <div class="absolute inset-0 bg-primary/10 rounded-full blur-3xl"></div>
                <svg class="relative z-10 w-full animate-float" viewBox="0 0 400 350" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Background circle -->
                    <circle cx="200" cy="175" r="140" fill="#FF4D8D" opacity="0.08"/>
                    <!-- Open book -->
                    <rect x="100" y="160" width="200" height="120" rx="8" fill="white" stroke="#FF4D8D" stroke-width="2"/>
                    <line x1="200" y1="160" x2="200" y2="280" stroke="#FF4D8D" stroke-width="2"/>
                    <rect x="110" y="175" width="80" height="6" rx="3" fill="#FF4D8D" opacity="0.3"/>
                    <rect x="110" y="190" width="60" height="6" rx="3" fill="#FF4D8D" opacity="0.2"/>
                    <rect x="110" y="205" width="70" height="6" rx="3" fill="#FF4D8D" opacity="0.15"/>
                    <rect x="210" y="175" width="80" height="6" rx="3" fill="#FF4D8D" opacity="0.3"/>
                    <rect x="210" y="190" width="60" height="6" rx="3" fill="#FF4D8D" opacity="0.2"/>
                    <rect x="210" y="205" width="70" height="6" rx="3" fill="#FF4D8D" opacity="0.15"/>
                    <!-- Pencil -->
                    <rect x="280" y="100" width="12" height="80" rx="3" fill="#FFD166" transform="rotate(-20 280 100)"/>
                    <polygon points="280,180 286,200 292,180" fill="#FF4D8D" transform="rotate(-20 280 100)"/>
                    <!-- Graduation cap -->
                    <polygon points="200,70 160,95 200,115 240,95" fill="#1A2B48"/>
                    <rect x="195" y="95" width="10" height="30" fill="#1A2B48"/>
                    <line x1="240" y1="95" x2="240" y2="125" stroke="#FF4D8D" stroke-width="2"/>
                    <circle cx="240" cy="128" r="5" fill="#FF4D8D"/>
                    <!-- Floating stars -->
                    <circle cx="120" cy="100" r="6" fill="#FFD166"/>
                    <circle cx="300" cy="140" r="4" fill="#FF4D8D"/>
                    <circle cx="140" cy="250" r="5" fill="#FFD166"/>
                    <!-- Decorative dots -->
                    <circle cx="90" cy="180" r="3" fill="#FF4D8D" opacity="0.4"/>
                    <circle cx="320" cy="200" r="3" fill="#FF4D8D" opacity="0.4"/>
                </svg>
            </div>
        </div>
    </div>
</section>

<!-- 2 & 3. Search, Filter and Subject Grid -->
<section class="py-20 px-4 md:px-12 bg-light min-h-[60vh]">
    <div class="max-w-7xl mx-auto">
        
        <!-- Search and Filter Bar -->
        <div class="flex flex-col md:flex-row gap-6 mb-16 items-center justify-between fade-up">
            <div class="relative w-full md:w-96">
                <input type="text" id="subject-search" placeholder="Search subject (e.g. Mathematics)..." class="w-full bg-white border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-full py-3.5 pl-12 pr-6 text-sm transition-all shadow-sm">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
                <span class="text-xs font-bold text-secondary uppercase tracking-widest whitespace-nowrap">Filter Class:</span>
                <button class="filter-btn active px-4 py-2 rounded-full text-xs font-bold bg-secondary text-white shadow-md transition-all whitespace-nowrap" data-class="all">All</button>
                @for($i=1; $i<=8; $i++)
                    <button class="filter-btn px-4 py-2 rounded-full text-xs font-bold bg-white text-gray-500 border border-gray-100 hover:bg-gray-50 transition-all whitespace-nowrap" data-class="{{ $i }}">Class {{ $i }}</button>
                @endfor
            </div>
        </div>

        <!-- Grid Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="subject-grid">
            @forelse($subjects as $index => $subject)
            <div class="subject-card group bg-white rounded-card shadow-soft hover:shadow-hover border border-gray-50 p-8 transition-all duration-500 fade-up" 
                 data-name="{{ strtolower($subject->name) }}" 
                 data-desc="{{ strtolower($subject->description) }}"
                 data-class-from="{{ $subject->class_range_from }}"
                 data-class-to="{{ $subject->class_range_to }}"
                 style="transition-delay: {{ ($index % 3) * 0.1 }}s;">
                
                <div class="flex justify-between items-start mb-6">
                    <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary text-2xl group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase tracking-wider border border-green-100">
                        Active
                    </span>
                </div>

                <h3 class="text-2xl font-black text-secondary mb-3 group-hover:text-primary transition-colors">{{ $subject->name }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-6 line-clamp-3 h-15">
                    {{ $subject->description }}
                </p>

                <div class="grid grid-cols-2 gap-4 mb-8 pt-6 border-t border-gray-50">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Class Range</p>
                        <p class="text-sm font-black text-secondary">Class {{ $subject->class_range_from }} - {{ $subject->class_range_to }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Duration</p>
                        <p class="text-sm font-black text-secondary">{{ $subject->session_duration }} Mins</p>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('doubts.create') }}" class="w-full bg-secondary hover:bg-primary text-white text-center py-3.5 rounded-full font-bold text-sm transition-all shadow-md hover:shadow-xl flex items-center justify-center gap-2 group/btn">
                        Submit Doubt <i class="fas fa-question-circle text-xs group-hover/btn:rotate-12 transition-transform"></i>
                    </a>
                    <a href="{{ route('student.booking.create') }}" class="w-full bg-white border-2 border-secondary/10 hover:border-primary text-secondary hover:text-primary text-center py-3.5 rounded-full font-bold text-sm transition-all flex items-center justify-center gap-2">
                        Book Session <i class="fas fa-calendar-alt text-xs"></i>
                    </a>
                </div>
            </div>
            @empty
            <!-- 4. Empty State -->
            <div class="col-span-full py-20 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl text-gray-300">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 class="text-2xl font-black text-secondary mb-2">No subjects available yet</h3>
                <p class="text-gray-500 mb-8">We are currently updating our curriculum. Please check back soon!</p>
                <a href="{{ route('contact') }}" class="inline-block bg-primary text-white font-bold py-4 px-10 rounded-full shadow-lg">
                    Contact Us for Updates
                </a>
            </div>
            @endforelse
        </div>

        <!-- No Search Results State -->
        <div id="no-search-results" class="hidden py-20 text-center col-span-full">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl text-gray-300">
                <i class="fas fa-search-minus"></i>
            </div>
            <h3 class="text-2xl font-black text-secondary mb-2">No matching subjects</h3>
            <p class="text-gray-500 mb-8">Try adjusting your search or filters to find what you're looking for.</p>
            <button onclick="resetFilters()" class="text-primary font-bold hover:underline">Clear all filters</button>
        </div>

    </div>
</section>

<!-- 5. CTA Section -->
<section class="py-24 px-4 md:px-12 bg-white">
    <div class="max-w-5xl mx-auto bg-secondary rounded-[3rem] p-10 md:p-16 text-center relative overflow-hidden shadow-2xl fade-up">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
        
        <div class="relative z-10">
            <h2 class="text-3xl md:text-5xl font-black text-white mb-6">Need Help Choosing?</h2>
            <p class="text-white/80 text-lg md:text-xl mb-10 max-w-2xl mx-auto font-medium">Not sure which subject is right for your child? Book a free consultation call with our head educator today.</p>
            
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ route('student.booking.create') }}" class="w-full sm:w-auto bg-primary hover:bg-white hover:text-primary text-white font-black py-4 px-10 rounded-full shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    Book Free Consultation
                </a>
                <a href="{{ route('contact') }}" class="w-full sm:w-auto bg-transparent border-2 border-white/30 text-white hover:bg-white/10 font-bold py-4 px-10 rounded-full transition-all duration-300">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('subject-search');
        const filterBtns = document.querySelectorAll('.filter-btn');
        const subjectCards = document.querySelectorAll('.subject-card');
        const noResults = document.getElementById('no-search-results');
        const grid = document.getElementById('subject-grid');

        let activeCategory = 'all';
        let searchTerm = '';

        function performFiltering() {
            let visibleCount = 0;

            subjectCards.forEach(card => {
                const name = card.dataset.name;
                const desc = card.dataset.desc;
                const from = parseInt(card.dataset.classFrom);
                const to = parseInt(card.dataset.classTo);

                const matchesSearch = name.includes(searchTerm) || desc.includes(searchTerm);
                
                let matchesCategory = false;
                if(activeCategory === 'all') {
                    matchesCategory = true;
                } else {
                    const catInt = parseInt(activeCategory);
                    if(catInt >= from && catInt <= to) {
                        matchesCategory = true;
                    }
                }

                if(matchesSearch && matchesCategory) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            if(visibleCount === 0 && subjectCards.length > 0) {
                noResults.classList.remove('hidden');
                grid.classList.add('hidden');
            } else {
                noResults.classList.add('hidden');
                grid.classList.remove('hidden');
            }
        }

        // Live Search
        searchInput.addEventListener('input', (e) => {
            searchTerm = e.target.value.toLowerCase().trim();
            performFiltering();
        });

        // Category Filter
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update UI
                filterBtns.forEach(b => {
                    b.classList.remove('active', 'bg-secondary', 'text-white', 'shadow-md');
                    b.classList.add('bg-white', 'text-gray-500', 'border-gray-100');
                });
                btn.classList.remove('bg-white', 'text-gray-500', 'border-gray-100');
                btn.classList.add('active', 'bg-secondary', 'text-white', 'shadow-md');

                activeCategory = btn.dataset.class;
                performFiltering();
            });
        });

        window.resetFilters = function() {
            searchInput.value = '';
            searchTerm = '';
            document.querySelector('.filter-btn[data-class="all"]').click();
        };
    });
</script>
@endpush
