@extends('layouts.admin')

@section('title', 'Role Management')
@section('page_title', 'Roles & Permissions')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Roles & Access Control</h2>
            <p class="text-sm text-muted-foreground mt-1">Manage system roles and assign granular permissions.</p>
        </div>
        <button onclick="openCreateModal()" class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-bold text-primary-foreground shadow-sm hover:opacity-90 transition-all gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Create New Role
        </button>
    </div>

    <x-admin.datatable 
        id="roles-table"
        title="Existing Roles"
        description="List of all system roles and their reach."
    >
    </x-admin.datatable>
</div>

<!-- Role Modal -->
<div id="role-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-card w-full max-w-4xl rounded-xl border border-border shadow-2xl animate-in zoom-in-95 duration-200 flex flex-col max-h-[90vh]">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <h3 id="role-modal-title" class="text-lg font-bold text-foreground">Create New Role</h3>
                <button onclick="closeModal()" class="text-muted-foreground hover:text-foreground">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <form id="role-form" class="overflow-hidden flex flex-col">
                <input type="hidden" id="role-id">
                <div class="p-6 space-y-6 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground block">Role Name</label>
                            <input type="text" id="role-name" name="name" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none" placeholder="e.g. Content Manager" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground block">Description</label>
                            <input type="text" id="role-description" name="description" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none" placeholder="Short summary of this role's purpose">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-black text-secondary uppercase tracking-tight">Permissions Matrix</h4>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="select-all-permissions" class="w-4 h-4 rounded text-primary focus:ring-primary border-border bg-background">
                                <span class="text-xs font-bold text-muted-foreground">Select All</span>
                            </label>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($permissions as $group => $groupPermissions)
                            <div class="p-4 bg-muted/30 border border-border rounded-xl space-y-3">
                                <h5 class="text-[10px] font-black text-primary uppercase tracking-widest">{{ $group }}</h5>
                                <div class="space-y-2">
                                    @foreach($groupPermissions as $permission)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="permission-checkbox w-4 h-4 rounded text-primary focus:ring-primary border-border bg-background transition-all">
                                        <span class="text-xs font-medium text-foreground group-hover:text-primary transition-colors">{{ $permission->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 p-4 border-t border-border bg-muted/30 rounded-b-xl">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-semibold text-muted-foreground hover:text-foreground transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-primary-foreground text-sm font-bold rounded-lg hover:opacity-90 shadow-sm transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span id="role-btn-save-text">Save Role</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let rolesTable;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('DOMContentLoaded', () => {
        rolesTable = new AdminDataTable('roles-table', {
            url: '{{ route("admin.roles.list") }}',
            perPage: 10,
            columns: [
                { 
                    key: 'name', 
                    title: 'Role Name',
                    render: (val, row) => `
                        <div>
                            <p class="font-bold text-foreground text-sm leading-none">${val}</p>
                            <p class="text-[10px] text-muted-foreground mt-1.5">${row.description || 'No description'}</p>
                        </div>
                    `
                },
                { 
                    key: 'slug', 
                    title: 'Slug',
                    render: (val) => `<code class="text-[10px] bg-muted px-1.5 py-0.5 rounded border border-border">${val}</code>`
                },
                { 
                    key: 'permissions_count', 
                    title: 'Permissions',
                    render: (val) => `<span class="px-2 py-0.5 rounded bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-wider">${val} Assigned</span>`
                },
                { 
                    key: 'users_count', 
                    title: 'Staff Members',
                    render: (val) => `<span class="px-2 py-0.5 rounded bg-muted text-foreground text-[10px] font-bold uppercase tracking-wider">${val} Users</span>`
                },
                {
                    key: 'actions',
                    title: 'Actions',
                    sortable: false,
                    searchable: false,
                    class: 'text-center w-24',
                    render: (val, row) => row.slug === 'super-admin' ? '<span class="text-[10px] text-muted-foreground font-bold italic">Protected</span>' : AdminDataTable.renderActions(`
                        <button onclick="openEditModal(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit Role
                        </button>
                        <div class="h-px bg-border my-1"></div>
                        <button onclick="deleteRole(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-rose-500/10 text-rose-500 flex items-center gap-2 transition-colors">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete Role
                        </button>
                    `)
                }
            ]
        });

        document.getElementById('role-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('role-id').value;
            const url = id ? `/admin/roles/${id}` : '/admin/roles';
            const method = id ? 'PUT' : 'POST';
            
            const formData = new FormData(e.target);
            const data = {
                name: formData.get('name'),
                description: formData.get('description'),
                permissions: Array.from(formData.getAll('permissions[]'))
            };

            try {
                const response = await fetch(url, {
                    method: method,
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
                    rolesTable.loadData();
                } else {
                    window.toast.error(result.message || 'Failed to save role');
                }
            } catch (error) {
                window.toast.error('Something went wrong');
            }
        });

        document.getElementById('select-all-permissions').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    });

    function openCreateModal() {
        document.getElementById('role-modal-title').textContent = 'Create New Role';
        document.getElementById('role-btn-save-text').textContent = 'Save Role';
        document.getElementById('role-id').value = '';
        document.getElementById('role-form').reset();
        document.getElementById('role-modal').classList.remove('hidden');
        lucide.createIcons();
    }

    async function openEditModal(id) {
        try {
            const response = await fetch(`/admin/roles/${id}`);
            const result = await response.json();
            if (response.ok) {
                const role = result.data;
                document.getElementById('role-modal-title').textContent = 'Edit Role: ' + role.name;
                document.getElementById('role-btn-save-text').textContent = 'Update Role';
                document.getElementById('role-id').value = role.id;
                document.getElementById('role-name').value = role.name;
                document.getElementById('role-description').value = role.description;
                
                // Reset and set permissions
                const checkboxes = document.querySelectorAll('.permission-checkbox');
                checkboxes.forEach(cb => cb.checked = false);
                
                const permissionIds = role.permissions.map(p => p.id.toString());
                checkboxes.forEach(cb => {
                    if (permissionIds.includes(cb.value)) cb.checked = true;
                });

                document.getElementById('role-modal').classList.remove('hidden');
                lucide.createIcons();
            }
        } catch (error) {
            window.toast.error('Failed to load role data');
        }
    }

    function closeModal() {
        document.getElementById('role-modal').classList.add('hidden');
    }

    function deleteRole(id) {
        window.confirmAction({
            title: 'Delete Role',
            message: 'Are you sure you want to permanently delete this role? This action cannot be undone.',
            onConfirm: async () => {
                try {
                    const response = await fetch(`/admin/roles/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (response.ok) {
                        window.toast.success(result.message);
                        rolesTable.loadData();
                    } else {
                        window.toast.error(result.message || 'Failed to delete role');
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
