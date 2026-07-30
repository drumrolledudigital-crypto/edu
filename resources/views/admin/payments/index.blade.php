@extends('layouts.admin')

@section('title', 'Manage Payments')
@section('page_title', 'Payment Transactions')

@section('content')
<div class="space-y-6">
    <x-admin.datatable 
        id="payments-table"
        title="Transactions"
        description="Monitor student payments and transaction history."
    >
        <x-slot name="filters">
            <select id="filter-payment-status" name="payment_status" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-40 text-foreground">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="successful">Successful</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
            </select>
        </x-slot>
    </x-admin.datatable>
</div>

@push('scripts')
<script>
    let paymentsTable;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('DOMContentLoaded', () => {
        paymentsTable = new AdminDataTable('payments-table', {
            url: '{{ route("admin.payments.list") }}',
            perPage: 10,
            filterSelectors: ['#filter-payment-status'],
            columns: [
                { 
                    key: 'student', 
                    title: 'Student',
                    render: (val) => `
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-[10px] font-bold border border-primary/10">
                                ${val.name.charAt(0)}
                            </div>
                            <div>
                                <p class="font-bold text-foreground text-xs leading-none">${val.name}</p>
                                <p class="text-[10px] text-muted-foreground mt-1">${val.email}</p>
                            </div>
                        </div>
                    `
                },
                { 
                    key: 'appointment', 
                    title: 'Subject / Booking',
                    render: (val) => `
                        <div>
                            <p class="font-semibold text-foreground text-xs">${val.subject.name}</p>
                            <p class="text-[10px] text-muted-foreground mt-1">${new Date(val.appointment_date).toLocaleDateString()}</p>
                        </div>
                    `
                },
                { 
                    key: 'amount', 
                    title: 'Amount',
                    render: (val, row) => `
                        <div class="flex items-center gap-1.5">
                            <span class="font-black text-foreground text-sm">$${val}</span>
                            <span class="text-[10px] font-bold text-muted-foreground uppercase">${row.currency}</span>
                        </div>
                    `
                },
                { 
                    key: 'transaction_id', 
                    title: 'Transaction ID',
                    render: (val) => `<span class="font-mono text-[10px] text-muted-foreground">${val || 'N/A'}</span>`
                },
                { 
                    key: 'payment_status', 
                    title: 'Status',
                    render: (val) => AdminDataTable.renderBadge(val === 'successful' ? 'Paid' : val, val)
                },
                { 
                    key: 'payment_date', 
                    title: 'Date',
                    render: (val) => val ? `<span class="text-xs text-muted-foreground">${new Date(val).toLocaleDateString()}</span>` : '-'
                },
                {
                    key: 'actions',
                    title: 'Actions',
                    sortable: false,
                    searchable: false,
                    class: 'text-center w-32',
                    render: (val, row) => AdminDataTable.renderActions(`
                        <button onclick="updatePaymentStatus(${row.id}, 'successful')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-500"></i> Mark Successful
                        </button>
                        <button onclick="markRefunded(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-rose-500"></i> Mark Refunded
                        </button>
                        <div class="h-px bg-border my-1"></div>
                        <button onclick="deletePayment(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold text-rose-500 hover:bg-rose-500/10 flex items-center gap-2 transition-colors">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                        </button>
                    `)
                }
            ]
        });
    });

    async function updatePaymentStatus(id, status) {
        try {
            const response = await fetch(`/admin/payments/${id}/status`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ payment_status: status })
            });
            const result = await response.json();
            if (response.ok) { window.toast.success(result.message); paymentsTable.loadData(); }
            else { window.toast.error(result.message); }
        } catch (error) { window.toast.error('Something went wrong'); }
    }

    async function markRefunded(id) {
        window.confirmAction({
            title: 'Mark Refunded',
            message: 'Are you sure you want to mark this payment as refunded? This is for internal tracking only.',
            onConfirm: async () => {
                try {
                    const response = await fetch(`/admin/payments/${id}/refund`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                    });
                    const result = await response.json();
                    if (response.ok) { window.toast.success(result.message); paymentsTable.loadData(); }
                } catch (error) { window.toast.error('Failed to update status'); }
            }
        });
    }

    function deletePayment(id) {
        window.confirmAction({
            title: 'Delete Record',
            message: 'Are you sure you want to delete this payment record?',
            onConfirm: async () => {
                try {
                    const response = await fetch(`/admin/payments/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                    });
                    if (response.ok) { window.toast.success('Payment record deleted'); paymentsTable.loadData(); }
                } catch (error) { window.toast.error('Failed to delete'); }
            }
        });
    }
</script>
@endpush
@endsection
