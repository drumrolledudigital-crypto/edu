@extends('layouts.student-app')

@section('title', 'My Doubts | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Mobile Doubts -->
<div class="lg:hidden">
    <x-mobile.page-header title="My Doubts" subtitle="Track your submitted questions" icon="fas fa-question-circle" actionText="New" actionHref="{{ route('doubts.create') }}" />

    @if(session('success'))
    <div class="px-4 py-2">
        <div class="p-3 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-2 fade-in-up" data-animate>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    </div>
    @endif

    <div class="px-3 pb-32 space-y-3">
        @forelse($doubts as $doubt)
            <x-mobile.doubt-card :doubt="$doubt" />
        @empty
            <div class="bg-white rounded-2xl p-6 shadow-card border border-gray-50 text-center fade-in-up" data-animate>
                <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300 text-xl">
                    <i class="fas fa-inbox"></i>
                </div>
                <p class="text-gray-500 font-bold mb-0.5 text-sm">No doubts yet</p>
                <p class="text-xs text-gray-400 mb-5">Have a question? Submit your first doubt!</p>
                <a href="{{ route('doubts.create') }}" class="inline-block bg-primary text-white font-bold py-2.5 px-6 rounded-xl btn-haptic text-sm">Submit Doubt</a>
            </div>
        @endforelse

        @if($doubts->hasPages())
        <div class="py-4">
            {{ $doubts->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Desktop Doubts -->
<div class="hidden lg:block bg-light min-h-screen py-12 px-4 md:px-12">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

        <!-- Sidebar -->
        <aside class="w-full lg:w-64 shrink-0">@include('partials.student-sidebar')</aside>

        <!-- Main Content -->
        <main class="flex-1 space-y-8">

            @if(session('success'))
            <div class="p-4 rounded-xl text-sm font-bold bg-green-50 text-green-600 border border-green-200 fade-up flex items-center gap-3">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            <div class="bg-white rounded-[2rem] shadow-soft border border-gray-50 p-8 fade-up">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                    <div>
                        <h2 class="text-3xl font-black text-secondary">My Doubts</h2>
                        <p class="text-gray-500 mt-1">Track the status of your submitted academic questions.</p>
                    </div>
                    <a href="{{ route('doubts.create') }}" class="bg-primary hover:bg-secondary text-white font-bold py-3 px-6 rounded-full shadow-md transition-all duration-300">
                        Submit New Doubt
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-100 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                <th class="px-4 py-4">Subject</th>
                                <th class="px-4 py-4">Topic & Title</th>
                                <th class="px-4 py-4">Status</th>
                                <th class="px-4 py-4">Submitted</th>
                                <th class="px-4 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($doubts as $doubt)
                            <tr class="group hover:bg-light/50 transition-colors">
                                <td class="px-4 py-4">
                                    <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-wider border border-primary/10">
                                        {{ $doubt->subject->name }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <h4 class="font-bold text-secondary text-sm">
                                        @if(is_array($doubt->title))
                                            {{ $doubt->title[0] ?? '' }}@if(count($doubt->title) > 1) <span class="text-gray-400 font-normal">+{{ count($doubt->title) - 1 }} more</span>@endif
                                        @else
                                            {{ $doubt->title }}
                                        @endif
                                    </h4>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $doubt->topic_name }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            'accepted' => 'bg-primary-50 text-primary-600 border-primary-100',
                                            'resolved' => 'bg-green-50 text-green-600 border-green-100',
                                            'cancelled' => 'bg-gray-50 text-gray-400 border-gray-100',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusColors[$doubt->status] ?? 'bg-gray-50 text-gray-500' }}">
                                        {{ $doubt->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-xs text-gray-500 font-medium">
                                    {{ $doubt->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <a href="{{ route('student.doubts.show', $doubt->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-light text-secondary hover:bg-primary hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-2xl">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <p class="text-gray-500 font-bold">No doubts submitted yet.</p>
                                    <p class="text-sm text-gray-400 mt-1">Have a question? Submit your first doubt now!</p>
                                    <a href="{{ route('doubts.create') }}" class="inline-block mt-6 text-primary font-black hover:underline">Submit Doubt <i class="fas fa-arrow-right text-xs ml-1"></i></a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8">
                    {{ $doubts->links() }}
                </div>
            </div>

        </main>
    </div>
</div>

@endsection
