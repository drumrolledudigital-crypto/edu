@extends('layouts.student-app')

@section('title', 'Refund History | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

@php
    $statusColors = [
        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
        'approved' => 'bg-primary-50 text-primary-600 border-primary-100',
        'rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
        'refunded' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
    ];
@endphp

<!-- Mobile Refund History -->
<div class="lg:hidden">
    <x-mobile.page-header title="Refund History" subtitle="Track your refund requests" icon="fas fa-undo" />

    @if(session('success'))
    <div class="px-4 py-2">
        <div class="p-3 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-2 fade-in-up" data-animate>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="px-4 py-2">
        <div class="p-3 rounded-xl text-sm font-bold bg-rose-50 text-rose-600 border border-rose-100 flex items-center gap-2 fade-in-up" data-animate>
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    </div>
    @endif

    <div class="px-4 pb-32 space-y-3">
        @forelse($refunds as $refund)
        <div class="bg-white rounded-2xl p-4 shadow-card border border-gray-50 card-press fade-in-up" data-animate>
            <div class="flex items-start justify-between mb-2">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0 border border-primary/10">
                        <i class="fas fa-undo text-xs"></i>
                    </div>
                    <div>
                        <p class="font-bold text-secondary text-sm">{{ $refund->payment->appointment->subject->name ?? 'Unknown' }}</p>
                        <p class="text-[10px] text-gray-400 font-mono">INV: {{ $refund->invoice->invoice_number ?? 'N/A' }}</p>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border {{ $statusColors[$refund->status] ?? 'bg-gray-100 text-gray-500 border-gray-200' }}">
                    {{ $refund->status }}
                </span>
            </div>
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                <span class="font-bold text-secondary text-sm">${{ number_format($refund->refund_amount, 2) }} <span class="text-[10px] text-gray-400 uppercase">{{ strtoupper(\App\Models\Setting::get('currency', 'USD')) }}</span></span>
                <span class="text-[10px] text-gray-400">{{ $refund->created_at->format('M d, Y') }}</span>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-8 shadow-card border border-gray-50 text-center fade-in-up" data-animate>
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-2xl">
                <i class="fas fa-undo"></i>
            </div>
            <p class="text-gray-500 font-bold">No refund requests</p>
            <p class="text-sm text-gray-400 mt-1">Refund history will appear here.</p>
        </div>
        @endforelse

        @if($refunds->hasPages())
        <div class="py-4">
            {{ $refunds->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Desktop Refund History -->
<div class="hidden lg:block bg-light min-h-screen py-12 px-4 md:px-12">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

        <!-- Sidebar -->
        <aside class="w-full lg:w-64 shrink-0">@include('partials.student-sidebar')</aside>

        <!-- Main Content -->
        <main class="flex-1 space-y-8">

            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-black text-secondary uppercase tracking-tight">Refund <span class="text-primary">History</span></h1>
                <p class="text-gray-500 mt-1">Track your refund requests and their status.</p>
            </div>

            @if(session('success'))
            <div class="p-4 rounded-xl text-sm font-bold bg-green-50 text-green-600 border border-green-200 fade-up flex items-center gap-3">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="p-4 rounded-xl text-sm font-bold bg-red-50 text-red-600 border border-red-200 fade-up flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
            @endif

            <div class="bg-white rounded-[2.5rem] shadow-soft border border-gray-50 p-8 md:p-12 fade-up">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-100 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                <th class="px-4 py-4">Booking / Transaction</th>
                                <th class="px-4 py-4 text-center">Amount</th>
                                <th class="px-4 py-4 text-center">Status</th>
                                <th class="px-4 py-4 text-right">Request Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            @forelse($refunds as $refund)
                            <tr class="group hover:bg-light/50 transition-colors">
                                <td class="px-4 py-4">
                                    <p class="font-black text-secondary">{{ $refund->payment->appointment->subject->name ?? 'Unknown Subject' }}</p>
                                    <p class="text-[10px] font-mono text-gray-400 mt-1">INV: {{ $refund->invoice->invoice_number ?? 'N/A' }}</p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="font-black text-secondary">${{ number_format($refund->refund_amount, 2) }}</span>
                                    <span class="text-[10px] text-gray-400 uppercase font-bold ml-1">{{ strtoupper(\App\Models\Setting::get('currency', 'USD')) }}</span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusColors[$refund->status] ?? 'bg-gray-50 text-gray-500' }}">
                                        {{ $refund->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right text-xs text-gray-400 font-medium">
                                    <span class="italic">{{ $refund->created_at->format('M d, Y') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center text-gray-400 italic">No refund requests found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8">
                    {{ $refunds->links() }}
                </div>
            </div>

        </main>
    </div>
</div>

@endsection
