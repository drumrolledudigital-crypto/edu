@props(['icon', 'label', 'value', 'sublabel' => '', 'color' => 'primary', 'href' => null])

@php
    $colorMap = [
        'primary' => 'bg-primary/10 text-primary',
        'blue' => 'bg-primary-50 text-primary-500',
        'green' => 'bg-emerald-50 text-emerald-500',
        'purple' => 'bg-purple-50 text-purple-500',
        'amber' => 'bg-amber-50 text-amber-500',
        'rose' => 'bg-rose-50 text-rose-500',
    ];
    $iconBg = $colorMap[$color] ?? $colorMap['primary'];
@endphp

@if($href)
    <a href="{{ $href }}" class="block bg-white rounded-2xl p-3 shadow-card border border-gray-50 card-press fade-in-up" data-animate>
@else
    <div class="bg-white rounded-2xl p-3 shadow-card border border-gray-50 fade-in-up" data-animate>
@endif
    <div class="flex items-center gap-2 overflow-hidden">
        <div class="w-9 h-9 {{ $iconBg }} rounded-xl flex items-center justify-center text-sm shrink-0">
            <i class="{{ $icon }}"></i>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-wider leading-tight truncate">{{ $label }}</p>
            <div class="flex items-baseline gap-0.5">
                <h4 class="text-lg font-black text-secondary leading-tight">{{ $value }}</h4>
                @if($sublabel)
                    <span class="text-[9px] font-medium text-gray-400 leading-tight truncate">{{ $sublabel }}</span>
                @endif
            </div>
        </div>
    </div>
@if($href)
    </a>
@else
    </div>
@endif
