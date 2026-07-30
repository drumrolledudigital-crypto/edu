@extends('layouts.student-app')

@section('title', 'Payment History | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

@php
    $statusColors = [
        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
        'successful' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'failed' => 'bg-rose-50 text-rose-600 border-rose-100',
        'refunded' => 'bg-gray-100 text-gray-500 border-gray-200',
    ];
@endphp

<!-- Mobile Payment History -->
<div class="lg:hidden">
    <x-mobile.page-header title="Payment History" subtitle="Track your transactions" icon="fas fa-credit-card" />

    <div class="px-4 pb-32 space-y-3">
        @forelse($payments as $payment)
        <div class="bg-white rounded-2xl p-4 shadow-card border border-gray-50 card-press fade-in-up" data-animate>
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0 border border-primary/10">
                        <i class="fas fa-receipt text-xs"></i>
                    </div>
                    <div>
                        <p class="font-bold text-secondary text-sm">{{ $payment->appointment->subject->name }}</p>
                        <p class="text-[10px] text-gray-400 font-mono">TXN: {{ $payment->transaction_id ?: 'PENDING' }}</p>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border {{ $statusColors[$payment->payment_status] ?? 'bg-gray-100 text-gray-500 border-gray-200' }}">
                    {{ $payment->payment_status }}
                </span>
            </div>

            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                <div>
                    <span class="font-bold text-secondary text-sm">${{ number_format($payment->amount, 2) }}</span>
                    <span class="text-[10px] text-gray-400 uppercase font-bold ml-1">{{ $payment->currency }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] text-gray-400">{{ $payment->payment_date ? $payment->payment_date->format('M d') : 'N/A' }}</span>
                    @if($payment->payment_status === 'successful' && $payment->invoice)
                        <a href="{{ route('student.invoices.download', $payment->invoice->id) }}" class="px-2 py-1 bg-light text-secondary text-[10px] font-bold rounded-lg border border-gray-100 btn-haptic">
                            <i class="fas fa-download mr-0.5"></i> PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-8 shadow-card border border-gray-50 text-center fade-in-up" data-animate>
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-2xl">
                <i class="fas fa-credit-card"></i>
            </div>
            <p class="text-gray-500 font-bold">No transactions yet</p>
            <p class="text-sm text-gray-400 mt-1">Payment records will appear here.</p>
        </div>
        @endforelse

        @if($payments->hasPages())
        <div class="py-4">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Desktop Payment History -->
<div class="hidden lg:block bg-light min-h-screen py-12 px-4 md:px-12">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

        <!-- Sidebar -->
        <aside class="w-full lg:w-64 shrink-0">@include('partials.student-sidebar')</aside>

        <!-- Main Content -->
        <main class="flex-1 space-y-8">

            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-black text-secondary uppercase tracking-tight">Payment <span class="text-primary">History</span></h1>
                <p class="text-gray-500 mt-1">Track your session payments and transaction details.</p>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-soft border border-gray-50 p-8 md:p-12 fade-up">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-100 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                <th class="px-4 py-4">Booking / Transaction</th>
                                <th class="px-4 py-4 text-center">Amount</th>
                                <th class="px-4 py-4 text-center">Status</th>
                                <th class="px-4 py-4 text-right">Date & Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            @forelse($payments as $payment)
                            <tr class="group hover:bg-light/50 transition-colors">
                                <td class="px-4 py-4">
                                    <p class="font-black text-secondary">{{ $payment->appointment->subject->name }}</p>
                                    <p class="text-[10px] font-mono text-gray-400 mt-1">TXN: {{ $payment->transaction_id ?: 'PENDING' }}</p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="font-black text-secondary">${{ number_format($payment->amount, 2) }}</span>
                                    <span class="text-[10px] text-gray-400 uppercase font-bold ml-1">{{ $payment->currency }}</span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusColors[$payment->payment_status] ?? 'bg-gray-50 text-gray-500' }}">
                                        {{ $payment->payment_status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right text-xs text-gray-400 font-medium">
                                    <div class="flex flex-col items-end gap-2">
                                        <span class="italic">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A' }}</span>
                                        @if($payment->payment_status === 'successful')
                                            <div class="flex gap-2 mt-1">
                                                @if($payment->invoice)
                                                    <a href="{{ route('student.invoices.download', $payment->invoice->id) }}" target="_blank" class="inline-flex items-center justify-center px-2 py-1 rounded bg-gray-50 text-gray-600 hover:text-primary transition-all shadow-sm border border-gray-100 text-[10px] uppercase font-bold tracking-widest">
                                                        <i class="fas fa-download mr-1"></i> Invoice
                                                    </a>
                                                @endif
                                                @if(!$payment->refund)
                                                    <a href="{{ route('student.refunds.create', $payment->id) }}" class="inline-flex items-center justify-center px-2 py-1 rounded bg-rose-50 text-rose-600 hover:text-white hover:bg-rose-500 transition-all shadow-sm border border-rose-100 text-[10px] uppercase font-bold tracking-widest">
                                                        Refund
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center text-gray-400 italic">No transactions recorded yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8">
                    {{ $payments->links() }}
                </div>
            </div>

        </main>
    </div>
</div>

@endsection
