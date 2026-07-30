@props(['title', 'actionText' => null, 'actionHref' => null])

<div class="flex items-center justify-between px-1 py-2.5 fade-in-up" data-animate>
    <h2 class="text-sm font-extrabold text-secondary">{{ $title }}</h2>
    @if($actionText && $actionHref)
        <a href="{{ $actionHref }}" class="text-[11px] font-bold text-primary flex items-center gap-1">
            {{ $actionText }}
        </a>
    @endif
</div>
