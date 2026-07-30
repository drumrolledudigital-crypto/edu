@extends('layouts.admin')

@section('title', 'Reports & Analytics')
@section('page_title', 'Analytics & Reports')

@section('content')
<div class="space-y-6">

    <!-- Header & Filters -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-card border border-border p-4 rounded-xl shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-foreground tracking-tight">System Analytics</h2>
            <p class="text-xs text-muted-foreground mt-0.5">Filter data by specific date ranges.</p>
        </div>
        
        <form id="filter-form" method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-center gap-3">
            <select name="range" id="date-range" class="bg-background border border-border text-sm font-medium rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-primary w-40 text-foreground" onchange="toggleCustomDate()">
                <option value="today" {{ $range === 'today' ? 'selected' : '' }}>Today</option>
                <option value="this_week" {{ $range === 'this_week' ? 'selected' : '' }}>This Week</option>
                <option value="this_month" {{ $range === 'this_month' ? 'selected' : '' }}>This Month</option>
                <option value="30_days" {{ $range === '30_days' ? 'selected' : '' }}>Last 30 Days</option>
                <option value="custom" {{ $range === 'custom' ? 'selected' : '' }}>Custom Range</option>
            </select>
            
            <div id="custom-date-inputs" class="flex items-center gap-2 {{ $range === 'custom' ? '' : 'hidden' }}">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="bg-background border border-border text-sm font-medium rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-primary text-foreground">
                <span class="text-muted-foreground">-</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="bg-background border border-border text-sm font-medium rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-primary text-foreground">
            </div>

            <button type="submit" class="bg-primary text-primary-foreground hover:opacity-90 px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-all">Apply</button>
            <div class="relative group">
                <button type="button" class="bg-card text-foreground border border-input hover:bg-accent px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i> Export Data
                </button>
                <div class="absolute right-0 top-full mt-2 w-48 bg-card border border-border rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 overflow-hidden">
                    <a href="{{ route('admin.reports.export', ['type' => 'bookings'] + request()->all()) }}" class="block px-4 py-2 text-sm text-foreground hover:bg-accent transition-colors font-medium">Bookings Report (CSV)</a>
                    <a href="{{ route('admin.reports.export', ['type' => 'payments'] + request()->all()) }}" class="block px-4 py-2 text-sm text-foreground hover:bg-accent transition-colors font-medium border-t border-border">Payments Report (CSV)</a>
                    <a href="{{ route('admin.reports.export', ['type' => 'refunds'] + request()->all()) }}" class="block px-4 py-2 text-sm text-foreground hover:bg-accent transition-colors font-medium border-t border-border">Refunds Report (CSV)</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Overview Stats -->
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm p-5">
            <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest mb-1">Revenue</p>
            <h3 class="text-2xl font-black text-foreground">${{ number_format($totalRevenue, 2) }}</h3>
        </div>
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm p-5">
            <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest mb-1">Bookings</p>
            <h3 class="text-2xl font-black text-foreground">{{ $totalBookings }}</h3>
        </div>
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm p-5">
            <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest mb-1">New Students</p>
            <h3 class="text-2xl font-black text-foreground">{{ $totalStudents }}</h3>
        </div>
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm p-5">
            <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest mb-1">Refunds Processed</p>
            <h3 class="text-2xl font-black text-rose-500">${{ number_format($totalRefunds, 2) }}</h3>
        </div>
    </div>

    <!-- Charts Area -->
    <div class="grid gap-6 md:grid-cols-2">
        <!-- Revenue Trend -->
        <div class="rounded-xl border border-border bg-card shadow-sm p-5">
            <h3 class="font-bold text-foreground mb-4">Revenue Trend</h3>
            <div class="relative h-64 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Booking Trend -->
        <div class="rounded-xl border border-border bg-card shadow-sm p-5">
            <h3 class="font-bold text-foreground mb-4">Booking Trend</h3>
            <div class="relative h-64 w-full">
                <canvas id="bookingChart"></canvas>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="rounded-xl border border-border bg-card shadow-sm p-5">
            <h3 class="font-bold text-foreground mb-4">Payment Status</h3>
            <div class="relative h-64 w-full flex justify-center">
                <canvas id="paymentStatusChart"></canvas>
            </div>
        </div>

        <!-- Subject Popularity -->
        <div class="rounded-xl border border-border bg-card shadow-sm p-5">
            <h3 class="font-bold text-foreground mb-4">Subject Popularity</h3>
            <div class="relative h-64 w-full flex justify-center">
                <canvas id="subjectChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function toggleCustomDate() {
        const range = document.getElementById('date-range').value;
        const customInputs = document.getElementById('custom-date-inputs');
        if (range === 'custom') {
            customInputs.classList.remove('hidden');
        } else {
            customInputs.classList.add('hidden');
        }
    }

    function exportData() {
        window.toast.success("Export functionality is ready to be hooked into a CSV generator.");
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Shared Chart Options
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#6b7280';
        
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, border: { display: false } },
                x: { grid: { display: false }, border: { display: false } }
            }
        };

        // Revenue Chart
        const revenueData = @json($revenueTrend);
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: revenueData.map(item => item.date),
                datasets: [{
                    label: 'Revenue',
                    data: revenueData.map(item => item.total),
                    borderColor: '#2596be',
                    backgroundColor: 'rgba(37, 150, 190, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: commonOptions
        });

        // Booking Chart
        const bookingData = @json($bookingTrend);
        new Chart(document.getElementById('bookingChart'), {
            type: 'bar',
            data: {
                labels: bookingData.map(item => item.date),
                datasets: [{
                    label: 'Bookings',
                    data: bookingData.map(item => item.total),
                    backgroundColor: '#8b5cf6',
                    borderRadius: 4
                }]
            },
            options: commonOptions
        });

        // Payment Status Chart (Doughnut)
        const paymentStatusData = @json($paymentStatus);
        new Chart(document.getElementById('paymentStatusChart'), {
            type: 'doughnut',
            data: {
                labels: paymentStatusData.map(item => item.payment_status.toUpperCase()),
                datasets: [{
                    data: paymentStatusData.map(item => item.count),
                    backgroundColor: ['#10b981', '#f59e0b', '#f43f5e', '#64748b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });

        // Subject Popularity Chart (Doughnut)
        const subjectData = @json($subjectPopularity);
        new Chart(document.getElementById('subjectChart'), {
            type: 'doughnut',
            data: {
                labels: subjectData.map(item => item.name),
                datasets: [{
                    data: subjectData.map(item => item.count),
                    backgroundColor: ['#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#06b6d4', '#64748b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });
    });
</script>
@endpush
@endsection
