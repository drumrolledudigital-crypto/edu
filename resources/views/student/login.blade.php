@extends('layouts.app')

@section('title', 'Student Login | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Mobile Login -->
<div class="lg:hidden min-h-screen bg-white flex flex-col">
    <div class="flex-1 flex flex-col justify-center px-6 py-12">
        <!-- Logo -->
        <div class="text-center mb-8 fade-in-up" data-animate>
            <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-primary/30 mx-auto mb-4">
                <i class="fas fa-drum text-2xl"></i>
            </div>
            <h1 class="text-2xl font-black text-secondary">Welcome Back</h1>
            <p class="text-sm text-gray-500 mt-1">Log in to continue learning</p>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 fade-in-up" data-animate>
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-4 p-3 rounded-xl text-sm font-bold bg-rose-50 text-rose-600 border border-rose-100 fade-in-up" data-animate>
            <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first() }}
        </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login.post') }}" class="space-y-4 fade-in-up" data-animate style="animation-delay: 0.1s;">
            @csrf

            <div>
                <label class="block text-xs font-bold text-secondary mb-1.5">Email or Mobile</label>
                <div class="relative">
                    <input type="text" name="login_id" value="{{ old('login_id') }}" required
                        class="w-full bg-light border @error('login_id') border-red-400 @else border-gray-200 @enderror focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3.5 pl-11 pr-4 text-sm font-medium transition-all"
                        placeholder="john@example.com">
                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                </div>
                @error('login_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label class="block text-xs font-bold text-secondary">Password</label>
                    <a href="{{ route('student.password.request') }}" class="text-xs font-bold text-primary">Forgot?</a>
                </div>
                <div class="relative">
                    <input type="password" name="password" id="login_password" required
                        class="w-full bg-light border @error('password') border-red-400 @else border-gray-200 @enderror focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3.5 pl-11 pr-12 text-sm font-medium transition-all"
                        placeholder="Your password">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <button type="button" id="toggle-password-mobile" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
                </div>
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember-mobile" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember-mobile" class="text-sm text-gray-500">Remember me</label>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-primary/20 btn-haptic transition-all">
                Log In <i class="fas fa-arrow-right ml-1 text-sm"></i>
            </button>
        </form>

        <div class="mt-6 text-center fade-in-up" data-animate style="animation-delay: 0.2s;">
            <p class="text-gray-500 text-sm">
                Don't have an account?
                <a href="{{ route('student.register') }}" class="font-bold text-primary">Register</a>
            </p>
        </div>
    </div>
</div>

<!-- Desktop Login -->
<div class="hidden lg:block">
    <section class="min-h-[85vh] bg-white py-12 px-4 md:px-12 flex items-center justify-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-primary/5 rounded-bl-[150px] -z-10"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent/10 rounded-tr-[100px] -z-10 blur-2xl"></div>

        <div class="max-w-6xl w-full mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

            <div class="fade-up w-full max-w-md mx-auto lg:mx-0">
                <div class="mb-8">
                    <span class="inline-block py-1.5 px-4 rounded-full bg-accent/20 text-secondary font-bold text-xs mb-4 tracking-wide uppercase">
                        Welcome Back
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black text-secondary leading-tight mb-4">
                        Continue Your <span class="text-primary">Learning Journey</span>
                    </h1>
                    <p class="text-gray-500 text-sm md:text-base leading-relaxed">
                        Log in to access your dashboard, join your next live session, or practice your skills.
                    </p>
                </div>

                @if(session('success'))
                <div class="mb-6 p-4 rounded-xl text-sm font-bold bg-green-50 text-green-600 border border-green-200">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 p-4 rounded-xl text-sm font-bold bg-red-50 text-red-600 border border-red-200">
                    <i class="fas fa-exclamation-circle mr-2"></i> Please fix the errors below.
                </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-6 bg-light p-8 rounded-[2rem] border border-gray-50 shadow-sm">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Student Email or Mobile</label>
                        <div class="relative">
                            <input type="text" name="login_id" value="{{ old('login_id') }}" required class="w-full bg-white border @error('login_id') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-4 pl-12 pr-5 text-sm transition-all shadow-sm" placeholder="e.g. john@example.com">
                            <i class="fas fa-user absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('login_id')<p class="text-red-500 text-xs font-semibold mt-2">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider">Password</label>
                            <a href="{{ route('student.password.request') }}" class="text-xs font-bold text-primary hover:text-secondary transition-colors">Forgot Password?</a>
                        </div>
                        <div class="relative">
                            <input type="password" name="password" id="login_password_desktop" required class="w-full bg-white border @error('password') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-4 pl-12 pr-12 text-sm transition-all shadow-sm" placeholder="••••••••">
                            <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <button type="button" id="toggle-password-desktop" class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors focus:outline-none">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')<p class="text-red-500 text-xs font-semibold mt-2">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-primary bg-white border-gray-200 rounded focus:ring-primary focus:ring-2 cursor-pointer" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="ml-2 text-sm font-medium text-gray-500 cursor-pointer select-none">Remember me for 30 days</label>
                    </div>

                    <button type="submit" class="relative w-full bg-primary hover:bg-secondary text-white font-black py-4 px-8 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex justify-center items-center">
                        <span>Secure Log In <i class="fas fa-arrow-right ml-2 text-xs"></i></span>
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-gray-500 text-sm">
                        Don't have an account yet?
                        <a href="{{ route('student.register') }}" class="font-bold text-secondary hover:text-primary transition-colors border-b-2 border-primary/20 hover:border-primary pb-0.5">Register Now</a>
                    </p>
                </div>
            </div>

            <div class="hidden lg:flex fade-up" style="transition-delay: 0.2s;">
                <div class="bg-secondary w-full text-white rounded-[2.5rem] p-12 relative overflow-hidden shadow-2xl flex flex-col justify-between min-h-[600px]">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

                    <div class="relative z-10">
                        <div class="w-full flex justify-center mb-10">
                            <div class="w-48 h-48 bg-white/10 rounded-full flex items-center justify-center p-4 backdrop-blur-sm border border-white/10 shadow-soft">
                                <svg class="w-full h-full animate-float" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="100" cy="70" r="25" fill="#FFD166"/>
                                    <rect x="70" y="100" width="60" height="50" rx="8" fill="#FF6B6B"/>
                                    <rect x="50" y="140" width="100" height="8" rx="4" fill="#1A2B48"/>
                                    <circle cx="92" cy="68" r="3" fill="#1A2B48"/>
                                    <circle cx="108" cy="68" r="3" fill="#1A2B48"/>
                                    <path d="M95 78 Q100 83 105 78" stroke="#1A2B48" stroke-width="2" fill="none"/>
                                    <rect x="60" y="120" width="80" height="30" rx="4" fill="#2596be"/>
                                    <rect x="75" y="125" width="50" height="20" rx="2" fill="#E5E7EB"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-3xl font-black mb-8 text-center">Your Child's Safety & Success is our Priority</h3>
                        <div class="space-y-6">
                            <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/10 backdrop-blur-sm">
                                <div class="w-12 h-12 rounded-full bg-green-500/20 text-green-400 flex items-center justify-center text-xl shrink-0">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Secure Login</h4>
                                    <p class="text-xs text-gray-400">Bank-level encryption for your data.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/10 backdrop-blur-sm">
                                <div class="w-12 h-12 rounded-full bg-primary/20 text-primary flex items-center justify-center text-xl shrink-0">
                                    <i class="fas fa-smile-beam"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Student Friendly</h4>
                                    <p class="text-xs text-gray-400">Easy to navigate dashboard for kids.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/10 backdrop-blur-sm">
                                <div class="w-12 h-12 rounded-full bg-accent/20 text-accent flex items-center justify-center text-xl shrink-0">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Quick Access</h4>
                                    <p class="text-xs text-gray-400">Join live classes with a single click.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Mobile password toggle
        const toggleMobile = document.getElementById('toggle-password-mobile');
        const passMobile = document.getElementById('login_password');
        if(toggleMobile && passMobile) {
            toggleMobile.addEventListener('click', () => {
                const type = passMobile.type === 'password' ? 'text' : 'password';
                passMobile.type = type;
                toggleMobile.querySelector('i').classList.toggle('fa-eye');
                toggleMobile.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }

        // Desktop password toggle
        const toggleDesktop = document.getElementById('toggle-password-desktop');
        const passDesktop = document.getElementById('login_password_desktop');
        if(toggleDesktop && passDesktop) {
            toggleDesktop.addEventListener('click', () => {
                const type = passDesktop.type === 'password' ? 'text' : 'password';
                passDesktop.type = type;
                toggleDesktop.querySelector('i').classList.toggle('fa-eye');
                toggleDesktop.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }
    });
</script>
@endpush
