<!-- Main Footer -->
<footer class="bg-white pt-24 pb-12 px-4 md:px-12 border-t border-gray-100">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-16">
            <!-- Col 1: Logo & Social -->
            <div class="lg:col-span-2 pr-0 lg:pr-12">
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-8">
                    @php $fLogo = \App\Models\Setting::get('website_logo') ?: 'assets/admin/logo/admin logo.png'; @endphp
                    <img src="{{ asset($fLogo) }}" alt="{{ \App\Models\Setting::get('platform_name', 'Drumroll') }}" class="h-10 w-auto object-contain">
                </a>
                <p class="text-gray-500 mb-8 leading-relaxed max-w-sm">{{ \App\Models\Setting::get('footer_text') ?: 'Empowering children to achieve their biggest dreams through engaging, personalized online learning experiences.' }}</p>
                <div class="flex items-center gap-4">
                    @php
                    $socialIcons = [
                        'social_facebook' => 'fab fa-facebook-f',
                        'social_instagram' => 'fab fa-instagram',
                        'social_twitter' => 'fab fa-twitter',
                        'social_linkedin' => 'fab fa-linkedin-in',
                        'social_youtube' => 'fab fa-youtube',
                        'social_telegram' => 'fab fa-telegram-plane',
                        'social_github' => 'fab fa-github',
                        'social_tiktok' => 'fab fa-tiktok',
                        'social_pinterest' => 'fab fa-pinterest-p',
                        'social_threads' => 'fab fa-threads',
                        'social_discord' => 'fab fa-discord',
                    ];
                    @endphp
                    @foreach($socialIcons as $key => $icon)
                    @php $url = \App\Models\Setting::get($key); @endphp
                    @if($url)
                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-light flex items-center justify-center text-secondary hover:bg-primary hover:text-white transition-all"><i class="{{ $icon }}"></i></a>
                    @endif
                    @endforeach
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div>
                <h4 class="font-bold text-secondary mb-8 uppercase text-xs tracking-widest">Quick Links</h4>
                <ul class="space-y-4 text-sm font-medium text-gray-500">
                    <li><a href="{{ route('home') }}" class="hover:text-primary transition">Home</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-primary transition">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-primary transition">Contact</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-primary transition">Student Login</a></li>
                    <li><a href="{{ route('student.register') }}" class="hover:text-primary transition">Register</a></li>
                </ul>
            </div>

            <!-- Col 3: Subjects -->
            <div>
                <h4 class="font-bold text-secondary mb-8 uppercase text-xs tracking-widest">Subjects</h4>
                <ul class="space-y-4 text-sm font-medium text-gray-500">
                    @foreach(\App\Models\Subject::where('status', 'active')->orderBy('sort_order')->take(4)->get() as $subject)
                    <li><a href="{{ route('subjects.index') }}" class="hover:text-primary transition">{{ $subject->name }}</a></li>
                    @endforeach
                    <li><a href="{{ route('student.booking.create') }}" class="hover:text-primary transition">Book a Session</a></li>
                    <li><a href="{{ route('doubts.create') }}" class="hover:text-primary transition">Submit Doubt</a></li>
                </ul>
            </div>

            <!-- Col 4: Support -->
            <div>
                <h4 class="font-bold text-secondary mb-8 uppercase text-xs tracking-widest">Support</h4>
                <ul class="space-y-4 text-sm font-medium text-gray-500">
                    <li><a href="{{ route('faq') }}" class="hover:text-primary transition">FAQs</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-primary transition">Help Center</a></li>
                    <li><a href="{{ route('privacy.policy') }}" class="hover:text-primary transition">Privacy Policy</a></li>
                    <li><a href="{{ route('terms.conditions') }}" class="hover:text-primary transition">Terms of Service</a></li>
                    <li><a href="{{ route('refund.policy') }}" class="hover:text-primary transition">Refund Policy</a></li>
                    <li><a href="{{ route('cancellation.policy') }}" class="hover:text-primary transition">Cancellation Policy</a></li>
                </ul>
            </div>
        </div>

        <div class="pt-12 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-sm text-gray-400 font-medium">&copy; {{ date('Y') }} {{ \App\Models\Setting::get('platform_name', 'Drumroll') }}. {{ \App\Models\Setting::get('copyright_text') ?: 'All rights reserved.' }}</p>
            <div class="flex flex-col sm:flex-row items-center gap-4 md:gap-8 text-sm font-bold text-secondary/60">
                <a href="tel:{{ \App\Models\Setting::get('support_phone', '+1 (234) 567-890') }}" class="flex items-center gap-2 hover:text-primary transition"><i class="fas fa-phone text-primary"></i> {{ \App\Models\Setting::get('support_phone', '+1 (234) 567-890') }}</a>
                <a href="mailto:{{ \App\Models\Setting::get('support_email', 'hello@drumroll.com') }}" class="flex items-center gap-2 hover:text-primary transition"><i class="fas fa-envelope text-primary"></i> {{ \App\Models\Setting::get('support_email', 'hello@drumroll.com') }}</a>
            </div>
        </div>
    </div>
</footer>

