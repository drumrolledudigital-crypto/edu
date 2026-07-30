<!-- Mobile Top App Bar -->
<header class="lg:hidden fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl border-b border-gray-100 safe-area-top">
    <div class="flex items-center justify-between px-4 py-3">
        <!-- Hamburger Menu -->
        <button onclick="toggleMobileSidebar()" class="w-10 h-10 rounded-xl bg-light flex items-center justify-center text-secondary active:bg-gray-100 transition-colors">
            <i class="fas fa-bars text-sm"></i>
        </button>

        <!-- Logo -->
        <a href="{{ route('student.dashboard') }}" class="flex items-center">
            @php $sLogo = \App\Models\Setting::get('website_logo') ?: 'assets/admin/logo/admin logo.png'; @endphp
            <img src="{{ asset($sLogo) }}" alt="{{ \App\Models\Setting::get('platform_name', 'Drumroll') }} Logo" class="h-10 w-auto object-contain">
        </a>

        <!-- Right Actions -->
        <div class="flex items-center gap-2">
            @if(request()->routeIs('student.dashboard'))
                <!-- Notification Bell for Dashboard -->
                <button class="relative w-10 h-10 rounded-full bg-light flex items-center justify-center text-gray-500 active:bg-gray-100 transition-colors">
                    <i class="fas fa-bell text-sm"></i>
                    @php $unreadCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0; @endphp
                    @if($unreadCount > 0)
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-primary rounded-full border-2 border-white"></span>
                    @endif
                </button>
            @endif
            <!-- Avatar -->
            <a href="{{ route('student.profile') }}" class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm border border-primary/20 active:scale-95 transition-transform">
                {{ substr(auth()->user()->name, 0, 1) }}
            </a>
        </div>
    </div>
</header>
