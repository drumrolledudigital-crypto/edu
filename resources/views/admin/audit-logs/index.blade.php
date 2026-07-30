@extends('layouts.admin')

@section('title', 'Audit Logs')
@section('page_title', 'Activity History')

@section('content')
<div class="space-y-6">
    <x-admin.datatable 
        id="audit-logs-table"
        title="System Audit Logs"
        description="Comprehensive enterprise trail of all system activities."
    >
        <x-slot name="filters">
            <div class="flex items-center gap-3">
                <select id="filter-module" name="module" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-32 text-foreground">
                    <option value="">All Modules</option>
                    <option value="Auth">Auth</option>
                    <option value="Booking">Booking</option>
                    <option value="Payment">Payment</option>
                    <option value="Refund">Refund</option>
                    <option value="Subject">Subject</option>
                    <option value="Doubt">Doubt</option>
                    <option value="Settings">Settings</option>
                    <option value="Invoice">Invoice</option>
                    <option value="System">System</option>
                </select>
                <select id="filter-action" name="action" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-32 text-foreground">
                    <option value="">All Actions</option>
                    <option value="Login">Login</option>
                    <option value="Logout">Logout</option>
                    <option value="Create">Create</option>
                    <option value="Update">Update</option>
                    <option value="Delete">Delete</option>
                    <option value="StatusUpdate">Status Update</option>
                </select>
                <select id="filter-role" name="role" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-32 text-foreground">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="student">Student</option>
                    <option value="system">System</option>
                </select>
            </div>
        </x-slot>
    </x-admin.datatable>
</div>

@push('scripts')
<script>
    let auditLogsTable;
    const routes = {
        auditLogShow: (id) => '{{ route("admin.audit-logs.show", ":id") }}'.replace(':id', id),
    };
    
    document.addEventListener('DOMContentLoaded', () => {
        auditLogsTable = new AdminDataTable('audit-logs-table', {
            url: '{{ route("admin.audit-logs.list") }}',
            perPage: 15,
            filterSelectors: ['#filter-module', '#filter-action', '#filter-role'],
            columns: [
                { 
                    key: 'id', 
                    title: 'ID',
                    class: 'w-16 text-center',
                    render: (val) => `<span class="text-xs font-mono text-muted-foreground">#${val}</span>`
                },
                { 
                    key: 'user', 
                    title: 'User / Role',
                    render: (val, row) => `
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold shrink-0">
                                ${val ? val.name.charAt(0).toUpperCase() : (row.role === 'system' ? 'S' : '?')}
                            </div>
                            <div>
                                <p class="font-bold text-foreground text-xs leading-none">${val ? val.name : 'System'}</p>
                                <p class="text-[10px] font-bold uppercase tracking-widest mt-1 ${row.role === 'admin' ? 'text-rose-500' : (row.role === 'system' ? 'text-amber-500' : 'text-primary-500')}">${row.role || 'system'}</p>
                            </div>
                        </div>
                    `
                },
                { 
                    key: 'module', 
                    title: 'Module & Action',
                    render: (val, row) => `
                        <div>
                            <span class="text-xs font-bold text-foreground block">${val}</span>
                            <span class="px-1.5 py-0.5 mt-1 inline-block rounded bg-muted text-muted-foreground text-[9px] font-bold uppercase tracking-wider border border-border">${row.action}</span>
                        </div>
                    `
                },
                { 
                    key: 'description', 
                    title: 'Description',
                    render: (val) => `<span class="text-xs text-muted-foreground line-clamp-2" title="${val}">${val}</span>`
                },
                { 
                    key: 'ip_address', 
                    title: 'Client Info',
                    render: (val, row) => `
                        <div>
                            <span class="text-[10px] font-mono text-muted-foreground block">${val || 'N/A'}</span>
                            <span class="text-[9px] text-muted-foreground/60 line-clamp-1 truncate max-w-[120px]" title="${row.user_agent}">${row.user_agent || 'Unknown'}</span>
                        </div>
                    `
                },
                { 
                    key: 'created_at', 
                    title: 'Date',
                    class: 'w-32 text-right',
                    render: (val) => `<span class="text-[11px] font-medium text-muted-foreground whitespace-nowrap">${new Date(val).toLocaleString()}</span>`
                },
                {
                    key: 'actions',
                    title: 'Actions',
                    sortable: false,
                    searchable: false,
                    class: 'text-center w-24',
                    render: (val, row) => AdminDataTable.renderActions(`
                        <a href="${routes.auditLogShow(row.id)}" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Details
                        </a>
                    `)
                }
            ]
        });
    });
</script>
@endpush
@endsection
