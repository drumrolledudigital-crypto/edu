@extends('layouts.student-app')

@section('title', 'My Invoices | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<!-- Mobile Invoices -->
<div class="lg:hidden">
    <x-mobile.page-header title="My Invoices" subtitle="View and download your invoices" icon="fas fa-file-invoice" />

    @if(session('success'))
    <div class="px-3 py-2">
        <div class="p-3 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-2 fade-in-up" data-animate>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    </div>
    @endif

    <div class="px-3 pb-32 space-y-3">
        @forelse($invoices as $invoice)
        <a href="{{ route('student.invoices.show', $invoice->id) }}" class="block bg-white rounded-2xl p-3 shadow-card border border-gray-50 card-press fade-in-up" data-animate>
            <div class="flex items-center justify-between mb-2">
                <span class="font-bold text-secondary text-[13px] leading-tight">{{ $invoice->invoice_number }}</span>
                @php
                    $statusColor = match($invoice->status) {
                        'generated' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                        'cancelled' => 'bg-gray-100 text-gray-500 border-gray-200',
                        default => 'bg-gray-100 text-gray-500 border-gray-200',
                    };
                @endphp
                <span class="px-1.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider border {{ $statusColor }}">
                    {{ $invoice->status }}
                </span>
            </div>

            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary shrink-0">
                    <i class="fas fa-receipt text-[9px]"></i>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-secondary text-[12px] leading-tight truncate">{{ $invoice->appointment->subject->name ?? 'N/A' }}</p>
                    <p class="text-[10px] text-gray-400">{{ $invoice->appointment->appointment_date ? \Carbon\Carbon::parse($invoice->appointment->appointment_date)->format('M d, Y') : 'N/A' }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $invoice->invoice_date ? $invoice->invoice_date->format('M d, Y') : 'N/A' }}</span>
                <span class="font-bold text-secondary text-[13px]">${{ number_format($invoice->amount, 2) }}</span>
            </div>
        </a>
        @empty
        <div class="bg-white rounded-2xl p-6 shadow-card border border-gray-50 text-center fade-in-up" data-animate>
            <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300 text-xl">
                <i class="fas fa-file-invoice"></i>
            </div>
            <p class="text-gray-500 font-bold mb-0.5 text-sm">No invoices available</p>
            <p class="text-xs text-gray-400 mb-5">Book a session to get your first invoice!</p>
            <a href="{{ route('student.booking.create') }}" class="inline-block bg-primary text-white font-bold py-2.5 px-6 rounded-xl btn-haptic text-sm">Book a Session</a>
        </div>
        @endforelse

        @if($invoices->hasPages())
        <div class="py-4">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Desktop Invoices -->
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

            <div class="bg-white rounded-[2rem] shadow-soft border border-gray-50 p-8 fade-up">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                    <div>
                        <h2 class="text-3xl font-black text-secondary">My Invoices</h2>
                        <p class="text-gray-500 mt-1">View and download your payment invoices.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-100 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                <th class="px-4 py-4">Invoice #</th>
                                <th class="px-4 py-4">Subject</th>
                                <th class="px-4 py-4">Session Date</th>
                                <th class="px-4 py-4">Amount</th>
                                <th class="px-4 py-4">Status</th>
                                <th class="px-4 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($invoices as $invoice)
                            <tr class="group hover:bg-light/50 transition-colors text-sm">
                                <td class="px-4 py-4">
                                    <a href="{{ route('student.invoices.show', $invoice->id) }}" class="font-black text-primary hover:underline">{{ $invoice->invoice_number }}</a>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="font-bold text-secondary">{{ $invoice->appointment->subject->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-4 py-4 text-xs text-gray-500 font-medium">
                                    {{ $invoice->appointment->appointment_date ? \Carbon\Carbon::parse($invoice->appointment->appointment_date)->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="font-black text-secondary">${{ number_format($invoice->amount, 2) }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    @php
                                        $statusColor = match($invoice->status) {
                                            'generated' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            'cancelled' => 'bg-gray-50 text-gray-400 border-gray-100',
                                            default => 'bg-gray-50 text-gray-500 border-gray-100',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusColor }}">
                                        {{ $invoice->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('student.invoices.show', $invoice->id) }}" class="inline-flex items-center justify-center h-8 px-3 rounded-lg bg-light text-secondary hover:bg-primary hover:text-white transition-all text-xs font-bold gap-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('student.invoices.download', $invoice->id) }}" class="inline-flex items-center justify-center h-8 px-3 rounded-lg bg-light text-secondary hover:bg-primary hover:text-white transition-all text-xs font-bold gap-1" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <a href="{{ route('student.invoices.print', $invoice->id) }}" target="_blank" class="inline-flex items-center justify-center h-8 px-3 rounded-lg bg-light text-secondary hover:bg-primary hover:text-white transition-all text-xs font-bold gap-1" title="Print">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-20 text-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-2xl">
                                        <i class="fas fa-file-invoice"></i>
                                    </div>
                                    <p class="text-gray-500 font-bold">No invoices available.</p>
                                    <p class="text-sm text-gray-400 mt-1">Book a session to get your first invoice!</p>
                                    <a href="{{ route('student.booking.create') }}" class="inline-block mt-6 text-primary font-black hover:underline">Book a Session <i class="fas fa-arrow-right text-xs ml-1"></i></a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8">
                    {{ $invoices->links() }}
                </div>
            </div>

        </main>
    </div>
</div>

@endsection
