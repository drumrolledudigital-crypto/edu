@extends('layouts.admin')

@section('title', 'System Settings')
@section('page_title', 'Configuration')

@section('content')
<div class="space-y-6 pb-12">
    
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Platform Settings</h2>
            <p class="text-sm text-muted-foreground mt-1">Manage global configuration for your application.</p>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-card border border-border rounded-xl p-2 shadow-sm overflow-x-auto scrollbar-hide">
        <nav class="flex space-x-2" aria-label="Tabs" id="settings-tabs">
            <button onclick="switchTab('session')" class="tab-btn px-4 py-2 text-sm font-semibold rounded-lg bg-primary/10 text-primary transition-all whitespace-nowrap">Session Rules</button>
            <button onclick="switchTab('stripe')" class="tab-btn px-4 py-2 text-sm font-semibold rounded-lg text-muted-foreground hover:bg-accent/50 hover:text-foreground transition-all whitespace-nowrap">Stripe Payments</button>
            <button onclick="switchTab('smtp')" class="tab-btn px-4 py-2 text-sm font-semibold rounded-lg text-muted-foreground hover:bg-accent/50 hover:text-foreground transition-all whitespace-nowrap">SMTP Email</button>
            <button onclick="switchTab('google')" class="tab-btn px-4 py-2 text-sm font-semibold rounded-lg text-muted-foreground hover:bg-accent/50 hover:text-foreground transition-all whitespace-nowrap">Google Integrations</button>
            <button onclick="switchTab('invoice')" class="tab-btn px-4 py-2 text-sm font-semibold rounded-lg text-muted-foreground hover:bg-accent/50 hover:text-foreground transition-all whitespace-nowrap">Invoice</button>
            <button onclick="switchTab('notifications')" class="tab-btn px-4 py-2 text-sm font-semibold rounded-lg text-muted-foreground hover:bg-accent/50 hover:text-foreground transition-all whitespace-nowrap">Notifications</button>
        </nav>
    </div>

    <!-- Google OAuth Connection Card (OUTSIDE main form to prevent nested form issue) -->
    <div id="tab-google-oauth" class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
        <div class="px-6 py-5 border-b border-border flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-foreground">Google OAuth</h3>
                <p class="text-sm text-muted-foreground">Connect Admin Google Account for Meet & Calendar.</p>
            </div>
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" alt="Google" class="w-6 h-6">
        </div>
        <div class="p-6 space-y-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    @if(\App\Models\GoogleAccount::where('user_id', auth()->id())->exists())
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-sm font-bold text-emerald-600">Connected</span>
                        </div>
                        <p class="text-xs text-muted-foreground mt-1">Ready for Calendar & Meet automations.</p>
                    @else
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span class="text-sm font-bold text-amber-600">Not Connected</span>
                        </div>
                        <p class="text-xs text-muted-foreground mt-1">Required for automated Meet links and Calendar events.</p>
                    @endif
                </div>
                <div class="flex gap-2">
                    @if(\App\Models\GoogleAccount::where('user_id', auth()->id())->exists())
                        <form action="{{ route('admin.settings.google-test') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 border border-border bg-background hover:bg-accent rounded-lg text-sm font-bold transition-all shadow-sm">Test Connection</button>
                        </form>
                        <form action="{{ route('admin.settings.google-disconnect') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-rose-500 text-white rounded-lg text-sm font-bold hover:bg-rose-600 transition-all shadow-sm">Disconnect</button>
                        </form>
                    @else
                        <form action="{{ route('admin.settings.google-connect') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white hover:bg-primary-700 rounded-lg text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                                Connect Google Account
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <p class="text-[10px] text-muted-foreground">Configure OAuth credentials in the Google tab below, then connect your account.</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Session Settings Tab -->
        <div id="tab-session" class="tab-content block">
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Session & Booking Rules</h3>
                    <p class="text-sm text-muted-foreground">Configure how students book and manage sessions.</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Session Price ({{ strtoupper($settings['currency'] ?? 'USD') }})</label>
                        <input type="number" name="session_price" value="{{ $settings['session_price'] ?? '32.00' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none" min="1" step="0.01">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Default Session Duration (Minutes)</label>
                        <input type="number" name="session_duration" value="{{ $settings['session_duration'] ?? '50' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none" min="15" max="180">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Advance Booking Days</label>
                        <input type="number" name="advance_booking_days" value="{{ $settings['advance_booking_days'] ?? '30' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none" min="1" max="365">
                        <p class="text-[10px] text-muted-foreground mt-1">How far in advance students can book a session.</p>
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">General Booking Rules</label>
                        <textarea name="booking_rules" rows="3" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">{{ $settings['booking_rules'] ?? '' }}</textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Cancellation Rules</label>
                        <textarea name="cancellation_rules" rows="3" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">{{ $settings['cancellation_rules'] ?? '' }}</textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Reschedule Rules</label>
                        <textarea name="reschedule_rules" rows="3" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">{{ $settings['reschedule_rules'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stripe Settings Tab -->
        <div id="tab-stripe" class="tab-content hidden">
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                <div class="px-6 py-5 border-b border-border flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-foreground">Stripe Configuration</h3>
                        <p class="text-sm text-muted-foreground">Manage payment gateway credentials.</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#635BFF]/10 text-[#635BFF] flex items-center justify-center">
                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <div class="p-4 bg-muted/30 border border-border rounded-xl">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="stripe_mode" value="test" {{ ($settings['stripe_mode'] ?? 'test') === 'test' ? 'checked' : '' }} class="w-4 h-4 text-primary focus:ring-primary border-border bg-background">
                            <span class="text-sm font-bold text-foreground">Test Mode</span>
                        </label>
                        <div class="mt-4 border-t border-border pt-4">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" name="stripe_mode" value="live" {{ ($settings['stripe_mode'] ?? 'test') === 'live' ? 'checked' : '' }} class="w-4 h-4 text-emerald-500 focus:ring-emerald-500 border-border bg-background">
                                <span class="text-sm font-bold text-emerald-600">Live Mode</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Publishable Key</label>
                        <input type="text" name="stripe_publishable_key" value="{{ $settings['stripe_publishable_key'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Secret Key</label>
                        <input type="password" name="stripe_secret_key" value="{{ $settings['stripe_secret_key'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- SMTP Settings Tab -->
        <div id="tab-smtp" class="tab-content hidden">
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                <div class="px-6 py-5 border-b border-border flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-foreground">SMTP Email Setup</h3>
                        <p class="text-sm text-muted-foreground">Configure email delivery parameters.</p>
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="smtp_enabled" value="1" {{ ($settings['smtp_enabled'] ?? '0') === '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary border-border bg-background transition-all">
                        <span class="text-sm font-bold text-foreground">Enable SMTP</span>
                    </label>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">SMTP Host</label>
                        <input type="text" name="smtp_host" value="{{ $settings['smtp_host'] ?? '' }}" placeholder="smtp.mailgun.org" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">SMTP Port</label>
                        <input type="text" name="smtp_port" value="{{ $settings['smtp_port'] ?? '' }}" placeholder="587" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">SMTP Username</label>
                        <input type="text" name="smtp_username" value="{{ $settings['smtp_username'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">SMTP Password</label>
                        <input type="password" name="smtp_password" value="{{ $settings['smtp_password'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Encryption (tls/ssl)</label>
                        <input type="text" name="smtp_encryption" value="{{ $settings['smtp_encryption'] ?? 'tls' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">From Email Address</label>
                        <input type="email" name="smtp_from_address" value="{{ $settings['smtp_from_address'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">From Name</label>
                        <input type="text" name="smtp_from_name" value="{{ $settings['smtp_from_name'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- Google Meet & Calendar Settings Tab (inside form for saving) -->
        <div id="tab-google" class="tab-content hidden">
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Google OAuth Credentials</h3>
                    <p class="text-sm text-muted-foreground">Get these from <a href="https://console.cloud.google.com/apis/credentials" target="_blank" class="text-primary underline">Google Cloud Console</a>. After saving, reconnect your Google account.</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Client ID</label>
                        <input type="text" name="google_client_id" value="{{ $settings['google_client_id'] ?? '' }}" placeholder="xxxx.apps.googleusercontent.com" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none font-mono">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Client Secret</label>
                        <input type="password" name="google_client_secret" value="{{ $settings['google_client_secret'] ?? '' }}" placeholder="GOCSPX-..." class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none font-mono">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Redirect URI</label>
                        <input type="text" value="{{ url('/admin/settings/google-callback') }}" readonly class="w-full bg-muted/50 border border-border rounded-lg p-3 text-sm text-muted-foreground font-mono">
                        <p class="text-[10px] text-muted-foreground mt-1">Add this exact URL in Google Cloud Console → Authorized redirect URIs.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm mt-6">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Meet & Calendar Settings</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-muted/30 border border-border rounded-xl">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="google_meet_auto_generate" value="1" {{ ($settings['google_meet_auto_generate'] ?? '1') === '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary border-border bg-background transition-all">
                                <div>
                                    <span class="text-sm font-bold text-foreground block">Auto-generate Meet Links</span>
                                    <span class="text-[10px] text-muted-foreground">Generate link instantly after payment success.</span>
                                </div>
                            </label>
                        </div>
                        <div class="p-4 bg-muted/30 border border-border rounded-xl">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="google_calendar_auto_create" value="1" {{ ($settings['google_calendar_auto_create'] ?? '1') === '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary border-border bg-background transition-all">
                                <div>
                                    <span class="text-sm font-bold text-foreground block">Auto-sync Calendar</span>
                                    <span class="text-[10px] text-muted-foreground">Create Calendar event alongside Meet link.</span>
                                </div>
                            </label>
                        </div>
                        <div class="p-4 bg-muted/30 border border-border rounded-xl">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="google_calendar_auto_update" value="1" {{ ($settings['google_calendar_auto_update'] ?? '1') === '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary border-border bg-background transition-all">
                                <div>
                                    <span class="text-sm font-bold text-foreground block">Auto-update on Reschedule</span>
                                    <span class="text-[10px] text-muted-foreground">Update Calendar event when appointment is rescheduled.</span>
                                </div>
                            </label>
                        </div>
                        <div class="p-4 bg-muted/30 border border-border rounded-xl">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="google_calendar_auto_delete" value="1" {{ ($settings['google_calendar_auto_delete'] ?? '1') === '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary border-border bg-background transition-all">
                                <div>
                                    <span class="text-sm font-bold text-foreground block">Auto-delete on Cancel</span>
                                    <span class="text-[10px] text-muted-foreground">Remove Calendar event when appointment is cancelled.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Target Google Calendar</label>
                        @if(empty($googleCalendars))
                            <select name="google_default_calendar" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                                <option value="primary">Primary Calendar (Default)</option>
                            </select>
                            <p class="text-[10px] text-amber-500 font-bold mt-1">Connect account to load available calendars.</p>
                        @else
                            <select name="google_calendar_id" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                                @foreach($googleCalendars as $calId => $calName)
                                    <option value="{{ $calId }}" {{ ($settings['google_calendar_id'] ?? 'primary') === $calId ? 'selected' : '' }}>{{ $calName }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="border-t border-border pt-6">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-3 block">Diagnostics & Verification</label>
                        <div class="flex flex-wrap gap-3">
                            <form action="{{ route('admin.settings.verify-calendar') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 border border-border bg-background hover:bg-accent rounded-lg text-sm font-bold transition-all shadow-sm flex items-center gap-2">
                                    <i data-lucide="shield-check" class="w-4 h-4"></i> Verify Calendar Access
                                </button>
                            </form>
                            <form action="{{ route('admin.settings.test-meet') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 border border-border bg-background hover:bg-accent rounded-lg text-sm font-bold transition-all shadow-sm flex items-center gap-2">
                                    <i data-lucide="video" class="w-4 h-4"></i> Test Meet Generation
                                </button>
                            </form>
                            <form action="{{ route('admin.settings.switch-calendar') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 border border-amber-300 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg text-sm font-bold transition-all shadow-sm flex items-center gap-2">
                                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Switch to Primary Calendar
                                </button>
                            </form>
                        </div>
                        <p class="text-[10px] text-muted-foreground mt-2">Use diagnostics to verify calendar permissions and Meet conference support before saving settings.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Settings Tab -->
        <div id="tab-invoice" class="tab-content hidden">
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Invoice Configuration</h3>
                    <p class="text-sm text-muted-foreground">Customize automated invoice generation details.</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Invoice Prefix</label>
                        <input type="text" name="invoice_prefix" value="{{ $settings['invoice_prefix'] ?? 'INV-' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                        <p class="text-[10px] text-muted-foreground mt-1">e.g. INV-2026-</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Starting Sequence Number</label>
                        <input type="number" name="invoice_starting_number" value="{{ $settings['invoice_starting_number'] ?? '1001' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Invoice Currency Display</label>
                        <input type="text" name="invoice_currency" value="{{ $settings['invoice_currency'] ?? 'USD' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Settings Tab -->
        <div id="tab-notifications" class="tab-content hidden">
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Automated Notifications</h3>
                    <p class="text-sm text-muted-foreground">Select which events trigger automated student emails.</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php
                        $notificationEvents = [
                            'notify_registration' => 'Student Registration Success',
                            'notify_booking' => 'New Booking Created',
                            'notify_payment' => 'Payment Successful',
                            'notify_reminder' => 'Session Reminder (Upcoming)',
                            'notify_reschedule' => 'Session Rescheduled',
                            'notify_refund' => 'Refund Processed'
                        ];
                    @endphp

                    @foreach($notificationEvents as $key => $label)
                    <label class="flex items-center gap-3 p-4 border border-border rounded-xl cursor-pointer hover:bg-muted/30 transition-colors">
                        <input type="checkbox" name="{{ $key }}" value="1" {{ ($settings[$key] ?? '1') === '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary border-border bg-background transition-all">
                        <span class="text-sm font-bold text-foreground">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Submit Button (Sticky at bottom) -->
        <div class="pt-6 border-t border-border flex justify-end">
            <button type="submit" class="bg-primary text-primary-foreground hover:bg-primary/90 px-8 py-3 rounded-xl text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Save Settings
            </button>
        </div>

    </form>

    <!-- SMTP Test Email Card (outside main form to avoid nesting) -->
    <div id="tab-smtp-test" class="rounded-xl border border-border bg-card text-card-foreground shadow-sm hidden">
        <div class="px-6 py-5 border-b border-border flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-foreground">Send Test Email</h3>
                <p class="text-sm text-muted-foreground">Verify your SMTP configuration by sending a test email.</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                <i data-lucide="mail" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.settings.smtp-test') }}" method="POST">
                @csrf
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <input type="email" name="test_email" required placeholder="Enter email address to test"
                            class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg text-sm font-bold shadow-sm transition-all flex items-center gap-2 whitespace-nowrap">
                        <i data-lucide="send" class="w-4 h-4"></i> Send Test Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('bg-primary/10', 'text-primary');
            el.classList.add('text-muted-foreground', 'hover:bg-accent/50', 'hover:text-foreground');
        });

        const oauthCard = document.getElementById('tab-google-oauth');
        const smtpTestCard = document.getElementById('tab-smtp-test');
        oauthCard.classList.add('hidden');
        if (smtpTestCard) smtpTestCard.classList.add('hidden');

        if (tabId === 'google') {
            oauthCard.classList.remove('hidden');
        }
        if (tabId === 'smtp' && smtpTestCard) {
            smtpTestCard.classList.remove('hidden');
        }

        document.getElementById(`tab-${tabId}`).classList.remove('hidden');

        const activeBtn = Array.from(document.querySelectorAll('.tab-btn')).find(el => el.getAttribute('onclick') === `switchTab('${tabId}')`);
        if(activeBtn) {
            activeBtn.classList.remove('text-muted-foreground', 'hover:bg-accent/50', 'hover:text-foreground');
            activeBtn.classList.add('bg-primary/10', 'text-primary');
        }
    }
</script>
@endpush
@endsection
