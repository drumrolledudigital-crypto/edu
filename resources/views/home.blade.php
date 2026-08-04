@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="hero-gradient pt-8 pb-16 md:py-24 px-4 md:px-12 relative overflow-hidden">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <!-- Hero Left -->
        <div class="z-10 fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-xs font-bold mb-6">
                <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span> {{ \App\Models\Setting::get('hero_badge', 'Trusted by ' . $stats['students'] . '+ Parents') }}
            </span>
            <h1 class="text-4xl md:text-6xl xl:text-7xl font-extrabold text-secondary leading-[1.1] mb-6 tracking-tight">
                {!! \App\Models\Setting::get('hero_heading', 'Big Dreams Begin with a <span class="gradient-text">Drumroll!</span>') !!}
            </h1>
            <p class="text-gray-600 text-lg md:text-xl mb-8 max-w-xl">
                {{ \App\Models\Setting::get('hero_subheading', 'Engaging online tutoring and practice resources to help your child learn, grow, and shine with confidence.') }}
            </p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 mb-10 text-sm font-semibold text-secondary">
                @if(\App\Models\Setting::get('hero_feature_1'))
                    <div class="flex items-center gap-3"><i class="fas fa-check-circle text-primary"></i> {{ \App\Models\Setting::get('hero_feature_1') }}</div>
                @endif
                @if(\App\Models\Setting::get('hero_feature_2'))
                    <div class="flex items-center gap-3"><i class="fas fa-check-circle text-primary"></i> {{ \App\Models\Setting::get('hero_feature_2') }}</div>
                @endif
                @if(\App\Models\Setting::get('hero_feature_3'))
                    <div class="flex items-center gap-3"><i class="fas fa-check-circle text-primary"></i> {{ \App\Models\Setting::get('hero_feature_3') }}</div>
                @endif
                @if(\App\Models\Setting::get('hero_feature_4'))
                    <div class="flex items-center gap-3"><i class="fas fa-check-circle text-primary"></i> {{ \App\Models\Setting::get('hero_feature_4') }}</div>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-4">
                @php
                    $heroCtaText = \App\Models\Setting::get('hero_cta_text', 'Book Free Consultation');
                @endphp
                <a href="{{ route('student.register') }}" class="w-full sm:w-auto bg-primary hover:bg-secondary text-white font-bold py-4 px-10 rounded-full shadow-lg shadow-primary/20 hover:shadow-secondary/20 transition-all duration-300 flex items-center justify-center gap-2">
                    {{ $heroCtaText }} <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

        <!-- Hero Right -->
        <div class="relative fade-up" style="transition-delay: 0.2s">
            <div class="relative z-10 w-full aspect-square max-w-[550px] mx-auto">
                <div class="w-full h-full rounded-full overflow-hidden bg-primary/5 border-4 border-white shadow-soft">
                    <video autoplay loop muted playsinline class="w-full h-full object-cover">
                        <source src="{{ asset('website/' . rawurlencode('banner video.mp4')) }}" type="video/mp4">
                    </video>
                </div>

                <!-- Decorative Elements -->
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-accent/20 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-primary/10 rounded-full blur-3xl"></div>

                <!-- Floating Icons -->
                <div class="absolute top-[15%] left-0 w-14 h-14 bg-white rounded-2xl shadow-soft flex items-center justify-center text-primary animate-float" style="animation-delay: 0.5s">
                    <i class="fas fa-book text-2xl"></i>
                </div>
                <div class="absolute bottom-[20%] right-0 w-16 h-16 bg-white rounded-2xl shadow-soft flex items-center justify-center text-accent animate-float" style="animation-delay: 1.2s">
                    <i class="fas fa-star text-3xl"></i>
                </div>
                <div class="absolute top-1/2 -right-8 w-12 h-12 bg-white rounded-2xl shadow-soft flex items-center justify-center text-primary-400 animate-float" style="animation-delay: 0.8s">
                    <i class="fas fa-pencil-alt text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-20 px-4 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            @php
                $benefits = [
                    ['icon' => 'laptop-code', 'color' => 'blue', 'title' => 'Digital Tools', 'desc' => 'Interactive whiteboards & games.'],
                    ['icon' => 'user-tie', 'color' => 'pink', 'title' => 'Mentor Support', 'desc' => '1:1 guidance for every student.'],
                    ['icon' => 'clock', 'color' => 'yellow', 'title' => 'Flexibility', 'desc' => 'Schedule sessions at your convenience.'],
                    ['icon' => 'chart-line', 'color' => 'green', 'title' => 'Progress tracking', 'desc' => 'Detailed monthly reports for parents.'],
                    ['icon' => 'shield-halved', 'color' => 'purple', 'title' => 'Safe Learning', 'desc' => 'Secure, moderated online environment.'],
                ];
            @endphp

            @foreach($benefits as $index => $benefit)
            <div class="p-8 bg-white border border-gray-100 rounded-card shadow-soft hover:shadow-hover hover:-translate-y-2 transition-all duration-300 flex flex-col items-center text-center fade-up" style="transition-delay: {{ 0.1 * ($index + 1) }}s">
                <div class="w-16 h-16 bg-{{ $benefit['color'] == 'pink' ? 'primary' : ($benefit['color'] == 'yellow' ? 'accent' : $benefit['color'].'-400') }}/10 rounded-2xl flex items-center justify-center text-{{ $benefit['color'] == 'pink' ? 'primary' : ($benefit['color'] == 'yellow' ? 'secondary' : $benefit['color'].'-500') }} text-2xl mb-6">
                    <i class="fas fa-{{ $benefit['icon'] }}"></i>
                </div>
                <h3 class="font-extrabold text-secondary mb-3">{{ $benefit['title'] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $benefit['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Subjects Section -->
<section class="py-24 px-4 md:px-12 bg-light relative overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="mb-12 fade-up">
            <h2 class="text-3xl md:text-4xl font-extrabold text-secondary mb-4">Our <span class="text-primary">Subjects</span></h2>
            <p class="text-gray-600 max-w-2xl">Holistic learning paths designed by experts to ignite curiosity and build academic excellence in children.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($subjects as $index => $subject)
            <div class="group p-8 bg-white rounded-card shadow-soft hover:shadow-hover transition-all duration-300 border-b-4 border-transparent hover:border-primary fade-up" style="transition-delay: {{ 0.1 * ($index % 3) }}s">
                <div class="w-12 h-12 bg-light rounded-xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300 mb-6">
                    <i class="fas fa-book-open text-xl"></i>
                </div>
                <h4 class="font-extrabold text-secondary mb-3 group-hover:text-primary transition-colors">{{ $subject->name }}</h4>
                <p class="text-gray-500 text-sm leading-relaxed line-clamp-3">{{ $subject->description }}</p>
            </div>
            @empty
            <div class="col-span-full py-10 text-center text-gray-400 font-medium italic">
                No active subjects available at the moment.
            </div>
            @endforelse
        </div>
        
        <div class="mt-12 fade-up">
            <a href="{{ route('subjects.index') }}" class="inline-flex items-center gap-2 font-bold text-secondary hover:text-primary transition border-b-2 border-primary/20 hover:border-primary pb-1">
                Explore All Subjects <i class="fas fa-chevron-right text-xs"></i>
            </a>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-20 px-4 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="bg-secondary rounded-[3rem] p-10 md:p-16 grid grid-cols-2 lg:grid-cols-4 gap-12 text-center relative overflow-hidden fade-up">
            <!-- Decorative circle -->
            <div class="absolute -bottom-24 -right-24 w-64 h-64 border-[32px] border-white/5 rounded-full"></div>
            
            <div class="flex flex-col items-center">
                <span class="text-4xl md:text-5xl font-black text-primary mb-3" id="count-students">0</span>
                <p class="text-white/60 font-bold text-sm uppercase tracking-widest">Happy Students</p>
            </div>
            <div class="flex flex-col items-center">
                <span class="text-4xl md:text-5xl font-black text-accent mb-3" id="count-years">0</span>
                <p class="text-white/60 font-bold text-sm uppercase tracking-widest">Years Experience</p>
            </div>
            <div class="flex flex-col items-center">
                <span class="text-4xl md:text-5xl font-black text-primary-400 mb-3" id="count-sessions">0</span>
                <p class="text-white/60 font-bold text-sm uppercase tracking-widest">Total Sessions</p>
            </div>
            <div class="flex flex-col items-center">
                <span class="text-4xl md:text-5xl font-black text-green-400 mb-3" id="count-percent">0</span>
                <p class="text-white/60 font-bold text-sm uppercase tracking-widest">Satisfaction</p>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-24 px-4 md:px-12 bg-white relative">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-20 items-center">
        <!-- Why Choose Left -->
        <div class="lg:w-1/2 relative fade-up">
            <div class="bg-primary/5 rounded-[2.5rem] p-10 relative z-10">
                <div class="flex items-center gap-6 mb-8">
                    <div class="w-20 h-20 bg-white rounded-full p-1 shadow-soft">
                        <img src="https://i.pravatar.cc/150?u=teacher" alt="Founder" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div>
                        <h4 class="text-xl font-extrabold text-secondary">Mrs. Sarah Drumroll</h4>
                        <p class="text-primary font-bold text-sm">Founder & Head Educator</p>
                    </div>
                </div>
                <p class="text-secondary/80 italic leading-relaxed mb-8">"Education is not just about filling a bucket, but lighting a fire. We created Drumroll to make learning a joyful adventure for every child."</p>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white p-5 rounded-2xl shadow-soft">
                        <span class="text-2xl font-black text-secondary block mb-1">15+</span>
                        <p class="text-gray-500 text-xs font-bold uppercase">Awards Won</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl shadow-soft">
                        <span class="text-2xl font-black text-secondary block mb-1">10k+</span>
                        <p class="text-gray-500 text-xs font-bold uppercase">Workbooks Sold</p>
                    </div>
                </div>
            </div>
            <!-- Decorative dots -->
            <div class="absolute -top-6 -left-6 grid grid-cols-5 gap-2 opacity-20">
                @for($i=0; $i<25; $i++) <div class="w-2 h-2 bg-primary rounded-full"></div> @endfor
            </div>
        </div>

        <!-- Why Choose Right -->
        <div class="lg:w-1/2 fade-up" style="transition-delay: 0.2s">
            <h2 class="text-3xl md:text-4xl font-extrabold text-secondary mb-6">Why Parents <span class="text-primary">Choose Drumroll?</span></h2>
            <p class="text-gray-600 mb-10 text-lg">We provide a unique blend of traditional teaching values and modern interactive technology.</p>
            
            <ul class="space-y-6">
                @php
                    $whys = [
                        ['title' => 'Certified Global Educators', 'desc' => 'Our teachers are hand-picked and highly trained in child psychology.'],
                        ['title' => 'Customized Curriculum', 'desc' => 'Every child gets a tailored learning path based on their initial assessment.'],
                        ['title' => 'Engaging Practice Material', 'desc' => 'Proprietary Drumroll workbooks and digital practice games.'],
                    ];
                @endphp
                @foreach($whys as $why)
                <li class="flex items-start gap-5 group">
                    <div class="w-12 h-12 bg-accent/20 rounded-full flex items-center justify-center shrink-0 text-secondary group-hover:bg-accent transition-all">
                        <i class="fas fa-check font-black"></i>
                    </div>
                    <div>
                        <h5 class="font-extrabold text-secondary mb-1">{{ $why['title'] }}</h5>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $why['desc'] }}</p>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

<!-- Testimonials Slider -->
<section class="py-24 px-4 md:px-12 bg-light">
    <div class="max-w-7xl mx-auto overflow-hidden">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl md:text-4xl font-extrabold text-secondary mb-4">What Our <span class="text-primary">Parents Say</span></h2>
            <div class="flex justify-center gap-1 text-accent">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
        </div>

        <div class="relative max-w-4xl mx-auto fade-up">
            <div id="testimonial-slider" class="flex transition-transform duration-500 ease-in-out">
                @for($i=0; $i<3; $i++)
                <div class="w-full shrink-0 px-4">
                    <div class="bg-white p-10 md:p-14 rounded-[2.5rem] shadow-soft border border-gray-50 text-center relative">
                        <i class="fas fa-quote-left text-primary/10 text-7xl absolute top-10 left-10"></i>
                        <p class="text-lg md:text-xl text-secondary font-medium relative z-10 leading-relaxed mb-10">
                            "The transformation in my son's math skills has been incredible. He used to dread his homework, but now he actually looks forward to his sessions at Drumroll. The teachers are so patient and encouraging!"
                        </p>
                        <div class="flex flex-col items-center">
                            <img src="https://i.pravatar.cc/150?u=parent{{ $i }}" alt="Parent" class="w-16 h-16 rounded-full border-4 border-white shadow-md mb-4">
                            <h5 class="font-extrabold text-secondary">Emily Richardson</h5>
                            <p class="text-primary text-xs font-bold uppercase tracking-widest">Mother of Class 4 Student</p>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            
            <!-- Dots -->
            <div class="flex justify-center gap-3 mt-10" id="slider-dots">
                <button class="w-3 h-3 rounded-full bg-primary transition-all duration-300"></button>
                <button class="w-3 h-3 rounded-full bg-gray-200 hover:bg-primary/50 transition-all duration-300"></button>
                <button class="w-3 h-3 rounded-full bg-gray-200 hover:bg-primary/50 transition-all duration-300"></button>
            </div>
        </div>
    </div>
</section>

<!-- CTA Banner -->
<section class="py-20 px-4 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="bg-gradient-to-r from-primary to-pink-400 rounded-[3rem] p-10 md:p-20 text-center text-white relative overflow-hidden fade-up">
            <div class="relative z-10 max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-5xl font-black mb-6 leading-tight">Ready to boost your child's academic journey?</h2>
                <p class="text-white/80 text-lg md:text-xl mb-10 font-medium">Join {{ $stats['students'] }}+ happy families and experience the future of learning today. Your first consultation is completely free!</p>
                <a href="{{ route('student.register') }}" class="inline-block bg-white text-primary hover:bg-secondary hover:text-white font-black py-5 px-12 rounded-full shadow-2xl transition-all duration-300 scale-100 hover:scale-105">
                    Start Your Free Journey Now
                </a>
            </div>
            <!-- Decorative backgrounds -->
            <div class="absolute top-0 right-0 p-10 opacity-10"><i class="fas fa-graduation-cap text-[200px]"></i></div>
            <div class="absolute bottom-0 left-0 p-10 opacity-10"><i class="fas fa-child-reaching text-[150px]"></i></div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Statistics Counter Observer
        const statsSection = document.getElementById('count-students').closest('section');
        let animated = false;

        const statsObserver = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && !animated) {
                animateCounter('count-students', {{ $stats['students'] }});
                animateCounter('count-years', 10);
                animateCounter('count-sessions', {{ $stats['sessions'] }});
                animateCounter('count-percent', {{ $stats['success_rate'] }});
                animated = true;
            }
        }, { threshold: 0.5 });

        statsObserver.observe(statsSection);

        // Testimonial Slider logic
        const slider = document.getElementById('testimonial-slider');
        const dots = document.querySelectorAll('#slider-dots button');
        let currentSlide = 0;

        function updateSlider() {
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            dots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.add('bg-primary', 'w-8');
                    dot.classList.remove('bg-gray-200', 'w-3');
                } else {
                    dot.classList.remove('bg-primary', 'w-8');
                    dot.classList.add('bg-gray-200', 'w-3');
                }
            });
        }

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateSlider();
            });
        });

        // Auto slide
        setInterval(() => {
            currentSlide = (currentSlide + 1) % 3;
            updateSlider();
        }, 5000);

        updateSlider(); // Initial state
    });
</script>
@endpush
