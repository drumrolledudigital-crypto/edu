@extends('layouts.student-app')

@section('title', 'Student Dashboard | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Mobile Dashboard -->
<div class="lg:hidden">
    <!-- Welcome Card -->
    <div class="px-3 pt-2 pb-3">
        <div class="gradient-navy rounded-2xl p-4 text-white relative overflow-hidden shadow-elevated fade-in-up" data-animate>
            <div class="absolute top-0 right-0 w-40 h-40 bg-primary/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative z-10">
                <p class="text-white/60 text-[10px] font-bold uppercase tracking-wider mb-0.5">Welcome back</p>
                <h2 class="text-xl font-black mb-0.5 leading-tight">{{ auth()->user()->name }} 👋</h2>
                <p class="text-white/70 text-xs">Ready to learn something new?</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="px-3 pb-4">
        <div class="grid grid-cols-4 gap-2">
            <x-mobile.quick-action icon="fas fa-question-circle" label="Submit Doubt" href="{{ route('doubts.create') }}" color="primary" />
            <x-mobile.quick-action icon="fas fa-calendar-plus" label="Book Session" href="{{ route('student.booking.create') }}" color="blue" />
            <x-mobile.quick-action icon="fas fa-video" label="Sessions" href="{{ route('student.sessions.upcoming') }}" color="green" />
            <x-mobile.quick-action icon="fas fa-file-invoice" label="Invoices" href="{{ route('student.booking.index') }}" color="purple" />
        </div>
    </div>

    <!-- Stats Row -->
    <div class="px-3 pb-4">
        <div class="grid grid-cols-3 gap-2 overflow-hidden">
            <x-mobile.stat-card icon="fas fa-calendar-check" label="Upcoming" value="{{ $counts['upcoming'] }}" sublabel="Sessions" color="blue" href="{{ route('student.sessions.upcoming') }}" />
            <x-mobile.stat-card icon="fas fa-check-double" label="Done" value="{{ $counts['completed'] }}" sublabel="Sessions" color="green" />
            <x-mobile.stat-card icon="fas fa-question-circle" label="Doubts" value="{{ $counts['doubts'] }}" sublabel="Total" color="purple" href="{{ route('student.doubts.index') }}" />
        </div>
    </div>

    <!-- Upcoming Session -->
    @if(isset($nextAppointment) && $nextAppointment)
    <div class="px-3 pb-3">
        <x-mobile.section-header title="Next Session" actionText="View All" actionHref="{{ route('student.sessions.upcoming') }}" />
        <x-mobile.session-card :appointment="$nextAppointment" />
    </div>
    @endif

    <!-- Recent Notifications -->
    <div class="px-3 pb-3">
        <x-mobile.section-header title="Notifications" />
        <div class="space-y-2">
            @forelse($recentNotifications as $notif)
                <x-mobile.notification-card :notification="$notif" />
            @empty
                <div class="bg-white rounded-2xl p-5 shadow-card border border-gray-50 text-center">
                    <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-2 text-gray-300">
                        <i class="fas fa-bell"></i>
                    </div>
                    <p class="text-gray-400 text-xs font-medium">No notifications yet</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Payments -->
    @if($recentPayments->count())
    <div class="px-3 pb-32">
        <x-mobile.section-header title="Recent Payments" actionText="View All" actionHref="{{ route('student.payments.history') }}" />
        <div class="space-y-2">
            @foreach($recentPayments as $pay)
                <x-mobile.payment-card :payment="$pay" />
            @endforeach
        </div>
    </div>
    @else
    <div class="px-3 pb-32"></div>
    @endif
</div>

<!-- Desktop Dashboard -->
<div class="hidden lg:block bg-light min-h-screen py-12 px-4 md:px-12">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

        <!-- Sidebar -->
        <aside class="w-full lg:w-64 shrink-0">@include('partials.student-sidebar')</aside>

        <!-- Main Content -->
        <main class="flex-1 space-y-8">

            @if(session('success'))
            <div class="p-4 rounded-xl text-sm font-bold bg-green-50 text-green-600 border border-green-200 fade-up">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
            @endif

            <!-- Welcome Banner -->
            <div class="bg-secondary rounded-[2.5rem] p-8 md:p-12 text-white relative overflow-hidden shadow-soft fade-up">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <h2 class="text-3xl font-black mb-2">Welcome, {{ auth()->user()->name }}! 🌟</h2>
                        <p class="text-white/80">Ready to learn something new today? Check your upcoming sessions or book a new one.</p>
                    </div>
                    <div class="shrink-0 flex gap-3">
                        <a href="{{ route('student.booking.create') }}" class="bg-primary hover:bg-white hover:text-primary text-white font-bold py-3 px-6 rounded-full shadow-lg transition-all duration-300">
                            Book Session
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <a href="{{ route('student.sessions.upcoming') }}" class="bg-white p-6 rounded-2xl shadow-soft border border-gray-50 flex items-center gap-4 fade-up hover:shadow-hover transition-all" style="transition-delay: 0.1s;">
                    <div class="w-14 h-14 bg-primary-50 text-primary-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Upcoming</p>
                        <h4 class="text-2xl font-black text-secondary">{{ $counts['upcoming'] }} <span class="text-sm font-medium text-gray-400">Sessions</span></h4>
                    </div>
                </a>

                <div class="bg-white p-6 rounded-2xl shadow-soft border border-gray-50 flex items-center gap-4 fade-up" style="transition-delay: 0.2s;">
                    <div class="w-14 h-14 bg-green-50 text-green-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Completed</p>
                        <h4 class="text-2xl font-black text-secondary">{{ $counts['completed'] }} <span class="text-sm font-medium text-gray-400">Sessions</span></h4>
                    </div>
                </div>

                <a href="{{ route('student.doubts.index') }}" class="bg-white p-6 rounded-2xl shadow-soft border border-gray-50 flex items-center gap-4 fade-up hover:shadow-hover transition-all" style="transition-delay: 0.3s;">
                    <div class="w-14 h-14 bg-purple-50 text-purple-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Doubts</p>
                        <h4 class="text-2xl font-black text-secondary">{{ $counts['doubts'] }} <span class="text-sm font-medium text-gray-400">Submitted</span></h4>
                    </div>
                </a>

            </div>

            <!-- Recent Activity & Payments -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                <!-- Recent Notifications -->
                <div class="bg-white p-8 rounded-[2rem] shadow-soft border border-gray-50 fade-up" style="transition-delay: 0.4s;">
                    <h3 class="text-xl font-extrabold text-secondary mb-6">Recent Notifications</h3>
                    <div class="space-y-4">
                        @forelse($recentNotifications as $notif)
                        <div class="flex items-start gap-4 p-4 bg-light rounded-2xl border border-gray-50 group hover:border-primary/20 transition-colors">
                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary shadow-sm shrink-0">
                                @if($notif->type === 'welcome_student')
                                    <i class="fas fa-hand-sparkles text-xs"></i>
                                @elseif($notif->type === 'payment_success')
                                    <i class="fas fa-credit-card text-xs"></i>
                                @else
                                    <i class="fas fa-bell text-xs"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="font-black text-secondary text-sm leading-tight mb-1">{{ $notif->subject }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $notif->sent_at ? $notif->sent_at->diffForHumans() : $notif->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-3xl">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <p class="text-gray-500 font-medium">No recent notifications.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="bg-white p-8 rounded-[2rem] shadow-soft border border-gray-50 fade-up" style="transition-delay: 0.5s;">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-extrabold text-secondary">Recent Payments</h3>
                        <a href="{{ route('student.payments.history') }}" class="text-xs font-bold text-primary hover:underline">View All</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentPayments as $pay)
                        <div class="flex items-center justify-between p-4 bg-light rounded-2xl border border-gray-50 group hover:border-primary/20 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="fas fa-receipt text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-black text-secondary text-sm">{{ $pay->appointment->subject->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $pay->payment_date ? $pay->payment_date->format('M d, Y') : 'Pending' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-secondary text-sm">${{ number_format($pay->amount, 2) }}</p>
                                <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full border {{ $pay->payment_status === 'successful' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                                    {{ $pay->payment_status === 'successful' ? 'Paid' : $pay->payment_status }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10 text-gray-400 italic text-sm">No payment history.</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

@endsection
