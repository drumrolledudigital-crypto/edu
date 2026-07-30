@extends('layouts.student-app')

@section('title', 'Past Sessions | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Mobile Past Sessions -->
<div class="lg:hidden">
    <x-mobile.page-header title="Past Sessions" subtitle="Review your completed sessions" icon="fas fa-history" />

    <div class="px-3 pb-32 space-y-3">
        @forelse($sessions as $session)
        <a href="{{ route('student.doubts.show', $session->doubt_id) }}" class="block bg-white rounded-2xl p-3 shadow-card border border-gray-50 card-press fade-in-up" data-animate>
            <div class="flex items-center gap-2 mb-1.5">
                <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center text-[10px] font-bold border border-gray-200 shrink-0">
                    {{ substr($session->subject?->name ?? 'NA', 0, 2) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-bold text-secondary text-[13px] leading-tight truncate">{{ $session->subject?->name ?? 'N/A' }}</p>
                </div>
                <span class="px-1.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider border shrink-0 {{ $session->status === 'completed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-gray-100 text-gray-500 border-gray-200' }}">
                    {{ $session->status }}
                </span>
            </div>
            <p class="text-[11px] text-gray-400 line-clamp-1 mb-2 pl-10">{{ $session->doubt?->title ?? 'N/A' }}</p>
            <div class="flex items-center gap-2.5 text-[11px] text-gray-500 pl-10">
                <span class="font-semibold">{{ $session->appointment_date->format('M d, Y') }}</span>
                <span>{{ date('h:i A', strtotime($session->start_time)) }}</span>
                <span class="text-gray-400">{{ $session->duration }} min</span>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-50 flex items-center justify-end">
                <span class="text-[10px] font-bold text-primary uppercase">View Details</span>
            </div>
        </a>
        @empty
        <div class="bg-white rounded-2xl p-6 shadow-card border border-gray-50 text-center fade-in-up" data-animate>
            <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300 text-xl">
                <i class="fas fa-history"></i>
            </div>
            <p class="text-gray-500 font-bold mb-0.5 text-sm">No past sessions</p>
            <p class="text-xs text-gray-400">Completed sessions will appear here.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Desktop Past Sessions -->
<div class="hidden lg:block bg-light min-h-screen py-12 px-4 md:px-12">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

        <!-- Sidebar -->
        <aside class="w-full lg:w-64 shrink-0">@include('partials.student-sidebar')</aside>

        <!-- Main Content -->
        <main class="flex-1 space-y-8">

            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-black text-secondary">Past Sessions</h1>
                <p class="text-gray-500 mt-1">Review your completed 1-on-1 learning journey.</p>
            </div>

            <div class="bg-white rounded-[2rem] shadow-soft border border-gray-50 p-8 md:p-12 fade-up">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-100 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                <th class="px-4 py-4">Date</th>
                                <th class="px-4 py-4">Subject & Doubt</th>
                                <th class="px-4 py-4">Status</th>
                                <th class="px-4 py-4 text-center">Duration</th>
                                <th class="px-4 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($sessions as $session)
                            <tr class="group hover:bg-light/50 transition-colors text-sm">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <p class="font-black text-secondary">{{ $session->appointment_date->format('M d, Y') }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">{{ date('h:i A', strtotime($session->start_time)) }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-0.5 rounded bg-muted text-foreground text-[10px] font-bold uppercase tracking-wider mb-1 inline-block">
                                        {{ $session->subject->name }}
                                    </span>
                                    <h4 class="font-bold text-secondary text-sm truncate max-w-[250px]" title="{{ $session->doubt->title }}">{{ $session->doubt->title }}</h4>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $session->status === 'completed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-gray-50 text-gray-400 border-gray-100' }}">
                                        {{ $session->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="text-xs font-bold text-gray-500">{{ $session->duration }} Min</span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <a href="{{ route('student.doubts.show', $session->doubt_id) }}" class="inline-flex items-center justify-center h-9 px-4 rounded-xl bg-light text-secondary hover:bg-primary hover:text-white transition-all text-xs font-bold gap-2">
                                        <i class="fas fa-eye"></i> Details
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-2xl">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <p class="text-gray-500 font-bold">No past sessions found.</p>
                                    <p class="text-sm text-gray-400 mt-1">Completed sessions will appear here for your review.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>

@endsection
