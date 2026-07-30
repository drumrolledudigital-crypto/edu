@extends('layouts.admin')

@section('title', 'Manage Doubts')
@section('page_title', 'Doubt Management')

@section('content')
<div class="space-y-6">
    <x-admin.datatable 
        id="doubts-table"
        title="Student Doubts"
        description="Review and manage academic questions submitted by students."
    >
        <x-slot name="filters">
            <div class="flex items-center gap-3">
                <select id="filter-status" name="status" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-36 text-foreground">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="accepted">Accepted</option>
                    <option value="resolved">Resolved</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <select id="filter-subject" name="subject_id" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-40 text-foreground">
                    <option value="">All Subjects</option>
                    @foreach(\App\Models\Subject::all() as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
        </x-slot>
    </x-admin.datatable>
</div>

@push('scripts')
<script>
    let doubtsTable;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const routes = {
        doubtShow: (id) => '{{ route("admin.doubts.show", ":id") }}'.replace(':id', id),
    };

    document.addEventListener('DOMContentLoaded', () => {
        doubtsTable = new AdminDataTable('doubts-table', {
            url: '{{ route("admin.doubts.list") }}',
            perPage: 10,
            filterSelectors: ['#filter-status', '#filter-subject'],
            columns: [
                { 
                    key: 'student', 
                    title: 'Student',
                    render: (val) => `
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold border border-primary/10">
                                ${val.name.charAt(0)}
                            </div>
                            <div>
                                <p class="font-bold text-foreground text-xs leading-none">${val.name}</p>
                                <p class="text-[10px] text-muted-foreground mt-1">${val.student_class || 'No Year'}${val.roll_number ? ' &middot; ' + val.roll_number : ''}</p>
                            </div>
                        </div>
                    `
                },
                { 
                    key: 'subject', 
                    title: 'Subject',
                    render: (val) => `<span class="px-2 py-0.5 rounded bg-muted text-foreground text-[10px] font-bold uppercase tracking-wider">${val.name}</span>`
                },
                {
                    key: 'title',
                    title: 'Title & Topic',
                    render: (val, row) => {
                        const titles = Array.isArray(val) ? val : [val];
                        const display = titles[0] || '';
                        const extra = titles.length > 1 ? ` <span class="text-muted-foreground font-normal">+${titles.length - 1} more</span>` : '';
                        return `
                            <div>
                                <p class="font-semibold text-foreground text-xs line-clamp-1" title="${titles.join(', ')}">${display}${extra}</p>
                                <p class="text-[10px] text-muted-foreground mt-0.5">${row.topic_name}</p>
                            </div>
                        `;
                    }
                },
                { 
                    key: 'status', 
                    title: 'Status',
                    render: (val) => AdminDataTable.renderBadge(val, val)
                },
                { 
                    key: 'created_at', 
                    title: 'Submitted',
                    render: (val) => `<span class="text-xs text-muted-foreground">${new Date(val).toLocaleDateString()}</span>`
                },
                {
                    key: 'actions',
                    title: 'Actions',
                    sortable: false,
                    searchable: false,
                    class: 'text-center',
                    render: (val, row) => {
                        const dropdown = AdminDataTable.renderActions(`
                            <button onclick="updateDoubtStatus(${row.id}, 'accepted')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Accept Doubt
                            </button>
                            <button onclick="updateDoubtStatus(${row.id}, 'resolved')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                                <i data-lucide="award" class="w-3.5 h-3.5"></i> Mark Resolved
                            </button>
                            <div class="h-px bg-border my-1"></div>
                            <button onclick="deleteDoubt(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold text-rose-500 hover:bg-rose-500/10 flex items-center gap-2 transition-colors">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                            </button>
                        `);
                        return \`
                            <div class="flex items-center gap-2 justify-center">
                                <a href="\${routes.doubtShow(row.id)}" class="w-8 h-8 rounded-lg bg-primary/10 text-primary hover:bg-primary/20 flex items-center justify-center transition-all" title="View Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                \${dropdown}
                            </div>
                        \`;
                    }
                }
                        <button onclick="updateDoubtStatus(${row.id}, 'accepted')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Accept Doubt
                        </button>
                        <button onclick="updateDoubtStatus(${row.id}, 'resolved')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="award" class="w-3.5 h-3.5"></i> Mark Resolved
                        </button>
                        <div class="h-px bg-border my-1"></div>
                        <button onclick="deleteDoubt(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold text-rose-500 hover:bg-rose-500/10 flex items-center gap-2 transition-colors">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                        </button>
                    `)
                }
            ]
        });
    });

    async function updateDoubtStatus(id, status) {
        try {
            const response = await fetch(`/admin/doubts/${id}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status })
            });

            const result = await response.json();
            if (response.ok) {
                window.toast.success(result.message);
                doubtsTable.loadData();
            } else {
                window.toast.error(result.message || 'Failed to update status.');
            }
        } catch (error) {
            window.toast.error('Something went wrong.');
        }
    }

    function deleteDoubt(id) {
        window.confirmAction({
            title: 'Delete Doubt',
            message: 'Are you sure you want to delete this doubt record? This action cannot be undone.',
            onConfirm: async () => {
                try {
                    const response = await fetch(`/admin/doubts/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (response.ok) {
                        window.toast.success(result.message);
                        doubtsTable.loadData();
                    } else {
                        window.toast.error('Failed to delete doubt');
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
