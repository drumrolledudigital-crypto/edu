@extends('layouts.app')

@section('title', 'Student Registration | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Mobile Registration -->
<div class="lg:hidden min-h-screen bg-white flex flex-col">
    <div class="flex-1 flex flex-col justify-center px-6 py-12">
        <!-- Logo -->
        <div class="text-center mb-6 fade-in-up" data-animate>
            <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-primary/30 mx-auto mb-4">
                <i class="fas fa-drum text-2xl"></i>
            </div>
            <h1 class="text-2xl font-black text-secondary">Create Account</h1>
            <p class="text-sm text-gray-500 mt-1">Start your learning journey today</p>
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

        <!-- Registration Form -->
        <form method="POST" action="{{ route('student.register.post') }}" class="space-y-4 fade-in-up" data-animate style="animation-delay: 0.1s;">
            @csrf

            <div>
                <label class="block text-xs font-bold text-secondary mb-1.5">Student Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full bg-light border @error('name') border-red-400 @else border-gray-200 @enderror focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3 px-4 text-sm font-medium transition-all"
                    placeholder="Child's full name">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary mb-1.5">Parent/Guardian Name *</label>
                <input type="text" name="parent_name" value="{{ old('parent_name') }}" required
                    class="w-full bg-light border @error('parent_name') border-red-400 @else border-gray-200 @enderror focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3 px-4 text-sm font-medium transition-all"
                    placeholder="Your full name">
                @error('parent_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary mb-1.5">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full bg-light border @error('email') border-red-400 @else border-gray-200 @enderror focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3 px-4 text-sm font-medium transition-all"
                    placeholder="parent@example.com">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary mb-1.5">Mobile Number *</label>
                <input type="tel" id="phone-mobile" required
                    class="w-full bg-light border @error('mobile_number') border-red-400 @else border-gray-200 @enderror focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3 px-4 text-sm font-medium transition-all"
                    placeholder="+1 (234) 567-8900">
                <input type="hidden" name="mobile_number" id="phone-mobile-hidden" value="{{ old('mobile_number') }}">
                @error('mobile_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary mb-1.5">Year / Grade *</label>
                <select name="student_class" required
                    class="w-full bg-light border @error('student_class') border-red-400 @else border-gray-200 @enderror focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3 px-4 text-sm font-medium transition-all appearance-none">
                    <option value="">Select Class</option>
                    @foreach(['Kindergarten', 'Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8'] as $class)
                        <option value="{{ $class }}" {{ old('student_class') == $class ? 'selected' : '' }}>{{ $class }}</option>
                    @endforeach
                </select>
                @error('student_class')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary mb-1.5">Password *</label>
                <div class="relative">
                    <input type="password" name="password" id="reg_password_mobile" required
                        class="w-full bg-light border @error('password') border-red-400 @else border-gray-200 @enderror focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3 px-4 pr-11 text-sm font-medium transition-all"
                        placeholder="Min. 8 characters">
                    <button type="button" id="toggle-reg-pass-mobile" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
                </div>
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary mb-1.5">Confirm Password *</label>
                <input type="password" name="password_confirmation" required
                    class="w-full bg-light border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl py-3 px-4 text-sm font-medium transition-all"
                    placeholder="Repeat password">
            </div>

            <div class="flex items-start gap-2">
                <input type="checkbox" name="terms" id="terms-mobile" required class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary mt-0.5">
                <label for="terms-mobile" class="text-xs text-gray-500">I agree to the <a href="#" class="text-primary font-bold">Terms</a> and <a href="#" class="text-primary font-bold">Privacy Policy</a>.</label>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-primary/20 btn-haptic transition-all mt-2">
                Create Account <i class="fas fa-paper-plane ml-1 text-sm"></i>
            </button>
        </form>

        <div class="mt-6 text-center fade-in-up" data-animate style="animation-delay: 0.2s;">
            <p class="text-gray-500 text-sm">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-primary">Log in</a>
            </p>
        </div>
    </div>
</div>

<!-- Desktop Registration -->
<div class="hidden lg:block">
    <section class="min-h-[85vh] bg-white py-12 px-4 md:px-12 flex items-center justify-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1/3 h-full bg-accent/5 rounded-br-[150px] -z-10"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-primary/10 rounded-tl-[100px] -z-10 blur-2xl"></div>

        <div class="max-w-7xl w-full mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">

            <div class="fade-up w-full lg:col-span-7 xl:col-span-8 order-2 lg:order-1">
                <div class="mb-8">
                    <span class="inline-block py-1.5 px-4 rounded-full bg-primary/10 text-primary font-bold text-xs mb-4 tracking-wide uppercase">
                        Join {{ \App\Models\Setting::get('platform_name', 'Drumroll') }}
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black text-secondary leading-tight mb-4">
                        Start Your <span class="text-primary">Learning Journey</span>
                    </h1>
                    <p class="text-gray-500 text-sm md:text-base leading-relaxed">
                        Create a free student account to book sessions, track progress, and unlock a world of personalized education.
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

                <form method="POST" action="{{ route('student.register.post') }}" class="space-y-6 bg-light p-8 rounded-[2rem] border border-gray-50 shadow-sm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Student Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-white border @error('name') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-4 pl-12 pr-5 text-sm transition-all shadow-sm" placeholder="Child's full name">
                            <i class="fas fa-child absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            @error('name')<p class="text-red-500 text-xs font-semibold mt-2">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Parent/Guardian Name *</label>
                            <input type="text" name="parent_name" value="{{ old('parent_name') }}" required class="w-full bg-white border @error('parent_name') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-4 pl-12 pr-5 text-sm transition-all shadow-sm" placeholder="Your full name">
                            <i class="fas fa-user-shield absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            @error('parent_name')<p class="text-red-500 text-xs font-semibold mt-2">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-white border @error('email') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-4 pl-12 pr-5 text-sm transition-all shadow-sm" placeholder="parent@example.com">
                            <i class="fas fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            @error('email')<p class="text-red-500 text-xs font-semibold mt-2">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Mobile Number *</label>
                            <input type="tel" id="phone-desktop" required class="w-full bg-white border @error('mobile_number') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-4 pl-12 pr-5 text-sm transition-all shadow-sm" placeholder="+1 (234) 567-8900">
                            <input type="hidden" name="mobile_number" id="phone-desktop-hidden" value="{{ old('mobile_number') }}">
                            <i class="fas fa-phone-alt absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            @error('mobile_number')<p class="text-red-500 text-xs font-semibold mt-2">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Year / Grade *</label>
                        <select name="student_class" required class="w-full bg-white border @error('student_class') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-4 pl-12 pr-5 text-sm transition-all shadow-sm appearance-none">
                            <option value="">Select Class</option>
                            @foreach(['Kindergarten', 'Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8'] as $class)
                                <option value="{{ $class }}" {{ old('student_class') == $class ? 'selected' : '' }}>{{ $class }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-graduation-cap absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        @error('student_class')<p class="text-red-500 text-xs font-semibold mt-2">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Password *</label>
                            <input type="password" name="password" id="reg_password_desktop" required class="w-full bg-white border @error('password') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-4 pl-12 pr-12 text-sm transition-all shadow-sm" placeholder="••••••••">
                            <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            @error('password')<p class="text-red-500 text-xs font-semibold mt-2">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Confirm Password *</label>
                            <input type="password" name="password_confirmation" required class="w-full bg-white border border-gray-200 hover:border-gray-300 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-4 pl-12 pr-12 text-sm transition-all shadow-sm" placeholder="••••••••">
                            <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="flex items-start mt-4">
                        <input id="terms" name="terms" type="checkbox" required class="w-4 h-4 text-primary bg-white border-gray-200 rounded focus:ring-primary focus:ring-2 cursor-pointer mt-0.5">
                        <label for="terms" class="ml-3 text-sm font-medium text-gray-500 cursor-pointer select-none">
                            I agree to the <a href="#" class="text-primary hover:underline">Terms of Service</a> and <a href="#" class="text-primary hover:underline">Privacy Policy</a>.
                        </label>
                    </div>

                    <button type="submit" class="relative w-full bg-primary hover:bg-secondary text-white font-black py-4 px-8 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex justify-center items-center mt-6">
                        <span>Create Account <i class="fas fa-paper-plane ml-2 text-xs"></i></span>
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-gray-500 text-sm">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-bold text-secondary hover:text-primary transition-colors border-b-2 border-primary/20 hover:border-primary pb-0.5">Log in here</a>
                    </p>
                </div>
            </div>

            <div class="lg:col-span-5 xl:col-span-4 order-1 lg:order-2 fade-up" style="transition-delay: 0.2s;">
                <div class="bg-secondary w-full text-white rounded-[2.5rem] p-10 relative overflow-hidden shadow-2xl flex flex-col justify-between sticky top-32">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-primary/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-accent/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

                    <div class="relative z-10">
                        <h3 class="text-2xl font-black mb-8">Why Join <span class="text-primary">Drumroll Edu?</span></h3>
                        <ul class="space-y-6 mb-10">
                            <li class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-primary"><i class="fas fa-user-graduate"></i></div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Personalized Learning</h4>
                                    <p class="text-xs text-gray-400 leading-relaxed">Curriculum adapted to your child's unique pace and style.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-accent"><i class="fas fa-chalkboard-teacher"></i></div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Expert Guidance</h4>
                                    <p class="text-xs text-gray-400 leading-relaxed">Learn from verified, passionate, and experienced educators.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-primary-400"><i class="fas fa-laptop-house"></i></div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Interactive Sessions</h4>
                                    <p class="text-xs text-gray-400 leading-relaxed">Engaging 1-on-1 video classes with digital whiteboards.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-green-400"><i class="fas fa-chart-line"></i></div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Progress Tracking</h4>
                                    <p class="text-xs text-gray-400 leading-relaxed">Detailed reports to monitor growth and build confidence.</p>
                                </div>
                            </li>
                        </ul>

                        <div class="w-full flex justify-center mt-auto">
                            <div class="w-40 h-40 bg-white/5 rounded-full flex items-center justify-center p-4 backdrop-blur-sm border border-white/5 shadow-soft">
                                <img src="https://illustrations.popsy.co/pink/freelancer.svg" alt="Why Join" class="w-full h-full object-contain animate-float" style="animation-delay: 1s;">
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
        const toggleMobile = document.getElementById('toggle-reg-pass-mobile');
        const passMobile = document.getElementById('reg_password_mobile');
        if(toggleMobile && passMobile) {
            toggleMobile.addEventListener('click', () => {
                const type = passMobile.type === 'password' ? 'text' : 'password';
                passMobile.type = type;
                toggleMobile.querySelector('i').classList.toggle('fa-eye');
                toggleMobile.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }

        // intl-tel-input setup
        function setupPhoneInput(inputId, hiddenId) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const iti = intlTelInput(input, {
                initialCountry: 'ca',
                preferredCountries: ['ca', 'us', 'gb', 'in', 'ae', 'sa'],
                separateDialCode: true,
                utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/utils.js'
            });
            input.form.addEventListener('submit', () => {
                document.getElementById(hiddenId).value = iti.getNumber();
            });
        }

        setupPhoneInput('phone-mobile', 'phone-mobile-hidden');
        setupPhoneInput('phone-desktop', 'phone-desktop-hidden');
    });
</script>
@endpush
