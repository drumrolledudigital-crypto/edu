@extends('layouts.app')

@section('title', 'Frequently Asked Questions | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- 1. Hero Section -->
<section class="relative pt-20 pb-32 px-4 md:px-12 bg-white overflow-hidden">
    <div class="absolute top-0 left-0 w-1/3 h-full bg-accent/5 rounded-br-[100px] -z-10"></div>
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="fade-up z-10">
            <div class="flex items-center gap-3 text-sm font-bold text-gray-400 mb-6">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-primary">FAQ</span>
            </div>
            
            <span class="inline-block py-1.5 px-4 rounded-full bg-primary/10 text-primary font-bold text-sm mb-6 tracking-wide uppercase">
                Frequently Asked Questions
            </span>
            <h1 class="text-4xl lg:text-6xl font-black text-secondary leading-tight mb-6">
                Have Questions? <br><span class="text-primary">We're Here to Help.</span>
            </h1>
            <p class="text-lg text-gray-600 mb-8 max-w-lg leading-relaxed">
                Everything you need to know about how Drumroll helps your child learn, grow, and succeed in a safe online environment.
            </p>
        </div>
        <div class="relative fade-up" style="transition-delay: 0.2s;">
            <div class="relative w-full max-w-lg mx-auto">
                <div class="absolute inset-0 bg-yellow-100 rounded-full opacity-30 blur-3xl"></div>
                <img src="https://illustrations.popsy.co/pink/question-mark.svg" alt="FAQ Illustration" class="relative z-10 w-full animate-float">
                
                <!-- Floating Elements -->
                <div class="absolute top-1/4 -right-6 w-14 h-14 bg-white rounded-xl shadow-soft flex items-center justify-center text-accent text-2xl animate-float" style="animation-delay: 1.2s;">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="absolute bottom-1/4 -left-4 w-16 h-16 bg-white rounded-2xl shadow-soft flex items-center justify-center text-primary text-2xl animate-float" style="animation-delay: 0.5s;">
                    <i class="fas fa-comments"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Quick Help Cards -->
<section class="py-16 px-4 md:px-12 bg-light relative -mt-16 z-20">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6">
            @php
                $quickHelps = [
                    ['icon' => 'user-plus', 'color' => 'blue', 'title' => 'Admissions'],
                    ['icon' => 'chalkboard-user', 'color' => 'pink', 'title' => 'Classes'],
                    ['icon' => 'book-open', 'color' => 'yellow', 'title' => 'Subjects'],
                    ['icon' => 'calendar-alt', 'color' => 'green', 'title' => 'Booking'],
                    ['icon' => 'credit-card', 'color' => 'purple', 'title' => 'Payments'],
                    ['icon' => 'headset', 'color' => 'red', 'title' => 'Support'],
                ];
            @endphp
            @foreach($quickHelps as $index => $help)
            <div class="bg-white p-6 rounded-2xl shadow-soft border border-gray-50 text-center cursor-pointer hover:shadow-hover hover:-translate-y-2 transition-all duration-300 group fade-up" style="transition-delay: {{ 0.1 * $index }}s;" onclick="filterByCategory('{{ strtolower($help['title']) }}')">
                <div class="w-12 h-12 mx-auto bg-{{ $help['color'] == 'pink' ? 'primary' : ($help['color'] == 'yellow' ? 'accent' : $help['color'].'-500') }}/10 rounded-xl flex items-center justify-center text-{{ $help['color'] == 'pink' ? 'primary' : ($help['color'] == 'yellow' ? 'secondary' : $help['color'].'-500') }} text-xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-{{ $help['icon'] }}"></i>
                </div>
                <h4 class="font-bold text-secondary text-sm">{{ $help['title'] }}</h4>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 3 & 4. Search Box and Categories -->
<section class="py-12 px-4 md:px-12 bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-4xl mx-auto">
        <!-- Search Box -->
        <div class="relative mb-8 fade-up">
            <input type="text" id="faq-search" placeholder="Search your question (e.g., How do I book a slot?)..." class="w-full bg-light border-transparent focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-full py-4 pl-14 pr-6 text-lg transition-all shadow-inner text-gray-700 placeholder:text-gray-400">
            <i class="fas fa-search absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
        </div>

        <!-- Categories -->
        <div class="flex flex-wrap items-center justify-center gap-2 fade-up" style="transition-delay: 0.1s;" id="faq-categories">
            <button class="category-btn active px-5 py-2.5 rounded-full text-sm font-bold bg-primary text-white shadow-md transition-all" data-filter="all">All Questions</button>
            <button class="category-btn px-5 py-2.5 rounded-full text-sm font-bold bg-light text-gray-500 hover:bg-gray-200 transition-all" data-filter="general">General</button>
            <button class="category-btn px-5 py-2.5 rounded-full text-sm font-bold bg-light text-gray-500 hover:bg-gray-200 transition-all" data-filter="registration">Student Registration</button>
            <button class="category-btn px-5 py-2.5 rounded-full text-sm font-bold bg-light text-gray-500 hover:bg-gray-200 transition-all" data-filter="login">Login & Account</button>
            <button class="category-btn px-5 py-2.5 rounded-full text-sm font-bold bg-light text-gray-500 hover:bg-gray-200 transition-all" data-filter="subjects">Subjects</button>
            <button class="category-btn px-5 py-2.5 rounded-full text-sm font-bold bg-light text-gray-500 hover:bg-gray-200 transition-all" data-filter="doubts">Doubt Submission</button>
            <button class="category-btn px-5 py-2.5 rounded-full text-sm font-bold bg-light text-gray-500 hover:bg-gray-200 transition-all" data-filter="booking">Calendar Booking</button>
            <button class="category-btn px-5 py-2.5 rounded-full text-sm font-bold bg-light text-gray-500 hover:bg-gray-200 transition-all" data-filter="parents">Parents</button>
            <button class="category-btn px-5 py-2.5 rounded-full text-sm font-bold bg-light text-gray-500 hover:bg-gray-200 transition-all" data-filter="support">Support</button>
        </div>
    </div>
</section>

<!-- 5. Main FAQ Section -->
<section class="py-20 px-4 md:px-12 bg-light min-h-[50vh]">
    <div class="max-w-4xl mx-auto space-y-12" id="faq-container">

        <!-- Error State (Hidden by default) -->
        <div id="no-results" class="hidden text-center py-12">
            <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 mx-auto mb-4 text-3xl">
                <i class="fas fa-search-minus"></i>
            </div>
            <h3 class="text-2xl font-bold text-secondary mb-2">No results found</h3>
            <p class="text-gray-500">We couldn't find any questions matching your search. Try different keywords or browse categories.</p>
        </div>

        @php
            $faqGroups = [
                'general' => [
                    'title' => 'General Information',
                    'icon' => 'info-circle',
                    'color' => 'blue',
                    'faqs' => [
                        ['q' => 'What is the platform about?', 'a' => 'Drumroll is a premium online tutoring platform dedicated to providing 1-on-1 personalized learning experiences for children below Class 8.'],
                        ['q' => 'Who can join?', 'a' => 'Any student from Kindergarten up to Class 8 can join. We tailor our subjects to suit the learning pace and style of each individual child.'],
                        ['q' => 'How do online classes work?', 'a' => 'Classes are conducted via our secure, interactive video platform. Students and tutors collaborate using a digital whiteboard, screen sharing, and interactive tools.'],
                    ]
                ],
                'registration' => [
                    'title' => 'Student Registration',
                    'icon' => 'user-plus',
                    'color' => 'pink',
                    'faqs' => [
                        ['q' => 'How do I register?', 'a' => 'Simply click the "Register" button in the top menu, fill out the basic information about the student and parent, and your account will be created instantly.'],
                        ['q' => 'Is registration free?', 'a' => 'Yes, creating a student account is 100% free. You only pay when you book a specific learning session.'],
                        ['q' => 'Can I edit my profile?', 'a' => 'Absolutely. Once logged in, you can update your profile details, grade level, and contact preferences from your Student Dashboard.'],
                    ]
                ],
                'login' => [
                    'title' => 'Login & Account',
                    'icon' => 'lock',
                    'color' => 'yellow',
                    'faqs' => [
                        ['q' => 'Forgot password?', 'a' => 'On the login page, click the "Forgot?" link next to the password field. We will send a secure password reset link to your registered email address.'],
                        ['q' => 'Change mobile number?', 'a' => 'You can update your mobile number at any time through your Student Dashboard under the "Account Settings" tab.'],
                    ]
                ],
                'subjects' => [
                    'title' => 'Subjects & Subjects',
                    'icon' => 'book-open',
                    'color' => 'green',
                    'faqs' => [
                        ['q' => 'Which subjects are available?', 'a' => 'We currently offer Mathematics, English Language Arts, General Science, Homework Help, and specialized Skill Building courses.'],
                        ['q' => 'How are subjects updated?', 'a' => 'Our curriculum experts continuously review and update our subjects to align with global educational standards and best practices.'],
                    ]
                ],
                'doubts' => [
                    'title' => 'Doubt Submission',
                    'icon' => 'question-circle',
                    'color' => 'purple',
                    'faqs' => [
                        ['q' => 'How do I submit doubts?', 'a' => 'Navigate to the "Submit Doubt" page, select your subject, describe your question, and optionally upload a photo of the problem. This helps tutors prepare before your session.'],
                        ['q' => 'How quickly are doubts answered?', 'a' => 'Doubts are addressed directly during your booked 1-on-1 session. By submitting them in advance, you ensure the entire session time is spent on solving the problem effectively.'],
                    ]
                ],
                'booking' => [
                    'title' => 'Calendar Booking',
                    'icon' => 'calendar-alt',
                    'color' => 'red',
                    'faqs' => [
                        ['q' => 'How do I book a slot?', 'a' => 'Go to "Book a Session", select your desired program, pick an available date from the calendar, choose a time slot, and proceed to checkout.'],
                        ['q' => 'Can I reschedule?', 'a' => 'Yes, you can request a reschedule from your dashboard up to 24 hours before the session. Our administrators will review and approve your request based on availability.'],
                    ]
                ],
                'parents' => [
                    'title' => 'For Parents',
                    'icon' => 'users',
                    'color' => 'blue',
                    'faqs' => [
                        ['q' => 'Can parents track progress?', 'a' => 'Yes, parents receive detailed monthly progress reports and have access to a Parent Dashboard view to monitor completed sessions and upcoming classes.'],
                        ['q' => 'How are updates shared?', 'a' => 'Updates, session reminders, and tutor feedback are shared directly to the registered parent email address and via SMS if opted in.'],
                    ]
                ],
                'support' => [
                    'title' => 'Platform Support',
                    'icon' => 'headset',
                    'color' => 'pink',
                    'faqs' => [
                        ['q' => 'How can I contact support?', 'a' => 'You can reach us via the Contact Us page using the secure form, or directly email us at ' . \App\Models\Setting::get('support_email', 'support@drumroll.com') . '.'],
                        ['q' => 'What are support hours?', 'a' => 'Our support team is available Monday to Friday from 8:00 AM to 8:00 PM, and on weekends from 9:00 AM to 5:00 PM.'],
                    ]
                ],
            ];
        @endphp

        @foreach($faqGroups as $key => $group)
        <div class="faq-group fade-up" data-category="{{ $key }}">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-{{ $group['color'] == 'pink' ? 'primary' : ($group['color'] == 'yellow' ? 'accent' : $group['color'].'-500') }}/10 flex items-center justify-center text-{{ $group['color'] == 'pink' ? 'primary' : ($group['color'] == 'yellow' ? 'secondary' : $group['color'].'-500') }} text-xl">
                    <i class="fas fa-{{ $group['icon'] }}"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-secondary">{{ $group['title'] }}</h2>
            </div>
            
            <div class="space-y-4">
                @foreach($group['faqs'] as $faq)
                <div class="faq-item bg-white rounded-card shadow-soft border border-gray-50 overflow-hidden transition-all duration-300">
                    <button class="faq-btn w-full text-left px-8 py-6 font-bold text-secondary flex justify-between items-center focus:outline-none text-lg hover:text-primary transition-colors">
                        <span class="faq-question pr-8">{{ $faq['q'] }}</span>
                        <div class="w-8 h-8 rounded-full bg-light flex items-center justify-center shrink-0 transition-transform duration-300 icon-container">
                            <i class="fas fa-plus text-primary text-sm transition-transform duration-300 faq-icon"></i>
                        </div>
                    </button>
                    <div class="faq-content hidden px-8 pb-6 text-gray-500 leading-relaxed">
                        {{ $faq['a'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

    </div>
</section>

<!-- 6. Still Need Help -->
<section class="py-20 px-4 md:px-12 bg-white">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12 fade-up">
            <h2 class="text-3xl md:text-4xl font-black text-secondary mb-4">Still Need <span class="text-primary">Help?</span></h2>
            <p class="text-gray-500 text-lg">Our dedicated team is always ready to assist you and your child.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-light p-8 rounded-card border border-gray-50 text-center hover:-translate-y-2 transition-transform duration-300 fade-up">
                <div class="w-16 h-16 mx-auto bg-primary-100 text-primary-500 rounded-full flex items-center justify-center text-2xl mb-6">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h3 class="font-bold text-secondary text-xl mb-2">Call Us</h3>
                <p class="text-gray-500 text-sm mb-4">Available during business hours</p>
                <a href="tel:{{ \App\Models\Setting::get('support_phone', '+1 (234) 567-890') }}" class="text-primary font-bold hover:underline">{{ \App\Models\Setting::get('support_phone', '+1 (234) 567-890') }}</a>
            </div>

            <div class="bg-light p-8 rounded-card border border-gray-50 text-center hover:-translate-y-2 transition-transform duration-300 fade-up" style="transition-delay: 0.1s;">
                <div class="w-16 h-16 mx-auto bg-pink-100 text-primary rounded-full flex items-center justify-center text-2xl mb-6">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3 class="font-bold text-secondary text-xl mb-2">Email Us</h3>
                <p class="text-gray-500 text-sm mb-4">We reply within 2 hours</p>
                <a href="mailto:{{ \App\Models\Setting::get('support_email', 'support@drumroll.com') }}" class="text-primary font-bold hover:underline">{{ \App\Models\Setting::get('support_email', 'support@drumroll.com') }}</a>
            </div>

            <div class="bg-light p-8 rounded-card border border-gray-50 text-center hover:-translate-y-2 transition-transform duration-300 fade-up" style="transition-delay: 0.2s;">
                <div class="w-16 h-16 mx-auto bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-2xl mb-6">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="font-bold text-secondary text-xl mb-2">Book a Session</h3>
                <p class="text-gray-500 text-sm mb-4">Talk to an education expert</p>
                <a href="{{ route('student.booking.create') }}" class="text-primary font-bold hover:underline">Book Now <i class="fas fa-arrow-right text-xs"></i></a>
            </div>
        </div>
    </div>
</section>

<!-- 7. Parent Confidence Section -->
<section class="py-16 px-4 md:px-12 bg-secondary text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-3xl"></div>
    <div class="max-w-7xl mx-auto relative z-10 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        <div class="fade-up">
            <i class="fas fa-chalkboard-teacher text-4xl text-accent mb-4 block"></i>
            <h4 class="font-bold text-lg mb-1">Expert Teachers</h4>
            <p class="text-gray-400 text-xs">Vetted and certified</p>
        </div>
        <div class="fade-up" style="transition-delay: 0.1s;">
            <i class="fas fa-clock text-4xl text-primary-400 mb-4 block"></i>
            <h4 class="font-bold text-lg mb-1">Flexible Schedules</h4>
            <p class="text-gray-400 text-xs">Learn on your time</p>
        </div>
        <div class="fade-up" style="transition-delay: 0.2s;">
            <i class="fas fa-gamepad text-4xl text-primary mb-4 block"></i>
            <h4 class="font-bold text-lg mb-1">Interactive Learning</h4>
            <p class="text-gray-400 text-xs">Fun and engaging</p>
        </div>
        <div class="fade-up" style="transition-delay: 0.3s;">
            <i class="fas fa-shield-alt text-4xl text-green-400 mb-4 block"></i>
            <h4 class="font-bold text-lg mb-1">Safe Environment</h4>
            <p class="text-gray-400 text-xs">Secure online platform</p>
        </div>
    </div>
</section>

<!-- 8. CTA Section -->
<section class="py-20 px-4 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="bg-gradient-to-r from-primary to-pink-400 rounded-[3rem] p-10 md:p-16 text-center text-white relative overflow-hidden fade-up">
            <div class="relative z-10 max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-5xl font-black mb-6 leading-tight">Ready to begin?</h2>
                <p class="text-white/90 text-lg mb-10 font-medium">Join 500+ happy families. Your first consultation is completely free!</p>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="{{ route('student.booking.create') }}" class="w-full sm:w-auto bg-white text-primary hover:bg-secondary hover:text-white font-black py-4 px-10 rounded-full shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        Book a Free Consultation
                    </a>
                    <a href="{{ route('contact') }}" class="w-full sm:w-auto bg-transparent border-2 border-white/30 text-white hover:bg-white/10 font-bold py-4 px-10 rounded-full transition-all duration-300">
                        Contact Us
                    </a>
                </div>
            </div>
            <!-- Decorative backgrounds -->
            <div class="absolute -bottom-10 -left-10 p-10 opacity-10"><i class="fas fa-comments text-[200px]"></i></div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        
        // 1. Accordion Logic (Mutually Exclusive)
        const faqButtons = document.querySelectorAll('.faq-btn');
        
        faqButtons.forEach(button => {
            button.addEventListener('click', () => {
                const currentItem = button.parentElement;
                const content = currentItem.querySelector('.faq-content');
                const icon = currentItem.querySelector('.faq-icon');
                const iconContainer = currentItem.querySelector('.icon-container');
                const isOpen = !content.classList.contains('hidden');

                // Close all other open accordions
                document.querySelectorAll('.faq-item').forEach(item => {
                    if (item !== currentItem) {
                        const otherContent = item.querySelector('.faq-content');
                        const otherIcon = item.querySelector('.faq-icon');
                        const otherIconContainer = item.querySelector('.icon-container');
                        
                        otherContent.classList.add('hidden');
                        item.classList.remove('border-primary/30', 'shadow-md');
                        
                        if(otherIcon) {
                            otherIcon.classList.remove('fa-minus', 'rotate-180', 'text-white');
                            otherIcon.classList.add('fa-plus', 'text-primary');
                        }
                        if(otherIconContainer) {
                            otherIconContainer.classList.remove('bg-primary');
                            otherIconContainer.classList.add('bg-light');
                        }
                    }
                });

                // Toggle Current
                if (isOpen) {
                    content.classList.add('hidden');
                    currentItem.classList.remove('border-primary/30', 'shadow-md');
                    icon.classList.remove('fa-minus', 'rotate-180', 'text-white');
                    icon.classList.add('fa-plus', 'text-primary');
                    iconContainer.classList.remove('bg-primary');
                    iconContainer.classList.add('bg-light');
                } else {
                    content.classList.remove('hidden');
                    currentItem.classList.add('border-primary/30', 'shadow-md');
                    icon.classList.remove('fa-plus', 'text-primary');
                    icon.classList.add('fa-minus', 'rotate-180', 'text-white');
                    iconContainer.classList.remove('bg-light');
                    iconContainer.classList.add('bg-primary');
                }
            });
        });

        // 2. Tab Category Filtering Logic
        const tabButtons = document.querySelectorAll('.category-btn');
        const faqGroups = document.querySelectorAll('.faq-group');
        const searchInput = document.getElementById('faq-search');
        const noResults = document.getElementById('no-results');

        // Allow external links (Quick Help Cards) to trigger filter
        window.filterByCategory = function(category) {
            // Check if it's 'admissions' or 'classes', map to general or registration for demo purposes
            if(category === 'admissions') category = 'registration';
            if(category === 'classes') category = 'general';
            if(category === 'payments') category = 'general';
            
            const btn = document.querySelector(`.category-btn[data-filter="${category}"]`);
            if(btn) btn.click();
            
            // Scroll to categories smoothly
            document.getElementById('faq-categories').scrollIntoView({ behavior: 'smooth', block: 'start' });
        };

        function filterFaqs(category) {
            let visibleCount = 0;
            searchInput.value = ''; // Reset search when clicking tabs
            
            faqGroups.forEach(group => {
                const groupCategory = group.getAttribute('data-category');
                const items = group.querySelectorAll('.faq-item');
                
                if (category === 'all' || category === groupCategory) {
                    group.style.display = 'block';
                    items.forEach(item => item.style.display = 'block');
                    visibleCount++;
                } else {
                    group.style.display = 'none';
                }
            });

            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Update active button styles
                tabButtons.forEach(btn => {
                    btn.classList.remove('bg-primary', 'text-white', 'active', 'shadow-md');
                    btn.classList.add('bg-light', 'text-gray-500');
                });
                button.classList.remove('bg-light', 'text-gray-500');
                button.classList.add('bg-primary', 'text-white', 'active', 'shadow-md');

                const filter = button.getAttribute('data-filter');
                filterFaqs(filter);
            });
        });

        // 3. Live Search Filtering Logic
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            let totalVisibleItems = 0;

            // When searching, switch tab visually to 'All'
            if(term.length > 0) {
                tabButtons.forEach(btn => {
                    if(btn.getAttribute('data-filter') === 'all') {
                        btn.classList.remove('bg-light', 'text-gray-500');
                        btn.classList.add('bg-primary', 'text-white', 'active', 'shadow-md');
                    } else {
                        btn.classList.remove('bg-primary', 'text-white', 'active', 'shadow-md');
                        btn.classList.add('bg-light', 'text-gray-500');
                    }
                });
            }

            faqGroups.forEach(group => {
                let groupHasVisibleItems = false;
                const items = group.querySelectorAll('.faq-item');

                items.forEach(item => {
                    const questionText = item.querySelector('.faq-question').textContent.toLowerCase();
                    const answerText = item.querySelector('.faq-content').textContent.toLowerCase();

                    if (questionText.includes(term) || answerText.includes(term)) {
                        item.style.display = 'block';
                        groupHasVisibleItems = true;
                        totalVisibleItems++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Hide the whole group if no items match
                group.style.display = groupHasVisibleItems ? 'block' : 'none';
            });

            noResults.style.display = totalVisibleItems === 0 ? 'block' : 'none';
        });

    });
</script>
@endpush
