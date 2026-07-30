<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - ' . \App\Models\Setting::get('platform_name', 'Drumroll'))</title>
    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        const currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else if (currentTheme === 'education') {
            document.documentElement.classList.add('education');
        } else if (currentTheme === 'light' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            // Default to light or system dark (though we want to explicitely support light)
            if (window.matchMedia('(prefers-color-scheme: dark)').matches && !localStorage.getItem('theme')) {
                document.documentElement.classList.add('dark');
            }
        }
    </script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        border: "hsl(var(--border))",
                        input: "hsl(var(--input))",
                        ring: "hsl(var(--ring))",
                        background: "hsl(var(--background))",
                        foreground: "hsl(var(--foreground))",
                        primary: {
                            DEFAULT: "hsl(var(--primary))",
                            foreground: "hsl(var(--primary-foreground))",
                        },
                        secondary: {
                            DEFAULT: "hsl(var(--secondary))",
                            foreground: "hsl(var(--secondary-foreground))",
                        },
                        destructive: {
                            DEFAULT: "hsl(var(--destructive))",
                            foreground: "hsl(var(--destructive-foreground))",
                        },
                        muted: {
                            DEFAULT: "hsl(var(--muted))",
                            foreground: "hsl(var(--muted-foreground))",
                        },
                        accent: {
                            DEFAULT: "hsl(var(--accent))",
                            foreground: "hsl(var(--accent-foreground))",
                        },
                        popover: {
                            DEFAULT: "hsl(var(--popover))",
                            foreground: "hsl(var(--popover-foreground))",
                        },
                        card: {
                            DEFAULT: "hsl(var(--card))",
                            foreground: "hsl(var(--card-foreground))",
                        },
                    },
                    borderRadius: {
                        lg: "var(--radius)",
                        md: "calc(var(--radius) - 2px)",
                        sm: "calc(var(--radius) - 4px)",
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        :root {
            --background: 210 40% 98%;
            --foreground: 222.2 84% 4.9%;
            --card: 0 0% 100%;
            --card-foreground: 222.2 84% 4.9%;
            --popover: 0 0% 100%;
            --popover-foreground: 222.2 84% 4.9%;
            --primary: 197 67% 45%; /* #2596be */
            --primary-foreground: 210 40% 98%;
            --secondary: 210 40% 96.1%;
            --secondary-foreground: 222.2 47.4% 11.2%;
            --muted: 210 40% 96.1%;
            --muted-foreground: 215.4 16.3% 46.9%;
            --accent: 210 40% 96.1%;
            --accent-foreground: 222.2 47.4% 11.2%;
            --destructive: 0 84.2% 60.2%;
            --destructive-foreground: 210 40% 98%;
            --border: 214.3 31.8% 86%;
            --input: 214.3 31.8% 91.4%;
            --ring: 197 67% 45%;
            --radius: 0.75rem;
            --sidebar-bg: 0 0% 100%;
        }

        .dark {
            --background: 222.2 84% 4.9%;
            --foreground: 210 40% 98%;
            --card: 222.2 84% 4.9%;
            --card-foreground: 210 40% 98%;
            --popover: 222.2 84% 4.9%;
            --popover-foreground: 210 40% 98%;
            --primary: 197 67% 45%; /* #2596be dark */
            --primary-foreground: 222.2 47.4% 11.2%;
            --secondary: 217.2 32.6% 17.5%;
            --secondary-foreground: 210 40% 98%;
            --muted: 217.2 32.6% 17.5%;
            --muted-foreground: 215 20.2% 65.1%;
            --accent: 217.2 32.6% 17.5%;
            --accent-foreground: 210 40% 98%;
            --destructive: 0 62.8% 30.6%;
            --destructive-foreground: 210 40% 98%;
            --border: 217.2 32.6% 17.5%;
            --input: 217.2 32.6% 17.5%;
            --ring: 212.7 26.8% 83.9%;
            --sidebar-bg: 222.2 84% 3.5%;
        }

        .education {
            --background: 187 40% 97%; /* Visible Teal Tint for background */
            --foreground: 60 1% 20%;
            --card: 0 0% 100%;
            --card-foreground: 60 1% 20%;
            --popover: 0 0% 100%;
            --popover-foreground: 60 1% 20%;
            --primary: 197 67% 45%; /* Primary #2596be */
            --primary-foreground: 0 0% 100%;
            --secondary: 41 100% 68%; /* Warm Yellow #FFCC58 */
            --secondary-foreground: 60 1% 20%;
            --muted: 187 20% 90%;
            --muted-foreground: 60 1% 40%;
            --accent: 187 100% 45%;
            --accent-foreground: 0 0% 100%;
            --destructive: 351 86% 60%; /* Accent Red #F0445E */
            --destructive-foreground: 0 0% 100%;
            --border: 187 30% 85%;
            --input: 187 30% 85%;
            --ring: 197 67% 45%;
            --radius: 0.85rem;
            --sidebar-bg: 197 67% 45%; /* #2596be SIDEBAR */
        }

        /* Education Mode Layout - HIGH VIBRANCY */
        .education header {
            background: #FFFFFF !important;
            border-bottom: 5px solid #009AAF !important;
            box-shadow: 0 4px 20px -5px rgba(0, 154, 175, 0.25);
        }

        .education aside {
            background-color: #009AAF !important; /* VIBRANT SOLID TEAL */
            border-right: none !important;
        }

        /* Sidebar Content for Education Mode (White Text on Teal) */
        .education aside span, 
        .education aside p, 
        .education aside i,
        .education aside a {
            color: rgba(255, 255, 255, 0.85) !important;
        }

        .education aside .text-primary,
        .education aside .bg-primary\/10 {
            color: #FFFFFF !important;
            background-color: rgba(255, 255, 255, 0.2) !important;
        }

        .education aside .nav-item-active {
            background-color: #FFFFFF !important;
            color: #009AAF !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
        }

        .education aside .nav-item-active i,
        .education aside .nav-item-active span {
            color: #009AAF !important;
        }

        /* Sidebar Item Hover States */
        .education aside a:hover:not(.nav-item-active) {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #FFFFFF !important;
        }

        /* VIBRANT Dashboard Cards */
        .education .dashboard-card-revenue { border-top: 8px solid #F0445E !important; background-color: #FFFFFF !important; box-shadow: 0 10px 15px -3px rgba(240, 68, 94, 0.1) !important; }
        .education .dashboard-card-revenue i { color: #F0445E !important; }
        .education .dashboard-card-students { border-top: 8px solid #009AAF !important; background-color: #FFFFFF !important; box-shadow: 0 10px 15px -3px rgba(0, 154, 175, 0.1) !important; }
        .education .dashboard-card-students i { color: #009AAF !important; }
        .education .dashboard-card-sessions { border-top: 8px solid #A6D20A !important; background-color: #FFFFFF !important; box-shadow: 0 10px 15px -3px rgba(166, 210, 10, 0.1) !important; }
        .education .dashboard-card-sessions i { color: #A6D20A !important; }
        .education .dashboard-card-doubts { border-top: 8px solid #FFCC58 !important; background-color: #FFFFFF !important; box-shadow: 0 10px 15px -3px rgba(255, 204, 88, 0.1) !important; }
        .education .dashboard-card-doubts i { color: #FFCC58 !important; }

        /* SATURATED Status Badges */
        .education .bg-blue-500\/10, .education .bg-blue-500 { background-color: #2596be !important; color: #FFFFFF !important; border: none !important; padding: 4px 12px !important; }
        .education .bg-emerald-500\/10, .education .bg-emerald-500 { background-color: #A6D20A !important; color: #FFFFFF !important; border: none !important; padding: 4px 12px !important; }
        .education .bg-amber-500\/10, .education .bg-amber-500 { background-color: #FFCC58 !important; color: #4E4E4D !important; border: none !important; padding: 4px 12px !important; }
        .education .bg-rose-500\/10, .education .bg-rose-500 { background-color: #F0445E !important; color: #FFFFFF !important; border: none !important; padding: 4px 12px !important; }

        /* Vibrant Subject Cards */
        .education [class*="subject-card-mathematics"] .subject-icon-container { background-color: #009AAF !important; color: #FFFFFF !important; }
        .education [class*="subject-card-science"] .subject-icon-container { background-color: #A6D20A !important; color: #FFFFFF !important; }
        .education [class*="subject-card-english"] .subject-icon-container { background-color: #FFCC58 !important; color: #4E4E4D !important; }
        .education [class*="subject-card-hindi"] .subject-icon-container { background-color: #F0445E !important; color: #FFFFFF !important; }
        .education [class*="subject-card-social-studies"] .subject-icon-container { background-color: #4E4E4D !important; color: #FFFFFF !important; }

        /* HIGH-POP Buttons */
        .education .bg-primary { background-color: #009AAF !important; box-shadow: 0 6px 20px rgba(0, 154, 175, 0.4) !important; font-weight: 700 !important; }
        .education .bg-primary:hover { transform: scale(1.02); }

        body {
            background-color: hsl(var(--background));
            color: hsl(var(--foreground));
        }

        .sidebar-bg {
            background-color: hsl(var(--sidebar-bg));
        }

        .nav-item-active {
            background-color: hsl(var(--accent));
            color: hsl(var(--foreground));
            font-weight: 600;
            box-shadow: inset 0 0 0 1px hsl(var(--border));
        }

        ::-webkit-scrollbar {
            width: 5px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: hsl(var(--muted));
            border-radius: 10px;
        }
        
        .premium-shadow {
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }

        .header-glass {
            background: hsla(var(--sidebar-bg), 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }
    </style>
    @stack('styles')
    <style>
        @media (max-width: 767px) {
            #sidebar {
                transform: translateX(-100%);
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
            }
            #sidebar.open {
                transform: translateX(0);
            }
            #sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 25;
            }
            #sidebar-overlay.open {
                display: block;
            }
        }
    </style>
</head>
<body class="font-sans antialiased selection:bg-primary selection:text-primary-foreground">
    <div class="flex h-screen overflow-hidden bg-background">
        <!-- Sidebar (Linear Inspired) -->
        <aside id="sidebar" class="w-[260px] border-r border-border h-full flex flex-col shrink-0 transition-all duration-300 z-30 sidebar-bg">
            <div class="h-[72px] flex items-center px-6 border-b border-border">
                <div class="flex items-center">
                    @php $adminLogo = \App\Models\Setting::get('website_logo') ?: 'assets/admin/logo/admin logo.png'; @endphp
                    <img src="{{ asset($adminLogo) }}" alt="{{ \App\Models\Setting::get('platform_name', 'Drumroll') }} Logo" class="h-16 w-auto object-contain">
                </div>
            </div>

            <!-- Command Search Trigger (Visual) -->
            <div class="px-4 mt-6">
                <button class="w-full flex items-center justify-between px-3 py-2 text-xs text-muted-foreground bg-background/50 border border-border rounded-lg hover:bg-background hover:border-border transition-all shadow-sm">
                    <div class="flex items-center gap-2">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Search...</span>
                    </div>
                    <kbd class="pointer-events-none hidden h-5 select-none items-center gap-1 rounded border bg-muted px-1.5 font-mono text-[10px] font-medium opacity-100 sm:flex border-border">
                        <span class="text-[10px]">⌘</span>K
                    </kbd>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5 scrollbar-hide">
                <div class="px-3 mb-2 flex items-center justify-between">
                    <span class="text-[10px] font-bold text-muted-foreground/60 uppercase tracking-widest">Main Menu</span>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-item-dashboard group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin') ? 'text-primary' : '' }}"></i>
                    <span>Dashboard</span>
                </a>
                
                <div class="px-3 mt-8 mb-2">
                    <span class="text-[10px] font-bold text-muted-foreground/60 uppercase tracking-widest">Management</span>
                </div>
                <a href="{{ route('admin.students.index') }}" class="sidebar-item-students group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin/students*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="users" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin/students*') ? 'text-primary' : '' }}"></i>
                    <span>Students</span>
                </a>
                <a href="{{ route('admin.subjects.index') }}" class="sidebar-item-subjects group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin/subjects*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="book-open" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin/subjects*') ? 'text-primary' : '' }}"></i>
                    <span>Subjects</span>
                </a>
                <a href="{{ route('admin.books.index') }}" class="sidebar-item-books group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin/books*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="library" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin/books*') ? 'text-primary' : '' }}"></i>
                    <span>Books</span>
                </a>
                <a href="{{ route('admin.book-purchases.index') }}" class="sidebar-item-group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin/book-purchases*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="shopping-bag" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin/book-purchases*') ? 'text-primary' : '' }}"></i>
                    <span>Book Purchases</span>
                </a>
                <a href="{{ route('admin.doubts.index') }}" class="sidebar-item-doubts group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin/doubts*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="help-circle" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin/doubts*') ? 'text-primary' : '' }}"></i>
                    <span>Doubts</span>
                </a>
                <a href="{{ route('admin.appointments.index') }}" class="sidebar-item-appointments group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin/appointments*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="calendar-check" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin/appointments*') ? 'text-primary' : '' }}"></i>
                    <span>Appointments</span>
                </a>
                <a href="{{ route('admin.slots.index') }}" class="sidebar-item-calendar group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin/calendar*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="calendar" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin/calendar*') ? 'text-primary' : '' }}"></i>
                    <span>Calendar</span>
                </a>

                <div class="px-3 mt-8 mb-2">
                    <span class="text-[10px] font-bold text-muted-foreground/60 uppercase tracking-widest">Financial</span>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="sidebar-item-payments group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin/payments*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="credit-card" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin/payments*') ? 'text-primary' : '' }}"></i>
                    <span>Payments</span>
                </a>
                <a href="{{ route('admin.refunds.index') }}" class="sidebar-item-refunds group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin/refunds*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="refresh-cw" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin/refunds*') ? 'text-primary' : '' }}"></i>
                    <span>Refunds</span>
                </a>
                <a href="{{ route('admin.invoices.index') }}" class="sidebar-item-invoices group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin/invoices*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="file-text" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin/invoices*') ? 'text-primary' : '' }}"></i>
                    <span>Invoices</span>
                </a>

                <div class="px-3 mt-8 mb-2">
                    <span class="text-[10px] font-bold text-muted-foreground/60 uppercase tracking-widest">System</span>
                </div>
                <a href="{{ route('admin.notification-center.index') }}" class="sidebar-item-notifications group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin/notifications*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="bell" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin/notifications*') ? 'text-primary' : '' }}"></i>
                    <span>Notifications</span>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="sidebar-item-reports group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin/reports*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin/reports*') ? 'text-primary' : '' }}"></i>
                    <span>Reports</span>
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="sidebar-item-audit-logs group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin/audit-logs*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="activity" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin/audit-logs*') ? 'text-primary' : '' }}"></i>
                    <span>Audit Logs</span>
                </a>
                <a href="{{ route('admin.roles.index') }}" class="sidebar-item-roles group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin/roles*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="shield-check" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin/roles*') ? 'text-primary' : '' }}"></i>
                    <span>Roles</span>
                </a>
                <a href="{{ route('admin.staff.index') }}" class="sidebar-item-staff group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->is('admin/staff*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="user-cog" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->is('admin/staff*') ? 'text-primary' : '' }}"></i>
                    <span>Staff</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="sidebar-item-settings group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->routeIs('admin.settings.*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="settings" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->routeIs('admin.settings.*') ? 'text-primary' : '' }}"></i>
                    <span>Settings</span>
                </a>
                <a href="{{ route('admin.website-settings.index') }}" class="sidebar-item-settings group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-accent/50 transition-all {{ request()->routeIs('admin.website-settings.*') ? 'nav-item-active !text-foreground' : '' }}">
                    <i data-lucide="globe" class="w-4 h-4 transition-colors group-hover:text-primary {{ request()->routeIs('admin.website-settings.*') ? 'text-primary' : '' }}"></i>
                    <span>Website Settings</span>
                </a>
            </nav>

            <div class="p-4 border-t border-border bg-muted/10">
                <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-accent/50 transition-all cursor-pointer group">
                    @php $adminUser = auth()->user(); @endphp
                    <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center border border-primary/20 shadow-inner group-hover:border-primary/40 transition-colors">
                        <span class="text-[10px] font-bold text-primary">{{ strtoupper(substr($adminUser->name, 0, 1) . (str_contains($adminUser->name, ' ') ? substr(str_replace(' ', '', $adminUser->name), 1, 1) : substr($adminUser->name, 1, 1))) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold truncate text-foreground">{{ $adminUser->name }}</p>
                        <p class="text-[10px] text-muted-foreground truncate">{{ $adminUser->email }}</p>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-muted-foreground hover:text-destructive transition-colors">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        <div id="sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
            <!-- Top Navigation (Vercel/shadcn style) -->
            <header class="h-[72px] border-b border-border header-glass flex items-center justify-between px-8 sticky top-0 z-20 shrink-0">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="md:hidden text-muted-foreground hover:text-foreground transition-colors">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <div class="flex items-center gap-2 text-[13px] font-medium text-muted-foreground/80">
                        <span class="hover:text-foreground cursor-pointer transition-colors">Admin</span>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 opacity-40"></i>
                        <span class="text-foreground font-semibold tracking-tight">@yield('page_title', 'Dashboard')</span>
                    </div>
                </div>

                <div class="flex items-center gap-5">
                    <div class="hidden sm:flex items-center gap-3">
                        <div class="relative group">
                            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors"></i>
                            <input type="text" placeholder="Quick search..." class="h-9 w-48 pl-9 pr-3 text-xs bg-muted/30 border border-transparent rounded-full focus:bg-background focus:border-border focus:w-64 transition-all outline-none">
                        </div>
                        <div class="relative group" id="admin-notification-dropdown">
                            <button onclick="toggleAdminNotifications()" class="w-9 h-9 rounded-full hover:bg-muted/50 flex items-center justify-center text-muted-foreground hover:text-foreground relative transition-all">
                                <i data-lucide="bell" class="w-4 h-4"></i>
                                @if($unreadNotifsCount > 0)
                                    <span class="absolute top-1.5 right-1.5 flex h-3 w-3 items-center justify-center rounded-full bg-primary text-[8px] font-bold text-primary-foreground ring-2 ring-background">{{ $unreadNotifsCount > 9 ? '9+' : $unreadNotifsCount }}</span>
                                @endif
                            </button>

                            <div id="admin-notifications-menu" class="absolute right-0 top-full mt-2 w-80 bg-card border border-border rounded-xl shadow-lg opacity-0 invisible transition-all z-50 overflow-hidden flex flex-col">
                                <div class="p-4 border-b border-border flex items-center justify-between bg-muted/30">
                                    <h3 class="font-bold text-foreground text-sm">Notifications</h3>
                                    @if($unreadNotifsCount > 0)
                                    <button onclick="markAllNotificationsRead()" class="text-[10px] font-bold text-primary hover:underline">Mark all read</button>
                                    @endif
                                </div>
                                <div class="flex-1 overflow-y-auto max-h-[300px] divide-y divide-border/50">
                                    @forelse($recentNotifs as $notif)
                                    <div class="p-4 hover:bg-muted/30 transition-colors {{ $notif->status === 'unread' ? 'bg-primary/5' : '' }}">
                                        <div class="flex gap-3">
                                            <div class="mt-0.5 shrink-0 w-8 h-8 rounded-full bg-background border border-border flex items-center justify-center text-muted-foreground">
                                                <i data-lucide="{{ $notif->icon ?? 'bell' }}" class="w-3.5 h-3.5 {{ $notif->status === 'unread' ? 'text-primary' : '' }}"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-2">
                                                    <p class="text-sm font-bold text-foreground truncate {{ $notif->status === 'unread' ? 'text-primary' : '' }}">{{ $notif->title }}</p>
                                                    <span class="text-[9px] text-muted-foreground whitespace-nowrap">{{ $notif->created_at->diffForHumans(null, true, true) }}</span>
                                                </div>
                                                <p class="text-xs text-muted-foreground line-clamp-2 mt-0.5">{{ $notif->message }}</p>
                                                <div class="flex items-center gap-3 mt-2">
                                                    @if($notif->url)
                                                    <a href="{{ $notif->url }}" class="text-[10px] font-bold text-primary hover:underline">View</a>
                                                    @endif
                                                    @if($notif->status === 'unread')
                                                    <button onclick="markNotificationRead({{ $notif->id }})" class="text-[10px] font-bold text-muted-foreground hover:text-foreground transition-colors">Mark read</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="p-6 text-center">
                                        <i data-lucide="bell-off" class="w-8 h-8 text-muted-foreground/50 mx-auto mb-2"></i>
                                        <p class="text-xs text-muted-foreground">No notifications yet.</p>
                                    </div>
                                    @endforelse
                                </div>
                                <div class="p-2 border-t border-border bg-muted/30">
                                    <a href="{{ route('admin.notification-center.index') }}" class="block w-full py-2 text-center text-xs font-bold text-muted-foreground hover:text-foreground hover:bg-background rounded-lg transition-colors">View All Notifications</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="h-6 w-[1px] bg-border/60 mx-1"></div>
                    <button id="theme-toggle" class="w-9 h-9 rounded-full hover:bg-muted/50 flex items-center justify-center text-muted-foreground hover:text-foreground transition-all active:scale-90 border border-transparent hover:border-border/50">
                        <i data-lucide="sun" class="w-4 h-4 hidden"></i>
                        <i data-lucide="moon" class="w-4 h-4 block education:hidden"></i>
                        <i data-lucide="book-open" class="w-4 h-4 hidden education:block"></i>
                    </button>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 overflow-y-auto p-8 scroll-smooth bg-background/30">
                <div class="w-full space-y-8 animate-in fade-in duration-500">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Reusable Confirmation Modal -->
    <div id="confirm-modal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-background/80 backdrop-blur-sm"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-card w-full max-w-sm rounded-xl border border-border shadow-2xl animate-in zoom-in-95 duration-200">
                <div class="p-6">
                    <div class="w-10 h-10 rounded-full bg-rose-500/10 text-rose-500 flex items-center justify-center mb-4">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                    <h3 id="confirm-modal-title" class="text-lg font-bold text-foreground">Confirm Action</h3>
                    <p id="confirm-modal-message" class="text-sm text-muted-foreground mt-2">Are you sure you want to proceed with this action?</p>
                </div>
                <div class="flex items-center justify-end gap-3 p-4 border-t border-border bg-muted/30 rounded-b-xl">
                    <button id="modal-cancel" class="px-4 py-2 text-sm font-semibold text-muted-foreground hover:text-foreground transition-colors">Cancel</button>
                    <button id="modal-confirm" class="px-4 py-2 bg-rose-500 text-white text-sm font-bold rounded-lg hover:opacity-90 shadow-sm transition-all">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification System -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-[100] flex flex-col gap-3"></div>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Theme Toggle Logic (Light -> Dark -> Education -> Light)
        const themeToggle = document.getElementById('theme-toggle');
        themeToggle.addEventListener('click', () => {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                html.classList.add('education');
                localStorage.setItem('theme', 'education');
            } else if (html.classList.contains('education')) {
                html.classList.remove('education');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            lucide.createIcons();
        });

        // Toast System
        window.toast = {
            show: function(message, type = 'success') {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = `flex items-center gap-3 px-4 py-3 rounded-xl border shadow-xl animate-in slide-in-from-right duration-300 min-w-[300px] ${
                    type === 'success' ? 'bg-card border-emerald-500/20 text-foreground' : 'bg-card border-rose-500/20 text-foreground'
                }`;
                
                const icon = type === 'success' ? 'check-circle' : 'alert-circle';
                const iconColor = type === 'success' ? 'text-emerald-500' : 'text-rose-500';

                toast.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-${type === 'success' ? 'emerald' : 'rose'}-500/10 flex items-center justify-center shrink-0">
                        <i data-lucide="${icon}" class="w-4 h-4 ${iconColor}"></i>
                    </div>
                    <p class="text-sm font-semibold flex-1">${message}</p>
                    <button onclick="this.parentElement.remove()" class="text-muted-foreground hover:text-foreground">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    </button>
                `;

                container.appendChild(toast);
                lucide.createIcons();

                setTimeout(() => {
                    toast.classList.add('animate-out', 'fade-out', 'slide-out-to-right');
                    setTimeout(() => toast.remove(), 300);
                }, 4000);
            },
            success: function(msg) { this.show(msg, 'success'); },
            error: function(msg) { this.show(msg, 'error'); }
        };

        // Confirmation Modal System
        window.confirmAction = function({ title, message, onConfirm }) {
            const modal = document.getElementById('confirm-modal');
            const titleEl = document.getElementById('confirm-modal-title');
            const messageEl = document.getElementById('confirm-modal-message');
            const confirmBtn = document.getElementById('modal-confirm');
            const cancelBtn = document.getElementById('modal-cancel');

            titleEl.textContent = title || 'Confirm Action';
            messageEl.innerHTML = message || 'Are you sure you want to proceed?';
            modal.classList.remove('hidden');

            const closeModal = () => modal.classList.add('hidden');

            confirmBtn.onclick = () => {
                onConfirm();
                closeModal();
            };
            cancelBtn.onclick = closeModal;
        };

        // Admin Notifications Logic
        function toggleAdminNotifications() {
            const menu = document.getElementById('admin-notifications-menu');
            if (menu.classList.contains('opacity-0')) {
                menu.classList.remove('opacity-0', 'invisible');
            } else {
                menu.classList.add('opacity-0', 'invisible');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('admin-notification-dropdown');
            if (dropdown && !dropdown.contains(event.target)) {
                document.getElementById('admin-notifications-menu').classList.add('opacity-0', 'invisible');
            }
        });

        async function markNotificationRead(id) {
            try {
                const response = await fetch('{{ url("/admin/notification-center") }}/' + id + '/read', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                if (response.ok) {
                    location.reload();
                }
            } catch (e) {
                if (window.toast) window.toast.error('Failed to mark notification as read.');
            }
        }

        async function markAllNotificationsRead() {
            try {
                const response = await fetch('{{ route("admin.notification-center.mark-all-read") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                if (response.ok) {
                    location.reload();
                }
            } catch (e) {
                if (window.toast) window.toast.error('Failed to mark all as read.');
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                if (window.toast) window.toast.success(@json(session('success')));
            @endif
            @if($errors->any())
                @foreach($errors->all() as $error)
                    if (window.toast) window.toast.error(@json($error));
                @endforeach
            @endif
        });
    </script>
    <script src="{{ asset('assets/admin/js/datatable.js') }}"></script>
    @stack('scripts')
</body>
</html>

