class AdminDataTable {
    constructor(containerId, options) {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            console.error(`AdminDataTable: Container #${containerId} not found.`);
            return;
        }

        this.options = Object.assign({
            url: '',
            data: null,
            columns: [],
            perPage: 10,
            selectable: true,
            sortable: true,
            searchable: true,
            filterSelectors: []
        }, options);

        this.originalData = [];
        this.data = [];
        this.currentPage = 1;
        this.sortKey = '';
        this.sortDir = 'asc';
        this.selectedIds = new Set();
        
        // Cache DOM elements
        this.els = {
            headerRow: this.container.querySelector('.dt-header-row'),
            body: this.container.querySelector('.dt-body'),
            search: this.container.querySelector('.dt-search'),
            perPage: this.container.querySelector('.dt-per-page'),
            prev: this.container.querySelector('.dt-prev'),
            next: this.container.querySelector('.dt-next'),
            pages: this.container.querySelector('.dt-pages'),
            loading: this.container.querySelector('.dt-loading'),
            empty: this.container.querySelector('.dt-empty'),
            totalRecords: this.container.querySelector('.dt-total-records'),
            refresh: this.container.querySelector('.dt-refresh'),
            bulkActions: this.container.querySelector('.dt-bulk-actions'),
            selectedCount: this.container.querySelector('.dt-selected-count')
        };

        this.filters = {};
        if (this.options.filterSelectors.length > 0) {
            this.options.filterSelectors.forEach(selector => {
                const el = document.querySelector(selector);
                if (el) {
                    this.filters[el.name || el.id] = el;
                    el.addEventListener('change', () => this.applyFilters());
                }
            });
        }

        this.init();
    }

    async init() {
        this.renderHeaders();
        this.bindEvents();
        await this.loadData();
    }

    bindEvents() {
        if (this.els.search) {
            this.els.search.addEventListener('input', this.debounce(() => this.applyFilters(), 300));
        }

        if (this.els.refresh) {
            this.els.refresh.addEventListener('click', () => this.loadData());
        }

        if (this.els.perPage) {
            this.els.perPage.value = this.options.perPage;
            this.els.perPage.addEventListener('change', (e) => {
                this.options.perPage = parseInt(e.target.value);
                this.currentPage = 1;
                this.render();
            });
        }

        if (this.els.prev) {
            this.els.prev.addEventListener('click', () => {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.render();
                }
            });
        }

        if (this.els.next) {
            this.els.next.addEventListener('click', () => {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                    this.render();
                }
            });
        }
    }

    renderHeaders() {
        if (!this.els.headerRow) return;
        this.els.headerRow.innerHTML = '';

        if (this.options.selectable) {
            const th = document.createElement('th');
            th.className = 'px-6 py-4 w-10';
            th.innerHTML = `<input type="checkbox" class="dt-select-all rounded border-border text-primary focus:ring-primary transition-colors cursor-pointer">`;
            
            const selectAllCb = th.querySelector('.dt-select-all');
            selectAllCb.addEventListener('change', (e) => this.toggleSelectAll(e.target.checked));
            
            this.els.headerRow.appendChild(th);
        }

        this.options.columns.forEach(col => {
            const th = document.createElement('th');
            th.className = `px-6 py-4 ${col.class || ''} ${col.sortable !== false ? 'cursor-pointer hover:bg-muted/80 transition-colors select-none group' : ''}`;
            
            const alignClass = (col.class && col.class.includes('text-center')) ? 'justify-center' : ((col.class && col.class.includes('text-right')) ? 'justify-end' : '');
            let html = `<div class="flex items-center gap-2 ${alignClass}"><span>${col.title}</span>`;
            if (col.sortable !== false) {
                html += `<i data-lucide="arrow-up-down" class="w-3.5 h-3.5 opacity-0 group-hover:opacity-50 transition-opacity dt-sort-icon"></i>`;
                th.addEventListener('click', () => this.sort(col.key));
            }
            html += `</div>`;
            th.innerHTML = html;
            th.dataset.key = col.key;
            this.els.headerRow.appendChild(th);
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons({ root: this.els.headerRow });
        }
    }

    async loadData() {
        this.showLoading();
        this.selectedIds.clear();
        this.updateBulkActions();

        try {
            if (this.options.url) {
                const response = await fetch(this.options.url);
                const result = await response.json();
                this.originalData = result.data || result || [];
            } else if (this.options.data) {
                this.originalData = [...this.options.data];
            }
            this.applyFilters();
        } catch (error) {
            console.error('DataTable Data Load Error:', error);
            if (window.toast) window.toast.error('Failed to load data.');
        } finally {
            this.hideLoading();
        }
    }

    applyFilters() {
        let filtered = [...this.originalData];

        // Search
        const searchTerm = this.els.search ? this.els.search.value.toLowerCase().trim() : '';
        if (searchTerm) {
            filtered = filtered.filter(row => {
                return this.options.columns.some(col => {
                    if (col.searchable === false) return false;
                    const val = row[col.key];
                    return val && String(val).toLowerCase().includes(searchTerm);
                });
            });
        }

        // Custom Filters (Dropdowns etc)
        for (const [key, el] of Object.entries(this.filters)) {
            const val = el.value.toLowerCase().trim();
            if (val) {
                filtered = filtered.filter(row => {
                    return row[key] && String(row[key]).toLowerCase() === val;
                });
            }
        }

        // Sorting
        if (this.sortKey) {
            filtered.sort((a, b) => {
                let valA = a[this.sortKey] || '';
                let valB = b[this.sortKey] || '';

                if (typeof valA === 'string') valA = valA.toLowerCase();
                if (typeof valB === 'string') valB = valB.toLowerCase();

                if (valA < valB) return this.sortDir === 'asc' ? -1 : 1;
                if (valA > valB) return this.sortDir === 'asc' ? 1 : -1;
                return 0;
            });
        }

        this.data = filtered;
        this.currentPage = 1;
        this.render();
    }

    sort(key) {
        if (this.sortKey === key) {
            this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortKey = key;
            this.sortDir = 'asc';
        }

        // Update headers visual
        this.els.headerRow.querySelectorAll('th').forEach(th => {
            const icon = th.querySelector('.dt-sort-icon');
            if (icon) {
                if (th.dataset.key === this.sortKey) {
                    icon.setAttribute('data-lucide', this.sortDir === 'asc' ? 'arrow-up' : 'arrow-down');
                    icon.classList.remove('opacity-0', 'group-hover:opacity-50');
                    icon.classList.add('opacity-100', 'text-primary');
                } else {
                    icon.setAttribute('data-lucide', 'arrow-up-down');
                    icon.classList.remove('opacity-100', 'text-primary');
                    icon.classList.add('opacity-0', 'group-hover:opacity-50');
                }
            }
        });

        if (typeof lucide !== 'undefined') lucide.createIcons({ root: this.els.headerRow });

        this.applyFilters();
    }

    render() {
        if (!this.els.body) return;
        this.els.body.innerHTML = '';

        if (this.els.totalRecords) {
            this.els.totalRecords.textContent = `${this.data.length} Total`;
        }

        if (this.data.length === 0) {
            this.els.empty.classList.remove('hidden');
            this.els.empty.classList.add('flex');
            this.updatePagination();
            return;
        } else {
            this.els.empty.classList.add('hidden');
            this.els.empty.classList.remove('flex');
        }

        this.totalPages = Math.ceil(this.data.length / this.options.perPage);
        if (this.currentPage > this.totalPages) this.currentPage = this.totalPages;

        const start = (this.currentPage - 1) * this.options.perPage;
        const end = start + this.options.perPage;
        const pageData = this.data.slice(start, end);

        pageData.forEach(row => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-muted/30 transition-colors group dt-row';
            if (this.selectedIds.has(row.id)) tr.classList.add('bg-primary/5');

            if (this.options.selectable) {
                const td = document.createElement('td');
                td.className = 'px-6 py-4';
                td.innerHTML = `<input type="checkbox" class="dt-row-checkbox rounded border-border text-primary focus:ring-primary transition-colors cursor-pointer" value="${row.id}" ${this.selectedIds.has(row.id) ? 'checked' : ''}>`;
                
                const cb = td.querySelector('.dt-row-checkbox');
                cb.addEventListener('change', (e) => {
                    if (e.target.checked) {
                        this.selectedIds.add(row.id);
                        tr.classList.add('bg-primary/5');
                    } else {
                        this.selectedIds.delete(row.id);
                        tr.classList.remove('bg-primary/5');
                    }
                    this.updateBulkActions();
                });
                tr.appendChild(td);
            }

            this.options.columns.forEach(col => {
                const td = document.createElement('td');
                td.className = `px-6 py-4 ${col.class || ''}`;
                
                if (col.render) {
                    td.innerHTML = col.render(row[col.key], row);
                } else {
                    td.textContent = row[col.key] || '-';
                }
                tr.appendChild(td);
            });

            this.els.body.appendChild(tr);
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons({ root: this.els.body });
        }

        this.updatePagination();
        this.updateBulkActions();
    }

    updatePagination() {
        if (!this.els.pages) return;

        this.els.prev.disabled = this.currentPage === 1 || this.data.length === 0;
        this.els.next.disabled = this.currentPage === this.totalPages || this.data.length === 0;

        this.els.pages.innerHTML = '';
        if (this.data.length === 0) return;

        const maxVisibleButtons = 5;
        let startPage = Math.max(1, this.currentPage - Math.floor(maxVisibleButtons / 2));
        let endPage = Math.min(this.totalPages, startPage + maxVisibleButtons - 1);

        if (endPage - startPage + 1 < maxVisibleButtons) {
            startPage = Math.max(1, endPage - maxVisibleButtons + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            const btn = document.createElement('button');
            btn.className = `w-8 h-8 rounded-md text-sm font-medium transition-colors ${i === this.currentPage ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:bg-accent hover:text-foreground'}`;
            btn.textContent = i;
            btn.addEventListener('click', () => {
                this.currentPage = i;
                this.render();
            });
            this.els.pages.appendChild(btn);
        }
    }

    toggleSelectAll(checked) {
        if (checked) {
            this.data.forEach(row => this.selectedIds.add(row.id));
        } else {
            this.selectedIds.clear();
        }
        this.render();
    }

    updateBulkActions() {
        if (!this.els.bulkActions) return;

        // Update select all checkbox state
        const selectAllCb = this.els.headerRow?.querySelector('.dt-select-all');
        if (selectAllCb) {
            const pageIds = this.data.slice((this.currentPage - 1) * this.options.perPage, this.currentPage * this.options.perPage).map(r => r.id);
            const allSelected = pageIds.length > 0 && pageIds.every(id => this.selectedIds.has(id));
            const someSelected = pageIds.some(id => this.selectedIds.has(id));
            
            selectAllCb.checked = allSelected;
            selectAllCb.indeterminate = someSelected && !allSelected;
        }

        this.els.selectedCount.textContent = this.selectedIds.size;

        if (this.selectedIds.size > 0) {
            this.els.bulkActions.classList.remove('hidden');
            this.els.bulkActions.classList.add('flex');
        } else {
            this.els.bulkActions.classList.add('hidden');
            this.els.bulkActions.classList.remove('flex');
        }
    }

    getSelectedIds() {
        return Array.from(this.selectedIds);
    }

    showLoading() {
        if (this.els.loading) {
            this.els.loading.classList.remove('hidden');
        }
    }

    hideLoading() {
        if (this.els.loading) {
            this.els.loading.classList.add('hidden');
        }
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Utility for rendering badges
    static renderBadge(text, type = 'default') {
        const types = {
            active: 'bg-emerald-500/10 text-emerald-500',
            inactive: 'bg-muted text-muted-foreground',
            disabled: 'bg-muted text-muted-foreground',
            pending: 'bg-amber-500/10 text-amber-600',
            cancelled: 'bg-rose-500/10 text-rose-500',
            completed: 'bg-blue-500/10 text-blue-500',
            generated: 'bg-emerald-500/10 text-emerald-500',
            paid: 'bg-emerald-500/10 text-emerald-500',
            refunded: 'bg-rose-500/10 text-rose-500',
            default: 'bg-primary/10 text-primary'
        };
        const colorClass = types[type.toLowerCase()] || types['default'];
        
        return `
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                    ${['active', 'completed', 'paid'].includes(type.toLowerCase()) ? `<span class="animate-ping absolute inline-flex h-full w-full rounded-full ${colorClass.split(' ')[1].replace('text', 'bg')} opacity-40"></span>` : ''}
                    <span class="relative inline-flex rounded-full h-2 w-2 ${colorClass.split(' ')[1].replace('text', 'bg')}"></span>
                </span>
                <span class="text-[10px] font-bold uppercase tracking-wider ${colorClass.split(' ')[1]}">${text}</span>
            </div>
        `;
    }

    // Utility for rendering actions
    static renderActions(actionsHTML) {
        return `
            <div class="relative dt-action-menu inline-block text-left">
                <button onclick="AdminDataTable.toggleMenu(this)" class="dt-action-btn inline-flex items-center gap-1.5 bg-card border border-border text-foreground text-[13px] font-medium px-3 py-1.5 rounded-md hover:bg-accent hover:text-accent-foreground transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <span class="pointer-events-none">Actions</span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-muted-foreground pointer-events-none transition-transform duration-200 dt-chevron"></i>
                </button>
                <div class="absolute right-0 w-40 bg-card border border-border rounded-lg shadow-xl py-1.5 hidden z-[100] transform transition-all duration-200">
                    ${actionsHTML}
                </div>
            </div>
        `;
    }

    static toggleMenu(button) {
        const menu = button.nextElementSibling;
        const chevron = button.querySelector('.dt-chevron');
        const isOpen = !menu.classList.contains('hidden');
        
        // Close all other menus first
        document.querySelectorAll('.dt-action-menu > div:not(.hidden)').forEach(m => {
            if (m !== menu) AdminDataTable.closeMenu(m);
        });

        if (isOpen) {
            AdminDataTable.closeMenu(menu);
        } else {
            // Measurements
            const btnRect = button.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            
            // Set fixed position styles to avoid clipping by overflow: hidden parents
            menu.style.position = 'fixed';
            menu.style.margin = '0';
            menu.classList.remove('hidden');
            
            const menuHeight = menu.offsetHeight;
            const menuWidth = menu.offsetWidth;
            
            let top, left;

            // Horizontal alignment (Right-aligned with button)
            left = btnRect.right - menuWidth;
            
            // Vertical alignment (Smart Positioning)
            if (btnRect.bottom + menuHeight + 10 > viewportHeight) {
                // Not enough space below, open UPWARD
                top = btnRect.top - menuHeight - 6;
                menu.style.transformOrigin = 'bottom right';
            } else {
                // Open DOWNWARD
                top = btnRect.bottom + 6;
                menu.style.transformOrigin = 'top right';
            }

            menu.style.top = `${top}px`;
            menu.style.left = `${left}px`;
            chevron.classList.add('rotate-180');
            
            // Trigger animation
            menu.classList.add('animate-in', 'fade-in', 'zoom-in-95', 'duration-200');
        }
    }

    static closeMenu(menu) {
        menu.classList.add('hidden');
        menu.classList.remove('animate-in', 'fade-in', 'zoom-in-95');
        const btn = menu.previousElementSibling;
        if (btn) {
            const chevron = btn.querySelector('.dt-chevron');
            if (chevron) chevron.classList.remove('rotate-180');
        }
    }
}

// Global Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    // Close action menus when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dt-action-menu')) {
            document.querySelectorAll('.dt-action-menu > div:not(.hidden)').forEach(menu => {
                AdminDataTable.closeMenu(menu);
            });
        }
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.dt-action-menu > div:not(.hidden)').forEach(menu => {
                AdminDataTable.closeMenu(menu);
            });
        }
    });

    // Close on scroll or resize to prevent fixed position drift
    window.addEventListener('scroll', () => {
        document.querySelectorAll('.dt-action-menu > div:not(.hidden)').forEach(menu => {
            AdminDataTable.closeMenu(menu);
        });
    }, true);

    window.addEventListener('resize', () => {
        document.querySelectorAll('.dt-action-menu > div:not(.hidden)').forEach(menu => {
            AdminDataTable.closeMenu(menu);
        });
    });
});
