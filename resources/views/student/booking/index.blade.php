@extends('layouts.student-app')

@section('title', 'My Bookings | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Mobile Bookings -->
<div class="lg:hidden">
    <x-mobile.page-header title="My Bookings" subtitle="Track your scheduled sessions" icon="fas fa-calendar-check" actionText="New" actionHref="{{ route('student.booking.create') }}" />

    @if(session('success'))
    <div class="px-4 py-2">
        <div class="p-3 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-2 fade-in-up" data-animate>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    </div>
    @endif

    <div class="px-4 pb-32 space-y-3">
        @forelse($bookings as $apt)
            <x-mobile.session-card :appointment="$apt" />
        @empty
            <div class="bg-white rounded-2xl p-8 shadow-card border border-gray-50 text-center fade-in-up" data-animate>
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-2xl">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <p class="text-gray-500 font-bold mb-1">No bookings yet</p>
                <p class="text-sm text-gray-400 mb-6">Start by selecting a doubt and choosing a time slot.</p>
                <a href="{{ route('student.booking.create') }}" class="inline-block bg-primary text-white font-bold py-3 px-8 rounded-xl btn-haptic">Book Now</a>
            </div>
        @endforelse

        @if($bookings->hasPages())
        <div class="py-4">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Desktop Bookings -->
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
                        <h2 class="text-3xl font-black text-secondary">My Bookings</h2>
                        <p class="text-gray-500 mt-1">Manage and track your scheduled 1-on-1 sessions.</p>
                    </div>
                    <a href="{{ route('student.booking.create') }}" class="bg-primary hover:bg-secondary text-white font-bold py-3 px-6 rounded-full shadow-md transition-all duration-300">
                        New Booking
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                        <tr class="border-b border-gray-100 text-[10px] font-black uppercase tracking-widest text-gray-400">
                            <th class="px-4 py-4">Subject & Doubt</th>
                            <th class="px-4 py-4">Session Time</th>
                            <th class="px-4 py-4 text-center">Meet Link</th>
                            <th class="px-4 py-4 text-center">Calendar</th>
                            <th class="px-4 py-4 text-center">Status</th>
                            <th class="px-4 py-4 text-center">Payment</th>
                            <th class="px-4 py-4 text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                        @forelse($bookings as $apt)
                        <tr class="group hover:bg-light/50 transition-colors text-sm">
                            <td class="px-4 py-4">
                                <p class="font-black text-secondary">{{ $apt->subject?->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5 line-clamp-1 truncate max-w-[200px]" title="{{ is_array($apt->doubt?->title) ? implode(', ', $apt->doubt->title) : ($apt->doubt?->title ?? '') }}">
                                    @if($apt->doubt)
                                        @if(is_array($apt->doubt->title))
                                            {{ $apt->doubt->title[0] ?? 'N/A' }}@if(count($apt->doubt->title) > 1) +{{ count($apt->doubt->title) - 1 }}@endif
                                        @else
                                            {{ $apt->doubt->title }}
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar-day text-primary/50 text-[10px]"></i>
                                    <span class="font-bold text-secondary">{{ $apt->appointment_date->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <i class="fas fa-clock text-primary/50 text-[10px]"></i>
                                    <span class="text-xs text-gray-500 font-medium">{{ date('h:i A', strtotime($apt->start_time)) }} - {{ date('h:i A', strtotime($apt->end_time)) }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($apt->status === 'confirmed' && ($apt->google_meet_link || $apt->meet_link))
                                    <a href="{{ $apt->google_meet_link ?? $apt->meet_link }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-50 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all border border-emerald-100 shadow-sm" title="Join Meeting">
                                        <i class="fas fa-video text-[10px]"></i>
                                    </a>
                                @else
                                    <span class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center mx-auto text-gray-300 border border-gray-100" title="Link Pending">
                                        <i class="fas fa-video-slash text-[10px]"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($apt->google_calendar_event_id)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border bg-purple-50 text-purple-600 border-purple-100" title="Calendar Event Created">
                                        Synced
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border bg-gray-50 text-gray-400 border-gray-100">
                                        {{ $apt->calendar_status ?? 'Pending' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'scheduled' => 'bg-primary-50 text-primary-600 border-primary-100',
                                        'confirmed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'completed' => 'bg-gray-50 text-gray-400 border-gray-100',
                                        'cancelled' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        'rescheduled' => 'bg-purple-50 text-purple-600 border-purple-100',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusColors[$apt->status] ?? 'bg-gray-50 text-gray-500' }}">
                                    {{ $apt->status }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($apt->payment && $apt->payment->payment_status === 'successful')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border bg-emerald-50 text-emerald-600 border-emerald-100">
                                        Paid
                                    </span>
                                @elseif($apt->payment && $apt->payment->payment_status === 'refunded')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border bg-gray-50 text-gray-400 border-gray-100">
                                        Refunded
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border bg-rose-50 text-rose-600 border-rose-100">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if(!$apt->payment || $apt->payment->payment_status !== 'successful')
                                        <a href="{{ route('student.payment.pay', $apt->id) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-primary text-white text-xs font-bold hover:bg-secondary transition-all shadow-sm gap-2">
                                            <i class="fas fa-credit-card"></i> Pay Now
                                        </a>
                                    @else
                                        @if($apt->payment && $apt->payment->invoice)
                                            <a href="{{ route('student.invoices.download', $apt->payment->invoice->id) }}" target="_blank" class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-gray-50 text-gray-600 text-xs font-bold hover:bg-gray-100 hover:text-primary transition-all border border-gray-100 shadow-sm gap-1.5" title="Download Invoice">
                                                <i class="fas fa-file-pdf text-rose-500"></i> Invoice
                                            </a>
                                        @else
                                            <span class="text-xs font-bold text-gray-300 italic">Confirmed</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-20 text-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-2xl">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <p class="text-gray-500 font-bold">No sessions booked yet.</p>
                                    <p class="text-sm text-gray-400 mt-1">Start by selecting a doubt and choosing a time slot.</p>
                                    <a href="{{ route('student.booking.create') }}" class="inline-block mt-6 text-primary font-black hover:underline uppercase text-xs tracking-widest">Book Now <i class="fas fa-arrow-right text-[10px] ml-1"></i></a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            </div>

        </main>
    </div>
</div>

@endsection
