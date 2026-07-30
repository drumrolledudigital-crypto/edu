@props(['appointment'])

@php
    $statusColors = [
        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
        'scheduled' => 'bg-primary-50 text-primary-600 border-primary-100',
        'confirmed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'completed' => 'bg-gray-100 text-gray-500 border-gray-200',
        'cancelled' => 'bg-rose-50 text-rose-600 border-rose-100',
        'rescheduled' => 'bg-purple-50 text-purple-600 border-purple-100',
    ];
    $status = $appointment->status;
    $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-500 border-gray-200';

    $hasMeetLink = $status === 'confirmed' && ($appointment->google_meet_link ?? $appointment->meet_link);
@endphp

<div class="bg-white rounded-2xl p-3 shadow-card border border-gray-50 card-press fade-in-up" data-animate>
    <div class="flex items-center gap-2 mb-1.5">
        <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-[10px] font-bold border border-primary/10 shrink-0">
            {{ substr($appointment->subject->name ?? 'S', 0, 2) }}
        </div>
        <div class="min-w-0 flex-1">
            <h4 class="font-bold text-secondary text-[13px] leading-tight truncate">{{ $appointment->subject->name ?? 'N/A' }}</h4>
        </div>
        <span class="px-1.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider border shrink-0 {{ $statusColor }}">
            {{ $status }}
        </span>
    </div>

    <p class="text-[11px] text-gray-400 font-medium line-clamp-1 mb-2 pl-10">{{ $appointment->doubt->title ?? '' }}</p>

    <div class="flex items-center gap-3 text-[11px] text-gray-500 mb-2.5 pl-10">
        <div class="flex items-center gap-1">
            <i class="fas fa-calendar-day text-primary/50 text-[10px]"></i>
            <span class="font-semibold">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</span>
        </div>
        <div class="flex items-center gap-1">
            <i class="fas fa-clock text-primary/50 text-[10px]"></i>
            <span class="font-semibold">{{ date('h:i A', strtotime($appointment->start_time)) }}</span>
        </div>
    </div>

    <div class="flex items-center gap-2">
        @if($hasMeetLink)
            <a href="{{ $appointment->google_meet_link ?? $appointment->meet_link }}" target="_blank" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white text-[11px] font-bold py-2.5 px-3 rounded-xl flex items-center justify-center gap-1.5 btn-haptic shadow-sm">
                <i class="fas fa-video text-[10px]"></i> Join Meet
            </a>
        @endif

        @if(!$appointment->payment || $appointment->payment->payment_status !== 'successful')
            <a href="{{ route('student.payment.pay', $appointment->id) }}" class="flex-1 bg-primary hover:bg-primary/90 text-white text-[11px] font-bold py-2.5 px-3 rounded-xl flex items-center justify-center gap-1.5 btn-haptic shadow-sm">
                <i class="fas fa-credit-card text-[10px]"></i> Pay Now
            </a>
        @elseif($appointment->payment && $appointment->payment->invoice)
            <a href="{{ route('student.invoices.download', $appointment->payment->invoice->id) }}" class="flex-1 bg-light hover:bg-gray-100 text-secondary text-[11px] font-bold py-2.5 px-3 rounded-xl flex items-center justify-center gap-1.5 btn-haptic border border-gray-100">
                <i class="fas fa-file-pdf text-rose-500 text-[10px]"></i> Invoice
            </a>
        @endif
    </div>
</div>
