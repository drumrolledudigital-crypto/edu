@extends('layouts.student-app')

@section('title', 'Invoice ' . $invoice->invoice_number . ' | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Mobile Invoice Details -->
<div class="lg:hidden">
    <x-mobile.page-header title="Invoice Details" subtitle="{{ $invoice->invoice_number }}" icon="fas fa-file-invoice" backUrl="{{ route('student.invoices.index') }}" />

    <div class="px-3 pb-32 space-y-3">
        <!-- Invoice Header Card -->
        <div class="bg-white rounded-2xl p-4 shadow-card border border-gray-50 fade-in-up" data-animate>
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Invoice Number</p>
                    <p class="font-bold text-secondary text-[15px]">{{ $invoice->invoice_number }}</p>
                </div>
                @php
                    $statusColor = match($invoice->status) {
                        'generated' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                        'cancelled' => 'bg-gray-100 text-gray-500 border-gray-200',
                        default => 'bg-gray-100 text-gray-500 border-gray-200',
                    };
                @endphp
                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border {{ $statusColor }}">
                    {{ $invoice->status }}
                </span>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Total Amount</p>
                    <p class="font-black text-primary text-xl">${{ number_format($invoice->amount, 2) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Invoice Date</p>
                    <p class="font-semibold text-secondary text-sm">{{ $invoice->invoice_date ? $invoice->invoice_date->format('M d, Y') : 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Student Info -->
        <div class="bg-white rounded-2xl p-4 shadow-card border border-gray-50 fade-in-up" data-animate>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Student Information</h3>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-gray-500">Name</span>
                    <span class="text-[12px] font-bold text-secondary">{{ $invoice->student->name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-gray-500">Email</span>
                    <span class="text-[12px] font-bold text-secondary truncate ml-4">{{ $invoice->student->email }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-gray-500">Class</span>
                    <span class="text-[12px] font-bold text-secondary">{{ $invoice->student->student_class ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Session Details -->
        <div class="bg-white rounded-2xl p-4 shadow-card border border-gray-50 fade-in-up" data-animate>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Session Details</h3>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-gray-500">Subject</span>
                    <span class="text-[12px] font-bold text-secondary">{{ $invoice->appointment->subject->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-gray-500">Appointment ID</span>
                    <span class="text-[12px] font-bold text-secondary">#{{ $invoice->appointment_id }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-gray-500">Session Date</span>
                    <span class="text-[12px] font-bold text-secondary">{{ $invoice->appointment->appointment_date ? \Carbon\Carbon::parse($invoice->appointment->appointment_date)->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-gray-500">Session Time</span>
                    <span class="text-[12px] font-bold text-secondary">{{ $invoice->appointment->start_time ? date('h:i A', strtotime($invoice->appointment->start_time)) : 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-gray-500">Duration</span>
                    <span class="text-[12px] font-bold text-secondary">{{ \App\Models\Setting::get('session_duration', 50) }} mins</span>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="bg-white rounded-2xl p-4 shadow-card border border-gray-50 fade-in-up" data-animate>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Payment Details</h3>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-gray-500">Amount</span>
                    <span class="text-[12px] font-bold text-secondary">${{ number_format($invoice->amount, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-gray-500">Currency</span>
                    <span class="text-[12px] font-bold text-secondary">{{ strtoupper($invoice->currency) }}</span>
                </div>
                @if($invoice->payment)
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-gray-500">Transaction ID</span>
                    <span class="text-[12px] font-bold text-secondary truncate ml-4">{{ $invoice->payment->transaction_id ?? $invoice->payment->stripe_payment_id ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[12px] text-gray-500">Payment Status</span>
                    @php
                        $payStatusColor = match($invoice->payment->payment_status) {
                            'successful' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                            'failed' => 'bg-rose-50 text-rose-600 border-rose-100',
                            default => 'bg-gray-100 text-gray-500 border-gray-200',
                        };
                    @endphp
                    <span class="px-1.5 py-0.5 rounded-full text-[8px] font-black uppercase border {{ $payStatusColor }}">
                        {{ $invoice->payment->payment_status }}
                    </span>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-2">
            <a href="{{ route('student.invoices.download', $invoice->id) }}" class="flex-1 bg-primary hover:bg-primary/90 text-white text-[12px] font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-2 btn-haptic shadow-sm">
                <i class="fas fa-download text-[11px]"></i> Download PDF
            </a>
            <a href="{{ route('student.invoices.print', $invoice->id) }}" target="_blank" class="flex-1 bg-light hover:bg-gray-100 text-secondary text-[12px] font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-2 btn-haptic border border-gray-100">
                <i class="fas fa-print text-[11px]"></i> Print
            </a>
        </div>
    </div>
</div>

<!-- Desktop Invoice Details -->
<div class="hidden lg:block bg-light min-h-screen py-12 px-4 md:px-12">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

        <!-- Sidebar -->
        <aside class="w-full lg:w-64 shrink-0">@include('partials.student-sidebar')</aside>

        <!-- Main Content -->
        <main class="flex-1 space-y-8">

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <a href="{{ route('student.invoices.index') }}" class="text-xs font-bold text-primary hover:underline mb-2 inline-flex items-center gap-1">
                        <i class="fas fa-arrow-left text-[10px]"></i> Back to Invoices
                    </a>
                    <h1 class="text-3xl font-black text-secondary">Invoice {{ $invoice->invoice_number }}</h1>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('student.invoices.download', $invoice->id) }}" class="bg-primary hover:bg-secondary text-white font-bold py-3 px-6 rounded-full shadow-md transition-all duration-300 flex items-center gap-2 text-sm">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                    <a href="{{ route('student.invoices.print', $invoice->id) }}" target="_blank" class="bg-white border border-gray-200 hover:bg-light text-secondary font-bold py-3 px-6 rounded-full shadow-sm transition-all duration-300 flex items-center gap-2 text-sm">
                        <i class="fas fa-print"></i> Print
                    </a>
                </div>
            </div>

            <!-- Invoice Card -->
            <div class="bg-white rounded-[2rem] shadow-soft border border-gray-50 p-8 md:p-12 fade-up">
                <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-10">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Invoice Number</p>
                        <h2 class="text-2xl font-black text-primary">{{ $invoice->invoice_number }}</h2>
                    </div>
                    <div class="text-left md:text-right">
                        @php
                            $statusColor = match($invoice->status) {
                                'generated' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                'cancelled' => 'bg-gray-50 text-gray-400 border-gray-100',
                                default => 'bg-gray-50 text-gray-500 border-gray-100',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusColor }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                        <p class="text-xs text-gray-400 font-bold uppercase mt-2">Date: {{ $invoice->invoice_date ? $invoice->invoice_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <!-- Student Info -->
                    <div class="p-6 bg-light rounded-2xl">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-3">Billed To</p>
                        <p class="font-black text-secondary text-lg mb-1">{{ $invoice->student->name }}</p>
                        <p class="text-sm text-gray-500">{{ $invoice->student->email }}</p>
                        <p class="text-sm text-gray-500">Year {{ $invoice->student->student_class ?? 'N/A' }}</p>
                    </div>
                    <!-- Session Info -->
                    <div class="p-6 bg-light rounded-2xl">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-3">Session Details</p>
                        <p class="font-black text-secondary text-lg mb-1">{{ $invoice->appointment->subject->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $invoice->appointment->appointment_date ? \Carbon\Carbon::parse($invoice->appointment->appointment_date)->format('M d, Y') : 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $invoice->appointment->start_time ? date('h:i A', strtotime($invoice->appointment->start_time)) : 'N/A' }} &middot; {{ \App\Models\Setting::get('session_duration', 50) }} mins</p>
                    </div>
                </div>

                <!-- Items Table -->
                <table class="w-full text-left mb-8">
                    <thead>
                        <tr class="border-b border-gray-100 text-[10px] font-black uppercase tracking-widest text-gray-400">
                            <th class="px-4 py-3">Description</th>
                            <th class="px-4 py-3">Session Date</th>
                            <th class="px-4 py-3">Duration</th>
                            <th class="px-4 py-3 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-50">
                            <td class="px-4 py-4">
                                <p class="font-bold text-secondary">1-on-1 Live Session</p>
                                <p class="text-xs text-gray-400">Appointment #{{ $invoice->appointment_id }}</p>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500">
                                {{ $invoice->appointment->appointment_date ? \Carbon\Carbon::parse($invoice->appointment->appointment_date)->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500">{{ \App\Models\Setting::get('session_duration', 50) }} Mins</td>
                            <td class="px-4 py-4 text-right font-black text-secondary">${{ number_format($invoice->amount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Total -->
                <div class="flex justify-end">
                    <div class="w-full md:w-64">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-500 font-medium">Subtotal</span>
                            <span class="font-bold text-secondary">${{ number_format($invoice->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-t-2 border-gray-200">
                            <span class="text-lg font-black text-secondary">Total</span>
                            <span class="text-2xl font-black text-primary">${{ number_format($invoice->amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                @if($invoice->payment)
                <!-- Payment Info -->
                <div class="mt-8 pt-8 border-t border-gray-100">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-3">Payment Information</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Transaction ID</p>
                            <p class="text-sm font-bold text-secondary">{{ $invoice->payment->transaction_id ?? $invoice->payment->stripe_payment_id ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Payment Status</p>
                            @php
                                $payStatusColor = match($invoice->payment->payment_status) {
                                    'successful' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'failed' => 'bg-rose-50 text-rose-600 border-rose-100',
                                    default => 'bg-gray-100 text-gray-500 border-gray-200',
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase border {{ $payStatusColor }}">
                                {{ $invoice->payment->payment_status }}
                            </span>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Payment Date</p>
                            <p class="text-sm font-bold text-secondary">{{ $invoice->payment->payment_date ? \Carbon\Carbon::parse($invoice->payment->payment_date)->format('M d, Y h:i A') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </main>
    </div>
</div>

@endsection
