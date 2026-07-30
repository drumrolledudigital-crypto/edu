@extends('layouts.app')

@section('title', 'Reset Password | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

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
                    Secure Account
                </span>
                <h1 class="text-3xl md:text-4xl font-black text-secondary leading-tight mb-4">
                    Reset <span class="text-primary">Password</span>
                </h1>
                <p class="text-gray-500 text-sm md:text-base leading-relaxed">
                    Choose a new secure password for your {{ \App\Models\Setting::get('platform_name', 'Drumroll') }} account.
                </p>
            </div>

            @if($errors->any())
            <div class="mb-6 p-4 rounded-xl text-sm font-bold transition-all duration-300 bg-red-50 text-red-600 border border-red-200 text-center">
                <i class="fas fa-exclamation-circle mr-2"></i> Please fix the errors below.
            </div>
            @endif

            <form method="POST" action="{{ route('student.password.update') }}" class="space-y-6 bg-light p-8 rounded-[2rem] border border-gray-50 shadow-sm relative">
                @csrf
                
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Email Address</label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ $email ?? old('email') }}" required class="w-full bg-white border @error('email') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 disabled:opacity-50 disabled:bg-gray-50 rounded-xl py-4 pl-12 pr-5 text-sm transition-all shadow-sm text-secondary placeholder:text-gray-400">
                        <i class="fas fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs font-semibold mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">New Password</label>
                    <div class="relative">
                        <input type="password" name="password" required class="w-full bg-white border @error('password') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 disabled:opacity-50 disabled:bg-gray-50 rounded-xl py-4 pl-12 pr-5 text-sm transition-all shadow-sm text-secondary placeholder:text-gray-400" placeholder="••••••••">
                        <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs font-semibold mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Confirm Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" required class="w-full bg-white border border-gray-200 hover:border-gray-300 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 disabled:opacity-50 disabled:bg-gray-50 rounded-xl py-4 pl-12 pr-5 text-sm transition-all shadow-sm text-secondary placeholder:text-gray-400" placeholder="••••••••">
                        <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="relative w-full bg-primary hover:bg-secondary text-white font-black py-4 px-8 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex justify-center items-center overflow-hidden">
                    <span>Reset Password <i class="fas fa-arrow-right ml-2 text-xs"></i></span>
                </button>
            </form>

        </div>

    </div>
</section>

@endsection
