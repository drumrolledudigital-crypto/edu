@php
$ws = \App\Models\Setting::whereIn('group', ['general','branding','seo','analytics','advanced'])->pluck('value','key')->toArray();
@endphp
<!DOCTYPE html>
<html lang="{{ $ws['language'] ?? 'en' }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', (($ws['meta_title'] ?? '') ?: (($ws['platform_name'] ?? 'Drumroll Edu') . ' | ' . ($ws['website_tagline'] ?? 'Premium Kids Learning Platform'))))</title>
    @if(!empty($ws['meta_description']))
    <meta name="description" content="{{ $ws['meta_description'] }}">
    @endif
    @if(!empty($ws['meta_keywords']))
    <meta name="keywords" content="{{ $ws['meta_keywords'] }}">
    @endif
    @if(!empty($ws['seo_author']))
    <meta name="author" content="{{ $ws['seo_author'] }}">
    @endif
    @if(!empty($ws['robots']))
    <meta name="robots" content="{{ $ws['robots'] }}">
    @endif
    @if(!empty($ws['canonical_url']))
    <link rel="canonical" href="{{ $ws['canonical_url'] }}">
    @endif
    @if(!empty($ws['favicon']))
    <link rel="icon" type="image/x-icon" href="{{ asset($ws['favicon']) }}">
    @endif
    @if(!empty($ws['apple_touch_icon']))
    <link rel="apple-touch-icon" href="{{ asset($ws['apple_touch_icon']) }}">
    @endif
    @if(!empty($ws['og_title']) || !empty($ws['og_description']) || !empty($ws['og_image']))
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $ws['og_title'] ?? ($ws['platform_name'] ?? 'Drumroll Edu') }}">
    <meta property="og:description" content="{{ $ws['og_description'] ?? ($ws['meta_description'] ?? '') }}">
    <meta property="og:site_name" content="{{ $ws['platform_name'] ?? 'Drumroll Edu' }}">
    @if(!empty($ws['og_image']))
    <meta property="og:image" content="{{ asset($ws['og_image']) }}">
    @endif
    @endif
    @if(!empty($ws['twitter_title']) || !empty($ws['twitter_description']))
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ws['twitter_title'] ?? ($ws['platform_name'] ?? 'Drumroll Edu') }}">
    <meta name="twitter:description" content="{{ $ws['twitter_description'] ?? '' }}">
    @if(!empty($ws['og_image']))
    <meta name="twitter:image" content="{{ asset($ws['og_image']) }}">
    @endif
    @endif
    @if(!empty($ws['google_verification']))
    <meta name="google-site-verification" content="{{ $ws['google_verification'] }}">
    @endif
    @if(!empty($ws['bing_verification']))
    <meta name="msvalidate.01" content="{{ $ws['bing_verification'] }}">
    @endif
    @if(!empty($ws['facebook_verification']))
    <meta name="facebook-domain-verification" content="{{ $ws['facebook_verification'] }}">
    @endif
    @if(!empty($ws['schema_json']))
    <script type="application/ld+json">{!! $ws['schema_json'] !!}</script>
    @endif
    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
                        secondary: '#1A2B48',  // Deep Navy
                        accent: '#FFD166',     // Soft Yellow
                        light: '#F8F9FA',      // Off White
                        navy: '#1A2B48',
                    },
                    borderRadius: {
                        'brand': '20px',
                        'card': '16px',
                    },
                    boxShadow: {
                        'soft': '0 10px 30px -5px rgba(0, 0, 0, 0.05)',
                        'hover': '0 20px 40px -5px rgba(0, 0, 0, 0.08)',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        .gradient-text {
            background: linear-gradient(90deg, #FF4D8D 0%, #FFD166 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-gradient {
            background: radial-gradient(circle at top right, rgba(255, 77, 141, 0.05) 0%, rgba(248, 249, 250, 1) 100%);
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    @if(!empty($ws['custom_head_scripts']))
    {!! $ws['custom_head_scripts'] !!}
    @endif
</head>
<body class="bg-light font-sans text-secondary overflow-x-hidden">

    @include('partials.topbar')
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Global Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Navbar Sticky & Shadow
            const header = document.getElementById('main-header');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    header.classList.add('shadow-soft', 'py-3');
                    header.classList.remove('py-5');
                } else {
                    header.classList.remove('shadow-soft', 'py-3');
                    header.classList.add('py-5');
                }
            });

            // Intersection Observer for Animations
            const fadeEls = document.querySelectorAll('.fade-up');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });

            fadeEls.forEach(el => observer.observe(el));

            // Mobile Menu Toggle
            const menuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            if (menuBtn && mobileMenu) {
                menuBtn.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                    mobileMenu.classList.toggle('flex');
                });
            }
        });

        // Counter Animation Function
        function animateCounter(id, target) {
            let current = 0;
            const step = target / 50;
            const el = document.getElementById(id);
            const interval = setInterval(() => {
                current += step;
                if (current >= target) {
                    el.innerText = target + (id.includes('percent') ? '%' : '+');
                    clearInterval(interval);
                } else {
                    el.innerText = Math.floor(current) + (id.includes('percent') ? '%' : '+');
                }
            }, 30);
        }
    </script>
    @if(!empty($ws['custom_body_scripts']))
    {!! $ws['custom_body_scripts'] !!}
    @endif
    @if(!empty($ws['custom_footer_scripts']))
    {!! $ws['custom_footer_scripts'] !!}
    @endif
    @if(!empty($ws['custom_css']))
    <style>{!! $ws['custom_css'] !!}</style>
    @endif
    @if(!empty($ws['custom_js']))
    <script>{!! $ws['custom_js'] !!}</script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/intlTelInput.min.js"></script>
    @stack('scripts')
</body>
</html>
