@extends('layouts.student-app')

@section('title', 'Upcoming Sessions | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Mobile Upcoming Sessions -->
<div class="lg:hidden">
    <x-mobile.page-header title="Upcoming Sessions" subtitle="Get ready for your live sessions" icon="fas fa-video" />

    <div class="px-3 pb-32 space-y-3">
        @forelse($sessions as $session)
        <div class="bg-white rounded-2xl p-3 shadow-card border border-gray-50 card-press fade-in-up" data-animate>
            <div class="flex items-center gap-2 mb-1.5">
                <div class="w-10 h-10 gradient-navy rounded-xl flex flex-col items-center justify-center text-white shrink-0">
                    <span class="text-[8px] font-bold uppercase opacity-60 leading-none">{{ $session->appointment_date->format('D') }}</span>
                    <span class="text-sm font-black leading-none mt-0.5">{{ $session->appointment_date->format('d') }}</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-bold text-secondary text-[13px] leading-tight truncate">{{ $session->subject?->name ?? 'N/A' }}</p>
                </div>
                <span class="px-1.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase border border-emerald-100 shrink-0">
                    {{ $session->status }}
                </span>
            </div>

            <p class="text-[11px] text-gray-400 line-clamp-1 mb-2 pl-12">{{ $session->doubt?->title ?? 'N/A' }}</p>

            <div class="flex items-center gap-3 text-[11px] text-gray-500 mb-2.5 pl-12">
                <div class="flex items-center gap-1">
                    <i class="fas fa-clock text-primary text-[10px]"></i>
                    <span class="font-semibold">{{ date('h:i A', strtotime($session->start_time)) }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <i class="fas fa-hourglass-half text-primary-400 text-[10px]"></i>
                    <span class="font-semibold">{{ \App\Models\Setting::get('session_duration', 50) }} mins</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if($session->status === 'confirmed' && ($session->google_meet_link || $session->meet_link))
                    <a href="{{ $session->google_meet_link ?? $session->meet_link }}" target="_blank" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white text-[11px] font-bold py-2.5 px-3 rounded-xl flex items-center justify-center gap-1.5 btn-haptic shadow-sm">
                        <i class="fas fa-video text-[10px]"></i> Join Meet
                    </a>
                @else
                    <div class="flex-1 bg-gray-100 text-gray-400 text-[11px] font-bold py-2.5 px-3 rounded-xl flex items-center justify-center gap-1.5">
                        <i class="fas fa-lock text-[10px]"></i> Link Pending
                    </div>
                @endif
                <a href="{{ route('student.doubts.show', $session->doubt_id) }}" class="px-3 py-2.5 bg-light text-secondary text-[11px] font-bold rounded-xl border border-gray-100 btn-haptic shrink-0">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-6 shadow-card border border-gray-50 text-center fade-in-up" data-animate>
            <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300 text-xl">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h3 class="text-lg font-black text-secondary mb-1.5">No upcoming sessions</h3>
            <p class="text-gray-500 text-xs mb-5">Book a session to continue learning!</p>
            <a href="{{ route('student.booking.create') }}" class="inline-block bg-primary text-white font-bold py-2.5 px-6 rounded-xl btn-haptic text-sm">Book a Session</a>
        </div>
        @endforelse
    </div>
</div>

<!-- Desktop Upcoming Sessions -->
<div class="hidden lg:block bg-light min-h-screen py-12 px-4 md:px-12">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

        <!-- Sidebar -->
        <aside class="w-full lg:w-64 shrink-0">@include('partials.student-sidebar')</aside>

        <!-- Main Content -->
        <main class="flex-1 space-y-8">

            <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-black text-secondary">Upcoming Sessions</h1>
                    <p class="text-gray-500 mt-1">Get ready for your live 1-on-1 learning moments.</p>
                </div>
                <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center text-xl animate-pulse">
                    <i class="fas fa-video"></i>
                </div>
            </div>

            <div class="space-y-6">
                @forelse($sessions as $session)
                <div class="bg-white rounded-[2rem] shadow-soft border border-gray-50 p-8 md:p-10 flex flex-col md:flex-row items-center gap-8 group fade-up">
                    <!-- Date & Time Badge -->
                    <div class="w-full md:w-32 shrink-0 flex flex-col items-center justify-center p-6 bg-secondary text-white rounded-3xl text-center group-hover:bg-primary transition-colors duration-500">
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-60">{{ $session->appointment_date->format('D') }}</p>
                        <p class="text-3xl font-black my-1">{{ $session->appointment_date->format('d') }}</p>
                        <p class="text-[10px] font-black uppercase tracking-widest">{{ $session->appointment_date->format('M, Y') }}</p>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded bg-muted text-foreground text-[10px] font-bold uppercase tracking-wider">
                                {{ $session->subject->name }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-wider border border-emerald-100">
                                {{ $session->status }}
                            </span>
                        </div>
                        <h3 class="text-xl font-black text-secondary mb-2">{{ $session->doubt->title }}</h3>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-500 font-medium">
                            <div class="flex items-center gap-2"><i class="fas fa-clock text-primary"></i> {{ date('h:i A', strtotime($session->start_time)) }}</div>
                            <div class="flex items-center gap-2"><i class="fas fa-hourglass-half text-primary-400"></i> {{ \App\Models\Setting::get('session_duration', 50) }} Mins</div>
                            <div class="flex items-center gap-2"><i class="fas fa-calendar-check text-purple-400"></i> {{ $session->calendar_status ?? 'Pending' }}</div>
                        </div>
                    </div>

                    <div class="w-full md:w-auto shrink-0 flex flex-col gap-3">
                        @if($session->status === 'confirmed' && ($session->google_meet_link || $session->meet_link))
                            <a href="{{ $session->google_meet_link ?? $session->meet_link }}" target="_blank" class="bg-emerald-500 hover:bg-emerald-600 text-white font-black py-4 px-8 rounded-full shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                                <i class="fas fa-video"></i> Join Meet
                            </a>
                        @else
                            <button disabled class="bg-gray-100 text-gray-400 font-bold py-4 px-8 rounded-full cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="fas fa-lock"></i> Link Pending
                            </button>
                        @endif
                        <a href="{{ route('student.doubts.show', $session->doubt_id) }}" class="text-xs font-black text-secondary hover:text-primary transition-colors text-center uppercase tracking-widest">
                            View Doubt Details
                        </a>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-[2rem] shadow-soft border border-gray-50 p-16 text-center fade-up">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300 text-3xl">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="text-2xl font-black text-secondary mb-2">No upcoming sessions</h3>
                    <p class="text-gray-500 mb-8 max-w-sm mx-auto">Your schedule looks empty. Book a new session to continue your learning journey!</p>
                    <a href="{{ route('student.booking.create') }}" class="bg-primary hover:bg-secondary text-white font-bold py-4 px-10 rounded-full shadow-lg transition-all duration-300">
                        Book a Session
                    </a>
                </div>
                @endforelse
            </div>

        </main>
    </div>
</div>

@endsection
