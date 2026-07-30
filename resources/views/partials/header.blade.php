<!-- Main Header -->
<header id="main-header" class="bg-white sticky top-0 z-50 transition-all duration-300 py-4 lg:py-5 px-4 md:px-8 xl:px-12 w-full border-b border-gray-100">
    <div class="max-w-[1400px] mx-auto flex justify-between items-center gap-4">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center shrink-0">
            @php $logo = \App\Models\Setting::get('website_logo') ?: 'assets/admin/logo/admin logo.png'; @endphp
            <img src="{{ asset($logo) }}" alt="{{ \App\Models\Setting::get('platform_name', 'Drumroll') }} Logo" class="h-12 w-auto object-contain">
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center gap-4 xl:gap-6 font-bold text-[13px] tracking-wide">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-primary' : 'text-secondary hover:text-primary' }} transition relative group">
                Home
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full {{ request()->routeIs('home') ? 'w-full' : '' }}"></span>
            </a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-primary' : 'text-secondary hover:text-primary' }} transition relative group">
                About Us
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full {{ request()->routeIs('about') ? 'w-full' : '' }}"></span>
            </a>
            <a href="{{ route('subjects.index') }}" class="{{ request()->routeIs('subjects.index') ? 'text-primary' : 'text-secondary hover:text-primary' }} transition relative group">
                Subjects
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full {{ request()->routeIs('subjects.index') ? 'w-full' : '' }}"></span>
            </a>
            <a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.*') ? 'text-primary' : 'text-secondary hover:text-primary' }} transition relative group">
                Books
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full {{ request()->routeIs('books.*') ? 'w-full' : '' }}"></span>
            </a>
            <a href="{{ route('doubts.create') }}" class="{{ request()->routeIs('doubts.create') ? 'text-primary' : 'text-secondary hover:text-primary' }} transition relative group">
                Submit Doubt
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full {{ request()->routeIs('doubts.create') ? 'w-full' : '' }}"></span>
            </a>
            <a href="{{ route('faq') }}" class="{{ request()->routeIs('faq') ? 'text-primary' : 'text-secondary hover:text-primary' }} transition relative group">
                FAQ
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full {{ request()->routeIs('faq') ? 'w-full' : '' }}"></span>
            </a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-primary' : 'text-secondary hover:text-primary' }} transition relative group">
                Contact
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full {{ request()->routeIs('contact') ? 'w-full' : '' }}"></span>
            </a>
        </nav>

        <!-- CTA & Hamburger -->
        <div class="flex items-center gap-3 xl:gap-5 shrink-0">
            <div class="hidden xl:flex items-center gap-4">
                @guest
                    <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'text-primary' : 'text-secondary hover:text-primary' }} font-bold text-[13px] transition flex items-center gap-1.5 group">
                        <i class="fas fa-user {{ request()->routeIs('login') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} transition-colors"></i> Login
                    </a>
                    <a href="{{ route('student.register') }}" class="{{ request()->routeIs('student.register') ? 'text-primary' : 'text-secondary hover:text-primary' }} font-bold text-[13px] transition flex items-center gap-1.5 group">
                        <i class="fas fa-heart {{ request()->routeIs('student.register') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} transition-colors"></i> Register
                    </a>
                @endguest
                @auth
                    <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'text-primary' : 'text-secondary hover:text-primary' }} font-bold text-[13px] transition flex items-center gap-1.5 group">
                        <i class="fas fa-tachometer-alt {{ request()->routeIs('student.dashboard') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} transition-colors"></i> Dashboard
                    </a>
                    <form method="POST" action="{{ route('student.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-secondary hover:text-red-500 font-bold text-[13px] transition flex items-center gap-1.5 group focus:outline-none">
                            <i class="fas fa-sign-out-alt text-gray-400 group-hover:text-red-500 transition-colors"></i> Logout
                        </button>
                    </form>
                @endauth
            </div>

            <a href="{{ route('student.booking.create') }}" class="hidden sm:inline-flex bg-accent hover:bg-secondary text-secondary hover:text-white font-bold py-2.5 px-5 xl:px-6 rounded-full text-[13px] shadow-sm hover:shadow-md transition-all duration-300">
                Book Free Session
            </a>
            
            <button id="mobile-menu-btn" class="lg:hidden text-secondary text-2xl focus:outline-none p-2">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden absolute top-full left-0 w-full bg-white border-t border-gray-100 flex-col p-6 shadow-xl lg:hidden animate-in slide-in-from-top duration-300 max-h-[85vh] overflow-y-auto">
        <nav class="flex flex-col gap-4 font-bold text-base text-secondary">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-primary' : 'hover:text-primary' }} flex items-center gap-3">
                <i class="fas fa-home w-5 text-center {{ request()->routeIs('home') ? 'text-primary' : 'text-gray-400' }}"></i> Home
            </a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-primary' : 'hover:text-primary' }} flex items-center gap-3">
                <i class="fas fa-info-circle w-5 text-center {{ request()->routeIs('about') ? 'text-primary' : 'text-gray-400' }}"></i> About
            </a>
            <a href="{{ route('subjects.index') }}" class="{{ request()->routeIs('subjects.index') ? 'text-primary' : 'hover:text-primary' }} flex items-center gap-3">
                <i class="fas fa-book w-5 text-center {{ request()->routeIs('subjects.index') ? 'text-primary' : 'text-gray-400' }}"></i> Subjects
            </a>
            <a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.*') ? 'text-primary' : 'hover:text-primary' }} flex items-center gap-3">
                <i class="fas fa-book-reader w-5 text-center {{ request()->routeIs('books.*') ? 'text-primary' : 'text-gray-400' }}"></i> Books
            </a>
            <a href="{{ route('doubts.create') }}" class="{{ request()->routeIs('doubts.create') ? 'text-primary' : 'hover:text-primary' }} flex items-center gap-3">
                <i class="fas fa-question-circle w-5 text-center {{ request()->routeIs('doubts.create') ? 'text-primary' : 'text-gray-400' }}"></i> Submit Doubt
            </a>
            <a href="{{ route('faq') }}" class="{{ request()->routeIs('faq') ? 'text-primary' : 'hover:text-primary' }} flex items-center gap-3">
                <i class="fas fa-comments w-5 text-center {{ request()->routeIs('faq') ? 'text-primary' : 'text-gray-400' }}"></i> FAQ
            </a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-primary' : 'hover:text-primary' }} flex items-center gap-3">
                <i class="fas fa-phone-alt w-5 text-center {{ request()->routeIs('contact') ? 'text-primary' : 'text-gray-400' }}"></i> Contact
            </a>
            
            <hr class="border-gray-100 my-2">
            
            @guest
                <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'text-primary' : 'hover:text-primary' }} flex items-center gap-3">
                    <i class="fas fa-user w-5 text-center {{ request()->routeIs('login') ? 'text-primary' : 'text-gray-400' }}"></i> Student Login
                </a>
                <a href="{{ route('student.register') }}" class="{{ request()->routeIs('student.register') ? 'text-primary' : 'hover:text-primary' }} flex items-center gap-3">
                    <i class="fas fa-heart w-5 text-center {{ request()->routeIs('student.register') ? 'text-primary' : 'text-gray-400' }}"></i> Student Register
                </a>
            @endguest
            @auth
                <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'text-primary' : 'hover:text-primary' }} flex items-center gap-3">
                    <i class="fas fa-tachometer-alt w-5 text-center {{ request()->routeIs('student.dashboard') ? 'text-primary' : 'text-gray-400' }}"></i> Dashboard
                </a>
                <form method="POST" action="{{ route('student.logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-red-500 flex items-center gap-3 w-full text-left focus:outline-none">
                        <i class="fas fa-sign-out-alt w-5 text-center text-gray-400 hover:text-red-500 transition-colors"></i> Logout
                    </button>
                </form>
            @endauth
            
            <hr class="border-gray-100 my-2">
            
            <a href="{{ route('student.booking.create') }}" class="bg-accent text-secondary hover:bg-secondary hover:text-white transition-colors text-center py-3.5 rounded-full mt-2 flex justify-center items-center gap-2">
                 Book Free Session
            </a>
        </nav>
    </div>
</header>
