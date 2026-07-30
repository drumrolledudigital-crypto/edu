@extends('layouts.admin')

@section('title', 'Doubt Details')
@section('page_title', 'Doubt Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4 mb-2">
        <a href="{{ route('admin.doubts.index') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-border bg-card text-muted-foreground hover:text-foreground transition-colors shadow-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Review Doubt #{{ $doubt->id }}</h2>
            <p class="text-sm text-muted-foreground">Submitted by {{ $doubt->student->name }} on {{ $doubt->created_at->format('M d, Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <section class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-border flex items-center justify-between">
                    <h3 class="text-lg font-bold text-foreground">Doubt Information</h3>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $doubt->status === 'pending' ? 'bg-amber-500/10 text-amber-600 border-amber-500/20' : ($doubt->status === 'accepted' ? 'bg-primary-500/10 text-primary-500 border-primary-500/20' : ($doubt->status === 'resolved' ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 'bg-muted text-muted-foreground')) }}">
                        {{ $doubt->status }}
                    </span>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-2">Subject & Topic</label>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-muted text-foreground text-xs font-bold">{{ $doubt->subject->name }}</span>
                            <span class="text-muted-foreground text-xs font-medium">/</span>
                            <span class="text-sm font-semibold text-foreground">{{ $doubt->topic_name }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-2">Doubt Title(s)</label>
                        <h4 class="text-lg font-bold text-foreground">
                            @if(is_array($doubt->title))
                                @foreach($doubt->title as $t)
                                    <span class="block">{{ $t }}</span>
                                @endforeach
                            @else
                                {{ $doubt->title }}
                            @endif
                        </h4>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-2">Detailed Description</label>
                        <div class="bg-muted/30 rounded-lg p-5 text-sm text-foreground leading-relaxed whitespace-pre-wrap border border-border/50">
                            {{ $doubt->description }}
                        </div>
                    </div>

@if($doubt->attachment)
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-2">Attachment</label>
                        <div class="mt-2 border border-border rounded-xl p-4 bg-muted/20 group relative overflow-hidden">
                            @php
                                $extension = pathinfo($doubt->attachment, PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                $attachmentUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($doubt->attachment);
                            @endphp

                            @if($isImage)
                                <div class="relative rounded-lg overflow-hidden border border-border shadow-sm max-w-sm mx-auto">
                                    <img src="{{ $attachmentUrl }}" alt="Doubt attachment" class="w-full h-auto">
                                </div>
                            @else
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-card rounded-lg border border-border flex items-center justify-center text-rose-500 shadow-sm">
                                        <i data-lucide="file-text" class="w-6 h-6"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-foreground truncate">Doubt_Attachment_{{ $doubt->id }}.{{ $extension }}</p>
                                        <p class="text-xs text-muted-foreground uppercase">{{ $extension }} Document</p>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-4 flex items-center gap-3">
                                <a href="{{ $attachmentUrl }}" target="_blank" class="flex-1 inline-flex items-center justify-center h-9 px-4 rounded-lg bg-card border border-border text-xs font-bold text-foreground hover:bg-accent transition-colors shadow-sm gap-2">
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Open File
                                </a>
                                <a href="{{ $attachmentUrl }}" download class="flex-1 inline-flex items-center justify-center h-9 px-4 rounded-lg bg-primary text-primary-foreground text-xs font-bold hover:opacity-90 transition-all shadow-sm gap-2">
                                    <i data-lucide="download" class="w-3.5 h-3.5"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </section>
        </div>

        <!-- Sidebar / Actions -->
        <div class="space-y-6">
            <section class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Manage Status</h3>
                </div>
                <div class="p-6 space-y-4">
                    <form action="{{ route('admin.doubts.update-status', $doubt->id) }}" method="POST" id="status-form" class="space-y-4">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-muted-foreground uppercase tracking-widest block">Update Status</label>
                            <select name="status" class="w-full h-10 px-4 bg-muted/30 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                                <option value="pending" {{ $doubt->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                                <option value="accepted" {{ $doubt->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="resolved" {{ $doubt->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="cancelled" {{ $doubt->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-primary text-primary-foreground rounded-lg text-sm font-black hover:opacity-90 shadow-sm transition-all flex items-center justify-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Save Status
                        </button>
                    </form>
                </div>
            </section>

            <section class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Student Profile</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xl font-bold border border-primary/20">
                            {{ substr($doubt->student->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-foreground truncate">{{ $doubt->student->name }}</p>
                            <p class="text-xs text-muted-foreground">{{ $doubt->student->student_class }}</p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-border/50 space-y-3">
                        <div class="flex justify-between text-xs">
                            <span class="text-muted-foreground">Parent Name:</span>
                            <span class="font-bold text-foreground">{{ $doubt->student->parent_name }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-muted-foreground">Contact:</span>
                            <span class="font-bold text-foreground">{{ $doubt->student->mobile_number }}</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.students.show', $doubt->user_id) }}" class="w-full mt-2 inline-flex items-center justify-center h-9 px-4 rounded-lg bg-muted/50 border border-border text-xs font-bold text-foreground hover:bg-accent transition-colors shadow-sm gap-2">
                        View Full Profile <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('status-form').onsubmit = async (e) => {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (response.ok) {
                window.toast.success(result.message);
                // Reload to reflect changes or handle dynamically
                setTimeout(() => location.reload(), 1000);
            } else {
                window.toast.error(result.message || 'Failed to update status');
            }
        } catch (error) {
            window.toast.error('Something went wrong. Please try again.');
        }
    };
</script>
@endpush
@endsection
