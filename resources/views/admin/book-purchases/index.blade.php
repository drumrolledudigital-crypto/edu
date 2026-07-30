@extends('layouts.admin')

@section('title', 'Book Purchases')
@section('page_title', 'Book Purchases')

@section('content')
<div class="space-y-6">
    <x-admin.datatable
        id="book-purchases-table"
        title="Book Purchases"
        description="View all book purchases made by students."
    >
        <x-slot name="filters">
            <div class="flex items-center gap-3">
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

@push('scripts')
<script>
    let purchasesTable;

    document.addEventListener('DOMContentLoaded', () => {
        purchasesTable = new AdminDataTable('book-purchases-table', {
            url: '{{ route("admin.book-purchases.list") }}',
            perPage: 10,
            filterSelectors: ['#filter-subject'],
            columns: [
                { key: 'id', title: 'ID', class: 'font-mono text-muted-foreground text-xs w-16' },
                {
                    key: 'student_name',
                    title: 'Student',
                    render: (val, row) => `<div><span class="font-bold text-foreground">${val}</span><br><span class="text-[10px] text-muted-foreground">${row.student_email}</span></div>`
                },
                {
                    key: 'book_title',
                    title: 'Book',
                    render: (val) => `<span class="font-semibold text-foreground">${val}</span>`
                },
                {
                    key: 'subject',
                    title: 'Subject',
                    render: (val) => `<span class="text-xs font-medium text-muted-foreground">${val}</span>`
                },
                {
                    key: 'amount',
                    title: 'Amount',
                    render: (val) => `<span class="font-bold text-primary">$${parseFloat(val).toFixed(2)}</span>`
                },
                {
                    key: 'purchased_at',
                    title: 'Purchased At',
                    render: (val) => `<span class="text-xs text-muted-foreground">${val || '-'}</span>`
                },
            ]
        });
    });
</script>
@endpush
@endsection
