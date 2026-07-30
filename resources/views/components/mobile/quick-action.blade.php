@props(['icon', 'label', 'href', 'color' => 'primary'])

@php
    $colorMap = [
        'primary' => 'bg-primary/10 text-primary border-primary/10',
        'blue' => 'bg-primary-50 text-primary-500 border-primary-100',
        'green' => 'bg-emerald-50 text-emerald-500 border-emerald-100',
        'purple' => 'bg-purple-50 text-purple-500 border-purple-100',
        'amber' => 'bg-amber-50 text-amber-500 border-amber-100',
    ];
    $iconClass = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<a href="{{ $href }}" class="flex flex-col items-center gap-1 card-press btn-haptic overflow-hidden">
    <div class="w-11 h-11 {{ $iconClass }} rounded-xl flex items-center justify-center text-sm border shadow-sm active:scale-95 transition-transform">
        <i class="{{ $icon }}"></i>
    </div>
    <span class="text-[9px] font-bold text-secondary text-center leading-tight line-clamp-2 max-w-full">{{ $label }}</span>
</a>
