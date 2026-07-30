@extends('layouts.admin')

@section('title', 'Audit Log Details')
@section('page_title', 'Activity Detail')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4 mb-2">
        <a href="{{ route('admin.audit-logs.index') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-border bg-card text-muted-foreground hover:text-foreground transition-colors shadow-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Audit Log #{{ $log->id }}</h2>
            <p class="text-sm text-muted-foreground">Recorded on {{ $log->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Overview -->
        <section class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden md:col-span-2">
            <div class="px-6 py-5 border-b border-border flex items-center justify-between">
                <h3 class="text-lg font-bold text-foreground">Activity Overview</h3>
                <span class="px-2.5 py-1 rounded bg-muted text-muted-foreground text-[10px] font-bold uppercase tracking-wider border border-border">{{ $log->action }}</span>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-1">Module</label>
                    <p class="text-sm font-bold text-foreground">{{ $log->module }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-1">User</label>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-foreground">{{ $log->user ? $log->user->name : 'System' }}</span>
                        <span class="px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider {{ $log->role === 'admin' ? 'bg-rose-500/10 text-rose-500' : ($log->role === 'student' ? 'bg-primary-500/10 text-primary-500' : 'bg-amber-500/10 text-amber-500') }}">{{ $log->role }}</span>
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-1">IP Address</label>
                    <p class="text-sm font-mono text-foreground">{{ $log->ip_address ?? 'N/A' }}</p>
                </div>
                <div class="sm:col-span-3">
                    <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-1">Description</label>
                    <p class="text-sm text-foreground bg-muted/30 p-3 rounded-lg border border-border">{{ $log->description }}</p>
                </div>
                <div class="sm:col-span-3">
                    <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block mb-1">User Agent</label>
                    <p class="text-[11px] text-muted-foreground bg-muted/30 p-2 rounded-lg border border-border break-all">{{ $log->user_agent ?? 'Unknown' }}</p>
                </div>
            </div>
        </section>

        <!-- Data Changes -->
        @if($log->old_values || $log->new_values)
            <section class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden md:col-span-2">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Data Changes</h3>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-[10px] uppercase bg-muted/50 text-muted-foreground">
                            <tr>
                                <th class="px-6 py-3 font-bold w-1/3">Field</th>
                                <th class="px-6 py-3 font-bold w-1/3 text-rose-500">Old Value</th>
                                <th class="px-6 py-3 font-bold w-1/3 text-emerald-500">New Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @php
                                $keys = array_unique(array_merge(
                                    array_keys($log->old_values ?? []),
                                    array_keys($log->new_values ?? [])
                                ));
                            @endphp
                            @foreach($keys as $key)
                                @php
                                    $old = $log->old_values[$key] ?? null;
                                    $new = $log->new_values[$key] ?? null;
                                    $isChanged = $old !== $new;
                                @endphp
                                <tr class="hover:bg-muted/30 transition-colors {{ $isChanged ? 'bg-primary/5' : '' }}">
                                    <td class="px-6 py-3 font-mono text-[11px] text-muted-foreground">{{ $key }}</td>
                                    <td class="px-6 py-3 font-mono text-[11px] {{ $isChanged ? 'text-rose-500 line-through decoration-rose-500/50' : 'text-foreground' }}">
                                        {{ is_array($old) ? json_encode($old) : ($old ?? 'null') }}
                                    </td>
                                    <td class="px-6 py-3 font-mono text-[11px] {{ $isChanged ? 'text-emerald-600 font-bold' : 'text-foreground' }}">
                                        {{ is_array($new) ? json_encode($new) : ($new ?? 'null') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </div>
</div>
@endsection
