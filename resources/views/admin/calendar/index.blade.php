@extends('layouts.admin')

@section('title', 'Manage Slots')
@section('page_title', 'Session Calendar')

@section('content')
<div class="space-y-6">
    <x-admin.datatable 
        id="slots-table"
        title="Availability Slots"
        description="Define and manage your teaching availability slots."
    >
        <x-slot name="actions">
            <button onclick="openSlotModal()" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Add New Slot
            </button>
        </x-slot>
        
        <x-slot name="bulkActions">
            <div class="flex items-center gap-2">
                <select id="bulk-slot-status" class="bg-card border border-border text-xs font-medium rounded-md px-2 py-1 outline-none focus:ring-1 focus:ring-ring transition-all w-32">
                    <option value="">Set Status</option>
                    <option value="available">Available</option>
                    <option value="inactive">Inactive</option>
                </select>
                <button class="text-xs font-semibold text-rose-500 hover:text-rose-600 hover:bg-rose-500/10 px-2 py-1 rounded transition-colors flex items-center" onclick="bulkDeleteSlots()">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5 mr-1"></i> Delete Selected
                </button>
            </div>
        </x-slot>

        <x-slot name="filters">
            <select id="filter-slot-status" name="status" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-36 text-foreground">
                <option value="">All Statuses</option>
                <option value="available">Available</option>
                <option value="booked">Booked</option>
                <option value="inactive">Inactive</option>
            </select>
        </x-slot>
    </x-admin.datatable>
</div>

<!-- Slot CRUD Modal -->
<div id="slot-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-card w-full max-w-md rounded-xl border border-border shadow-2xl animate-in zoom-in-95 duration-200">
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h3 id="modal-slot-title" class="text-lg font-bold text-foreground">Add Availability Slot</h3>
                <button onclick="closeSlotModal()" class="text-muted-foreground hover:text-foreground">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="slot-form" class="p-6 space-y-4">
                <input type="hidden" id="slot-id" name="id">
                
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-foreground">Date</label>
                    <input type="date" id="slot-date" name="date" required class="w-full px-4 py-2 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-foreground">Start Time</label>
                        <input type="time" id="slot-start-time" name="start_time" required class="w-full px-4 py-2 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-foreground">End Time</label>
                        <input type="time" id="slot-end-time" name="end_time" required class="w-full px-4 py-2 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-sm font-semibold text-foreground">Status</label>
                    <select id="slot-status" name="status" class="w-full px-4 py-2 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                        <option value="available">Available</option>
                        <option value="booked">Booked (View Only)</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-border mt-6">
                    <button type="button" onclick="closeSlotModal()" class="px-4 py-2 text-sm font-semibold text-muted-foreground hover:text-foreground transition-colors">Cancel</button>
                    <button type="submit" id="save-slot-btn" class="px-6 py-2 bg-primary text-primary-foreground rounded-lg text-sm font-bold hover:opacity-90 shadow-sm transition-all">
                        <span>Save Slot</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let slotsTable;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('DOMContentLoaded', () => {
        slotsTable = new AdminDataTable('slots-table', {
            url: '{{ route("admin.slots.list") }}',
            perPage: 10,
            filterSelectors: ['#filter-slot-status'],
            columns: [
                { 
                    key: 'date', 
                    title: 'Date',
                    render: (val) => `<p class="text-sm font-bold text-foreground">${new Date(val).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}</p>`
                },
                { 
                    key: 'start_time', 
                    title: 'Time Range',
                    render: (val, row) => `
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                            <span class="font-semibold text-foreground">${val.substring(0, 5)} - ${row.end_time.substring(0, 5)}</span>
                        </div>
                    `
                },
                { 
                    key: 'status', 
                    title: 'Status',
                    render: (val) => AdminDataTable.renderBadge(val, val)
                },
                { 
                    key: 'created_at', 
                    title: 'Created At',
                    render: (val) => `<span class="text-xs text-muted-foreground">${new Date(val).toLocaleDateString()}</span>`
                },
                {
                    key: 'actions',
                    title: 'Actions',
                    sortable: false,
                    searchable: false,
                    class: 'text-center w-32',
                    render: (val, row) => AdminDataTable.renderActions(`
                        <button onclick="openSlotModal(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit Slot
                        </button>
                        <div class="h-px bg-border my-1"></div>
                        <button onclick="deleteSlot(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold text-rose-500 hover:bg-rose-500/10 flex items-center gap-2 transition-colors">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                        </button>
                    `)
                }
            ]
        });

        // Bulk status update
        document.getElementById('bulk-slot-status').addEventListener('change', function() {
            const status = this.value;
            if (!status) return;
            const ids = slotsTable.getSelectedIds();
            if (ids.length === 0) { window.toast.error('Select slots first'); this.value = ''; return; }

            window.confirmAction({
                title: 'Update Slots',
                message: `Mark ${ids.length} selected slots as ${status}?`,
                onConfirm: async () => {
                    await updateBulkSlotStatus(ids, status);
                    this.value = '';
                }
            });
        });
    });

    const slotModal = document.getElementById('slot-modal');
    const slotForm = document.getElementById('slot-form');

    function openSlotModal(id = null) {
        slotForm.reset();
        document.getElementById('slot-id').value = '';
        const title = document.getElementById('modal-slot-title');
        const submitBtn = document.querySelector('#save-slot-btn span');
        if (id) {
            title.textContent = 'Edit Slot';
            submitBtn.textContent = 'Update Slot';
            fetchSlotDetails(id);
        } else {
            title.textContent = 'Add Availability Slot';
            submitBtn.textContent = 'Save Slot';
        }
        slotModal.classList.remove('hidden');
    }

    function closeSlotModal() { slotModal.classList.add('hidden'); }

    async function fetchSlotDetails(id) {
        try {
            const response = await fetch(`/admin/slots/${id}`);
            const result = await response.json();
            if (result.status === 'success') {
                const s = result.data;
                document.getElementById('slot-id').value = s.id;
                document.getElementById('slot-date').value = s.date;
                document.getElementById('slot-start-time').value = s.start_time;
                document.getElementById('slot-end-time').value = s.end_time;
                document.getElementById('slot-status').value = s.status;
            }
        } catch (error) { window.toast.error('Failed to fetch slot details'); }
    }

    slotForm.onsubmit = async (e) => {
        e.preventDefault();
        const id = document.getElementById('slot-id').value;
        const data = Object.fromEntries(new FormData(slotForm).entries());
        const url = id ? `/admin/slots/${id}` : '/admin/slots';
        const method = id ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, { method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify(data) });
            const result = await response.json();
            if (response.ok) {
                window.toast.success(result.message);
                closeSlotModal();
                slotsTable.loadData();
            } else if (response.status === 422) {
                window.toast.error(result.message || 'Validation failed');
            }
        } catch (error) { window.toast.error('Something went wrong'); }
    };

    function deleteSlot(id) {
        window.confirmAction({
            title: 'Remove Slot',
            message: 'Are you sure you want to remove this availability slot?',
            onConfirm: async () => {
                try {
                    const response = await fetch(`/admin/slots/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
                    const result = await response.json();
                    if (response.ok) { window.toast.success(result.message); slotsTable.loadData(); }
                    else { window.toast.error(result.message || 'Failed to delete'); }
                } catch (error) { window.toast.error('Something went wrong'); }
            }
        });
    }

    async function updateBulkSlotStatus(ids, status) {
        try {
            const response = await fetch('/admin/slots/change-status', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ ids, status }) });
            const result = await response.json();
            if (response.ok) { window.toast.success(result.message); slotsTable.loadData(); }
            else { window.toast.error(result.message || 'Failed to update'); }
        } catch (error) { window.toast.error('Something went wrong'); }
    }

    function bulkDeleteSlots() {
        const ids = slotsTable.getSelectedIds();
        if (ids.length === 0) return;
        window.confirmAction({
            title: 'Bulk Delete',
            message: `Remove ${ids.length} selected slots?`,
            onConfirm: async () => {
                try {
                    const response = await fetch('/admin/slots/bulk-delete', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ ids }) });
                    const result = await response.json();
                    if (response.ok) { window.toast.success(result.message); slotsTable.loadData(); }
                    else { window.toast.error(result.message || 'Failed to delete'); }
                } catch (error) { window.toast.error('Something went wrong'); }
            }
        });
    }
</script>
@endpush
@endsection
