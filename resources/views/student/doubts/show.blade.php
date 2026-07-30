@extends('layouts.student-app')

@section('title', 'Doubt Details | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

@php
    use Illuminate\Support\Facades\Storage;
    $statusColors = [
        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
        'accepted' => 'bg-primary-50 text-primary-600 border-primary-100',
        'resolved' => 'bg-green-50 text-green-600 border-green-100',
        'cancelled' => 'bg-gray-50 text-gray-400 border-gray-100',
    ];
    $isImage = false;
    $attachmentUrl = null;
    if ($doubt->attachment) {
        $extension = pathinfo($doubt->attachment, PATHINFO_EXTENSION);
        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
        $attachmentUrl = Storage::disk('public')->url($doubt->attachment);
    }
@endphp

<!-- Mobile Doubt Detail -->
<div class="lg:hidden">
    <x-mobile.page-header title="Doubt Details" backUrl="{{ route('student.doubts.index') }}" icon="fas fa-question-circle" />

    <div class="px-4 pb-32 space-y-3">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl p-4 shadow-card border border-gray-50 fade-in-up" data-animate>
            <div class="flex items-center gap-2 mb-3">
                <span class="px-2.5 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-wider border border-primary/10">
                    {{ $doubt->subject->name }}
                </span>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border {{ $statusColors[$doubt->status] ?? 'bg-gray-100 text-gray-500 border-gray-200' }}">
                    {{ $doubt->status }}
                </span>
            </div>
            <h1 class="text-lg font-black text-secondary mb-1">
                @if(is_array($doubt->title))
                    @foreach($doubt->title as $t)
                        <span class="block">{{ $t }}</span>
                    @endforeach
                @else
                    {{ $doubt->title }}
                @endif
            </h1>
            <p class="text-xs text-gray-500">Topic: {{ $doubt->topic_name }}</p>
            <p class="text-[10px] text-gray-400 font-bold mt-2 uppercase">{{ $doubt->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>

        <!-- Description -->
        <div class="bg-white rounded-2xl p-4 shadow-card border border-gray-50 fade-in-up" data-animate style="animation-delay: 0.1s;">
            <h3 class="font-bold text-secondary text-sm mb-2">Description</h3>
            <div class="bg-light rounded-xl p-3 text-sm text-gray-600 leading-relaxed">
                {!! nl2br(e($doubt->description)) !!}
            </div>
        </div>

        <!-- Attachment -->
        @if($doubt->attachment)
        <div class="bg-white rounded-2xl p-4 shadow-card border border-gray-50 fade-in-up" data-animate style="animation-delay: 0.15s;">
            <h3 class="font-bold text-secondary text-sm mb-2">Attachment</h3>
            @if($isImage)
                <img src="{{ $attachmentUrl }}" alt="Attachment" class="w-full rounded-xl mb-3">
            @else
                <div class="bg-light rounded-xl p-6 text-center text-gray-400 mb-3">
                    <i class="fas fa-file-pdf text-3xl mb-2 block"></i>
                    <span class="text-xs font-bold uppercase">PDF Document</span>
                </div>
            @endif
            <a href="{{ $attachmentUrl }}" download class="w-full py-2.5 bg-secondary text-white rounded-xl text-center text-xs font-bold btn-haptic flex items-center justify-center gap-2">
                <i class="fas fa-download"></i> Download
            </a>
        </div>
        @endif

        <!-- Guidance -->
        <div class="bg-accent/10 rounded-2xl p-4 border border-accent/20 fade-in-up" data-animate style="animation-delay: 0.2s;">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-accent/30 flex items-center justify-center text-secondary shrink-0">
                    <i class="fas fa-info-circle text-xs"></i>
                </div>
                <div>
                    <h4 class="font-bold text-secondary text-sm">What's Next?</h4>
                    <p class="text-xs text-gray-600 mt-1">Book a session to get this doubt resolved by our educator.</p>
                    <a href="{{ route('student.booking.create') }}" class="inline-block mt-2 text-primary font-bold text-xs">Book a Session <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Desktop Doubt Detail -->
<div class="hidden lg:block bg-light min-h-screen py-12 px-4 md:px-12">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

        <!-- Sidebar -->
        <aside class="w-full lg:w-64 shrink-0">@include('partials.student-sidebar')</aside>

        <!-- Main Content -->
        <main class="flex-1 space-y-8">

            <div class="bg-white rounded-[2rem] shadow-soft border border-gray-50 p-8 md:p-12 fade-up">
                <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-10 pb-8 border-b border-gray-50">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-wider border border-primary/10">
                                {{ $doubt->subject->name }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusColors[$doubt->status] ?? 'bg-gray-50 text-gray-500' }}">
                                {{ $doubt->status }}
                            </span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black text-secondary">
                            @if(is_array($doubt->title))
                                @foreach($doubt->title as $t)
                                    <span class="block">{{ $t }}</span>
                                @endforeach
                            @else
                                {{ $doubt->title }}
                            @endif
                        </h1>
                        <p class="text-gray-500 mt-2 font-medium">Topic: {{ $doubt->topic_name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Submitted On</p>
                        <p class="text-sm font-black text-secondary">{{ $doubt->created_at->format('F d, Y') }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $doubt->created_at->format('h:i A') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    <div class="lg:col-span-8">
                        <h3 class="text-xs font-bold text-secondary uppercase tracking-wider mb-4">Description</h3>
                        <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed bg-light p-6 rounded-2xl border border-gray-50">
                            {!! nl2br(e($doubt->description)) !!}
                        </div>
                    </div>

                    <div class="lg:col-span-4">
                        <h3 class="text-xs font-bold text-secondary uppercase tracking-wider mb-4">Attachment</h3>
                        @if($doubt->attachment)
                            <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm group">
                                @if($isImage)
                                    <div class="relative rounded-xl overflow-hidden aspect-square mb-4">
                                        <img src="{{ $attachmentUrl }}" alt="Doubt attachment" class="w-full h-full object-cover">
                                        <a href="{{ $attachmentUrl }}" target="_blank" class="absolute inset-0 bg-secondary/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xl">
                                            <i class="fas fa-search-plus"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="w-full aspect-square bg-light rounded-xl flex flex-col items-center justify-center mb-4 text-gray-400">
                                        <i class="fas fa-file-pdf text-4xl mb-2"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-widest">PDF Document</span>
                                    </div>
                                @endif
                                <a href="{{ $attachmentUrl }}" download class="w-full py-3 bg-secondary text-white rounded-xl text-center text-xs font-bold hover:bg-primary transition-colors flex items-center justify-center gap-2">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                            </div>
                        @else
                            <div class="bg-light rounded-2xl p-8 text-center border border-dashed border-gray-200">
                                <i class="fas fa-file-alt text-gray-300 text-2xl mb-2 block"></i>
                                <p class="text-xs text-gray-400 font-medium tracking-wide">No attachment provided</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-12 p-6 bg-accent/5 rounded-2xl border border-accent/20 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-accent/20 text-secondary flex items-center justify-center shrink-0">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-secondary text-sm mb-1">What's Next?</h4>
                        <p class="text-xs text-gray-600 leading-relaxed italic">"Our educator will review your doubt and provide a solution during your next scheduled session. Make sure to book a slot if you haven't already!"</p>
                        <a href="{{ route('student.booking.create') }}" class="inline-block mt-3 text-primary font-black text-xs hover:underline">Book a Session Now <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

@endsection
