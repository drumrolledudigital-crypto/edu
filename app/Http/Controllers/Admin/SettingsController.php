<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Services\GoogleCalendarService;
use App\Services\GoogleOAuthService;
use App\Services\IntegrationAutomationService;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index(GoogleOAuthService $googleOAuthService, GoogleCalendarService $googleCalendarService)
    {
        $settings = Setting::all()->pluck('value', 'key');
        
        // Mask sensitive values instead of decrypting them for display
        $sensitiveKeys = ['stripe_publishable_key', 'stripe_secret_key', 'google_client_id', 'google_client_secret', 'smtp_password'];
        foreach ($sensitiveKeys as $key) {
            if (isset($settings[$key]) && $settings[$key]) {
                $settings[$key] = '••••••••';
            }
        }
        
        $admin = Auth::user();
        $googleAccount = $googleOAuthService->connectedAccount($admin);
        $googleCalendars = [];
        $googleConnectionError = null;

        if ($googleAccount) {
            try {
                $googleCalendars = $googleCalendarService->listCalendars();
            } catch (\Throwable $exception) {
                $googleConnectionError = $exception->getMessage();
            }
        }

        return view('admin.settings.index', compact('settings', 'admin', 'googleAccount', 'googleCalendars', 'googleConnectionError'));
    }

    public function update(Request $request, \App\Services\AuditLoggerService $logger)
    {
        $request->validate([
            'session_duration' => 'nullable|integer|min:15|max:180',
            'session_price' => 'nullable|numeric|min:0',
            'advance_booking_days' => 'nullable|integer|min:1|max:365',
            'invoice_starting_number' => 'nullable|integer|min:1',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
        ]);

        $keys = [
            // General
            'platform_name',
            'platform_logo',
            'platform_favicon',
            'support_email',
            'support_phone',
            'contact_email',
            'address',
            'timezone',
            'currency',
            
            // Session
            'session_duration',
            'session_price',
            'advance_booking_days',
            'booking_rules',
            'cancellation_rules',
            'reschedule_rules',
            
            // Stripe
            'stripe_publishable_key',
            'stripe_secret_key',
            'stripe_mode',
            
            // SMTP
            'smtp_host',
            'smtp_port',
            'smtp_username',
            'smtp_password',
            'smtp_encryption',
            'smtp_from_name',
            'smtp_from_address',
            
            // Google
            'google_client_id',
            'google_client_secret',
            'google_redirect_uri',
            'google_calendar_id',
            'google_default_calendar',
            
            // Invoice
            'invoice_prefix',
            'invoice_starting_number',
            'invoice_currency',
            
            // Notifications (Checkboxes)
            'notify_registration',
            'notify_booking',
            'notify_payment',
            'notify_reminder',
            'notify_reschedule',
            'notify_refund',
            
            // Old keys that might still be checked
            'smtp_enabled',
            'google_meet_auto_generate',
            'google_calendar_auto_create',
            'google_calendar_auto_update',
            'google_calendar_auto_delete',
        ];

        $data = $request->only($keys);

        $checkboxes = [
            'notify_registration',
            'notify_booking',
            'notify_payment',
            'notify_reminder',
            'notify_reschedule',
            'notify_refund',
            'smtp_enabled',
            'google_meet_auto_generate',
            'google_calendar_auto_create',
            'google_calendar_auto_update',
            'google_calendar_auto_delete',
        ];

        foreach ($checkboxes as $checkbox) {
            $data[$checkbox] = $request->has($checkbox) ? '1' : '0';
        }

        foreach ($data as $key => $value) {
            if ($value !== null && $value !== '••••••••') {
                Setting::set($key, $value, $this->getGroup($key));
            }
        }
        
        $logger->log('Settings', 'Update', 'System settings were updated.', null, $data);
        
        // Clear cache
        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        return back()->with('success', 'Settings updated successfully.');
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

        if (!$admin) {
            return back()->withErrors(['error' => 'No admin user found to update.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'current_password' => 'required_with:password|current_password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function redirectGoogle(GoogleOAuthService $googleOAuthService)
    {
        try {
            return redirect()->away($googleOAuthService->getAuthUrl());
        } catch (\Throwable $exception) {
            return back()->withErrors(['google' => $exception->getMessage()]);
        }
    }

    public function callbackGoogle(Request $request, GoogleOAuthService $googleOAuthService)
    {
        if ($request->filled('error')) {
            return redirect()->route('admin.settings.index')->withErrors(['google' => $request->get('error_description', $request->get('error'))]);
        }

        $request->validate([
            'code' => 'required|string',
            'state' => 'required|string',
        ]);

        try {
            $account = $googleOAuthService->handleCallback($request->code, $request->state, Auth::user());

            Setting::set('google_meet_connected', '1', 'integrations');
            Setting::set('google_calendar_connected', '1', 'integrations');
            Setting::set('google_account_email', $account->google_email, 'integrations');
            Setting::set('google_connection_status', 'connected', 'integrations');
            Setting::set('google_last_sync_at', now()->toDateTimeString(), 'integrations');

            return redirect()->route('admin.settings.index')->with('success', 'Google account connected successfully.');
        } catch (\Throwable $exception) {
            return redirect()->route('admin.settings.index')->withErrors(['google' => $exception->getMessage()]);
        }
    }

    public function disconnectGoogle(GoogleOAuthService $googleOAuthService)
    {
        try {
            $googleOAuthService->disconnect(Auth::user());
        } catch (\Throwable $exception) {
            return back()->withErrors(['google' => $exception->getMessage()]);
        }

        Setting::set('google_meet_connected', '0', 'integrations');
        Setting::set('google_calendar_connected', '0', 'integrations');
        Setting::set('google_connection_status', 'disconnected', 'integrations');
        Setting::set('google_account_email', '', 'integrations');

        return back()->with('success', 'Google account disconnected successfully.');
    }

    public function testGoogle(GoogleOAuthService $googleOAuthService, GoogleCalendarService $calendarService)
    {
        try {
            $email = $googleOAuthService->testConnection(Auth::user());
            $calendarAccess = $googleOAuthService->testCalendarAccess(Auth::user());
            Setting::set('google_last_sync_at', now()->toDateTimeString(), 'integrations');

            $message = "Google integration connection is active for {$email}.";
            if ($calendarAccess) {
                $message .= " Calendar API read access verified.";
            } else {
                $message .= " Warning: Calendar API access could not be verified.";
            }

            $writeCheck = $calendarService->diagnoseCalendarAccess();
            if ($writeCheck['can_write']) {
                $calName = $writeCheck['calendar_name'] ?? $writeCheck['calendar_id'];
                $message .= " Write access confirmed ({$writeCheck['access_level']}) on [{$calName}].";
            } else {
                $message .= " Warning: {$writeCheck['recommendation']}";
                if ($writeCheck['calendar_id'] !== 'primary' && $writeCheck['primary_fallback_available']) {
                    $calendarService->switchToPrimaryCalendar();
                    $message .= " Switched to primary calendar.";
                }
            }

            return back()->with('success', $message);
        } catch (\Throwable $exception) {
            return back()->withErrors(['google' => 'Google integration test failed: ' . $exception->getMessage()]);
        }
    }

    public function verifyCalendar(GoogleCalendarService $calendarService)
    {
        $diagnosis = $calendarService->diagnoseCalendarAccess();

        $lines = [];
        $lines[] = "Connected Account: {$diagnosis['connected_email']}";
        $lines[] = "Calendar ID: {$diagnosis['calendar_id']}";
        $calName = $diagnosis['calendar_name'] ?? 'N/A';
        $accessLevel = $diagnosis['access_level'] ?? 'N/A';
        $lines[] = "Calendar Name: {$calName}";
        $lines[] = "Access Level: {$accessLevel}";
        $lines[] = "Can Write: " . ($diagnosis['can_write'] ? 'Yes' : 'No');

        if ($diagnosis['error']) {
            $lines[] = "Error: {$diagnosis['error']}";
        }

        $lines[] = "Recommendation: {$diagnosis['recommendation']}";

        if (!$diagnosis['can_write'] && $diagnosis['calendar_id'] !== 'primary' && $diagnosis['primary_fallback_available']) {
            $calendarService->switchToPrimaryCalendar();
            $lines[] = "Action: Switched to primary calendar automatically.";
        }

        return back()->with('success', implode("\n", $lines));
    }

    public function testMeet(GoogleCalendarService $calendarService)
    {
        $result = $calendarService->testMeetConference();

        if ($result['success']) {
            $msg = "Google Meet conference creation is working.";
            $msg .= " Meet Link: {$result['meet_link']}";
            $msg .= " Test event created and deleted successfully.";
            return back()->with('success', $msg);
        } else {
            return back()->withErrors(['google' => "Meet test failed: {$result['error']}"]);
        }
    }

    public function switchCalendar(GoogleCalendarService $calendarService)
    {
        if ($calendarService->switchToPrimaryCalendar()) {
            return back()->with('success', 'Switched to primary calendar. Events will be created in your connected Google account\'s primary calendar.');
        }
        return back()->withErrors(['google' => 'Failed to switch calendar. Primary calendar not accessible.']);
    }

    public function sendTestEmail(Request $request, IntegrationAutomationService $automation)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        $log = $automation->sendTestEmail($request->test_email);

        if ($log->status === 'failed') {
            return back()->withErrors(['smtp' => 'SMTP test failed: ' . $log->error_message]);
        }

        return back()->with('success', 'SMTP test email sent successfully.');
    }

    public function verifySmtp()
    {
        $required = ['smtp_host', 'smtp_port', 'smtp_username', 'smtp_password'];
        $missing = collect($required)->filter(fn ($key) => blank(Setting::get($key)))->values();

        if ($missing->isNotEmpty()) {
            return back()->withErrors(['smtp' => 'SMTP configuration incomplete. Missing: ' . $missing->implode(', ')]);
        }

        return back()->with('success', 'SMTP configuration fields are complete.');
    }

    private function getGroup($key)
    {
        if (str_starts_with($key, 'stripe_')) return 'stripe';
        if (str_starts_with($key, 'google_')) return 'google';
        if (str_starts_with($key, 'smtp_') || str_starts_with($key, 'mail_')) return 'smtp';
        if (str_starts_with($key, 'notify_')) return 'notifications';
        if (str_starts_with($key, 'invoice_')) return 'invoice';
        if (str_starts_with($key, 'session_') || str_contains($key, '_rules') || str_starts_with($key, 'advance_booking')) return 'session';
        return 'general';
    }
}
