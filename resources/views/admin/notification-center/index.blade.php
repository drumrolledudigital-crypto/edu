@extends('layouts.admin')

@section('title', 'Admin Notifications')
@section('page_title', 'Notification Center')

@section('content')
<div class="space-y-6">
    <x-admin.datatable 
        id="notification-center-table"
        title="Admin Notifications"
        description="Internal system alerts and business events."
    >
        <x-slot name="filters">
            <div class="flex items-center gap-3">
                <button onclick="markAllNotificationsRead()" class="bg-card text-primary border border-primary/20 hover:bg-primary/10 px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-all mr-2">
                    <i data-lucide="check-check" class="w-3.5 h-3.5 inline mr-1"></i> Mark All Read
                </button>
                <select id="filter-status" name="status" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-32 text-foreground">
                    <option value="">All Statuses</option>
                    <option value="unread">Unread</option>
                    <option value="read">Read</option>
                    <option value="archived">Archived</option>
                </select>
                <select id="filter-type" name="type" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-32 text-foreground">
                    <option value="">All Types</option>
                    <option value="Student">Student</option>
                    <option value="Doubt">Doubt</option>
                    <option value="Booking">Booking</option>
                    <option value="Payment">Payment</option>
                    <option value="Invoice">Invoice</option>
                    <option value="Refund">Refund</option>
                    <option value="System">System</option>
                </select>
            </div>
        </x-slot>
    </x-admin.datatable>
</div>

@push('scripts')
<script>
    let notifCenterTable;
    const centerCsrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('DOMContentLoaded', () => {
        notifCenterTable = new AdminDataTable('notification-center-table', {
            url: '{{ route("admin.notification-center.list") }}',
            perPage: 15,
            filterSelectors: ['#filter-status', '#filter-type'],
            columns: [
                { 
                    key: 'type', 
                    title: 'Type',
                    class: 'w-12 text-center',
                    render: (val, row) => {
                        const isUnread = row.status === 'unread';
                        return `
                        <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center border ${isUnread ? 'bg-primary/10 border-primary/20 text-primary' : 'bg-muted border-border text-muted-foreground'}">
                            <i data-lucide="${row.icon || 'bell'}" class="w-4 h-4"></i>
                        </div>
                        `;
                    }
                },
                { 
                    key: 'title', 
                    title: 'Notification',
                    render: (val, row) => {
                        const isUnread = row.status === 'unread';
                        return `
                        <div class="flex flex-col">
                            <span class="text-sm ${isUnread ? 'font-black text-foreground' : 'font-medium text-muted-foreground'}">${val}</span>
                            <span class="text-xs text-muted-foreground line-clamp-1 mt-0.5">${row.message}</span>
                            ${row.user ? `<span class="text-[10px] text-muted-foreground/70 mt-1 uppercase tracking-widest font-bold">User: ${row.user.name}</span>` : ''}
                        </div>
                        `;
                    }
                },
                { 
                    key: 'status', 
                    title: 'Status',
                    class: 'w-24 text-center',
                    render: (val) => {
                        const typeMap = {
                            'unread': 'active', // primary
                            'read': 'default',  // muted
                            'archived': 'cancelled', // rose/gray
                        };
                        return AdminDataTable.renderBadge(val, typeMap[val] || 'default');
                    }
                },
                { 
                    key: 'created_at', 
                    title: 'Time',
                    class: 'w-32 text-right',
                    render: (val) => `<span class="text-[11px] font-medium text-muted-foreground whitespace-nowrap">${new Date(val).toLocaleString()}</span>`
                },
                {
                    key: 'actions',
                    title: 'Actions',
                    sortable: false,
                    searchable: false,
                    class: 'text-center w-32',
                    render: (val, row) => AdminDataTable.renderActions(`
                        ${row.url ? `
                        <a href="${row.url}" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i> View Details
                        </a>
                        ` : ''}
                        ${row.status === 'unread' ? `
                            <button onclick="handleNotifAction(${row.id}, 'read')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Mark Read
                            </button>
                        ` : `
                            <button onclick="handleNotifAction(${row.id}, 'unread')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                                <i data-lucide="mail" class="w-3.5 h-3.5"></i> Mark Unread
                            </button>
                        `}
                        ${row.status !== 'archived' ? `
                            <button onclick="handleNotifAction(${row.id}, 'archive')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-amber-500/10 text-amber-600 flex items-center gap-2 transition-colors">
                                <i data-lucide="archive" class="w-3.5 h-3.5"></i> Archive
                            </button>
                        ` : ''}
                        <div class="h-px bg-border my-1"></div>
                        <button onclick="deleteNotif(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-rose-500/10 text-rose-500 flex items-center gap-2 transition-colors">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                        </button>
                    `)
                }
            ]
        });
    });

    async function handleNotifAction(id, action) {
        try {
            const response = await fetch(`/admin/notification-center/${id}/${action}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': centerCsrfToken, 'Accept': 'application/json' }
            });
            const result = await response.json();
            if (response.ok) {
                window.toast.success(result.message);
                notifCenterTable.loadData();
                // Optionally update top bell here if we were doing it via JS entirely
            } else {
                window.toast.error('Failed to update notification.');
            }
        } catch (e) {
            window.toast.error('Something went wrong.');
        }
    }

    function deleteNotif(id) {
        window.confirmAction({
            title: 'Delete Notification',
            message: 'Are you sure you want to permanently delete this notification record?',
            onConfirm: async () => {
                try {
                    const response = await fetch(`/admin/notification-center/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': centerCsrfToken, 'Accept': 'application/json' }
                    });
                    const result = await response.json();
                    if (response.ok) {
                        window.toast.success(result.message);
                        notifCenterTable.loadData();
                    } else {
                        window.toast.error('Failed to delete notification.');
                    }
                } catch (e) {
                    window.toast.error('Something went wrong.');
                }
            }
        });
    }
</script>
@endpush
@endsection
