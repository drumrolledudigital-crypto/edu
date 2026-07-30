<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#FFFFFF">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>@yield('title', \App\Models\Setting::get('platform_name', 'Drumroll') . ' | Student Panel')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- intl-tel-input CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/css/intlTelInput.min.css">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2596be',
                        secondary: '#1A2B48',
                        accent: '#FFD166',
                        light: '#F8F9FA',
                        navy: '#1A2B48',
                    },
                    borderRadius: {
                        'brand': '20px',
                        'card': '16px',
                        '2xl': '16px',
                        '3xl': '20px',
                        '4xl': '24px',
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'card': '0 2px 12px -1px rgba(0, 0, 0, 0.06)',
                        'elevated': '0 8px 30px -4px rgba(0, 0, 0, 0.08)',
                        'float': '0 12px 40px -8px rgba(255, 77, 141, 0.15)',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        * { -webkit-tap-highlight-color: transparent; }

        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Safe area for notch devices */
        .safe-area-top { padding-top: env(safe-area-inset-top); }
        .safe-area-bottom { padding-bottom: env(safe-area-inset-bottom); }

        /* Mobile app padding */
        .mobile-app-layout {
            padding-top: calc(56px + env(safe-area-inset-top, 0px));
            padding-bottom: calc(72px + env(safe-area-inset-bottom, 0px));
        }

        /* Smooth page transitions */
        .page-enter { animation: pageEnter 0.3s ease-out; }
        @keyframes pageEnter {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Card press effect */
        .card-press { transition: transform 0.15s ease, box-shadow 0.15s ease; }
        .card-press:active { transform: scale(0.98); }

        /* Fade in up animation */
        .fade-in-up {
            opacity: 0;
            transform: translateY(16px);
            animation: fadeInUp 0.4s ease-out forwards;
        }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }

        /* Stagger children */
        .stagger > *:nth-child(1) { animation-delay: 0.05s; }
        .stagger > *:nth-child(2) { animation-delay: 0.1s; }
        .stagger > *:nth-child(3) { animation-delay: 0.15s; }
        .stagger > *:nth-child(4) { animation-delay: 0.2s; }
        .stagger > *:nth-child(5) { animation-delay: 0.25s; }
        .stagger > *:nth-child(6) { animation-delay: 0.3s; }
        .stagger > *:nth-child(7) { animation-delay: 0.35s; }
        .stagger > *:nth-child(8) { animation-delay: 0.4s; }

        /* Scrollbar hide for horizontal scroll */
        .scroll-hide::-webkit-scrollbar { display: none; }
        .scroll-hide { -ms-overflow-style: none; scrollbar-width: none; }

        /* Bottom nav safe area */
        #student-bottom-nav { padding-bottom: env(safe-area-inset-bottom, 0px); }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 100px; }

        /* Haptic-like button feedback */
        .btn-haptic:active { transform: scale(0.96); transition: transform 0.1s ease; }

        /* Gradient backgrounds */
        .gradient-primary { background: linear-gradient(135deg, #FF4D8D 0%, #FF7EB3 100%); }
        .gradient-navy { background: linear-gradient(135deg, #1A2B48 0%, #2D4A7A 100%); }
        .gradient-accent { background: linear-gradient(135deg, #FFD166 0%, #FFE08A 100%); }

        /* Mobile table card conversion */
        @media (max-width: 1023px) {
            .mobile-card-list .table-row { display: none; }
        }

        /* Skeleton loading */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton 1.5s infinite;
        }
        @keyframes skeleton {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Mobile sidebar transitions */
        #mobile-sidebar {
            will-change: transform;
        }
        #mobile-sidebar.open {
            transform: translateX(0);
        }
        #mobile-sidebar-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        /* Sidebar nav item hover effect */
        #mobile-sidebar nav a {
            position: relative;
        }
        #mobile-sidebar nav a:active {
            transform: scale(0.98);
            transition: transform 0.1s ease;
        }
    </style>
</head>
<body class="bg-light font-sans text-secondary overflow-x-hidden">

    <!-- Mobile Sidebar Overlay -->
    <div id="mobile-sidebar-overlay" class="lg:hidden fixed inset-0 z-[60] bg-black/40 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300" onclick="toggleMobileSidebar()"></div>

    <!-- Mobile Sidebar Panel -->
    <div id="mobile-sidebar" class="lg:hidden fixed top-0 left-0 bottom-0 z-[70] w-[280px] bg-white transform -translate-x-full transition-transform duration-300 ease-out shadow-2xl">
        <div class="flex flex-col h-full">
            <!-- Sidebar Header -->
            <div class="gradient-navy p-4 safe-area-top">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        @php $sLogo2 = \App\Models\Setting::get('website_logo') ?: 'assets/admin/logo/admin logo.png'; @endphp
                        <img src="{{ asset($sLogo2) }}" alt="{{ \App\Models\Setting::get('platform_name', 'Drumroll') }} Logo" class="h-10 w-auto object-contain">
                    </div>
                    <button onclick="toggleMobileSidebar()" class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex-1 overflow-y-auto py-3 px-3">
                <!-- Main Menu -->
                <div class="mb-2">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-2">Menu</p>
                    <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('student.dashboard') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-light' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('student.dashboard') ? 'bg-primary/20' : 'bg-gray-100' }} flex items-center justify-center text-xs">
                            <i class="fas fa-home {{ request()->routeIs('student.dashboard') ? 'text-primary' : 'text-gray-500' }}"></i>
                        </div>
                        Dashboard
                    </a>
                </div>

                <div class="h-px bg-gray-100 my-2 mx-3"></div>

                <!-- Quick Actions -->
                <div class="mb-2">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-2">Quick Actions</p>
                    <a href="{{ route('student.booking.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('student.booking.create') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-light' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('student.booking.create') ? 'bg-primary/20' : 'bg-primary-50' }} flex items-center justify-center text-xs">
                            <i class="fas fa-calendar-plus {{ request()->routeIs('student.booking.create') ? 'text-primary' : 'text-primary-500' }}"></i>
                        </div>
                        Book Session
                    </a>
                    <a href="{{ route('doubts.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('doubts.create') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-light' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('doubts.create') ? 'bg-primary/20' : 'bg-purple-50' }} flex items-center justify-center text-xs">
                            <i class="fas fa-question-circle {{ request()->routeIs('doubts.create') ? 'text-primary' : 'text-purple-500' }}"></i>
                        </div>
                        Submit Doubt
                    </a>
                </div>

                <div class="h-px bg-gray-100 my-2 mx-3"></div>

                <!-- My Activity -->
                <div class="mb-2">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-2">My Activity</p>
                    <a href="{{ route('student.doubts.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('student.doubts.index*') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-light' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('student.doubts.index*') ? 'bg-primary/20' : 'bg-gray-100' }} flex items-center justify-center text-xs">
                            <i class="fas fa-book-open {{ request()->routeIs('student.doubts.index*') ? 'text-primary' : 'text-gray-500' }}"></i>
                        </div>
                        My Doubts
                    </a>
                    <a href="{{ route('student.booking.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('student.booking.index*') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-light' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('student.booking.index*') ? 'bg-primary/20' : 'bg-gray-100' }} flex items-center justify-center text-xs">
                            <i class="fas fa-calendar-check {{ request()->routeIs('student.booking.index*') ? 'text-primary' : 'text-gray-500' }}"></i>
                        </div>
                        My Bookings
                    </a>
                </div>

                <div class="h-px bg-gray-100 my-2 mx-3"></div>

                <!-- Sessions & Payments -->
                <div class="h-px bg-gray-100 my-2 mx-3"></div>

                <!-- Books -->
                <div class="mb-2">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-2">Books</p>
                    <a href="{{ route('student.books.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('student.books.*') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-light' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('student.books.*') ? 'bg-primary/20' : 'bg-orange-50' }} flex items-center justify-center text-xs">
                            <i class="fas fa-book-open {{ request()->routeIs('student.books.*') ? 'text-primary' : 'text-orange-500' }}"></i>
                        </div>
                        My Books
                    </a>
                    <a href="{{ route('student.cart.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('student.cart.*') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-light' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('student.cart.*') ? 'bg-primary/20' : 'bg-pink-50' }} flex items-center justify-center text-xs">
                            <i class="fas fa-shopping-cart {{ request()->routeIs('student.cart.*') ? 'text-primary' : 'text-pink-500' }}"></i>
                        </div>
                        Cart
                    </a>
                </div>

                <div class="h-px bg-gray-100 my-2 mx-3"></div>

                <div class="mb-2">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-2">Sessions & Payments</p>
                    <a href="{{ route('student.sessions.upcoming') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('student.sessions.upcoming') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-light' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('student.sessions.upcoming') ? 'bg-primary/20' : 'bg-emerald-50' }} flex items-center justify-center text-xs">
                            <i class="fas fa-video {{ request()->routeIs('student.sessions.upcoming') ? 'text-primary' : 'text-emerald-500' }}"></i>
                        </div>
                        Upcoming Sessions
                    </a>
                    <a href="{{ route('student.sessions.past') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('student.sessions.past') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-light' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('student.sessions.past') ? 'bg-primary/20' : 'bg-gray-100' }} flex items-center justify-center text-xs">
                            <i class="fas fa-history {{ request()->routeIs('student.sessions.past') ? 'text-primary' : 'text-gray-500' }}"></i>
                        </div>
                        Past Sessions
                    </a>
                    <a href="{{ route('student.payments.history') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('student.payments.history') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-light' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('student.payments.history') ? 'bg-primary/20' : 'bg-amber-50' }} flex items-center justify-center text-xs">
                            <i class="fas fa-credit-card {{ request()->routeIs('student.payments.history') ? 'text-primary' : 'text-amber-500' }}"></i>
                        </div>
                        Payment History
                    </a>
                    <a href="{{ route('student.invoices.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('student.invoices.*') ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-light' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('student.invoices.*') ? 'bg-primary/20' : 'bg-purple-50' }} flex items-center justify-center text-xs">
                            <i class="fas fa-file-invoice {{ request()->routeIs('student.invoices.*') ? 'text-primary' : 'text-purple-500' }}"></i>
                        </div>
                        Invoices
                    </a>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="border-t border-gray-100 safe-area-bottom p-3">
                <div class="flex items-center gap-2">
                    <!-- Profile Card -->
                    <a href="{{ route('student.profile') }}" class="flex items-center gap-2.5 flex-1 min-w-0 p-2.5 rounded-xl bg-light hover:bg-gray-100 transition-colors">
                        <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs border border-primary/20 shrink-0">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-secondary text-[12px] leading-tight truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[9px] text-gray-400 font-medium">Profile</p>
                        </div>
                    </a>
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit" class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center text-red-500 hover:bg-red-100 transition-colors shrink-0">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Header -->
    @include('partials.student-mobile-header')

    <!-- Desktop Header (hidden on mobile) -->
    <div class="hidden lg:block">
        @include('partials.topbar')
        @include('partials.header')
    </div>

    <!-- Main Content -->
    <main class="mobile-app-layout min-h-screen">
        <div class="page-enter">
            @yield('content')
        </div>
    </main>

    <!-- Mobile Bottom Navigation -->
    @include('partials.student-bottom-nav')

    <!-- Desktop Sidebar (hidden on mobile) -->
    {{-- Sidebar is now integrated into each page's desktop layout --}}

    <script>
        // Mobile Sidebar Toggle
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');
            const isOpen = sidebar.classList.contains('open');

            if (isOpen) {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
                document.body.style.overflow = '';
            } else {
                sidebar.classList.add('open');
                overlay.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        }

        // Close sidebar on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('mobile-sidebar');
                if (sidebar.classList.contains('open')) {
                    toggleMobileSidebar();
                }
            }
        });

        // Close sidebar on swipe left
        let touchStartX = 0;
        let touchEndX = 0;
        const sidebar = document.getElementById('mobile-sidebar');

        sidebar.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        sidebar.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            if (touchStartX - touchEndX > 50) {
                toggleMobileSidebar();
            }
        }, { passive: true });

        document.addEventListener('DOMContentLoaded', () => {
            // Intersection Observer for fade-in-up animations
            const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -20px 0px' };
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in-up');
                        entry.target.style.animationDelay = (entry.target.dataset.delay || '0') + 's';
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));

            // Bottom nav active state
            const currentPath = window.location.pathname;
            document.querySelectorAll('#student-bottom-nav a').forEach(link => {
                if (link.pathname === currentPath) {
                    link.querySelector('i')?.classList.add('text-primary');
                    link.querySelector('span')?.classList.add('text-primary');
                }
            });

            // Smooth scroll to top on page change
            if ('scrollRestoration' in history) {
                history.scrollRestoration = 'manual';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/intlTelInput.min.js"></script>
    @stack('scripts')
</body>
</html>
