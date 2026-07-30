@props(['payment'])

@php
    $statusColor = match($payment->payment_status) {
        'successful' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
        'failed' => 'bg-rose-50 text-rose-600 border-rose-100',
        'refunded' => 'bg-gray-100 text-gray-500 border-gray-200',
        default => 'bg-gray-100 text-gray-500 border-gray-200',
    };
    $statusLabel = match($payment->payment_status) {
        'successful' => 'Paid',
        default => ucfirst($payment->payment_status),
    };
@endphp

<div class="flex items-center justify-between p-3 bg-white rounded-2xl border border-gray-50 shadow-card card-press fade-in-up" data-animate>
    <div class="flex items-center gap-2 min-w-0">
        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary shrink-0 border border-primary/10">
            <i class="fas fa-receipt text-[9px]"></i>
        </div>
        <div class="min-w-0">
            <p class="font-bold text-secondary text-[13px] truncate leading-tight">{{ $payment->appointment->subject->name ?? 'Payment' }}</p>
            <p class="text-[9px] text-gray-400 font-bold uppercase">
                {{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'Pending' }}
            </p>
        </div>
    </div>
    <div class="text-right shrink-0 ml-2">
        <p class="font-bold text-secondary text-[13px] leading-tight">${{ number_format($payment->amount, 2) }}</p>
        <span class="px-1.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider border {{ $statusColor }}">
            {{ $statusLabel }}
        </span>
    </div>
</div>
