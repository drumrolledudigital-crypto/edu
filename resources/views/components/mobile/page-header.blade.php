@props(['title', 'subtitle' => null, 'backUrl' => null, 'actionText' => null, 'actionHref' => null, 'icon' => null])

<div class="px-3 pt-3 pb-1 fade-in-up" data-animate>
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2.5 min-w-0">
            @if($backUrl)
                <a href="{{ $backUrl }}" class="w-9 h-9 rounded-xl bg-white shadow-card border border-gray-50 flex items-center justify-center text-secondary btn-haptic shrink-0">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
            @endif
            <div class="min-w-0">
                <h1 class="text-lg font-black text-secondary flex items-center gap-1.5 leading-tight">
                    @if($icon)
                        <i class="{{ $icon }} text-primary text-base shrink-0"></i>
                    @endif
                    <span class="truncate">{{ $title }}</span>
                </h1>
                @if($subtitle)
                    <p class="text-[11px] text-gray-500 font-medium mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        @if($actionText && $actionHref)
            <a href="{{ $actionHref }}" class="bg-primary hover:bg-primary/90 text-white font-bold text-[11px] py-2 px-3 rounded-xl shadow-sm btn-haptic flex items-center gap-1 shrink-0 ml-2">
                {{ $actionText }}
            </a>
        @endif
    </div>
</div>
