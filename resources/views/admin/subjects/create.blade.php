@extends('layouts.admin')

@section('title', 'Add New Subject')
@section('page_title', 'Subjects')

@section('content')
<div class="space-y-6">
    <a href="{{ route('admin.subjects.index') }}" class="text-sm font-semibold text-primary flex items-center gap-2 hover:underline">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back to Subjects
    </a>

    <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-border">
            <h3 class="text-lg font-bold text-foreground">Create New Subject</h3>
        </div>
        <form class="p-6 space-y-5" id="subject-form" method="POST" action="{{ route('admin.subjects.store') }}">
            @csrf
            <div class="space-y-1">
                <label class="text-sm font-semibold text-foreground">Subject Name</label>
                <input type="text" name="name" placeholder="e.g. Mathematics, Science" required class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
            </div>
            
            <div class="space-y-1">
                <label class="text-sm font-semibold text-foreground">Description</label>
                <textarea name="description" rows="3" placeholder="Briefly describe what topics this subject covers..." required class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-foreground">From Class</label>
                    <select name="class_range_from" required class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                        @for($i=1; $i<=8; $i++) <option value="{{ $i }}">Class {{ $i }}</option> @endfor
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-foreground">To Class</label>
                    <select name="class_range_to" required class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                        @for($i=1; $i<=8; $i++) <option value="{{ $i }}" {{ $i == 8 ? 'selected' : '' }}>Class {{ $i }}</option> @endfor
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-foreground">Session Duration (minutes)</label>
                    <input type="number" name="session_duration" value="{{ \App\Models\Setting::get('session_duration', 50) }}" min="10" required class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-foreground">Sort Order</label>
                    <input type="number" name="sort_order" value="0" min="0" required class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-semibold text-foreground">Status</label>
                <select name="status" required class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                    <option value="active">Active</option>
                    <option value="disabled">Disabled</option>
                </select>
            </div>

            <div class="pt-4 flex items-center gap-3">
                <button type="submit" class="px-6 py-2 bg-primary text-primary-foreground rounded-lg text-sm font-bold hover:opacity-90 shadow-sm transition-all">Save Subject</button>
                <a href="{{ route('admin.subjects.index') }}" type="button" class="px-6 py-2 bg-background border border-border text-muted-foreground rounded-lg text-sm font-bold hover:bg-accent hover:text-accent-foreground transition-all inline-block text-center">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

