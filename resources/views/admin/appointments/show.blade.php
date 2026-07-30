@extends('layouts.admin')

@section('title', 'Appointment Details')
@section('page_title', 'Appointment Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4 mb-2">
        <a href="{{ route('admin.appointments.index') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-border bg-card text-muted-foreground hover:text-foreground transition-colors shadow-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Review Session #{{ $appointment->id }}</h2>
            <p class="text-sm text-muted-foreground">Booked by {{ $appointment->student->name }} on {{ $appointment->created_at->format('M d, Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <section class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-border flex items-center justify-between">
                    <h3 class="text-lg font-bold text-foreground">Session Information</h3>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $appointment->status === 'confirmed' ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' : 'bg-muted text-muted-foreground' }}">
                        {{ $appointment->status }}
                    </span>
                </div>
                <div class="p-6 space-y-8">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-2">Subject</label>
                            <span class="px-3 py-1 rounded bg-primary/10 text-primary text-xs font-bold border border-primary/20 inline-block">{{ $appointment->subject->name }}</span>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-2">Duration</label>
                            <span class="text-sm font-bold text-foreground">{{ $appointment->duration }} Minutes</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8 pt-6 border-t border-border/50">
                        <div>
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-2">Scheduled Date</label>
                            <div class="flex items-center gap-2 text-foreground font-black">
                                <i data-lucide="calendar" class="w-4 h-4 text-primary"></i>
                                <span>{{ $appointment->appointment_date->format('F d, Y') }}</span>
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-2">Time Range</label>
                            <div class="flex items-center gap-2 text-foreground font-black">
                                <i data-lucide="clock" class="w-4 h-4 text-primary"></i>
                                <span>{{ date('h:i A', strtotime($appointment->start_time)) }} - {{ date('h:i A', strtotime($appointment->end_time)) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-border/50">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-4">Linked Doubt</label>
                        <div class="bg-muted/30 rounded-xl p-6 border border-border/50">
                            <h4 class="text-md font-bold text-secondary mb-2">{{ $appointment->doubt->title }}</h4>
                            <p class="text-xs font-bold text-primary uppercase mb-4">{{ $appointment->doubt->topic_name }}</p>
                            <p class="text-sm text-muted-foreground leading-relaxed italic">"{{ $appointment->doubt->description }}"</p>
                            @if($appointment->doubt->attachment)
                                <div class="mt-6">
                                    <a href="{{ asset('storage/' . $appointment->doubt->attachment) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-card border border-border text-xs font-bold text-foreground hover:bg-accent transition-colors shadow-sm">
                                        <i data-lucide="paperclip" class="w-3.5 h-3.5"></i> View Attachment
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Sidebar / Actions -->
        <div class="space-y-6">
            <section class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Update Status</h3>
                </div>
                <div class="p-6 space-y-4">
                    <form action="{{ route('admin.appointments.update-status', $appointment->id) }}" method="POST" id="apt-status-form" class="space-y-4">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-muted-foreground uppercase tracking-widest block">Status</label>
                            <select name="status" class="w-full h-10 px-4 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                                <option value="pending" {{ $appointment->status === 'pending' ? 'selected' : '' }}>Pending Confirmation</option>
                                <option value="scheduled" {{ $appointment->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $appointment->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-primary text-primary-foreground rounded-lg text-sm font-black hover:opacity-90 shadow-sm transition-all flex items-center justify-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Save Status
                        </button>
                    </form>
                </div>
            </section>

            <section class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.appointments.print', $appointment->id) }}" target="_blank" class="w-full py-2.5 bg-card border border-border text-foreground rounded-lg text-xs font-bold hover:bg-accent transition-all flex items-center justify-center gap-2 shadow-sm">
                        <i data-lucide="printer" class="w-3.5 h-3.5"></i> Print Booking
                    </a>
                    <button onclick="sendEmailAgain()" class="w-full py-2.5 bg-card border border-border text-foreground rounded-lg text-xs font-bold hover:bg-accent transition-all flex items-center justify-center gap-2 shadow-sm">
                        <i data-lucide="mail" class="w-3.5 h-3.5"></i> Send Email Again
                    </button>
                    <button onclick="generateInvoice()" class="w-full py-2.5 bg-card border border-border text-foreground rounded-lg text-xs font-bold hover:bg-accent transition-all flex items-center justify-center gap-2 shadow-sm">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Generate Invoice
                    </button>
                </div>
            </section>

            <section class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-border flex items-center justify-between">
                    <h3 class="text-lg font-bold text-foreground">Google Meet</h3>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border {{ $appointment->google_meet_link ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 border-amber-500/20' }}">
                        {{ $appointment->meeting_status ?? 'pending' }}
                    </span>
                </div>
                <div class="p-6 space-y-5">
                    @if($appointment->google_meet_link)
                        <div class="space-y-4">
                            <div class="p-4 bg-muted/30 rounded-lg border border-border/50 break-all">
                                <label class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest block mb-2">Meeting Link</label>
                                <a href="{{ $appointment->google_meet_link }}" target="_blank" class="text-sm font-bold text-primary hover:underline break-all">{{ $appointment->google_meet_link }}</a>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="copyLink('{{ $appointment->google_meet_link }}')" class="flex-1 py-2 bg-card border border-border text-foreground rounded-lg text-xs font-bold hover:bg-accent transition-all flex items-center justify-center gap-2 shadow-sm">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Copy Link
                                </button>
                                <button onclick="regenerateMeet()" class="flex-1 py-2 bg-card border border-border text-foreground rounded-lg text-xs font-bold hover:bg-accent transition-all flex items-center justify-center gap-2 shadow-sm">
                                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Regenerate
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="w-12 h-12 bg-muted/50 rounded-full flex items-center justify-center mx-auto mb-3 text-muted-foreground">
                                <i data-lucide="video-off" class="w-6 h-6"></i>
                            </div>
                            <p class="text-xs text-muted-foreground mb-4">No meeting link generated yet.</p>
                            <button onclick="regenerateMeet()" class="w-full py-2.5 bg-primary text-primary-foreground rounded-lg text-xs font-black hover:opacity-90 shadow-sm transition-all flex items-center justify-center gap-2">
                                <i data-lucide="video" class="w-4 h-4"></i> Generate Meet Link
                            </button>
                        </div>
                    @endif
                </div>
            </section>

            <section class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-border flex items-center justify-between">
                    <h3 class="text-lg font-bold text-foreground">Google Calendar</h3>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border {{ $appointment->google_calendar_event_id ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 border-amber-500/20' }}">
                        {{ $appointment->calendar_status ?? 'pending' }}
                    </span>
                </div>
                <div class="p-6 space-y-5">
                    @if($appointment->google_calendar_event_id)
                        <div class="space-y-4">
                            <div class="p-4 bg-muted/30 rounded-lg border border-border/50">
                                <label class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest block mb-2">Event ID</label>
                                <code class="text-[10px] font-mono text-foreground break-all">{{ $appointment->google_calendar_event_id }}</code>
                            </div>
                            <button onclick="syncCalendar()" class="w-full py-2.5 bg-card border border-border text-foreground rounded-lg text-xs font-bold hover:bg-accent transition-all flex items-center justify-center gap-2 shadow-sm">
                                <i data-lucide="refresh-cw" class="w-4 h-4"></i> Sync Calendar Event
                            </button>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="w-12 h-12 bg-muted/50 rounded-full flex items-center justify-center mx-auto mb-3 text-muted-foreground">
                                <i data-lucide="calendar-off" class="w-6 h-6"></i>
                            </div>
                            <p class="text-xs text-muted-foreground mb-4">No calendar event created yet.</p>
                            <button onclick="syncCalendar()" class="w-full py-2.5 bg-primary text-primary-foreground rounded-lg text-xs font-black hover:opacity-90 shadow-sm transition-all flex items-center justify-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4"></i> Create Calendar Event
                            </button>
                        </div>
                    @endif
                </div>
            </section>

            <section class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Student Info</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xl font-bold border border-primary/20">
                            {{ substr($appointment->student->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-foreground truncate">{{ $appointment->student->name }}</p>
                            <p class="text-xs text-muted-foreground">Year {{ $appointment->student->student_class }}</p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-border/50 space-y-2">
                        <p class="text-xs text-muted-foreground flex justify-between items-center">
                            <span>Email:</span>
                            <span class="font-bold text-foreground">{{ $appointment->student->email }}</span>
                        </p>
                        <p class="text-xs text-muted-foreground flex justify-between items-center">
                            <span>Phone:</span>
                            <span class="font-bold text-foreground">{{ $appointment->student->mobile_number }}</span>
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('apt-status-form').onsubmit = async (e) => {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (response.ok) {
                window.toast.success(result.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                window.toast.error(result.message || 'Failed to update status');
            }
        } catch (error) {
            window.toast.error('Something went wrong. Please try again.');
        }
    };

    function copyLink(text) {
        navigator.clipboard.writeText(text);
        window.toast.success('Link copied to clipboard');
    }

    async function regenerateMeet() {
        try {
            const response = await fetch('{{ route("admin.appointments.regenerate-meet", $appointment->id) }}', {
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
                window.toast.error(result.message || 'Failed to regenerate link');
            }
        } catch (error) {
            window.toast.error('Something went wrong. Please try again.');
        }
    }

    async function syncCalendar() {
        try {
            const response = await fetch('{{ route("admin.appointments.sync-calendar", $appointment->id) }}', {
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
                window.toast.error(result.message || 'Failed to sync calendar');
            }
        } catch (error) {
            window.toast.error('Something went wrong. Please try again.');
        }
    }

    async function sendEmailAgain() {
        try {
            const response = await fetch('{{ route("admin.appointments.send-email", $appointment->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            if (response.ok) {
                window.toast.success(result.message);
            } else {
                window.toast.error(result.message || 'Failed to send email');
            }
        } catch (error) {
            window.toast.error('Something went wrong. Please try again.');
        }
    }

    async function generateInvoice() {
        try {
            const response = await fetch('{{ route("admin.appointments.generate-invoice", $appointment->id) }}', {
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
                window.toast.error(result.message || 'Failed to generate invoice');
            }
        } catch (error) {
            window.toast.error('Something went wrong. Please try again.');
        }
    }
</script>
@endpush
@endsection
