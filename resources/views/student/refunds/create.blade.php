@extends('layouts.student-app')

@section('title', 'Request Refund | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Mobile Refund Request -->
<div class="lg:hidden">
    <x-mobile.page-header title="Request Refund" backUrl="{{ route('student.payments.history') }}" icon="fas fa-undo" />

    <div class="px-4 pb-32 space-y-3">
        @if($errors->any())
        <div class="p-3 rounded-xl text-sm font-bold bg-red-50 text-red-600 border border-red-200 fade-in-up" data-animate>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Payment Details Card -->
        <div class="bg-white rounded-2xl p-4 shadow-card border border-gray-50 fade-in-up" data-animate>
            <h3 class="font-bold text-secondary text-sm mb-3">Payment Details</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Subject</span>
                    <span class="text-sm font-bold text-secondary">{{ $payment->appointment->subject->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Transaction ID</span>
                    <span class="text-sm font-bold text-secondary font-mono">{{ $payment->transaction_id ?: $payment->stripe_payment_id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Amount</span>
                    <span class="text-sm font-bold text-secondary">${{ number_format($payment->amount, 2) }} {{ strtoupper($payment->currency) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Payment Date</span>
                    <span class="text-sm font-bold text-secondary">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Refund Form -->
        <div class="bg-white rounded-2xl p-4 shadow-card border border-gray-50 fade-in-up" data-animate style="animation-delay: 0.1s;">
            <form action="{{ route('student.refunds.store', $payment->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="reason-mobile" class="text-xs font-bold text-secondary uppercase tracking-wider mb-2 block">Reason for Refund <span class="text-rose-500">*</span></label>
                    <textarea name="reason" id="reason-mobile" rows="4" class="w-full bg-light border border-gray-200 text-secondary text-sm rounded-xl focus:ring-primary focus:border-primary p-4 transition-all" placeholder="Please explain why you are requesting a refund..." required>{{ old('reason') }}</textarea>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('student.payments.history') }}" class="flex-1 py-3 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold text-center">Cancel</a>
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded-xl shadow-lg transition-all">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Desktop Refund Request -->
<div class="hidden lg:block bg-light min-h-screen py-12 px-4 md:px-12">
    <div class="max-w-4xl mx-auto flex flex-col lg:flex-row gap-8">

        <!-- Sidebar -->
        <aside class="w-full lg:w-64 shrink-0">@include('partials.student-sidebar')</aside>

        <!-- Main Content -->
        <main class="flex-1 space-y-8">
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-black text-secondary uppercase tracking-tight">Request <span class="text-primary">Refund</span></h1>
                <p class="text-gray-500 mt-1">Submit a refund request for your session booking.</p>
            </div>

            @if($errors->any())
            <div class="p-4 rounded-xl text-sm font-bold bg-red-50 text-red-600 border border-red-200 fade-up">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white rounded-[2.5rem] shadow-soft border border-gray-50 p-8 md:p-12 fade-up">
                <div class="bg-light p-6 rounded-2xl border border-gray-100 mb-8">
                    <h3 class="text-lg font-black text-secondary mb-4">Payment Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Subject</p>
                            <p class="text-sm font-bold text-secondary">{{ $payment->appointment->subject->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Transaction ID</p>
                            <p class="text-sm font-bold text-secondary">{{ $payment->transaction_id ?? $payment->stripe_payment_id }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Amount</p>
                            <p class="text-sm font-bold text-secondary">${{ number_format($payment->amount, 2) }} {{ strtoupper($payment->currency) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Payment Date</p>
                            <p class="text-sm font-bold text-secondary">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('student.refunds.store', $payment->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label for="reason" class="text-xs font-black uppercase tracking-widest text-secondary block">Reason for Refund <span class="text-rose-500">*</span></label>
                        <textarea name="reason" id="reason" rows="4" class="w-full bg-light border border-gray-200 text-secondary text-sm rounded-xl focus:ring-primary focus:border-primary block p-4 transition-all" placeholder="Please explain why you are requesting a refund..." required>{{ old('reason') }}</textarea>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row gap-4 justify-end">
                        <a href="{{ route('student.payments.history') }}" class="w-full sm:w-auto px-8 py-3 rounded-full border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition-all text-center">Cancel</a>
                        <button type="submit" class="w-full sm:w-auto bg-primary hover:bg-secondary text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

@endsection
