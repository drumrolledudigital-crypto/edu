@props(['notification'])

@php
    $iconMap = [
        'welcome_student' => 'fas fa-hand-sparkles',
        'payment_success' => 'fas fa-credit-card',
        'booking_confirmed' => 'fas fa-calendar-check',
        'session_reminder' => 'fas fa-bell',
        'default' => 'fas fa-bell',
    ];
    $icon = $iconMap[$notification->type] ?? $iconMap['default'];
@endphp

<div class="flex items-start gap-2 p-3 bg-white rounded-2xl border border-gray-50 shadow-card card-press fade-in-up" data-animate>
    <div class="w-7 h-7 rounded-full bg-primary/10 flex items-center justify-center text-primary text-[9px] shrink-0 border border-primary/10">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="flex-1 min-w-0">
        <p class="font-bold text-secondary text-[13px] leading-tight line-clamp-1">{{ $notification->subject }}</p>
        @if($notification->body ?? null)
            <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-2">{{ $notification->body }}</p>
        @endif
        <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase">
            {{ $notification->sent_at ? $notification->sent_at->diffForHumans() : $notification->created_at->diffForHumans() }}
        </p>
    </div>
</div>
