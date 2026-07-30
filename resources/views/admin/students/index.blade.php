@extends('layouts.admin')

@section('title', 'All Students')
@section('page_title', 'Students')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Student Directory</h2>
            <p class="text-sm text-muted-foreground mt-1">View and manage registered students and their access.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative group">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors"></i>
                <input type="text" id="student-search" placeholder="Search students..." class="h-10 w-64 pl-10 pr-4 bg-card border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all shadow-sm">
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-muted/30 border-b border-border">
                        <th class="px-6 py-4 text-[11px] font-bold text-muted-foreground uppercase tracking-widest">Student</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-muted-foreground uppercase tracking-widest">Class</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-muted-foreground uppercase tracking-widest">Contact</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-muted-foreground uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-muted-foreground uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="students-table-body" class="divide-y divide-border">
                    <!-- Loading State -->
                    <tr>
                        <td colspan="5" class="py-12 text-center text-muted-foreground">
                            <div class="flex flex-col items-center gap-2">
                                <div class="animate-spin">
                                    <i data-lucide="loader-2" class="w-6 h-6"></i>
                                </div>
                                <span class="text-sm font-medium">Loading student records...</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Empty State (Hidden by default) -->
        <div id="empty-state" class="hidden py-12 flex flex-col items-center justify-center text-muted-foreground">
            <div class="w-16 h-16 rounded-full bg-muted flex items-center justify-center mb-4">
                <i data-lucide="users" class="w-8 h-8 opacity-20"></i>
            </div>
            <p class="font-medium">No students found matching your criteria.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let allStudents = [];
    const tableBody = document.getElementById('students-table-body');
    const searchInput = document.getElementById('student-search');
    const emptyState = document.getElementById('empty-state');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('DOMContentLoaded', fetchStudents);

    const routes = {
        studentShow: (id) => `{{ route('admin.students.show', ':id') }}`.replace(':id', id),
    };

    async function fetchStudents() {
        try {
            const response = await fetch('{{ route("admin.students.list") }}');
            const result = await response.json();
            if (result.status === 'success') {
                allStudents = result.data;
                renderStudents(allStudents);
            }
        } catch (error) {
            window.toast.error('Failed to load students');
        }
    }

    function renderStudents(students) {
        if (students.length === 0) {
            tableBody.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        tableBody.innerHTML = students.map(student => `
            <tr class="hover:bg-muted/20 transition-colors group">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs border border-primary/20">
                            ${student.name.split(' ').map(n => n[0]).join('').toUpperCase()}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-foreground">${student.name}</p>
                            <p class="text-[11px] text-muted-foreground">${student.email}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-xs font-semibold px-2.5 py-1 bg-muted rounded-md text-foreground">Year ${student.student_class || 'N/A'}</span>
                </td>
                <td class="px-6 py-4">
                    <p class="text-xs font-medium text-foreground">${student.mobile_number || 'No Phone'}</p>
                    <p class="text-[10px] text-muted-foreground">Parent: ${student.parent_name || 'N/A'}</p>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center">
                        <button onclick="toggleStudentStatus(${student.id})" class="px-2.5 py-1 text-[10px] font-bold rounded-full uppercase border transition-all ${
                            student.is_active 
                            ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20 hover:bg-emerald-500/20' 
                            : 'bg-rose-500/10 text-rose-500 border-rose-500/20 hover:bg-rose-500/20'
                        }">
                            ${student.is_active ? 'Active' : 'Inactive'}
                        </button>
                    </div>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="${routes.studentShow(student.id)}" class="p-2 rounded-lg hover:bg-accent text-muted-foreground hover:text-foreground transition-colors shadow-sm border border-transparent hover:border-border">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </a>
                        <button onclick="deleteStudent(${student.id})" class="p-2 rounded-lg hover:bg-rose-500/10 text-muted-foreground hover:text-rose-500 transition-colors shadow-sm border border-transparent hover:border-rose-500/20">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
        
        lucide.createIcons();
    }

    // Search Logic
    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        const filtered = allStudents.filter(s => 
            s.name.toLowerCase().includes(query) || 
            s.email.toLowerCase().includes(query) ||
            (s.student_class && s.student_class.toLowerCase().includes(query))
        );
        renderStudents(filtered);
    });

    async function toggleStudentStatus(id) {
        try {
            const response = await fetch(`/admin/students/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const result = await response.json();
            if (response.ok) {
                window.toast.success(result.message);
                fetchStudents();
            }
        } catch (error) {
            window.toast.error('Failed to update status');
        }
    }

    function deleteStudent(id) {
        window.confirmAction({
            title: 'Delete Student Account',
            message: 'Are you sure you want to permanently delete this student account? This will remove all their records.',
            onConfirm: async () => {
                try {
                    const response = await fetch(`/admin/students/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (response.ok) {
                        window.toast.success(result.message);
                        fetchStudents();
                    } else {
                        window.toast.error('Failed to delete student');
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

