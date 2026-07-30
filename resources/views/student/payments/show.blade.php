@extends('layouts.student-app')

@section('title', 'Complete Payment | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Mobile Payment -->
<div class="lg:hidden min-h-[80vh] flex flex-col justify-center px-4 py-8">
    @if(session('error'))
    <div class="mb-4 p-3 rounded-xl text-sm font-bold bg-rose-50 text-rose-600 border border-rose-100 flex items-center gap-2 fade-in-up" data-animate>
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-3xl shadow-elevated border border-gray-50 overflow-hidden fade-in-up" data-animate>
        <!-- Summary Header -->
        <div class="gradient-navy p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative z-10">
                <h2 class="text-lg font-black uppercase tracking-wider text-primary mb-4">Booking Summary</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-white/50 text-[10px] font-bold uppercase">Subject</p>
                        <p class="font-bold">{{ $appointment->subject->name }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 pt-3 border-t border-white/10">
                        <div>
                            <p class="text-white/50 text-[10px] font-bold uppercase">Date</p>
                            <p class="font-bold text-sm">{{ $appointment->appointment_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-white/50 text-[10px] font-bold uppercase">Time</p>
                            <p class="font-bold text-sm">{{ date('h:i A', strtotime($appointment->start_time)) }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-white/50 text-[10px] font-bold uppercase">Duration</p>
                        <p class="font-bold text-accent">{{ \App\Models\Setting::get('session_duration', 50) }} Minutes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Action -->
        <div class="p-6 text-center">
            <div class="w-14 h-14 bg-primary/10 text-primary rounded-2xl flex items-center justify-center text-xl mx-auto mb-4">
                <i class="fas fa-credit-card"></i>
            </div>
            <h3 class="text-xl font-black text-secondary mb-1">Complete Payment</h3>
            <p class="text-gray-500 text-sm mb-6">Pay to confirm your session</p>

            <div class="bg-light rounded-2xl p-4 mb-6 border border-gray-50">
                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Total Amount</p>
                <p class="text-3xl font-black text-secondary">${{ number_format((float) (\App\Models\Setting::get('session_price', '32.00')), 2) }} <span class="text-xs text-gray-400 uppercase">{{ strtoupper(\App\Models\Setting::get('currency', 'USD')) }}</span></p>
            </div>

            <form action="{{ route('student.payment.checkout', $appointment->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/20 btn-haptic transition-all flex items-center justify-center gap-2">
                    <span>Pay Securely</span>
                    <i class="fab fa-stripe text-xl"></i>
                </button>
            </form>

            <div class="mt-6 flex items-center justify-center gap-3 text-gray-300">
                <i class="fab fa-cc-stripe text-lg"></i>
                <i class="fab fa-cc-visa text-lg"></i>
                <i class="fab fa-cc-mastercard text-lg"></i>
            </div>
        </div>
    </div>
</div>

<!-- Desktop Payment -->
<div class="hidden lg:block bg-light min-h-[70vh] py-12 px-4 md:px-12 flex items-center justify-center">
    <div class="max-w-4xl w-full mx-auto">

        @if(session('error'))
        <div class="mb-8 p-4 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 font-bold fade-up flex items-center gap-3">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-soft border border-gray-50 overflow-hidden fade-up">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="p-8 md:p-12 bg-secondary text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-primary/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                    <div class="relative z-10">
                        <h2 class="text-2xl font-black mb-8 uppercase tracking-widest text-primary">Booking Summary</h2>
                        <div class="space-y-6">
                            <div>
                                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Subject</p>
                                <p class="text-xl font-bold">{{ $appointment->subject->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Topic</p>
                                <p class="text-sm font-medium text-white/80 leading-relaxed">{{ $appointment->doubt->topic_name }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 pt-6 border-t border-white/10">
                                <div>
                                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Date</p>
                                    <p class="font-bold">{{ $appointment->appointment_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Time</p>
                                    <p class="font-bold">{{ date('h:i A', strtotime($appointment->start_time)) }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Duration</p>
                                <p class="font-bold text-accent">{{ \App\Models\Setting::get('session_duration', 50) }} Minutes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-12 flex flex-col justify-center text-center">
                    <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center text-2xl mx-auto mb-6">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="text-2xl font-black text-secondary mb-2">Complete Your Booking</h3>
                    <p class="text-gray-500 text-sm mb-8">Please pay the session fee to confirm your 1-on-1 live doubt solving appointment.</p>

                    <div class="bg-light rounded-2xl p-6 mb-8 border border-gray-50">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Amount</p>
                        <p class="text-4xl font-black text-secondary">${{ number_format((float) (\App\Models\Setting::get('session_price', '32.00')), 2) }} <span class="text-sm text-gray-400 uppercase font-bold">{{ strtoupper(\App\Models\Setting::get('currency', 'USD')) }}</span></p>
                    </div>

                    <form action="{{ route('student.payment.checkout', $appointment->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-black py-4 px-8 rounded-full shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center gap-3 group">
                            <span>Proceed to Secure Payment</span>
                            <i class="fab fa-stripe text-2xl group-hover:text-white/80 transition-colors"></i>
                        </button>
                    </form>

                    <div class="mt-8 flex flex-col items-center gap-3">
                        <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Secure Global Payments</span>
                        <div class="flex items-center gap-4 text-gray-200 text-xl grayscale opacity-50">
                            <i class="fab fa-cc-stripe"></i>
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
