@extends('layouts.admin')

@section('title', 'Student Profile')
@section('page_title', 'Student Details')

@section('content')
<div class="space-y-6">
    <!-- Profile Header -->
    <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm p-8 flex flex-col md:flex-row gap-8 items-start md:items-center">
        <div class="w-24 h-24 rounded-full bg-primary/10 text-primary flex items-center justify-center text-3xl font-bold shrink-0 border border-primary/20">
            {{ strtoupper(substr($student->name, 0, 2)) }}
        </div>
        <div class="flex-1 w-full">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-foreground">{{ $student->name }}</h2>
                    <p class="text-muted-foreground">Year {{ $student->student_class ?? 'N/A' }} Student • Registered on {{ $student->created_at->format('d M, Y') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="window.history.back()" class="px-4 py-2 bg-background border border-border rounded-lg text-sm font-medium text-foreground hover:bg-accent transition-colors shadow-sm">Back to List</button>
                    <button class="px-4 py-2 bg-primary text-primary-foreground rounded-lg text-sm font-bold hover:opacity-90 transition-colors shadow-sm">Send Email</button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm p-6">
                <h3 class="text-sm font-bold text-foreground uppercase tracking-wider mb-4">Contact Information</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-muted-foreground uppercase">Parent Name</p>
                        <p class="text-sm font-medium text-foreground">{{ $student->parent_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-muted-foreground uppercase">Email Address</p>
                        <p class="text-sm font-medium text-foreground">{{ $student->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-muted-foreground uppercase">Mobile Number</p>
                        <p class="text-sm font-medium text-foreground">{{ $student->mobile_number ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm p-6">
                <h3 class="text-sm font-bold text-foreground uppercase tracking-wider mb-4">Quick Stats</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-muted/50 rounded-xl border border-border text-center">
                        <p class="text-[10px] font-bold text-muted-foreground uppercase">Total Sessions</p>
                        <p class="text-lg font-bold text-foreground">{{ $totalSessions }}</p>
                    </div>
                    <div class="p-3 bg-muted/50 rounded-xl border border-border text-center">
                        <p class="text-[10px] font-bold text-muted-foreground uppercase">Total Paid</p>
                        <p class="text-lg font-bold text-emerald-500">${{ number_format($totalPaid, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-border">
                    <div class="flex items-center gap-6">
                        <button onclick="showStudentTab('appointments')" id="tab-appointments" class="text-sm font-bold text-primary border-b-2 border-primary pb-4 -mb-4.5">Appointments</button>
                        <button onclick="showStudentTab('invoices')" id="tab-invoices" class="text-sm font-medium text-muted-foreground hover:text-foreground pb-4 transition-colors">Invoices</button>
                    </div>
                </div>

                <!-- Appointments Tab -->
                <div id="tab-content-appointments" class="p-6">
                    @if($appointments->isEmpty())
                    <div class="text-center py-12 text-muted-foreground">
                        <i data-lucide="calendar" class="w-12 h-12 mx-auto mb-4 opacity-10"></i>
                        <p class="text-sm">No appointments recorded for this student yet.</p>
                    </div>
                    @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-border text-[10px] font-bold text-muted-foreground uppercase tracking-wider">
                                    <th class="px-3 py-3">Date & Time</th>
                                    <th class="px-3 py-3">Subject</th>
                                    <th class="px-3 py-3">Status</th>
                                    <th class="px-3 py-3">Payment</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @foreach($appointments as $appointment)
                                <tr class="hover:bg-muted/50 transition-colors">
                                    <td class="px-3 py-3">
                                        <p class="font-medium text-foreground">{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('d M, Y') : 'N/A' }}</p>
                                        <p class="text-xs text-muted-foreground">{{ $appointment->start_time ? date('h:i A', strtotime($appointment->start_time)) : '' }}</p>
                                    </td>
                                    <td class="px-3 py-3 font-medium text-foreground">{{ $appointment->subject->name ?? 'N/A' }}</td>
                                    <td class="px-3 py-3">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                                                'scheduled' => 'bg-primary-50 text-primary-600 border-primary-200',
                                                'confirmed' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                                'completed' => 'bg-gray-100 text-gray-600 border-gray-200',
                                                'cancelled' => 'bg-rose-50 text-rose-600 border-rose-200',
                                                'rescheduled' => 'bg-purple-50 text-purple-600 border-purple-200',
                                            ];
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-500 border-gray-200' }}">
                                            {{ $appointment->status }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3">
                                        @if($appointment->payment)
                                            <span class="text-xs font-medium text-foreground">${{ number_format($appointment->payment->amount, 2) }}</span>
                                        @else
                                            <span class="text-xs text-muted-foreground">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                <!-- Invoices Tab -->
                <div id="tab-content-invoices" class="p-6 hidden">
                    @if($invoices->isEmpty())
                    <div class="text-center py-12 text-muted-foreground">
                        <i data-lucide="file-text" class="w-12 h-12 mx-auto mb-4 opacity-10"></i>
                        <p class="text-sm">No invoices recorded for this student yet.</p>
                    </div>
                    @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-border text-[10px] font-bold text-muted-foreground uppercase tracking-wider">
                                    <th class="px-3 py-3">Invoice #</th>
                                    <th class="px-3 py-3">Subject</th>
                                    <th class="px-3 py-3">Amount</th>
                                    <th class="px-3 py-3">Date</th>
                                    <th class="px-3 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @foreach($invoices as $invoice)
                                <tr class="hover:bg-muted/50 transition-colors">
                                    <td class="px-3 py-3 font-bold text-primary">{{ $invoice->invoice_number }}</td>
                                    <td class="px-3 py-3 font-medium text-foreground">{{ $invoice->appointment->subject->name ?? 'N/A' }}</td>
                                    <td class="px-3 py-3 font-bold text-foreground">${{ number_format($invoice->amount, 2) }}</td>
                                    <td class="px-3 py-3 text-xs text-muted-foreground">{{ $invoice->invoice_date->format('d M, Y') }}</td>
                                    <td class="px-3 py-3">
                                        @if($invoice->pdf_path)
                                        <a href="{{ route('admin.invoices.download', $invoice->id) }}" class="text-xs font-bold text-primary hover:underline">Download</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showStudentTab(tab) {
    document.querySelectorAll('[id^="tab-content-"]').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('[id^="tab-"]').forEach(el => {
        el.classList.remove('text-sm', 'font-bold', 'text-primary', 'border-b-2', 'border-primary', 'pb-4', '-mb-4.5');
        el.classList.add('text-sm', 'font-medium', 'text-muted-foreground', 'hover:text-foreground', 'pb-4', 'transition-colors');
    });
    document.getElementById('tab-content-' + tab).classList.remove('hidden');
    const activeTab = document.getElementById('tab-' + tab);
    activeTab.classList.remove('text-sm', 'font-medium', 'text-muted-foreground', 'hover:text-foreground', 'pb-4', 'transition-colors');
    activeTab.classList.add('text-sm', 'font-bold', 'text-primary', 'border-b-2', 'border-primary', 'pb-4', '-mb-4.5');
}
</script>
@endpush

