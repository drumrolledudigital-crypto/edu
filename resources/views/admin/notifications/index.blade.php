@extends('layouts.admin')

@section('title', 'Email Notification History')
@section('page_title', 'Email History')

@section('content')
<div class="space-y-6">
    <x-admin.datatable 
        id="notifications-table"
        title="Email Notifications"
        description="Track all outgoing emails and their delivery status."
    >
        <x-slot name="filters">
            <div class="flex items-center gap-3">
                <select id="filter-status" name="status" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-40 text-foreground">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="sent">Sent</option>
                    <option value="failed">Failed</option>
                    <option value="queued">Queued</option>
                </select>
                <select id="filter-type" name="type" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-40 text-foreground">
                    <option value="">All Types</option>
                    <option value="welcome_student">Welcome Email</option>
                    <option value="appointment_created">Booking Created</option>
                    <option value="payment_success">Payment Success</option>
                    <option value="appointment_confirmed">Session Confirmed</option>
                    <option value="appointment_rescheduled">Rescheduled</option>
                    <option value="appointment_cancelled">Cancelled</option>
                    <option value="meeting_reminder">Reminder</option>
                </select>
            </div>
        </x-slot>
    </x-admin.datatable>
</div>

@push('scripts')
<script>
    let notificationsTable;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const routes = {
        notificationShow: (id) => '{{ route("admin.notifications.show", ":id") }}'.replace(':id', id),
    };

    document.addEventListener('DOMContentLoaded', () => {
        notificationsTable = new AdminDataTable('notifications-table', {
            url: '{{ route("admin.notifications.list") }}',
            perPage: 10,
            filterSelectors: ['#filter-status', '#filter-type'],
            columns: [
                { 
                    key: 'student_name', 
                    title: 'Recipient',
                    render: (val, row) => `
                        <div>
                            <p class="font-bold text-foreground text-xs leading-none">${val}</p>
                            <p class="text-[10px] text-muted-foreground mt-1.5">${row.recipient}</p>
                        </div>
                    `
                },
                { 
                    key: 'subject', 
                    title: 'Subject',
                    render: (val) => `<span class="text-xs font-semibold text-foreground line-clamp-1">${val}</span>`
                },
                { 
                    key: 'type', 
                    title: 'Type',
                    render: (val) => `<span class="px-2 py-0.5 rounded bg-muted text-foreground text-[10px] font-bold uppercase tracking-wider">${val.replace(/_/g, ' ')}</span>`
                },
                { 
                    key: 'status', 
                    title: 'Status',
                    render: (val) => {
                        const typeMap = {
                            'sent': 'paid', // emerald
                            'failed': 'cancelled', // rose
                            'pending': 'pending', // amber
                            'queued': 'active' // emerald pulse
                        };
                        return AdminDataTable.renderBadge(val, typeMap[val] || 'default');
                    }
                },
                { 
                    key: 'created_at', 
                    title: 'Created At',
                    render: (val) => `<span class="text-[10px] text-muted-foreground">${val}</span>`
                },
                { 
                    key: 'sent_at', 
                    title: 'Sent At',
                    render: (val) => `<span class="text-[10px] text-muted-foreground">${val || 'N/A'}</span>`
                },
                {
                    key: 'actions',
                    title: 'Actions',
                    sortable: false,
                    searchable: false,
                    class: 'text-center w-24',
                    render: (val, row) => AdminDataTable.renderActions(`
                        <a href="${routes.notificationShow(row.id)}" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Details
                        </a>
                        ${row.status === 'failed' ? `
                        <button onclick="resendEmail(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Resend Email
                        </button>
                        ` : ''}
                    `)
                }
            ]
        });
    });

    async function resendEmail(id) {
        try {
            const response = await fetch(`/admin/notifications/${id}/resend`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            if (response.ok) {
                window.toast.success(result.message);
                notificationsTable.loadData();
            } else {
                window.toast.error(result.message || 'Failed to resend email');
            }
        } catch (error) {
            window.toast.error('Something went wrong');
        }
    }
</script>
@endpush
@endsection
