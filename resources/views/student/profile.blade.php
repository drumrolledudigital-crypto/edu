@extends('layouts.student-app')

@section('title', 'My Profile | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Mobile Profile -->
<div class="lg:hidden">
    <!-- Profile Header -->
    <div class="px-4 pt-4 pb-2">
        <div class="bg-white rounded-3xl p-5 shadow-card border border-gray-50 text-center fade-in-up" data-animate>
            <div class="w-20 h-20 rounded-full bg-primary/10 text-primary flex items-center justify-center text-3xl font-bold mx-auto mb-3 border-2 border-primary/20">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <h2 class="text-xl font-black text-secondary">{{ auth()->user()->name }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ auth()->user()->student_class }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ auth()->user()->email }}</p>
        </div>
    </div>

    @if(session('success'))
    <div class="px-4 py-2">
        <div class="p-3 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-2 fade-in-up" data-animate>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="px-4 py-2">
        <div class="p-3 rounded-xl text-sm font-bold bg-rose-50 text-rose-600 border border-rose-100 flex items-center gap-2 fade-in-up" data-animate>
            <i class="fas fa-exclamation-circle"></i> Please fix the errors below.
        </div>
    </div>
    @endif

    <!-- Profile Form -->
    <div class="px-4 py-2">
        <div class="bg-white rounded-2xl shadow-card border border-gray-50 overflow-hidden fade-in-up" data-animate>
            <div class="p-4 border-b border-gray-50">
                <h3 class="font-extrabold text-secondary">Personal Information</h3>
            </div>
            <form method="POST" action="{{ route('student.profile.update') }}" class="p-4 space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-secondary mb-1.5">Student Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-light border @error('name') border-red-400 @else border-gray-200 @enderror focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3 px-4 text-sm font-medium transition-all">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-secondary mb-1.5">Parent/Guardian Name</label>
                    <input type="text" name="parent_name" value="{{ old('parent_name', $user->parent_name) }}" required
                        class="w-full bg-light border @error('parent_name') border-red-400 @else border-gray-200 @enderror focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3 px-4 text-sm font-medium transition-all">
                    @error('parent_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-secondary mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-light border @error('email') border-red-400 @else border-gray-200 @enderror focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3 px-4 text-sm font-medium transition-all">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-secondary mb-1.5">Mobile Number</label>
                    <input type="tel" id="phone-profile-mobile"
                        class="w-full bg-light border @error('mobile_number') border-red-400 @else border-gray-200 @enderror focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3 px-4 text-sm font-medium transition-all">
                    <input type="hidden" name="mobile_number" id="phone-profile-mobile-hidden" value="{{ old('mobile_number', $user->mobile_number) }}">
                    @error('mobile_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-secondary mb-1.5">Year / Grade</label>
                    <select name="student_class" required
                        class="w-full bg-light border @error('student_class') border-red-400 @else border-gray-200 @enderror focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3 px-4 text-sm font-medium transition-all appearance-none">
                        <option value="">Select Class</option>
                        @php
                            $classes = ['Kindergarten', 'Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8'];
                        @endphp
                        @foreach($classes as $class)
                            <option value="{{ $class }}" {{ old('student_class', $user->student_class) == $class ? 'selected' : '' }}>{{ $class }}</option>
                        @endforeach
                    </select>
                    @error('student_class')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3.5 rounded-xl shadow-md btn-haptic transition-all">
                    Save Changes
                </button>
            </form>
        </div>
    </div>

    <!-- Password Form -->
    <div class="px-4 py-2 pb-32">
        <div class="bg-white rounded-2xl shadow-card border border-gray-50 overflow-hidden fade-in-up" data-animate style="animation-delay: 0.1s;">
            <div class="p-4 border-b border-gray-50">
                <h3 class="font-extrabold text-secondary">Change Password</h3>
            </div>
            <form method="POST" action="{{ route('student.profile.password') }}" class="p-4 space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-secondary mb-1.5">Current Password</label>
                    <input type="password" name="current_password" required
                        class="w-full bg-light border @error('current_password') border-red-400 @else border-gray-200 @enderror focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3 px-4 text-sm font-medium transition-all">
                    @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-secondary mb-1.5">New Password</label>
                    <input type="password" name="password" required
                        class="w-full bg-light border @error('password') border-red-400 @else border-gray-200 @enderror focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3 px-4 text-sm font-medium transition-all">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-secondary mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full bg-light border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3 px-4 text-sm font-medium transition-all">
                </div>

                <button type="submit" class="w-full bg-secondary hover:bg-secondary/90 text-white font-bold py-3.5 rounded-xl shadow-md btn-haptic transition-all">
                    Update Password
                </button>
            </form>
        </div>
    </div>

    <!-- Logout Button (Mobile) -->
    <div class="px-4 pb-8">
        <form method="POST" action="{{ route('student.logout') }}">
            @csrf
            <button type="submit" class="w-full bg-white border border-rose-200 text-rose-500 font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 btn-haptic">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</div>

<!-- Desktop Profile -->
<div class="hidden lg:block bg-light min-h-screen py-12 px-4 md:px-12">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

        <!-- Sidebar -->
        <aside class="w-full lg:w-64 shrink-0">@include('partials.student-sidebar')</aside>

        <!-- Main Content -->
        <main class="flex-1 space-y-8">

            @if(session('success'))
            <div class="p-4 rounded-xl text-sm font-bold bg-green-50 text-green-600 border border-green-200 fade-up">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="p-4 rounded-xl text-sm font-bold bg-red-50 text-red-600 border border-red-200 fade-up">
                <i class="fas fa-exclamation-circle mr-2"></i> Please fix the errors below.
            </div>
            @endif

            <!-- Profile Info Form -->
            <div class="bg-white rounded-[2rem] shadow-soft border border-gray-50 p-8 fade-up">
                <h3 class="text-2xl font-extrabold text-secondary mb-6">Personal Information</h3>

                <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Student Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-white border @error('name') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-3.5 px-4 text-sm font-medium transition-all shadow-sm disabled:opacity-50 disabled:bg-gray-50">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Parent/Guardian Name</label>
                            <input type="text" name="parent_name" value="{{ old('parent_name', $user->parent_name) }}" required class="w-full bg-white border @error('parent_name') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-3.5 px-4 text-sm font-medium transition-all shadow-sm disabled:opacity-50 disabled:bg-gray-50">
                            @error('parent_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-white border @error('email') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-3.5 px-4 text-sm font-medium transition-all shadow-sm disabled:opacity-50 disabled:bg-gray-50">
                            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Mobile Number</label>
                            <input type="tel" id="phone-profile-desktop" class="w-full bg-white border @error('mobile_number') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-3.5 px-4 text-sm font-medium transition-all shadow-sm disabled:opacity-50 disabled:bg-gray-50">
                            <input type="hidden" name="mobile_number" id="phone-profile-desktop-hidden" value="{{ old('mobile_number', $user->mobile_number) }}">
                            @error('mobile_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Year / Grade</label>
                        <select name="student_class" required class="w-full bg-white border @error('student_class') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-3.5 px-4 text-sm font-medium transition-all shadow-sm disabled:opacity-50 disabled:bg-gray-50">
                            <option value="">Select Class</option>
                            @php
                                $classes = ['Kindergarten', 'Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8'];
                            @endphp
                            @foreach($classes as $class)
                                <option value="{{ $class }}" {{ old('student_class', $user->student_class) == $class ? 'selected' : '' }}>{{ $class }}</option>
                            @endforeach
                        </select>
                        @error('student_class')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-primary hover:bg-secondary text-white font-bold py-3 px-8 rounded-full shadow-md transition-all duration-300">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Form -->
            <div class="bg-white rounded-[2rem] shadow-soft border border-gray-50 p-8 fade-up" style="transition-delay: 0.1s;">
                <h3 class="text-2xl font-extrabold text-secondary mb-6">Change Password</h3>

                <form method="POST" action="{{ route('student.profile.password') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Current Password</label>
                        <input type="password" name="current_password" required class="w-full bg-white border @error('current_password') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-3.5 px-4 text-sm font-medium transition-all shadow-sm disabled:opacity-50 disabled:bg-gray-50">
                        @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">New Password</label>
                            <input type="password" name="password" required class="w-full bg-white border @error('password') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-3.5 px-4 text-sm font-medium transition-all shadow-sm disabled:opacity-50 disabled:bg-gray-50">
                            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Confirm New Password</label>
                            <input type="password" name="password_confirmation" required class="w-full bg-white border border-gray-200 hover:border-gray-300 focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-3.5 px-4 text-sm font-medium transition-all shadow-sm disabled:opacity-50 disabled:bg-gray-50">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-secondary hover:bg-primary text-white font-bold py-3 px-8 rounded-full shadow-md transition-all duration-300">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

        </main>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        function setupPhoneInput(inputId, hiddenId) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const iti = intlTelInput(input, {
                initialCountry: 'ca',
                preferredCountries: ['ca', 'us', 'gb', 'in', 'ae', 'sa'],
                separateDialCode: true,
                utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/utils.js'
            });
            // Pre-fill with existing number
            const existingNumber = document.getElementById(hiddenId).value;
            if (existingNumber) {
                iti.setNumber(existingNumber);
            }
            input.form.addEventListener('submit', () => {
                document.getElementById(hiddenId).value = iti.getNumber();
            });
        }

        setupPhoneInput('phone-profile-mobile', 'phone-profile-mobile-hidden');
        setupPhoneInput('phone-profile-desktop', 'phone-profile-desktop-hidden');
    });
</script>
@endpush
