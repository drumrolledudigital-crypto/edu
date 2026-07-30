@extends('layouts.admin')

@section('title', 'Staff Management')
@section('page_title', 'Internal Staff')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Staff & User Management</h2>
            <p class="text-sm text-muted-foreground mt-1">Manage administrative users and assign their functional roles.</p>
        </div>
        <button onclick="openCreateModal()" class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-bold text-primary-foreground shadow-sm hover:opacity-90 transition-all gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Staff Member
        </button>
    </div>

    <x-admin.datatable 
        id="staff-table"
        title="Administrative Staff"
        description="Internal users with access to the admin panel."
    >
    </x-admin.datatable>
</div>

<!-- Staff Modal -->
<div id="staff-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-card w-full max-w-lg rounded-xl border border-border shadow-2xl animate-in zoom-in-95 duration-200">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <h3 id="staff-modal-title" class="text-lg font-bold text-foreground">Add New Staff Member</h3>
                <button onclick="closeModal()" class="text-muted-foreground hover:text-foreground">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <form id="staff-form" class="p-6 space-y-5">
                <input type="hidden" id="staff-id">
                
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground block">Full Name</label>
                    <input type="text" name="name" id="staff-name" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none" placeholder="John Doe" required>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground block">Email Address</label>
                    <input type="email" name="email" id="staff-email" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none" placeholder="john@example.com" required>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground block">Password <span id="pwd-hint" class="text-[10px] lowercase italic font-normal text-muted-foreground hidden">(Leave blank to keep current)</span></label>
                    <input type="password" name="password" id="staff-password" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none" placeholder="••••••••">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground block">Assign Role</label>
                        <select name="role_id" id="staff-role" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none" required>
                            <option value="">Select a role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground block">Status</label>
                        <select name="is_active" id="staff-status" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-border mt-6">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-semibold text-muted-foreground hover:text-foreground transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-primary-foreground text-sm font-bold rounded-lg hover:opacity-90 shadow-sm transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span id="staff-btn-save-text">Save Member</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let staffTable;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('DOMContentLoaded', () => {
        staffTable = new AdminDataTable('staff-table', {
            url: '{{ route("admin.staff.list") }}',
            perPage: 10,
            columns: [
                { 
                    key: 'name', 
                    title: 'User Details',
                    render: (val, row) => `
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold shrink-0">
                                ${val.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <p class="font-bold text-foreground text-xs leading-none">${val}</p>
                                <p class="text-[10px] text-muted-foreground mt-1.5">${row.email}</p>
                            </div>
                        </div>
                    `
                },
                { 
                    key: 'roles', 
                    title: 'Current Role',
                    render: (val) => {
                        if (!val || val.length === 0) return '<span class="text-[10px] text-muted-foreground italic">No Role</span>';
                        const role = val[0];
                        const colorClass = role.slug === 'super-admin' ? 'bg-rose-500/10 text-rose-500 border-rose-500/20' : 'bg-primary/10 text-primary border-primary/20';
                        return `<span class="px-2 py-0.5 rounded-full border ${colorClass} text-[9px] font-black uppercase tracking-wider">${role.name}</span>`;
                    }
                },
                { 
                    key: 'is_active', 
                    title: 'Status',
                    render: (val) => AdminDataTable.renderBadge(val ? 'active' : 'disabled', val ? 'Active' : 'Disabled')
                },
                { 
                    key: 'created_at', 
                    title: 'Joined',
                    render: (val) => `<span class="text-[10px] text-muted-foreground">${new Date(val).toLocaleDateString()}</span>`
                },
                {
                    key: 'actions',
                    title: 'Actions',
                    sortable: false,
                    searchable: false,
                    class: 'text-center w-24',
                    render: (val, row) => row.roles.some(r => r.slug === 'super-admin') ? '<span class="text-[10px] text-muted-foreground font-bold italic">Protected</span>' : AdminDataTable.renderActions(`
                        <button onclick="openEditModal(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit Member
                        </button>
                        <div class="h-px bg-border my-1"></div>
                        <button onclick="deleteStaff(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-rose-500/10 text-rose-500 flex items-center gap-2 transition-colors">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete Member
                        </button>
                    `)
                }
            ]
        });

        document.getElementById('staff-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('staff-id').value;
            const url = id ? `/admin/staff/${id}` : '/admin/staff';
            const method = id ? 'PUT' : 'POST';
            
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            if (id) {
                data._method = 'PUT';
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (response.ok) {
                    window.toast.success(result.message);
                    closeModal();
                    staffTable.loadData();
                } else {
                    window.toast.error(result.message || 'Failed to save staff member');
                }
            } catch (error) {
                window.toast.error('Something went wrong');
            }
        });
    });

    function openCreateModal() {
        document.getElementById('staff-modal-title').textContent = 'Add New Staff Member';
        document.getElementById('staff-btn-save-text').textContent = 'Save Member';
        document.getElementById('pwd-hint').classList.add('hidden');
        document.getElementById('staff-password').required = true;
        document.getElementById('staff-id').value = '';
        document.getElementById('staff-form').reset();
        document.getElementById('staff-modal').classList.remove('hidden');
        lucide.createIcons();
    }

    async function openEditModal(id) {
        try {
            const response = await fetch(`/admin/staff/${id}`);
            const result = await response.json();
            if (response.ok) {
                const user = result.data;
                document.getElementById('staff-modal-title').textContent = 'Edit Staff: ' + user.name;
                document.getElementById('staff-btn-save-text').textContent = 'Update Member';
                document.getElementById('pwd-hint').classList.remove('hidden');
                document.getElementById('staff-password').required = false;
                document.getElementById('staff-id').value = user.id;
                document.getElementById('staff-name').value = user.name;
                document.getElementById('staff-email').value = user.email;
                document.getElementById('staff-status').value = user.is_active ? '1' : '0';
                
                if (user.roles && user.roles.length > 0) {
                    document.getElementById('staff-role').value = user.roles[0].id;
                }

                document.getElementById('staff-modal').classList.remove('hidden');
                lucide.createIcons();
            }
        } catch (error) {
            window.toast.error('Failed to load user data');
        }
    }

    function closeModal() {
        document.getElementById('staff-modal').classList.add('hidden');
    }

    function deleteStaff(id) {
        window.confirmAction({
            title: 'Remove Staff',
            message: 'Are you sure you want to remove this staff member? They will lose all admin access.',
            onConfirm: async () => {
                try {
                    const response = await fetch(`/admin/staff/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (response.ok) {
                        window.toast.success(result.message);
                        staffTable.loadData();
                    } else {
                        window.toast.error(result.message || 'Failed to remove member');
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
