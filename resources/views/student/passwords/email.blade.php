@extends('layouts.app')

@section('title', 'Forgot Password | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Main Content Area -->
<section class="min-h-[85vh] bg-white py-12 px-4 md:px-12 flex items-center justify-center relative overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 right-0 w-1/3 h-full bg-primary/5 rounded-bl-[150px] -z-10"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent/10 rounded-tr-[100px] -z-10 blur-2xl"></div>

    <div class="max-w-xl w-full mx-auto">
        
        <!-- Left: Form Card -->
        <div class="fade-up w-full mx-auto">
            <div class="mb-8 text-center">
                <span class="inline-block py-1.5 px-4 rounded-full bg-accent/20 text-secondary font-bold text-xs mb-4 tracking-wide uppercase">
                    Reset Password
                </span>
                <h1 class="text-3xl md:text-4xl font-black text-secondary leading-tight mb-4">
                    Forgot your <span class="text-primary">Password?</span>
                </h1>
                <p class="text-gray-500 text-sm md:text-base leading-relaxed">
                    No problem. Just let us know your email address and we will email you a password reset link.
                </p>
            </div>

            <!-- Error/Success Message Container -->
            @if(session('success'))
            <div class="mb-6 p-4 rounded-xl text-sm font-bold transition-all duration-300 bg-green-50 text-green-600 border border-green-200 text-center">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 rounded-xl text-sm font-bold transition-all duration-300 bg-red-50 text-red-600 border border-red-200 text-center">
                <i class="fas fa-exclamation-circle mr-2"></i> Please fix the errors below.
            </div>
            @endif

            <form method="POST" action="{{ route('student.password.email') }}" class="space-y-6 bg-light p-8 rounded-[2rem] border border-gray-50 shadow-sm relative">
                @csrf
                
                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Email Address</label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-white border @error('email') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 disabled:opacity-50 disabled:bg-gray-50 rounded-xl py-4 pl-12 pr-5 text-sm transition-all shadow-sm text-secondary placeholder:text-gray-400" placeholder="e.g. john@example.com">
                        <i class="fas fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs font-semibold mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="relative w-full bg-primary hover:bg-secondary text-white font-black py-4 px-8 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex justify-center items-center overflow-hidden">
                    <span>Email Password Reset Link <i class="fas fa-paper-plane ml-2 text-xs"></i></span>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-500 text-sm">
                    Remember your password? 
                    <a href="{{ route('login') }}" class="font-bold text-secondary hover:text-primary transition-colors border-b-2 border-primary/20 hover:border-primary pb-0.5">Log in here</a>
                </p>
            </div>
        </div>

    </div>
</section>

@endsection

