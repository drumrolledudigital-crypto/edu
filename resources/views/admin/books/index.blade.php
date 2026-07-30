@extends('layouts.admin')

@section('title', 'Manage Books')
@section('page_title', 'Books')

@section('content')
<div class="space-y-6">
    <x-admin.datatable
        id="books-table"
        title="Books"
        description="Manage the book library for students."
    >
        <x-slot name="actions">
            <button onclick="openBookModal()" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Add Book
            </button>
        </x-slot>

        <x-slot name="bulkActions">
            <div class="flex items-center gap-2">
                <select id="bulk-status" class="bg-card border border-border text-xs font-medium rounded-md px-2 py-1 outline-none focus:ring-1 focus:ring-ring transition-all w-32">
                    <option value="">Change Status</option>
                    <option value="active">Set Active</option>
                    <option value="inactive">Set Inactive</option>
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
                    <option value="inactive">Inactive</option>
                </select>
                <select id="filter-subject" name="subject_id" class="bg-card border border-border text-sm font-medium rounded-lg px-3 py-1.5 outline-none focus:ring-1 focus:ring-ring transition-all w-40 text-foreground">
                    <option value="">All Subjects</option>
                    @foreach(\App\Models\Subject::orderBy('name')->get() as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
        </x-slot>
    </x-admin.datatable>
</div>

<!-- Book CRUD Modal -->
<div id="book-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-card w-full max-w-2xl rounded-xl border border-border shadow-2xl animate-in zoom-in-95 duration-200">
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h3 id="modal-book-title" class="text-lg font-bold text-foreground">Add New Book</h3>
                <button onclick="closeBookModal()" class="text-muted-foreground hover:text-foreground">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="book-form" class="p-6 space-y-5" enctype="multipart/form-data">
                <input type="hidden" id="book-id" name="id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-foreground">Book Title <span class="text-rose-500">*</span></label>
                            <input type="text" id="book-title" name="title" placeholder="e.g. Introduction to Mathematics" required class="w-full px-4 py-2 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-foreground">Subject <span class="text-rose-500">*</span></label>
                            <select id="book-subject" name="subject_id" required class="w-full px-4 py-2 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                                <option value="">Select Subject</option>
                                @foreach(\App\Models\Subject::orderBy('name')->get() as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-foreground">Short Description</label>
                            <textarea id="book-description" name="short_description" rows="4" placeholder="Briefly describe this book..." class="w-full px-4 py-2 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all"></textarea>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-foreground">Status <span class="text-rose-500">*</span></label>
                            <select id="book-status" name="status" required class="w-full px-4 py-2 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-foreground">Cover Image</label>
                            <div class="relative">
                                <input type="file" id="book-cover" name="cover_image" accept="image/*" class="hidden" onchange="previewCover(this)">
                                <label for="book-cover" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-border rounded-lg cursor-pointer hover:border-primary/50 transition-colors bg-muted/10">
                                    <div id="cover-preview-empty" class="flex flex-col items-center justify-center">
                                        <i data-lucide="image" class="w-8 h-8 text-muted-foreground/50 mb-2"></i>
                                        <span class="text-xs text-muted-foreground">Click to upload cover</span>
                                        <span class="text-[10px] text-muted-foreground/60 mt-1">JPG, PNG up to 5MB</span>
                                    </div>
                                    <div id="cover-preview" class="hidden w-full h-full p-1">
                                        <img id="cover-preview-img" class="w-full h-full object-contain rounded" alt="Preview">
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-foreground">PDF File</label>
                            <div class="relative">
                                <input type="file" id="book-pdf" name="pdf_file" accept=".pdf" class="hidden" onchange="previewPdf(this)">
                                <label for="book-pdf" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-border rounded-lg cursor-pointer hover:border-primary/50 transition-colors bg-muted/10">
                                    <div id="pdf-preview-empty" class="flex flex-col items-center justify-center">
                                        <i data-lucide="file-text" class="w-8 h-8 text-muted-foreground/50 mb-2"></i>
                                        <span class="text-xs text-muted-foreground">Click to upload PDF</span>
                                        <span class="text-[10px] text-muted-foreground/60 mt-1">PDF up to 20MB</span>
                                    </div>
                                    <div id="pdf-preview" class="hidden flex-col items-center justify-center">
                                        <i data-lucide="file-check" class="w-6 h-6 text-emerald-500 mb-1"></i>
                                        <span id="pdf-preview-name" class="text-xs text-foreground font-medium truncate max-w-[200px]"></span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-between border-t border-border mt-6">
                    <button type="button" id="reset-form-btn" class="px-4 py-2 text-sm font-semibold text-muted-foreground hover:text-foreground transition-colors">Reset Form</button>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="closeBookModal()" class="px-4 py-2 text-sm font-semibold text-muted-foreground hover:text-foreground transition-colors">Cancel</button>
                        <button type="submit" id="save-book-btn" class="px-6 py-2 bg-primary text-primary-foreground rounded-lg text-sm font-bold hover:opacity-90 shadow-sm transition-all flex items-center gap-2">
                            <span>Save Book</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let booksTable;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('DOMContentLoaded', () => {
        booksTable = new AdminDataTable('books-table', {
            url: '{{ route("admin.books.list") }}',
            perPage: 10,
            filterSelectors: ['#filter-status', '#filter-subject'],
            columns: [
                { key: 'id', title: 'ID', class: 'font-mono text-muted-foreground text-xs w-16' },
                {
                    key: 'cover_image',
                    title: 'Cover',
                    sortable: false,
                    searchable: false,
                    class: 'w-16',
                    render: (val) => val
                        ? `<img src="/storage/${val}" class="w-10 h-10 rounded object-cover border border-border" alt="Cover">`
                        : `<div class="w-10 h-10 rounded bg-muted flex items-center justify-center"><i data-lucide="image" class="w-4 h-4 text-muted-foreground/50"></i></div>`
                },
                {
                    key: 'title',
                    title: 'Title',
                    render: (val) => `<span class="font-bold text-foreground">${val}</span>`
                },
                {
                    key: 'subject',
                    title: 'Subject',
                    render: (val) => val ? `<span class="text-xs font-medium text-muted-foreground">${val.name}</span>` : '<span class="text-xs text-muted-foreground">-</span>'
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
                        <button onclick="viewBook(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Details
                        </button>
                        <button onclick="openBookModal(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit Book
                        </button>
                        ${row.pdf_file ? `
                        <a href="/storage/${row.pdf_file}" target="_blank" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i> Download PDF
                        </a>` : ''}
                        <button onclick="quickToggleStatus(${row.id}, '${row.status === 'active' ? 'inactive' : 'active'}')" class="w-full text-left px-3 py-1.5 text-xs font-semibold hover:bg-accent text-foreground flex items-center gap-2 transition-colors">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Mark as ${row.status === 'active' ? 'Inactive' : 'Active'}
                        </button>
                        <div class="h-px bg-border my-1"></div>
                        <button onclick="deleteBook(${row.id})" class="w-full text-left px-3 py-1.5 text-xs font-semibold text-rose-500 hover:bg-rose-500/10 flex items-center gap-2 transition-colors">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                        </button>
                    `)
                }
            ]
        });

        document.getElementById('bulk-status').addEventListener('change', function() {
            const status = this.value;
            if (!status) return;

            const ids = booksTable.getSelectedIds();
            if (ids.length === 0) {
                window.toast.error('Please select at least one book.');
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

        document.getElementById('reset-form-btn').addEventListener('click', () => {
            bookForm.reset();
            clearErrors(bookForm);
            resetPreviews();
        });
    });

    const bookModal = document.getElementById('book-modal');
    const bookForm = document.getElementById('book-form');

    function previewCover(input) {
        const empty = document.getElementById('cover-preview-empty');
        const preview = document.getElementById('cover-preview');
        const img = document.getElementById('cover-preview-img');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                img.src = e.target.result;
                empty.classList.add('hidden');
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewPdf(input) {
        const empty = document.getElementById('pdf-preview-empty');
        const preview = document.getElementById('pdf-preview');
        const name = document.getElementById('pdf-preview-name');

        if (input.files && input.files[0]) {
            name.textContent = input.files[0].name;
            empty.classList.add('hidden');
            preview.classList.remove('hidden');
            preview.classList.add('flex');
        }
    }

    function resetPreviews() {
        document.getElementById('cover-preview-empty').classList.remove('hidden');
        document.getElementById('cover-preview').classList.add('hidden');
        document.getElementById('pdf-preview-empty').classList.remove('hidden');
        document.getElementById('pdf-preview').classList.add('hidden');
        document.getElementById('pdf-preview').classList.remove('flex');
        lucide.createIcons();
    }

    function openBookModal(id = null) {
        const title = document.getElementById('modal-book-title');
        const submitBtn = document.querySelector('#save-book-btn span');

        bookForm.reset();
        document.getElementById('book-id').value = '';
        clearErrors(bookForm);
        resetPreviews();

        if (id) {
            title.textContent = 'Edit Book';
            submitBtn.textContent = 'Update Book';
            fetchBookDetails(id);
        } else {
            title.textContent = 'Add New Book';
            submitBtn.textContent = 'Save Book';
        }

        bookModal.classList.remove('hidden');
    }

    function closeBookModal() {
        bookModal.classList.add('hidden');
    }

    async function fetchBookDetails(id) {
        try {
            const response = await fetch(`/admin/books/${id}`);
            const result = await response.json();
            if (result.status === 'success') {
                const b = result.data;
                document.getElementById('book-id').value = b.id;
                document.getElementById('book-title').value = b.title;
                document.getElementById('book-subject').value = b.subject_id;
                document.getElementById('book-description').value = b.short_description || '';
                document.getElementById('book-status').value = b.status;

                if (b.cover_image) {
                    const empty = document.getElementById('cover-preview-empty');
                    const preview = document.getElementById('cover-preview');
                    const img = document.getElementById('cover-preview-img');
                    img.src = `/storage/${b.cover_image}`;
                    empty.classList.add('hidden');
                    preview.classList.remove('hidden');
                }
            }
        } catch (error) {
            window.toast.error('Failed to fetch book details');
        }
    }

    bookForm.onsubmit = async (e) => {
        e.preventDefault();
        const id = document.getElementById('book-id').value;
        const formData = new FormData(bookForm);

        const url = id ? `/admin/books/${id}` : '/admin/books';
        const method = id ? 'PUT' : 'POST';

        if (id) {
            formData.append('_method', 'PUT');
        }

        clearErrors(bookForm);

        const submitBtn = document.querySelector('#save-book-btn');
        const submitSpan = document.querySelector('#save-book-btn span');
        submitBtn.disabled = true;
        submitSpan.textContent = id ? 'Updating...' : 'Saving...';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok) {
                window.toast.success(result.message);
                bookModal.classList.add('hidden');
                booksTable.loadData();
            } else if (response.status === 422) {
                if (result.errors) {
                    showErrors(bookForm, result.errors);
                } else {
                    window.toast.error(result.message);
                }
            }
        } catch (error) {
            window.toast.error('Something went wrong. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitSpan.textContent = id ? 'Update Book' : 'Save Book';
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

    function deleteBook(id) {
        window.confirmAction({
            title: 'Delete Book',
            message: 'Are you sure you want to delete this book? This action cannot be undone.',
            onConfirm: async () => {
                try {
                    const response = await fetch(`/admin/books/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        window.toast.success(result.message);
                        booksTable.loadData();
                    } else {
                        window.toast.error('Failed to delete book');
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
            const response = await fetch('/admin/books/change-status', {
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
                booksTable.loadData();
            } else {
                window.toast.error(result.message || 'Failed to update status.');
            }
        } catch (error) {
            window.toast.error('Something went wrong.');
        }
    }

    function viewBook(id) {
        openBookModal(id);
    }

    function bulkDelete() {
        const ids = booksTable.getSelectedIds();
        if (ids.length === 0) {
            window.toast.error('Please select at least one record.');
            return;
        }

        window.confirmAction({
            title: 'Bulk Delete',
            message: `Are you sure you want to delete ${ids.length} selected books?`,
            onConfirm: async () => {
                try {
                    const response = await fetch('/admin/books/bulk-delete', {
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
                        booksTable.loadData();
                    } else {
                        window.toast.error(result.message || 'Failed to delete books.');
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
