@extends('layouts.admin')

@section('title', 'Manage Appointments')
@section('page_title', 'Appointments')

@section('content')
<div class="space-y-6">
    <x-admin.datatable 
        id="appointments-table"
        title="Teaching Sessions"
        description="Manage scheduled sessions and track automation status."
    >
        <x-slot name="filters">
            <div class="flex items-center gap-3">
                <select id="filter-status" name="status" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-40 text-foreground">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="rescheduled">Rescheduled</option>
                </select>
                <select id="filter-subject" name="subject_id" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-40 text-foreground">
                    <option value="">All Subjects</option>
                    @foreach(\App\Models\Subject::all() as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
        </x-slot>

        <x-slot name="actions">
            <a href="{{ route('admin.appointments.create') }}" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg text-sm font-bold hover:opacity-90 shadow-sm transition-all flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Create Booking
            </a>
        </x-slot>
    </x-admin.datatable>
</div>

@push('scripts')
<script>
    let appointmentsTable;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const routes = {
        appointmentShow: (id) => '{{ route("admin.appointments.show", ":id") }}'.replace(':id', id),
    };

    document.addEventListener('DOMContentLoaded', () => {
        appointmentsTable = new AdminDataTable('appointments-table', {
            url: '{{ route("admin.appointments.list") }}',
            perPage: 10,
            filterSelectors: ['#filter-status', '#filter-subject'],
            columns: [
                { 
                    key: 'slot', 
                    title: 'Date & Time',
                    render: (val) => `
                        <div>
                            <p class="font-bold text-foreground text-xs">${new Date(val.date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}</p>
                            <p class="text-[10px] text-muted-foreground mt-1">${val.start_time.substring(0, 5)} - ${val.end_time.substring(0, 5)}</p>
                        </div>
                    `
                },
                { 
                    key: 'student', 
                    title: 'Student / Subject',
                    render: (val, row) => `
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-[10px] font-bold border border-primary/10">
                                ${val.name.charAt(0)}
                            </div>
                            <div>
                                <p class="font-bold text-foreground text-xs leading-none">${val.name}</p>
                                <p class="text-[10px] font-semibold text-primary uppercase mt-1.5">${row.subject.name}</p>
                            </div>
                        </div>
                    `
                },
                { 
                    key: 'status', 
                    title: 'Status',
                    render: (val) => AdminDataTable.renderBadge(val, val)
                },
                { 
                    key: 'meeting_status', 
                    title: 'Google Meet',
                    render: (val, row) => {
                        const status = row.google_meet_link ? 'generated' : val;
                        const badge = AdminDataTable.renderBadge(status, status === 'generated' ? 'Meet Active' : status);
                        const link = row.google_meet_link ? `
                            <div class="mt-1.5 flex items-center gap-2">
                                <a href="${row.google_meet_link}" target="_blank" class="text-[10px] font-bold text-primary hover:underline flex items-center gap-1">
                                    <i data-lucide="video" class="w-3 h-3"></i> Join
                                </a>
                                <button onclick="copyToClipboard('${row.google_meet_link}')" class="text-[10px] font-bold text-muted-foreground hover:text-foreground transition-colors">
                                    Copy
                                </button>
                            </div>
                        ` : '';
                        return `<div>${badge}${link}</div>`;
                    }
                },
                { 
                    key: 'calendar_status', 
                    title: 'Calendar',
                    render: (val, row) => {
                        const status = row.google_calendar_event_id ? 'active' : (val || 'pending');
                        return AdminDataTable.renderBadge(status, status === 'active' ? 'Synced' : status);
                    }
                },
                { 
                    key: 'duration', 
                    title: 'Duration',
                    render: (val) => `<span class="px-2 py-0.5 rounded bg-muted text-foreground text-[10px] font-bold uppercase tracking-wider">${val} Min</span>`
                },
                { 
                    key: 'created_at', 
                    title: 'Booked On',
                    render: (val) => `<span class="text-xs text-muted-foreground">${new Date(val).toLocaleDateString()}</span>`
                },
                {
                    key: 'actions',
                    title: 'Actions',
                    sortable: false,
                    searchable: false,
                    class: 'text-center w-32',
                    render: (val, row) => AdminDataTable.renderActions(`
                        <a href="${routes.appointmentShow(row.id)}" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Detail
                        </a>
                        <button onclick="updateAptStatus(${row.id}, 'confirmed')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Confirm
                        </button>
                        <button onclick="regenerateMeet(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Regenerate Meet
                        </button>
                        <button onclick="syncCalendar(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i> Sync Calendar
                        </button>
                        <button onclick="updateAptStatus(${row.id}, 'completed')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="award" class="w-3.5 h-3.5"></i> Mark Done
                        </button>
                        <div class="h-px bg-border my-1"></div>
                        <button onclick="updateAptStatus(${row.id}, 'cancelled')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-rose-500/10 text-rose-500 flex items-center gap-2 transition-colors">
                            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> Cancel Appointment
                        </button>
                        <button onclick="deleteApt(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold text-rose-500 hover:bg-rose-500/10 flex items-center gap-2 transition-colors">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete Permanent
                        </button>
                    `)
                }
            ]
        });
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        window.toast.success('Link copied to clipboard');
    }

    async function regenerateMeet(id) {
        try {
            const response = await fetch(`/admin/appointments/${id}/regenerate-meet`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            if (response.ok) {
                window.toast.success(result.message);
                appointmentsTable.loadData();
            } else {
                window.toast.error(result.message || 'Failed to regenerate link');
            }
        } catch (error) {
            window.toast.error('Something went wrong');
        }
    }

    async function syncCalendar(id) {
        try {
            const response = await fetch(`/admin/appointments/${id}/sync-calendar`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            if (response.ok) {
                window.toast.success(result.message);
                appointmentsTable.loadData();
            } else {
                window.toast.error(result.message || 'Failed to sync calendar');
            }
        } catch (error) {
            window.toast.error('Something went wrong');
        }
    }

    async function updateAptStatus(id, status) {
        try {
            const response = await fetch(`/admin/appointments/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status })
            });

            const result = await response.json();
            if (response.ok) {
                window.toast.success(result.message);
                appointmentsTable.loadData();
            } else {
                window.toast.error(result.message || 'Failed to update status.');
            }
        } catch (error) {
            window.toast.error('Something went wrong.');
        }
    }

    function deleteApt(id) {
        window.confirmAction({
            title: 'Delete Appointment',
            message: 'Are you sure you want to delete this appointment record? This action will free up the slot.',
            onConfirm: async () => {
                try {
                    const response = await fetch(`/admin/appointments/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (response.ok) {
                        window.toast.success(result.message);
                        appointmentsTable.loadData();
                    } else {
                        window.toast.error('Failed to delete record');
                    }
                } catch (error) {
                    window.toast.error('Something went wrong');
                }
            }
        });
    }
</script>
@endpush
@endsection
