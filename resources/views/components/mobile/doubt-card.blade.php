@props(['doubt'])

@php
    $statusColors = [
        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
        'accepted' => 'bg-primary-50 text-primary-600 border-primary-100',
        'resolved' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'cancelled' => 'bg-gray-100 text-gray-500 border-gray-200',
    ];
    $statusColor = $statusColors[$doubt->status] ?? 'bg-gray-100 text-gray-500 border-gray-200';
@endphp

<a href="{{ route('student.doubts.show', $doubt->id) }}" class="block bg-white rounded-2xl p-3 shadow-card border border-gray-50 card-press fade-in-up" data-animate>
    <div class="flex items-center gap-1.5 mb-1.5 overflow-hidden">
        <span class="px-1.5 py-0.5 rounded bg-primary/10 text-primary text-[8px] font-black uppercase tracking-wider border border-primary/10 shrink-0">
            {{ $doubt->subject->name }}
        </span>
        <span class="px-1.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider border shrink-0 {{ $statusColor }}">
            {{ $doubt->status }}
        </span>
    </div>
    <h4 class="font-bold text-secondary text-[13px] mb-0.5 line-clamp-1">
        @if(is_array($doubt->title))
            {{ $doubt->title[0] ?? '' }}@if(count($doubt->title) > 1) <span class="text-gray-400 font-normal">+{{ count($doubt->title) - 1 }}</span>@endif
        @else
            {{ $doubt->title }}
        @endif
    </h4>
    <p class="text-[11px] text-gray-400 line-clamp-1">{{ $doubt->topic_name }}</p>
    <div class="mt-2 pt-2 border-t border-gray-50">
        <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $doubt->created_at->format('M d, Y') }}</span>
    </div>
</a>
