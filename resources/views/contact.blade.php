@extends('layouts.app')

@section('title', 'Contact Us | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- 1. Hero Section -->
<section class="relative pt-20 pb-32 px-4 md:px-12 bg-white overflow-hidden">
    <div class="absolute top-0 right-0 w-1/2 h-full bg-primary/5 rounded-bl-[100px] -z-10"></div>
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="fade-up z-10">
            <span class="inline-block py-1.5 px-4 rounded-full bg-accent/20 text-secondary font-bold text-sm mb-6 tracking-wide uppercase">
                Get In Touch
            </span>
            <h1 class="text-4xl lg:text-6xl font-black text-secondary leading-tight mb-6">
                Let's Build Your Child's <span class="text-primary">Learning Journey</span> Together
            </h1>
            <p class="text-lg text-gray-600 mb-8 max-w-lg leading-relaxed">
                Whether you have questions about our subjects, need help choosing the right path, or want to book a demo, our friendly team is here to assist you.
            </p>
            <div class="flex items-center gap-3 text-sm font-bold text-gray-400">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-primary">Contact</span>
            </div>
        </div>
        <div class="relative fade-up" style="transition-delay: 0.2s;">
            <div class="relative w-full max-w-lg mx-auto">
                <div class="absolute inset-0 bg-primary-100 rounded-full opacity-30 blur-3xl"></div>
                <svg class="relative z-10 w-full animate-float" viewBox="0 0 400 350" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="200" cy="175" r="140" fill="#FF4D8D" opacity="0.08"/>
                    <!-- Chat bubble -->
                    <rect x="80" y="80" width="240" height="150" rx="20" fill="white" stroke="#FF4D8D" stroke-width="2"/>
                    <polygon points="160,230 180,260 200,230" fill="white" stroke="#FF4D8D" stroke-width="2"/>
                    <rect x="160" y="228" width="40" height="4" fill="white"/>
                    <!-- Message lines -->
                    <rect x="110" y="110" width="120" height="8" rx="4" fill="#FF4D8D" opacity="0.25"/>
                    <rect x="110" y="130" width="160" height="8" rx="4" fill="#FF4D8D" opacity="0.15"/>
                    <rect x="110" y="150" width="100" height="8" rx="4" fill="#FF4D8D" opacity="0.1"/>
                    <!-- Reply bubble -->
                    <rect x="170" y="175" width="130" height="40" rx="12" fill="#FF4D8D" opacity="0.15"/>
                    <rect x="185" y="188" width="80" height="6" rx="3" fill="#FF4D8D" opacity="0.35"/>
                    <!-- Envelope icon -->
                    <rect x="270" y="60" width="60" height="45" rx="6" fill="#FFD166" opacity="0.9"/>
                    <polyline points="270,60 300,85 330,60" fill="none" stroke="white" stroke-width="2.5"/>
                    <!-- Decorative dots -->
                    <circle cx="70" cy="140" r="4" fill="#FF4D8D" opacity="0.3"/>
                    <circle cx="340" cy="200" r="4" fill="#FFD166" opacity="0.5"/>
                    <circle cx="310" cy="280" r="3" fill="#FF4D8D" opacity="0.25"/>
                    <circle cx="100" cy="270" r="3" fill="#FFD166" opacity="0.4"/>
                </svg>
                
                <!-- Floating Elements -->
                <div class="absolute top-1/4 -left-6 w-14 h-14 bg-white rounded-xl shadow-soft flex items-center justify-center text-primary text-xl animate-float" style="animation-delay: 0.8s;">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="absolute bottom-1/4 -right-4 w-16 h-16 bg-white rounded-2xl shadow-soft flex items-center justify-center text-accent text-2xl animate-float" style="animation-delay: 1.5s;">
                    <i class="fas fa-phone-volume"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Contact Information Cards -->
<section class="py-16 px-4 md:px-12 bg-light relative -mt-16 z-20">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Phone -->
            <div class="bg-white p-8 rounded-card shadow-soft hover:shadow-hover border border-gray-50 transition-all duration-300 hover:-translate-y-2 group fade-up">
                <div class="w-14 h-14 bg-primary-50 rounded-xl flex items-center justify-center text-primary-500 text-2xl mb-6 group-hover:bg-primary-500 group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-phone"></i>
                </div>
                <h3 class="text-xl font-extrabold text-secondary mb-2">Phone</h3>
                <p class="text-gray-500 text-sm">{{ \App\Models\Setting::get('support_phone') ?: '+1 (234) 567-890' }}</p>
            </div>
            
            <!-- Email -->
            <div class="bg-white p-8 rounded-card shadow-soft hover:shadow-hover border border-gray-50 transition-all duration-300 hover:-translate-y-2 group fade-up" style="transition-delay: 0.1s;">
                <div class="w-14 h-14 bg-pink-50 rounded-xl flex items-center justify-center text-primary text-2xl mb-6 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <h3 class="text-xl font-extrabold text-secondary mb-2">Email</h3>
                <p class="text-gray-500 text-sm">{{ \App\Models\Setting::get('support_email') ?: 'hello@drumroll.com' }}</p>
            </div>

            <!-- Online Classes -->
            <div class="bg-white p-8 rounded-card shadow-soft hover:shadow-hover border border-gray-50 transition-all duration-300 hover:-translate-y-2 group fade-up" style="transition-delay: 0.2s;">
                <div class="w-14 h-14 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-500 text-2xl mb-6 group-hover:bg-accent group-hover:text-secondary transition-colors duration-300">
                    <i class="fas fa-laptop-house"></i>
                </div>
                <h3 class="text-xl font-extrabold text-secondary mb-2">Online Classes</h3>
                <p class="text-gray-500 text-sm mb-1">Available Worldwide</p>
                <p class="text-gray-500 text-sm">Interactive 1-on-1 Sessions</p>
            </div>

            <!-- Support Hours -->
            <div class="bg-white p-8 rounded-card shadow-soft hover:shadow-hover border border-gray-50 transition-all duration-300 hover:-translate-y-2 group fade-up" style="transition-delay: 0.3s;">
                <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center text-green-500 text-2xl mb-6 group-hover:bg-green-500 group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="text-xl font-extrabold text-secondary mb-2">Business Hours</h3>
                @php $hours = \App\Models\Setting::get('business_hours'); @endphp
                @if($hours)
                <p class="text-gray-500 text-sm">{{ $hours }}</p>
                @else
                <p class="text-gray-500 text-sm mb-1">Mon - Fri: 8am - 8pm</p>
                <p class="text-gray-500 text-sm">Sat - Sun: 9am - 5pm</p>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- 3. Main Contact Section -->
<section class="py-24 px-4 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-16">
        
        <!-- Left: Large Contact Form -->
        <div class="lg:w-3/5 fade-up">
            <h2 class="text-3xl md:text-4xl font-black text-secondary mb-8">Send Us a Message</h2>
            
            <div id="form-message" class="hidden mb-6 p-4 rounded-xl text-sm font-bold"></div>

            <form id="contact-form" class="space-y-6 bg-light p-8 md:p-10 rounded-[2rem] border border-gray-50 shadow-sm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Student Name *</label>
                        <input type="text" id="student_name" name="student_name" required class="w-full bg-white border border-gray-200 hover:border-gray-300 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 disabled:opacity-50 disabled:bg-gray-50 rounded-xl py-4 px-5 text-sm transition-all shadow-sm" placeholder="Child's name">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Parent Name *</label>
                        <input type="text" id="parent_name" name="parent_name" required class="w-full bg-white border border-gray-200 hover:border-gray-300 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 disabled:opacity-50 disabled:bg-gray-50 rounded-xl py-4 px-5 text-sm transition-all shadow-sm" placeholder="Your name">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Email Address *</label>
                        <input type="email" id="email" name="email" required class="w-full bg-white border border-gray-200 hover:border-gray-300 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 disabled:opacity-50 disabled:bg-gray-50 rounded-xl py-4 px-5 text-sm transition-all shadow-sm" placeholder="john@example.com">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Mobile Number *</label>
                        <input type="tel" id="phone" required class="w-full bg-white border border-gray-200 hover:border-gray-300 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 disabled:opacity-50 disabled:bg-gray-50 rounded-xl py-4 px-5 text-sm transition-all shadow-sm" placeholder="+1 (234) 567-8900">
                        <input type="hidden" name="phone" id="phone-hidden">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Student Grade</label>
                        <select id="grade" name="grade" class="w-full bg-white border border-gray-200 hover:border-gray-300 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 disabled:opacity-50 disabled:bg-gray-50 rounded-xl py-4 px-5 text-sm transition-all shadow-sm text-gray-600">
                            <option value="">Select Grade</option>
                            @for($i = 1; $i <= 8; $i++)
                            <option value="Class {{ $i }}">Class {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Subject Interested</label>
                        <select id="subject" name="subject" class="w-full bg-white border border-gray-200 hover:border-gray-300 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 disabled:opacity-50 disabled:bg-gray-50 rounded-xl py-4 px-5 text-sm transition-all shadow-sm text-gray-600">
                            <option value="">Select Subject</option>
                            @foreach(\App\Models\Subject::where('status', 'active')->orderBy('sort_order')->get() as $subject)
                            <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                            @endforeach
                            <option value="Not Sure Yet">Not Sure Yet</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Message *</label>
                    <textarea id="message" name="message" required rows="5" class="w-full bg-white border border-gray-200 hover:border-gray-300 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 disabled:opacity-50 disabled:bg-gray-50 rounded-xl py-4 px-5 text-sm transition-all shadow-sm resize-none" placeholder="Tell us how we can help your child..."></textarea>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-black py-4 px-8 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    Send Message <i class="fas fa-paper-plane ml-2"></i>
                </button>
            </form>
        </div>

        <!-- Right: Information Card -->
        <div class="lg:w-2/5 fade-up" style="transition-delay: 0.2s;">
            <div class="bg-secondary text-white rounded-[2.5rem] p-10 relative overflow-hidden shadow-2xl h-full flex flex-col justify-center">
                <!-- Decorative BG -->
                <div class="absolute top-0 right-0 w-48 h-48 bg-primary/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
                
                <div class="relative z-10">
                    <h3 class="text-3xl font-black mb-8">Why Contact <span class="text-primary">Us?</span></h3>
                    
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-accent">
                                <i class="fas fa-desktop"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg mb-1">Book a Demo</h4>
                                <p class="text-gray-400 text-sm leading-relaxed">Experience our interactive platform firsthand before committing.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-primary">
                                <i class="fas fa-compass"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg mb-1">Learning Guidance</h4>
                                <p class="text-gray-400 text-sm leading-relaxed">Not sure where to start? We'll help assess your child's needs.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-primary-400">
                                <i class="fas fa-book-reader"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg mb-1">Homework Support</h4>
                                <p class="text-gray-400 text-sm leading-relaxed">Get immediate assistance for challenging school assignments.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-green-400">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg mb-1">Quick Response</h4>
                                <p class="text-gray-400 text-sm leading-relaxed">Our education consultants typically reply within 2 hours.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- 4. Book Free Consultation CTA -->
<section class="py-12 px-4 md:px-12 bg-white">
    <div class="max-w-5xl mx-auto bg-primary/5 rounded-[2.5rem] p-10 md:p-14 border border-primary/10 flex flex-col md:flex-row items-center justify-between gap-8 fade-up">
        <div>
            <h2 class="text-3xl md:text-4xl font-black text-secondary mb-3">Skip the form?</h2>
            <p class="text-gray-600 text-lg">Pick a time that works for you and let's chat directly.</p>
        </div>
        <a href="{{ route('student.booking.create') }}" class="shrink-0 bg-primary hover:bg-secondary text-white font-bold py-4 px-8 rounded-full shadow-lg transition-all duration-300 transform hover:-translate-y-1 whitespace-nowrap">
            Book Your Free Session
        </a>
    </div>
</section>

<!-- 5. FAQ Preview -->
<section class="py-24 px-4 md:px-12 bg-light">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl md:text-5xl font-black text-secondary mb-4">Got Questions? <span class="text-primary">We Have Answers</span></h2>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto">Here are a few common questions parents ask us.</p>
        </div>

        <div class="space-y-4 mb-12 fade-up" style="transition-delay: 0.1s;">
            <!-- FAQ 1 -->
            <div class="faq-item bg-white rounded-card shadow-soft border border-gray-50 overflow-hidden">
                <button class="w-full text-left px-6 py-5 font-bold text-secondary flex justify-between items-center focus:outline-none text-lg">
                    How quickly will someone contact me?
                    <i class="fas fa-plus text-primary text-sm transition-transform duration-300 faq-icon"></i>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-gray-500 leading-relaxed text-sm">
                    Our team strives to respond to all inquiries within 2-4 business hours during our regular support times.
                </div>
            </div>
            <!-- FAQ 2 -->
            <div class="faq-item bg-white rounded-card shadow-soft border border-gray-50 overflow-hidden">
                <button class="w-full text-left px-6 py-5 font-bold text-secondary flex justify-between items-center focus:outline-none text-lg">
                    Do I need to pay for a consultation?
                    <i class="fas fa-plus text-primary text-sm transition-transform duration-300 faq-icon"></i>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-gray-500 leading-relaxed text-sm">
                    No, the initial consultation and demo session are completely free. We want to ensure our platform is the right fit for your child.
                </div>
            </div>
            <!-- FAQ 3 -->
            <div class="faq-item bg-white rounded-card shadow-soft border border-gray-50 overflow-hidden">
                <button class="w-full text-left px-6 py-5 font-bold text-secondary flex justify-between items-center focus:outline-none text-lg">
                    What equipment does my child need?
                    <i class="fas fa-plus text-primary text-sm transition-transform duration-300 faq-icon"></i>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-gray-500 leading-relaxed text-sm">
                    A laptop or tablet with a stable internet connection, a webcam, and a microphone. We handle the rest through our interactive platform.
                </div>
            </div>
            <!-- FAQ 4 -->
            <div class="faq-item bg-white rounded-card shadow-soft border border-gray-50 overflow-hidden">
                <button class="w-full text-left px-6 py-5 font-bold text-secondary flex justify-between items-center focus:outline-none text-lg">
                    Can I choose my child's tutor?
                    <i class="fas fa-plus text-primary text-sm transition-transform duration-300 faq-icon"></i>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-gray-500 leading-relaxed text-sm">
                    Yes! We match tutors based on your child's needs, but you always have the option to request a specific tutor or change tutors if needed.
                </div>
            </div>
        </div>

        <div class="text-center fade-up" style="transition-delay: 0.2s;">
            <a href="{{ route('faq') }}" class="inline-flex items-center gap-2 font-bold text-secondary hover:text-primary transition border-b-2 border-primary/20 hover:border-primary pb-1">
                View All FAQs <i class="fas fa-chevron-right text-xs"></i>
            </a>
        </div>
    </div>
</section>

<!-- 6. Learning Support Promise & 7. Location (Combined) -->
<section class="py-24 px-4 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-16 items-center">
        <!-- Promise -->
        <div class="lg:w-1/2 fade-up">
            <span class="inline-block py-1.5 px-4 rounded-full bg-green-100 text-green-600 font-bold text-sm mb-6 tracking-wide uppercase">
                Our Guarantee
            </span>
            <h2 class="text-3xl md:text-5xl font-black text-secondary mb-6">Our Learning Support <span class="text-primary">Promise</span></h2>
            <p class="text-gray-600 mb-8 text-lg">When you reach out to Drumroll, you aren't just getting an automated reply. You are getting a dedicated team committed to your child's success.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="flex items-start gap-3">
                    <i class="fas fa-check-circle text-primary text-xl mt-1"></i>
                    <div>
                        <h5 class="font-bold text-secondary mb-1">Fast Response</h5>
                        <p class="text-sm text-gray-500">Quick answers to your queries.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fas fa-check-circle text-primary text-xl mt-1"></i>
                    <div>
                        <h5 class="font-bold text-secondary mb-1">Expert Guidance</h5>
                        <p class="text-sm text-gray-500">Advice from real educators.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fas fa-check-circle text-primary text-xl mt-1"></i>
                    <div>
                        <h5 class="font-bold text-secondary mb-1">Flexible Schedules</h5>
                        <p class="text-sm text-gray-500">Learning on your time.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fas fa-check-circle text-primary text-xl mt-1"></i>
                    <div>
                        <h5 class="font-bold text-secondary mb-1">Safe Environment</h5>
                        <p class="text-sm text-gray-500">100% secure online classrooms.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location / Online -->
        <div class="lg:w-1/2 fade-up" style="transition-delay: 0.2s;">
            <div class="bg-light rounded-[2.5rem] p-8 border border-gray-100 shadow-soft relative overflow-hidden">
                <!-- Map Placeholder (Stylized representation of global reach) -->
                <div class="w-full h-64 bg-primary-50 rounded-2xl mb-8 relative flex items-center justify-center overflow-hidden">
                    <svg class="w-full h-full opacity-30" viewBox="0 0 400 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Simplified world map dots -->
                        <!-- North America -->
                        <circle cx="80" cy="60" r="3" fill="#3B82F6"/><circle cx="90" cy="55" r="2.5" fill="#3B82F6"/>
                        <circle cx="75" cy="70" r="2" fill="#3B82F6"/><circle cx="85" cy="65" r="2.5" fill="#3B82F6"/>
                        <circle cx="95" cy="62" r="2" fill="#3B82F6"/><circle cx="70" cy="62" r="2" fill="#3B82F6"/>
                        <!-- South America -->
                        <circle cx="105" cy="120" r="2.5" fill="#3B82F6"/><circle cx="100" cy="130" r="2" fill="#3B82F6"/>
                        <circle cx="110" cy="125" r="2" fill="#3B82F6"/><circle cx="103" cy="140" r="2.5" fill="#3B82F6"/>
                        <!-- Europe -->
                        <circle cx="190" cy="50" r="2.5" fill="#3B82F6"/><circle cx="200" cy="48" r="2" fill="#3B82F6"/>
                        <circle cx="195" cy="55" r="2" fill="#3B82F6"/><circle cx="205" cy="52" r="2.5" fill="#3B82F6"/>
                        <!-- Africa -->
                        <circle cx="200" cy="90" r="2.5" fill="#3B82F6"/><circle cx="195" cy="100" r="2" fill="#3B82F6"/>
                        <circle cx="205" cy="95" r="2" fill="#3B82F6"/><circle cx="200" cy="110" r="2.5" fill="#3B82F6"/>
                        <!-- Asia -->
                        <circle cx="260" cy="55" r="3" fill="#3B82F6"/><circle cx="270" cy="50" r="2.5" fill="#3B82F6"/>
                        <circle cx="280" cy="60" r="2" fill="#3B82F6"/><circle cx="290" cy="55" r="2.5" fill="#3B82F6"/>
                        <circle cx="265" cy="65" r="2" fill="#3B82F6"/><circle cx="275" cy="70" r="2" fill="#3B82F6"/>
                        <circle cx="255" cy="48" r="2" fill="#3B82F6"/><circle cx="285" cy="48" r="2" fill="#3B82F6"/>
                        <!-- India highlight -->
                        <circle cx="265" cy="80" r="4" fill="#FF4D8D" opacity="0.8"/>
                        <circle cx="265" cy="80" r="7" fill="#FF4D8D" opacity="0.2"/>
                        <!-- Australia -->
                        <circle cx="320" cy="120" r="2.5" fill="#3B82F6"/><circle cx="330" cy="118" r="2" fill="#3B82F6"/>
                        <circle cx="325" cy="125" r="2" fill="#3B82F6"/>
                    </svg>
                    <div class="absolute bg-white/90 backdrop-blur-sm py-2 px-4 rounded-xl shadow-sm font-bold text-secondary flex items-center gap-2">
                        <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span> Classes Available Worldwide
                    </div>
                </div>
                <h3 class="text-2xl font-black text-secondary mb-3">100% Online, 100% Engaging</h3>
                <p class="text-gray-500 text-sm leading-relaxed">No matter where you are located, Drumroll brings the best educators right to your living room. Experience seamless interactive learning without the commute.</p>
            </div>
        </div>
    </div>
</section>

<!-- 8. Final CTA -->
<section class="py-20 px-4 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="bg-gradient-to-r from-secondary to-blue-900 rounded-[3rem] p-10 md:p-20 text-center text-white relative overflow-hidden fade-up">
            <div class="relative z-10 max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-5xl font-black mb-6 leading-tight">Ready to Start?</h2>
                <p class="text-white/80 text-lg md:text-xl mb-10 font-medium">Join the hundreds of parents who have already transformed their child's learning experience.</p>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="{{ route('student.booking.create') }}" class="w-full sm:w-auto bg-primary hover:bg-white hover:text-primary text-white font-black py-4 px-10 rounded-full shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        Book Free Session
                    </a>
                </div>
            </div>
            <!-- Decorative backgrounds -->
            <div class="absolute top-0 left-0 p-10 opacity-10"><i class="fas fa-paper-plane text-[150px]"></i></div>
            <div class="absolute bottom-0 right-0 p-10 opacity-10"><i class="fas fa-globe text-[200px]"></i></div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // intl-tel-input setup for contact form
        const phoneInput = document.getElementById('phone');
        const phoneHidden = document.getElementById('phone-hidden');
        if (phoneInput && phoneHidden) {
            const iti = intlTelInput(phoneInput, {
                initialCountry: 'ca',
                preferredCountries: ['ca', 'us', 'gb', 'in', 'ae', 'sa'],
                separateDialCode: true,
                utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/utils.js'
            });
            // Update hidden input before form submit
            document.getElementById('contact-form').addEventListener('submit', () => {
                phoneHidden.value = iti.getNumber();
            });
        }

        // Form Validation & Success Message (Vanilla JS)
        const form = document.getElementById('contact-form');
        const messageDiv = document.getElementById('form-message');

        if(form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
                submitBtn.disabled = true;
                messageDiv.classList.add('hidden');

                try {
                    const formData = new FormData(form);
                    const response = await fetch('{{ route("contact.submit") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        messageDiv.classList.remove('hidden', 'bg-red-100', 'text-red-700');
                        messageDiv.classList.add('bg-green-100', 'text-green-700');
                        messageDiv.innerHTML = '<i class="fas fa-check-circle mr-2"></i> ' + (result.message || 'Your message has been sent. Our team will contact you shortly.');
                        form.reset();
                    } else {
                        const errors = result.errors ? Object.values(result.errors).flat().join(' ') : (result.message || 'Something went wrong. Please try again.');
                        messageDiv.classList.remove('hidden', 'bg-green-100', 'text-green-700');
                        messageDiv.classList.add('bg-red-100', 'text-red-700');
                        messageDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i> ' + errors;
                    }
                } catch (error) {
                    console.error('Contact form error:', error);
                    messageDiv.classList.remove('hidden', 'bg-green-100', 'text-green-700');
                    messageDiv.classList.add('bg-red-100', 'text-red-700');
                    messageDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i> Network error. Please check your connection and try again.';
                } finally {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    setTimeout(() => { messageDiv.classList.add('hidden'); }, 8000);
                }
            });
        }

        // FAQ Accordion Logic
        const faqItems = document.querySelectorAll('.faq-item button');
        
        faqItems.forEach(item => {
            item.addEventListener('click', () => {
                const content = item.nextElementSibling;
                const icon = item.querySelector('.faq-icon');
                
                // Toggle current content
                content.classList.toggle('hidden');
                
                // Toggle icon
                if (content.classList.contains('hidden')) {
                    icon.classList.remove('fa-minus');
                    icon.classList.add('fa-plus');
                    icon.classList.remove('rotate-180');
                } else {
                    icon.classList.remove('fa-plus');
                    icon.classList.add('fa-minus');
                    icon.classList.add('rotate-180');
                }
            });
        });
    });
</script>
@endpush
