@extends('layouts.admin')

@section('title', 'Manage Subjects')
@section('page_title', 'Subjects')

@section('content')
<div class="space-y-6">
    <x-admin.datatable 
        id="subjects-table"
        title="Subjects"
        description="Manage the curriculum and available doubt solving categories."
    >
        <x-slot name="actions">
            <button onclick="openSubjectModal()" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Add Subject
            </button>
        </x-slot>
        
        <x-slot name="bulkActions">
            <div class="flex items-center gap-2">
                <select id="bulk-status" class="bg-card border border-border text-xs font-medium rounded-md px-2 py-1 outline-none focus:ring-1 focus:ring-ring transition-all w-32">
                    <option value="">Change Status</option>
                    <option value="active">Set Active</option>
                    <option value="disabled">Set Inactive</option>
                </select>
                <button class="text-xs font-semibold text-rose-500 hover:text-rose-600 hover:bg-rose-500/10 px-2 py-1 rounded transition-colors flex items-center" onclick="bulkDelete()">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5 mr-1"></i> Delete Selected
                </button>
            </div>
        </x-slot>

        <x-slot name="filters">
            <div class="flex items-center gap-3">
                <select id="filter-status" name="status" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-32 text-foreground">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="disabled">Inactive</option>
                </select>
                <select id="filter-class" name="class_range_from" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-32 text-foreground">
                    <option value="">All Classes</option>
                    @for($i=1; $i<=8; $i++)
                        <option value="{{ $i }}">Class {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </x-slot>
    </x-admin.datatable>
</div>

<!-- Subject CRUD Modal -->
<div id="subject-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-card w-full max-w-2xl rounded-xl border border-border shadow-2xl animate-in zoom-in-95 duration-200">
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h3 id="modal-subject-title" class="text-lg font-bold text-foreground">Add New Subject</h3>
                <button onclick="closeSubjectModal()" class="text-muted-foreground hover:text-foreground">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="subject-form" class="p-6 space-y-5">
                <input type="hidden" id="subject-id" name="id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-foreground">Subject Name <span class="text-rose-500">*</span></label>
                            <input type="text" id="subject-name" name="name" placeholder="e.g. Mathematics" required class="w-full px-4 py-2 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                        </div>
                        
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-foreground">Short Description <span class="text-rose-500">*</span></label>
                            <textarea id="subject-description" name="description" rows="4" placeholder="Briefly describe what this subject covers..." required class="w-full px-4 py-2 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all"></textarea>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-foreground">From Class <span class="text-rose-500">*</span></label>
                                <select id="subject-class-from" name="class_range_from" required class="w-full px-4 py-2 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                                    @for($i=1; $i<=8; $i++) <option value="{{ $i }}">Class {{ $i }}</option> @endfor
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-foreground">To Class <span class="text-rose-500">*</span></label>
                                <select id="subject-class-to" name="class_range_to" required class="w-full px-4 py-2 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                                    @for($i=1; $i<=8; $i++) <option value="{{ $i }}" {{ $i == 8 ? 'selected' : '' }}>Class {{ $i }}</option> @endfor
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-foreground">Duration (Min) <span class="text-rose-500">*</span></label>
                                <input type="number" id="subject-duration" name="session_duration" value="{{ \App\Models\Setting::get('session_duration', 50) }}" required class="w-full px-4 py-2 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-foreground">Sort Order <span class="text-rose-500">*</span></label>
                                <input type="number" id="subject-sort" name="sort_order" value="0" required class="w-full px-4 py-2 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-foreground">Status <span class="text-rose-500">*</span></label>
                            <select id="subject-status" name="status" required class="w-full px-4 py-2 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                                <option value="active">Active</option>
                                <option value="disabled">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-between border-t border-border mt-6">
                    <button type="button" id="reset-form-btn" class="px-4 py-2 text-sm font-semibold text-muted-foreground hover:text-foreground transition-colors">Reset Form</button>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="closeSubjectModal()" class="px-4 py-2 text-sm font-semibold text-muted-foreground hover:text-foreground transition-colors">Cancel</button>
                        <button type="submit" id="save-subject-btn" class="px-6 py-2 bg-primary text-primary-foreground rounded-lg text-sm font-bold hover:opacity-90 shadow-sm transition-all flex items-center gap-2">
                            <span>Save Subject</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let subjectsTable;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('DOMContentLoaded', () => {
        subjectsTable = new AdminDataTable('subjects-table', {
            url: '{{ route("admin.subjects.list") }}',
            perPage: 10,
            filterSelectors: ['#filter-status', '#filter-class'],
            columns: [
                { key: 'id', title: 'ID', class: 'font-mono text-muted-foreground text-xs w-16' },
                { 
                    key: 'name', 
                    title: 'Subject Name',
                    render: (val) => `<span class="font-bold text-foreground">${val}</span>`
                },
                { 
                    key: 'description', 
                    title: 'Description',
                    class: 'max-w-xs',
                    render: (val) => `<span class="text-muted-foreground text-xs line-clamp-1 block w-48" title="${val || ''}">${val || '-'}</span>`
                },
                { 
                    key: 'class_range_from', 
                    title: 'Class Range',
                    render: (val, row) => `<span class="px-2 py-0.5 rounded bg-muted text-foreground text-xs font-bold">Class ${row.class_range_from} - ${row.class_range_to}</span>`
                },
                { 
                    key: 'session_duration', 
                    title: 'Duration',
                    render: (val) => `<span class="text-xs font-medium text-muted-foreground">${val} Minutes</span>`
                },
                { 
                    key: 'status', 
                    title: 'Status',
                    render: (val) => AdminDataTable.renderBadge(val === 'active' ? 'Active' : 'Inactive', val === 'active' ? 'active' : 'inactive')
                },
                { 
                    key: 'created_at', 
                    title: 'Created Date',
                    render: (val) => `<span class="text-xs text-muted-foreground">${new Date(val).toLocaleDateString()}</span>`
                },
                {
                    key: 'actions',
                    title: 'Actions',
                    sortable: false,
                    searchable: false,
                    class: 'text-center w-32',
                    render: (val, row) => AdminDataTable.renderActions(`
                        <button onclick="viewSubject(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Details
                        </button>
                        <button onclick="openSubjectModal(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit Subject
                        </button>
                        <button onclick="quickToggleStatus(${row.id}, '${row.status === 'active' ? 'disabled' : 'active'}')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Mark as ${row.status === 'active' ? 'Inactive' : 'Active'}
                        </button>
                        <div class="h-px bg-border my-1"></div>
                        <button onclick="deleteSubject(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold text-rose-500 hover:bg-rose-500/10 flex items-center gap-2 transition-colors">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                        </button>
                    `)
                }
            ]
        });

        // Bulk status update listener
        document.getElementById('bulk-status').addEventListener('change', function() {
            const status = this.value;
            if (!status) return;
            
            const ids = subjectsTable.getSelectedIds();
            if (ids.length === 0) {
                window.toast.error('Please select at least one subject.');
                this.value = '';
                return;
            }

            window.confirmAction({
                title: 'Update Status',
                message: `Are you sure you want to mark ${ids.length} selected records as ${status}?`,
                onConfirm: async () => {
                    await updateBulkStatus(ids, status);
                    this.value = '';
                }
            });
        });
        
        // Reset form button
        document.getElementById('reset-form-btn').addEventListener('click', () => {
            subjectForm.reset();
            clearErrors(subjectForm);
        });
    });

    const subjectModal = document.getElementById('subject-modal');
    const subjectForm = document.getElementById('subject-form');

    function openSubjectModal(id = null) {
        const title = document.getElementById('modal-subject-title');
        const submitBtn = document.querySelector('#save-subject-btn span');
        
        subjectForm.reset();
        document.getElementById('subject-id').value = '';
        clearErrors(subjectForm);
        
        if (id) {
            title.textContent = 'Edit Subject';
            submitBtn.textContent = 'Update Subject';
            fetchSubjectDetails(id);
        } else {
            title.textContent = 'Add New Subject';
            submitBtn.textContent = 'Save Subject';
        }
        
        subjectModal.classList.remove('hidden');
    }

    function closeSubjectModal() {
        // Simple unsaved changes check
        const isDirty = Array.from(new FormData(subjectForm).values()).some(val => val !== "" && val !== "active" && val !== "1" && val !== "8" && val !== "50" && val !== "0");
        if (isDirty && !document.getElementById('subject-id').value) {
            if (!confirm('You have unsaved changes. Are you sure you want to close?')) return;
        }
        subjectModal.classList.add('hidden');
    }

    async function fetchSubjectDetails(id) {
        try {
            const response = await fetch(`/admin/subjects/${id}`);
            const result = await response.json();
            if (result.status === 'success') {
                const s = result.data;
                document.getElementById('subject-id').value = s.id;
                document.getElementById('subject-name').value = s.name;
                document.getElementById('subject-description').value = s.description;
                document.getElementById('subject-class-from').value = s.class_range_from;
                document.getElementById('subject-class-to').value = s.class_range_to;
                document.getElementById('subject-duration').value = s.session_duration;
                document.getElementById('subject-sort').value = s.sort_order;
                document.getElementById('subject-status').value = s.status;
            }
        } catch (error) {
            window.toast.error('Failed to fetch subject details');
        }
    }

    subjectForm.onsubmit = async (e) => {
        e.preventDefault();
        const id = document.getElementById('subject-id').value;
        const formData = new FormData(subjectForm);
        const data = Object.fromEntries(formData.entries());
        
        const url = id ? `/admin/subjects/${id}` : '/admin/subjects';
        const method = id ? 'PUT' : 'POST';

        clearErrors(subjectForm);

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
                subjectModal.classList.add('hidden');
                subjectsTable.loadData();
            } else if (response.status === 422) {
                if (result.errors) {
                    showErrors(subjectForm, result.errors);
                } else {
                    window.toast.error(result.message);
                }
            }
        } catch (error) {
            window.toast.error('Something went wrong. Please try again.');
        }
    };

    function showErrors(form, errors) {
        Object.keys(errors).forEach(field => {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('border-rose-500', 'ring-rose-500/20');
                const errorMsg = document.createElement('p');
                errorMsg.className = 'validation-error text-[11px] font-bold text-rose-500 mt-1 animate-in fade-in slide-in-from-top-1';
                errorMsg.textContent = errors[field][0];
                input.parentElement.appendChild(errorMsg);
            }
        });
    }

    function clearErrors(form) {
        form.querySelectorAll('.validation-error').forEach(el => el.remove());
        form.querySelectorAll('input, select, textarea').forEach(el => {
            el.classList.remove('border-rose-500', 'ring-rose-500/20');
        });
    }

    function deleteSubject(id) {
        window.confirmAction({
            title: 'Delete Subject',
            message: 'Are you sure you want to delete this subject? This action cannot be undone.',
            onConfirm: async () => {
                try {
                    const response = await fetch(`/admin/subjects/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        window.toast.success(result.message);
                        subjectsTable.loadData();
                    } else {
                        window.toast.error('Failed to delete subject');
                    }
                } catch (error) {
                    window.toast.error('Something went wrong');
                }
            }
        });
    }

    async function quickToggleStatus(id, newStatus) {
        await updateBulkStatus([id], newStatus);
    }

    async function updateBulkStatus(ids, status) {
        try {
            const response = await fetch('/admin/subjects/change-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids, status })
            });

            const result = await response.json();
            if (response.ok) {
                window.toast.success(result.message);
                subjectsTable.loadData();
            } else {
                window.toast.error(result.message || 'Failed to update status.');
            }
        } catch (error) {
            window.toast.error('Something went wrong.');
        }
    }

    function viewSubject(id) {
        // For now, view detail just opens the edit modal as a read-only or similar
        openSubjectModal(id);
    }

    function bulkDelete() {
        const ids = subjectsTable.getSelectedIds();
        if (ids.length === 0) {
            window.toast.error('Please select at least one record.');
            return;
        }
        
        window.confirmAction({
            title: 'Bulk Delete',
            message: `Are you sure you want to delete ${ids.length} selected subjects?`,
            onConfirm: async () => {
                try {
                    const response = await fetch('/admin/subjects/bulk-delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ ids })
                    });

                    const result = await response.json();
                    if (response.ok) {
                        window.toast.success(result.message);
                        subjectsTable.loadData();
                    } else {
                        window.toast.error(result.message || 'Failed to delete subjects.');
                    }
                } catch (error) {
                    window.toast.error('Something went wrong.');
                }
            }
        });
    }
</script>
@endpush
@endsection
