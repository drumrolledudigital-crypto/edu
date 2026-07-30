@extends('layouts.admin')

@section('title', 'Email Details')
@section('page_title', 'Email Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4 mb-2">
        <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-border bg-card text-muted-foreground hover:text-foreground transition-colors shadow-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Email Log #{{ $log->id }}</h2>
            <p class="text-sm text-muted-foreground">Generated on {{ $log->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <section class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-border flex items-center justify-between">
                    <h3 class="text-lg font-bold text-foreground">Delivery Information</h3>
                    @php
                        $statusColors = [
                            'sent' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                            'failed' => 'bg-rose-500/10 text-rose-600 border-rose-500/20',
                            'pending' => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                        ];
                    @endphp
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusColors[$log->status] ?? 'bg-muted text-muted-foreground' }}">
                        {{ $log->status }}
                    </span>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-2">Recipient Email</label>
                            <p class="text-sm font-bold text-foreground">{{ $log->recipient }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-2">Email Type</label>
                            <span class="px-2 py-0.5 rounded bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-wider border border-primary/20 inline-block">
                                {{ str_replace('_', ' ', $log->type) }}
                            </span>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-2">Sent At</label>
                            <p class="text-sm font-bold text-foreground">{{ $log->sent_at ? $log->sent_at->format('M d, Y - h:i A') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-2">Retry Count</label>
                            <p class="text-sm font-bold text-foreground">{{ $log->retry_count }}</p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-border/50">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-2">Subject Line</label>
                        <p class="text-md font-bold text-secondary">{{ $log->subject }}</p>
                    </div>

                    @if($log->error_message)
                    <div class="pt-6 border-t border-border/50">
                        <label class="text-[10px] font-bold text-rose-500 uppercase tracking-widest block mb-2">Error Message</label>
                        <div class="p-4 bg-rose-500/5 border border-rose-500/10 rounded-lg">
                            <code class="text-xs text-rose-600 break-all">{{ $log->error_message }}</code>
                        </div>
                    </div>
                    @endif
                </div>
            </section>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            @if($log->appointment_id)
            <section class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Linked Appointment</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary text-sm font-bold border border-primary/20">
                            {{ substr($log->appointment->student->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-foreground truncate text-sm">{{ $log->appointment->student->name }}</p>
                            <p class="text-[10px] text-muted-foreground font-bold uppercase">{{ $log->appointment->subject->name }}</p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-border/50">
                        <a href="{{ route('admin.appointments.show', $log->appointment_id) }}" class="w-full py-2 bg-card border border-border text-foreground rounded-lg text-xs font-bold hover:bg-accent transition-all flex items-center justify-center gap-2 shadow-sm">
                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i> View Appointment
                        </a>
                    </div>
                </div>
            </section>
            @endif

            @if($log->status === 'failed')
            <section class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Actions</h3>
                </div>
                <div class="p-6">
                    <button onclick="resendEmail()" class="w-full py-2.5 bg-primary text-primary-foreground rounded-lg text-sm font-black hover:opacity-90 shadow-sm transition-all flex items-center justify-center gap-2">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i> Attempt Resend
                    </button>
                </div>
            </section>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    async function resendEmail() {
        try {
            const response = await fetch('{{ route("admin.notifications.resend", $log->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            if (response.ok) {
                window.toast.success(result.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                window.toast.error(result.message || 'Failed to resend email');
            }
        } catch (error) {
            window.toast.error('Something went wrong. Please try again.');
        }
    }
</script>
@endpush
@endsection
