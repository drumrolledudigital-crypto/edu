@extends('layouts.admin')

@section('title', 'Refunds')
@section('page_title', 'Refund Management')

@section('content')
<div class="space-y-6">
    <x-admin.datatable 
        id="refunds-table"
        title="Refund Requests"
        description="Manage and process student refund requests."
    >
        <x-slot name="filters">
            <div class="flex items-center gap-3">
                <select id="filter-status" name="status" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-40 text-foreground">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>
        </x-slot>
    </x-admin.datatable>
</div>

<!-- Admin Notes Modal -->
<div id="notes-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-card w-full max-w-md rounded-xl border border-border shadow-2xl animate-in zoom-in-95 duration-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-foreground">Update Refund Status</h3>
                    <button onclick="closeNotesModal()" class="text-muted-foreground hover:text-foreground">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <input type="hidden" id="modal-refund-id">
                <input type="hidden" id="modal-refund-status">
                
                <div class="space-y-4">
                    <div class="p-3 bg-muted/50 rounded-lg text-sm text-foreground">
                        Status changing to: <span id="modal-status-label" class="font-bold uppercase tracking-wider"></span>
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-1 block">Admin Notes (Optional)</label>
                        <textarea id="modal-admin-notes" rows="4" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none" placeholder="Add a note to the student or for internal records..."></textarea>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 p-4 border-t border-border bg-muted/30 rounded-b-xl">
                <button onclick="closeNotesModal()" class="px-4 py-2 text-sm font-semibold text-muted-foreground hover:text-foreground transition-colors">Cancel</button>
                <button onclick="submitStatusUpdate()" class="px-4 py-2 bg-primary text-primary-foreground text-sm font-bold rounded-lg hover:opacity-90 shadow-sm transition-all">Confirm Update</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let refundsTable;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const routes = {
        invoiceDownload: (id) => '{{ route("admin.invoices.download", ":id") }}'.replace(':id', id),
    };

    document.addEventListener('DOMContentLoaded', () => {
        refundsTable = new AdminDataTable('refunds-table', {
            url: '{{ route("admin.refunds.list") }}',
            perPage: 10,
            filterSelectors: ['#filter-status'],
            columns: [
                { 
                    key: 'student', 
                    title: 'Student / Booking',
                    render: (val, row) => `
                        <div>
                            <p class="font-bold text-foreground text-xs leading-none">${val ? val.name : 'N/A'}</p>
                            <p class="text-[10px] text-muted-foreground mt-1.5">${row.payment && row.payment.appointment && row.payment.appointment.subject ? row.payment.appointment.subject.name : 'N/A'}</p>
                        </div>
                    `
                },
                { 
                    key: 'invoice', 
                    title: 'Invoice',
                    render: (val, row) => val ? `<a href="${routes.invoiceDownload(val.id)}" target="_blank" class="text-xs font-bold text-primary hover:underline">${val.invoice_number}</a>` : '<span class="text-xs text-muted-foreground">N/A</span>'
                },
                { 
                    key: 'refund_amount', 
                    title: 'Amount',
                    render: (val, row) => `<span class="text-xs font-bold text-foreground">$${parseFloat(val || row.amount).toFixed(2)}</span>`
                },
                { 
                    key: 'status', 
                    title: 'Status',
                    render: (val) => {
                        const typeMap = {
                            'pending': 'pending', // amber
                            'approved': 'active', // primary
                            'rejected': 'cancelled', // rose
                            'refunded': 'paid' // emerald
                        };
                        return AdminDataTable.renderBadge(val, typeMap[val] || 'default');
                    }
                },
                { 
                    key: 'created_at', 
                    title: 'Requested On',
                    render: (val) => `<span class="text-[10px] text-muted-foreground">${new Date(val).toLocaleDateString()}</span>`
                },
                {
                    key: 'actions',
                    title: 'Actions',
                    sortable: false,
                    searchable: false,
                    class: 'text-center w-32',
                    render: (val, row) => AdminDataTable.renderActions(`
                        ${row.status === 'pending' ? `
                            <button onclick="openNotesModal(${row.id}, 'approved')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-primary/10 text-primary flex items-center gap-2 transition-colors">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Approve
                            </button>
                            <button onclick="openNotesModal(${row.id}, 'rejected')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-rose-500/10 text-rose-500 flex items-center gap-2 transition-colors">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i> Reject
                            </button>
                            <div class="h-px bg-border my-1"></div>
                        ` : ''}
                        ${row.status === 'approved' ? `
                            <button onclick="openNotesModal(${row.id}, 'refunded')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-emerald-500/10 text-emerald-600 flex items-center gap-2 transition-colors">
                                <i data-lucide="dollar-sign" class="w-3.5 h-3.5"></i> Mark Refunded
                            </button>
                        ` : ''}
                        <button onclick="viewReason('${escapeHtml(row.reason)}', '${escapeHtml(row.admin_notes || '')}')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Details
                        </button>
                    `)
                }
            ]
        });
    });

    function escapeHtml(unsafe) {
        return (unsafe || '').replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;").replace(/\\n/g, "<br>");
    }

    function viewReason(reason, adminNotes) {
        let content = `<div class="text-left text-sm text-muted-foreground"><p class="font-bold text-foreground mb-1">Student Reason:</p><p class="mb-4">${reason}</p>`;
        if (adminNotes) {
            content += `<p class="font-bold text-foreground mb-1">Admin Notes:</p><p>${adminNotes}</p>`;
        }
        content += `</div>`;
        
        window.confirmAction({
            title: 'Refund Request Details',
            message: content,
            onConfirm: () => {} // Just close
        });
    }

    function openNotesModal(id, status) {
        document.getElementById('modal-refund-id').value = id;
        document.getElementById('modal-refund-status').value = status;
        
        const label = document.getElementById('modal-status-label');
        label.textContent = status;
        
        if (status === 'approved') label.className = 'font-bold uppercase tracking-wider text-primary';
        else if (status === 'rejected') label.className = 'font-bold uppercase tracking-wider text-rose-500';
        else if (status === 'refunded') label.className = 'font-bold uppercase tracking-wider text-emerald-500';
        
        document.getElementById('modal-admin-notes').value = '';
        document.getElementById('notes-modal').classList.remove('hidden');
    }

    function closeNotesModal() {
        document.getElementById('notes-modal').classList.add('hidden');
    }

    async function submitStatusUpdate() {
        const id = document.getElementById('modal-refund-id').value;
        const status = document.getElementById('modal-refund-status').value;
        const notes = document.getElementById('modal-admin-notes').value;

        try {
            const response = await fetch(`/admin/refunds/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status, admin_notes: notes })
            });

            const result = await response.json();
            if (response.ok) {
                window.toast.success(result.message);
                closeNotesModal();
                refundsTable.loadData();
            } else {
                window.toast.error(result.message || 'Failed to update status');
            }
        } catch (error) {
            window.toast.error('Something went wrong');
        }
    }
</script>
@endpush
@endsection
