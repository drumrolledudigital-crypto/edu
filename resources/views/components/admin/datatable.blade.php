<div id="{{ $id }}" class="datatable-container w-full space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">{{ $title }}</h2>
            <div class="flex items-center gap-2 mt-1">
                @if(isset($description))
                    <p class="text-sm text-muted-foreground">{{ $description }}</p>
                @endif
                <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-bold tracking-wider uppercase dt-total-records">0 Total</span>
            </div>
        </div>
        <div class="flex items-center flex-wrap gap-3">
            <div class="relative group">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors"></i>
                <input type="text" class="dt-search h-9 pl-9 pr-4 bg-card border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all w-full sm:w-64" placeholder="Search...">
            </div>
            
            @if(isset($filters))
                <div class="flex items-center gap-2">
                    {{ $filters }}
                    <button class="dt-reset-filters text-muted-foreground hover:text-foreground text-xs font-semibold px-2 py-1 rounded hover:bg-accent transition-colors" style="display:none;">Reset</button>
                </div>
            @endif

            <button class="dt-refresh text-muted-foreground hover:text-foreground p-2 rounded-md hover:bg-accent transition-colors border border-border bg-card shadow-sm" title="Refresh">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            </button>
            
            @if(isset($actions))
                {{ $actions }}
            @endif
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="dt-bulk-actions hidden items-center gap-3 p-3 bg-muted/30 border border-border rounded-lg animate-in fade-in slide-in-from-top-2">
        <span class="text-sm font-semibold text-foreground"><span class="dt-selected-count">0</span> selected</span>
        <div class="h-4 w-px bg-border"></div>
        @if(isset($bulkActions))
            {{ $bulkActions }}
        @endif
    </div>

    <!-- Table Container -->
    <div class="bg-card border border-border rounded-xl shadow-sm overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-muted/50 border-b border-border text-muted-foreground font-semibold">
                    <tr class="dt-header-row">
                        <!-- JS will populate headers here based on config -->
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-foreground dt-body">
                    <!-- JS will populate rows here -->
                </tbody>
            </table>
        </div>
        
        <!-- Loading State -->
        <div class="dt-loading absolute inset-0 bg-card/80 backdrop-blur-sm flex flex-col items-center justify-center z-10 hidden">
            <i data-lucide="loader-2" class="w-8 h-8 animate-spin text-primary mb-2"></i>
            <span class="text-sm font-medium text-muted-foreground">Loading data...</span>
        </div>

        <!-- Empty State -->
        <div class="dt-empty hidden flex flex-col items-center justify-center py-16 px-4 text-center">
            <div class="w-16 h-16 bg-muted/50 rounded-full flex items-center justify-center mb-4">
                <i data-lucide="folder-search" class="w-8 h-8 text-muted-foreground"></i>
            </div>
            <h3 class="text-lg font-bold text-foreground mb-1">No records found</h3>
            <p class="text-sm text-muted-foreground mb-4">Try adjusting your filters or search query.</p>
            @if(isset($emptyAction))
                {{ $emptyAction }}
            @endif
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-sm text-muted-foreground">
            <span>Show</span>
            <select class="dt-per-page bg-card border border-border rounded-md px-2 py-1 focus:outline-none focus:ring-1 focus:ring-ring">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span>entries</span>
        </div>
        
        <div class="flex items-center justify-end gap-1">
            <button class="dt-prev px-3 py-1.5 rounded-md border border-border bg-card text-muted-foreground hover:text-foreground hover:bg-accent disabled:opacity-50 disabled:pointer-events-none transition-colors text-sm font-medium">
                Previous
            </button>
            <div class="flex items-center gap-1 dt-pages">
                <!-- Page buttons -->
            </div>
            <button class="dt-next px-3 py-1.5 rounded-md border border-border bg-card text-muted-foreground hover:text-foreground hover:bg-accent disabled:opacity-50 disabled:pointer-events-none transition-colors text-sm font-medium">
                Next
            </button>
        </div>
    </div>
</div>
