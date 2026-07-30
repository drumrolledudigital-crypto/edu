@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Analytics Overview')

@section('content')
<div class="space-y-8">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Dashboard Analytics</h2>
            <p class="text-sm text-muted-foreground mt-1">Platform overview and recent activities.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 shadow-sm">
                <i data-lucide="bar-chart-2" class="w-4 h-4 mr-2 text-primary"></i>
                Full Reports
            </a>
        </div>
    </div>

    <!-- Top KPI Cards -->
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <a href="{{ route('admin.payments.index') }}" class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden relative group hover:border-primary/50 transition-colors">
            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="tracking-tight text-sm font-medium text-muted-foreground group-hover:text-foreground transition-colors">Total Revenue</h3>
                <div class="p-2 bg-emerald-500/10 rounded-lg text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-colors"><i data-lucide="dollar-sign" class="w-4 h-4"></i></div>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold text-foreground">${{ number_format($totalRevenue, 2) }}</div>
                <p class="text-xs text-muted-foreground mt-1 font-medium">Successful Payments: {{ $successfulPayments }}</p>
            </div>
        </a>

        <a href="{{ route('admin.students.index') }}" class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden relative group hover:border-primary/50 transition-colors">
            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="tracking-tight text-sm font-medium text-muted-foreground group-hover:text-foreground transition-colors">Total Students</h3>
                <div class="p-2 bg-primary-500/10 rounded-lg text-primary-500 group-hover:bg-primary-500 group-hover:text-white transition-colors"><i data-lucide="users" class="w-4 h-4"></i></div>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold text-foreground">{{ $totalStudents }}</div>
                <p class="text-xs text-muted-foreground mt-1 font-medium">Registered Learners</p>
            </div>
        </a>

        <a href="{{ route('admin.appointments.index') }}" class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden relative group hover:border-primary/50 transition-colors">
            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="tracking-tight text-sm font-medium text-muted-foreground group-hover:text-foreground transition-colors">Total Bookings</h3>
                <div class="p-2 bg-indigo-500/10 rounded-lg text-indigo-500 group-hover:bg-indigo-500 group-hover:text-white transition-colors"><i data-lucide="calendar" class="w-4 h-4"></i></div>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold text-foreground">{{ $totalBookings }}</div>
                <p class="text-xs text-muted-foreground mt-1 font-medium">Upcoming: {{ $upcomingSessions }} • Completed: {{ $completedSessions }}</p>
            </div>
        </a>

        <a href="{{ route('admin.doubts.index') }}" class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden relative group hover:border-primary/50 transition-colors">
            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="tracking-tight text-sm font-medium text-muted-foreground group-hover:text-foreground transition-colors">Total Doubts</h3>
                <div class="p-2 bg-amber-500/10 rounded-lg text-amber-500 group-hover:bg-amber-500 group-hover:text-white transition-colors"><i data-lucide="help-circle" class="w-4 h-4"></i></div>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold text-foreground">{{ $totalDoubts }}</div>
                <p class="text-xs text-muted-foreground mt-1 font-medium">Across {{ $totalSubjects }} Subjects</p>
            </div>
        </a>
    </div>

    <!-- Secondary KPI Cards -->
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">Pending Payments</p>
                <p class="text-xl font-bold text-foreground mt-1">{{ $pendingPayments }}</p>
            </div>
            <div class="h-10 w-10 bg-amber-500/10 text-amber-500 flex items-center justify-center rounded-full"><i data-lucide="clock" class="w-5 h-5"></i></div>
        </div>
        
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">Refunded Payments</p>
                <p class="text-xl font-bold text-foreground mt-1">{{ $refundedPayments }}</p>
            </div>
            <div class="h-10 w-10 bg-gray-500/10 text-gray-500 flex items-center justify-center rounded-full"><i data-lucide="corner-up-left" class="w-5 h-5"></i></div>
        </div>

        <a href="{{ route('admin.refunds.index') }}" class="rounded-xl border border-rose-200 bg-rose-50 text-rose-900 shadow-sm p-4 flex items-center justify-between hover:bg-rose-100 transition-colors">
            <div>
                <p class="text-xs font-bold text-rose-700/70 uppercase tracking-widest">Pending Refunds</p>
                <p class="text-xl font-bold text-rose-700 mt-1">{{ $pendingRefunds }} Requests</p>
            </div>
            <div class="h-10 w-10 bg-rose-200 text-rose-600 flex items-center justify-center rounded-full"><i data-lucide="alert-circle" class="w-5 h-5"></i></div>
        </a>

        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">Total Subjects</p>
                <p class="text-xl font-bold text-foreground mt-1">{{ $totalSubjects }}</p>
            </div>
            <div class="h-10 w-10 bg-primary/10 text-primary flex items-center justify-center rounded-full"><i data-lucide="book-open" class="w-5 h-5"></i></div>
        </div>

        <a href="{{ route('admin.book-purchases.index') }}" class="rounded-xl border border-border bg-card text-card-foreground shadow-sm p-4 flex items-center justify-between hover:border-primary/50 transition-colors">
            <div>
                <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">Book Purchases</p>
                <p class="text-xl font-bold text-foreground mt-1">{{ $totalBookPurchases }}</p>
                <p class="text-[10px] text-muted-foreground mt-0.5 font-medium">${{ number_format($totalBookRevenue, 2) }} Revenue</p>
            </div>
            <div class="h-10 w-10 bg-sky-500/10 text-sky-500 flex items-center justify-center rounded-full"><i data-lucide="shopping-bag" class="w-5 h-5"></i></div>
        </a>
    </div>

    <!-- Recent Activity Grids -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Recent Notifications -->
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm flex flex-col lg:col-span-1">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-foreground">Notifications</h3>
                    @if($unreadNotificationsCount > 0)
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-primary text-[10px] font-bold text-primary-foreground">{{ $unreadNotificationsCount }}</span>
                    @endif
                </div>
                <a href="{{ route('admin.notification-center.index') }}" class="text-xs font-bold text-primary hover:underline">View All</a>
            </div>
            <div class="p-0 flex-1 overflow-auto">
                <div class="divide-y divide-border">
                    @forelse($recentNotifications as $notif)
                    <div class="p-4 hover:bg-muted/30 transition-colors {{ $notif->status === 'unread' ? 'bg-primary/5' : '' }}">
                        <div class="flex gap-3">
                            <div class="mt-0.5 shrink-0 w-8 h-8 rounded-full bg-background border border-border flex items-center justify-center text-muted-foreground">
                                <i data-lucide="{{ $notif->icon ?? 'bell' }}" class="w-3.5 h-3.5 {{ $notif->status === 'unread' ? 'text-primary' : '' }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="text-sm font-bold text-foreground truncate {{ $notif->status === 'unread' ? 'text-primary' : '' }}">{{ $notif->title }}</p>
                                    <span class="text-[9px] text-muted-foreground whitespace-nowrap">{{ $notif->created_at->diffForHumans(null, true, true) }}</span>
                                </div>
                                <p class="text-xs text-muted-foreground line-clamp-2 mt-0.5">{{ $notif->message }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center">
                        <i data-lucide="bell-off" class="w-8 h-8 text-muted-foreground/50 mx-auto mb-2"></i>
                        <p class="text-xs text-muted-foreground">No notifications yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm flex flex-col lg:col-span-2">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <h3 class="font-bold text-foreground">Recent Bookings</h3>
                <a href="{{ route('admin.appointments.index') }}" class="text-xs font-bold text-primary hover:underline">View All</a>
            </div>
            <div class="p-0 flex-1 overflow-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] uppercase bg-muted/50 text-muted-foreground">
                        <tr>
                            <th class="px-6 py-3 font-bold">Student</th>
                            <th class="px-6 py-3 font-bold">Subject</th>
                            <th class="px-6 py-3 font-bold text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($recentBookings as $booking)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="px-6 py-3 font-medium text-foreground">{{ $booking->student->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-muted-foreground">{{ $booking->subject->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-right">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                    {{ $booking->status === 'confirmed' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500' }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-muted-foreground">No recent bookings.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm flex flex-col">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <h3 class="font-bold text-foreground">Recent Payments</h3>
                <a href="{{ route('admin.payments.index') }}" class="text-xs font-bold text-primary hover:underline">View All</a>
            </div>
            <div class="p-0 flex-1 overflow-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] uppercase bg-muted/50 text-muted-foreground">
                        <tr>
                            <th class="px-6 py-3 font-bold">Student</th>
                            <th class="px-6 py-3 font-bold">Amount</th>
                            <th class="px-6 py-3 font-bold text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($recentPayments as $payment)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="px-6 py-3 font-medium text-foreground">{{ $payment->student->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3 font-bold text-foreground">${{ number_format($payment->amount, 2) }}</td>
                            <td class="px-6 py-3 text-right">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                    {{ $payment->payment_status === 'successful' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500' }}">
                                    {{ $payment->payment_status === 'successful' ? 'Paid' : $payment->payment_status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-muted-foreground">No recent payments.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Doubts -->
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm flex flex-col">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <h3 class="font-bold text-foreground">Recent Doubts</h3>
                <a href="{{ route('admin.doubts.index') }}" class="text-xs font-bold text-primary hover:underline">View All</a>
            </div>
            <div class="p-0 flex-1 overflow-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] uppercase bg-muted/50 text-muted-foreground">
                        <tr>
                            <th class="px-6 py-3 font-bold">Student</th>
                            <th class="px-6 py-3 font-bold">Topic</th>
                            <th class="px-6 py-3 font-bold text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($recentDoubts as $doubt)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="px-6 py-3 font-medium text-foreground">{{ $doubt->student->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-muted-foreground truncate max-w-[150px]">{{ $doubt->topic_name }}</td>
                            <td class="px-6 py-3 text-right text-muted-foreground text-xs">{{ $doubt->created_at->format('M d') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-muted-foreground">No recent doubts.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Refunds -->
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm flex flex-col">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <h3 class="font-bold text-foreground">Recent Refund Requests</h3>
                <a href="{{ route('admin.refunds.index') }}" class="text-xs font-bold text-primary hover:underline">View All</a>
            </div>
            <div class="p-0 flex-1 overflow-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] uppercase bg-muted/50 text-muted-foreground">
                        <tr>
                            <th class="px-6 py-3 font-bold">Student</th>
                            <th class="px-6 py-3 font-bold">Amount</th>
                            <th class="px-6 py-3 font-bold text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($recentRefunds as $refund)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="px-6 py-3 font-medium text-foreground">{{ $refund->student->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3 font-bold text-rose-500">${{ number_format($refund->refund_amount, 2) }}</td>
                            <td class="px-6 py-3 text-right">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                    {{ $refund->status === 'pending' ? 'bg-amber-500/10 text-amber-500' : ($refund->status === 'refunded' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-muted text-muted-foreground') }}">
                                    {{ $refund->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-muted-foreground">No recent refund requests.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm flex flex-col md:col-span-2 lg:col-span-3">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <h3 class="font-bold text-foreground">Recent System Activities</h3>
                <a href="{{ route('admin.audit-logs.index') }}" class="text-xs font-bold text-primary hover:underline">View Audit Logs</a>
            </div>
            <div class="p-0 flex-1 overflow-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] uppercase bg-muted/50 text-muted-foreground">
                        <tr>
                            <th class="px-6 py-3 font-bold">User</th>
                            <th class="px-6 py-3 font-bold">Action</th>
                            <th class="px-6 py-3 font-bold">Description</th>
                            <th class="px-6 py-3 font-bold text-right">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($recentActivities as $activity)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="px-6 py-3">
                                <div class="flex flex-col">
                                    <span class="font-medium text-foreground">{{ $activity->user ? $activity->user->name : 'System' }}</span>
                                    <span class="text-[10px] text-muted-foreground uppercase font-bold tracking-widest">{{ $activity->role }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-muted text-muted-foreground border border-border">
                                    {{ $activity->module }}: {{ $activity->action }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-muted-foreground text-xs">{{ $activity->description }}</td>
                            <td class="px-6 py-3 text-right text-muted-foreground text-xs">{{ $activity->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-muted-foreground">No recent activities.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Students -->
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm flex flex-col md:col-span-2">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <h3 class="font-bold text-foreground">Recent Student Registrations</h3>
                <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-primary hover:underline">View All</a>
            </div>
            <div class="p-0 flex-1 overflow-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] uppercase bg-muted/50 text-muted-foreground">
                        <tr>
                            <th class="px-6 py-3 font-bold">Name</th>
                            <th class="px-6 py-3 font-bold">Email</th>
                            <th class="px-6 py-3 font-bold">Year</th>
                            <th class="px-6 py-3 font-bold text-right">Registered</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($recentStudents as $student)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="px-6 py-3 font-medium text-foreground">{{ $student->name }}</td>
                            <td class="px-6 py-3 text-muted-foreground">{{ $student->email }}</td>
                            <td class="px-6 py-3 text-muted-foreground">Year {{ $student->student_class }}</td>
                            <td class="px-6 py-3 text-right text-muted-foreground text-xs">{{ $student->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-muted-foreground">No recent registrations.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
