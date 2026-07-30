@extends('layouts.student-app')

@section('title', 'Payment Failed | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Mobile Failed -->
<div class="lg:hidden min-h-[80vh] flex flex-col justify-center px-4 py-8">
    <div class="bg-white rounded-3xl shadow-elevated border border-gray-50 p-6 text-center fade-in-up" data-animate>
        <div class="w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
            <i class="fas fa-times-circle"></i>
        </div>
        <h1 class="text-2xl font-black text-secondary mb-2">Payment Failed</h1>
        <p class="text-gray-500 text-sm mb-6">We couldn't process your payment. Please try again.</p>

        <div class="bg-light rounded-2xl p-4 mb-6 text-left border border-gray-50">
            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Incomplete Booking</h3>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Subject</span>
                    <span class="text-sm font-bold text-secondary">{{ $appointment->subject->name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Scheduled</span>
                    <span class="text-sm font-bold text-secondary">{{ $appointment->appointment_date->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <a href="{{ route('student.payment.pay', $appointment->id) }}" class="block w-full bg-primary hover:bg-primary/90 text-white font-bold py-3.5 rounded-xl shadow-lg btn-haptic transition-all">
            Retry Payment
        </a>
        <a href="{{ route('student.booking.index') }}" class="block mt-3 text-sm font-bold text-gray-400 hover:text-primary">Go to My Bookings</a>
    </div>
</div>

<!-- Desktop Failed -->
<div class="hidden lg:block bg-light min-h-[70vh] py-12 px-4 md:px-12 flex items-center justify-center">
    <div class="max-w-xl w-full mx-auto bg-white rounded-[2.5rem] shadow-soft border border-gray-50 p-8 md:p-12 text-center fade-up">
        <div class="w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-6">
            <i class="fas fa-times-circle"></i>
        </div>
        <h1 class="text-3xl font-black text-secondary mb-2">Payment Failed</h1>
        <p class="text-gray-500 mb-8">We couldn't process your payment. Your booking remains pending. Please try again.</p>

        <div class="bg-light rounded-2xl p-6 mb-8 text-left border border-gray-50">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Incomplete Booking</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">Subject:</span>
                    <span class="text-sm font-black text-secondary">{{ $appointment->subject->name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">Scheduled:</span>
                    <span class="text-sm font-black text-secondary">{{ $appointment->appointment_date->format('M d, Y') }} ({{ date('h:i A', strtotime($appointment->start_time)) }})</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <a href="{{ route('student.payment.pay', $appointment->id) }}" class="bg-primary hover:bg-secondary text-white font-black py-4 rounded-full shadow-lg transition-all duration-300">
                Retry Payment
            </a>
            <a href="{{ route('student.booking.index') }}" class="text-sm font-bold text-gray-400 hover:text-primary transition-colors">
                Go to My Bookings
            </a>
        </div>
    </div>
</div>

@endsection
