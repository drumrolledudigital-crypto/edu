<!-- Mobile Bottom Navigation -->
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl border-t border-gray-100 safe-area-bottom" id="student-bottom-nav">
    <div class="flex items-center justify-around px-2 py-1 max-w-lg mx-auto">
        @php
            $navItems = [
                ['route' => 'student.dashboard', 'icon' => 'fas fa-home', 'label' => 'Home'],
                ['route' => 'student.doubts.index', 'icon' => 'fas fa-book-open', 'label' => 'Doubts'],
                ['route' => 'student.booking.create', 'icon' => 'fas fa-plus-circle', 'label' => 'Book', 'highlight' => true],
                ['route' => 'student.booking.index', 'icon' => 'fas fa-calendar-check', 'label' => 'Sessions'],
                ['route' => 'student.profile', 'icon' => 'fas fa-user', 'label' => 'Profile'],
            ];
        @endphp

        @foreach($navItems as $item)
            @if($item['highlight'] ?? false)
                <a href="{{ route($item['route']) }}" class="flex flex-col items-center justify-center -mt-5">
                    <div class="w-14 h-14 bg-primary rounded-full flex items-center justify-center text-white shadow-lg shadow-primary/30 transform transition-transform active:scale-95">
                        <i class="{{ $item['icon'] }} text-xl"></i>
                    </div>
                    <span class="text-[9px] font-bold text-primary mt-1">{{ $item['label'] }}</span>
                </a>
            @else
                <a href="{{ route($item['route']) }}" class="flex flex-col items-center justify-center py-2 px-3 relative group">
                    <div class="relative">
                        <i class="{{ $item['icon'] }} text-lg {{ request()->routeIs($item['route']) ? 'text-primary' : 'text-gray-400' }} transition-colors"></i>
                        @if(request()->routeIs($item['route']))
                            <div class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-1 h-1 bg-primary rounded-full"></div>
                        @endif
                    </div>
                    <span class="text-[9px] font-bold {{ request()->routeIs($item['route']) ? 'text-primary' : 'text-gray-400' }} mt-1 transition-colors">{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </div>
</nav>
