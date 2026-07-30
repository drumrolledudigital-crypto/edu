@extends('layouts.admin')

@section('title', 'Invoices')
@section('page_title', 'Invoice Management')

@section('content')
<div class="space-y-6">
    <x-admin.datatable 
        id="invoices-table"
        title="Generated Invoices"
        description="Manage system-generated invoices for successful payments."
    >
        <x-slot name="filters">
            <div class="flex items-center gap-3">
                <input type="text" id="filter-invoice-number" placeholder="Invoice Number..." class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-40 text-foreground">
            </div>
        </x-slot>
    </x-admin.datatable>
</div>

@push('scripts')
<script>
    let invoicesTable;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const routes = {
        invoiceDownload: (id) => '{{ route("admin.invoices.download", ":id") }}'.replace(':id', id),
    };

    document.addEventListener('DOMContentLoaded', () => {
        invoicesTable = new AdminDataTable('invoices-table', {
            url: '{{ route("admin.invoices.list") }}',
            perPage: 10,
            columns: [
                { 
                    key: 'invoice_number', 
                    title: 'Invoice Number',
                    render: (val, row) => `<span class="text-xs font-bold text-foreground">${val}</span>`
                },
                { 
                    key: 'student', 
                    title: 'Student / Subject',
                    render: (val, row) => `
                        <div>
                            <p class="font-bold text-foreground text-xs leading-none">${val ? val.name : 'N/A'}</p>
                            <p class="text-[10px] text-muted-foreground mt-1.5">${row.appointment && row.appointment.subject ? row.appointment.subject.name : 'N/A'}</p>
                        </div>
                    `
                },
                { 
                    key: 'amount', 
                    title: 'Amount',
                    render: (val, row) => `<span class="text-xs font-bold text-foreground">${row.currency.toUpperCase()} ${parseFloat(val).toFixed(2)}</span>`
                },
                { 
                    key: 'status', 
                    title: 'Status',
                    render: (val) => AdminDataTable.renderBadge(val, 'paid')
                },
                { 
                    key: 'invoice_date', 
                    title: 'Invoice Date',
                    render: (val) => `<span class="text-[10px] text-muted-foreground">${new Date(val).toLocaleDateString()}</span>`
                },
                {
                    key: 'actions',
                    title: 'Actions',
                    sortable: false,
                    searchable: false,
                    class: 'text-center w-24',
                    render: (val, row) => AdminDataTable.renderActions(`
                        <a href="${routes.invoiceDownload(row.id)}" target="_blank" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i> Download PDF
                        </a>
                        <button onclick="regenerateInvoice(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Regenerate
                        </button>
                        <div class="h-px bg-border my-1"></div>
                        <button onclick="deleteInvoice(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-rose-500/10 text-rose-500 flex items-center gap-2 transition-colors">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                        </button>
                    `)
                }
            ]
        });
    });

    async function regenerateInvoice(id) {
        try {
            const response = await fetch(`/admin/invoices/${id}/regenerate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            if (response.ok) {
                window.toast.success(result.message);
                invoicesTable.loadData();
            } else {
                window.toast.error(result.message || 'Failed to regenerate invoice');
            }
        } catch (error) {
            window.toast.error('Something went wrong');
        }
    }

    function deleteInvoice(id) {
        window.confirmAction({
            title: 'Delete Invoice',
            message: 'Are you sure you want to delete this invoice? The associated PDF will also be removed.',
            onConfirm: async () => {
                try {
                    const response = await fetch(`/admin/invoices/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (response.ok) {
                        window.toast.success(result.message);
                        invoicesTable.loadData();
                    } else {
                        window.toast.error('Failed to delete invoice');
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
