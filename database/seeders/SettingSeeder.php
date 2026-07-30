<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'platform_name', 'value' => 'Drumroll Edu', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'contact@drumroll.com', 'group' => 'general'],
            ['key' => 'theme_mode', 'value' => 'light', 'group' => 'theme'],
            ['key' => 'smtp_enabled', 'value' => '0', 'group' => 'smtp'],
            ['key' => 'mail_driver', 'value' => 'smtp', 'group' => 'smtp'],
            ['key' => 'smtp_host', 'value' => '', 'group' => 'smtp'],
            ['key' => 'smtp_port', 'value' => '587', 'group' => 'smtp'],
            ['key' => 'smtp_username', 'value' => '', 'group' => 'smtp'],
            ['key' => 'smtp_password', 'value' => '', 'group' => 'smtp'],
            ['key' => 'smtp_encryption', 'value' => 'tls', 'group' => 'smtp'],
            ['key' => 'google_meet_connected', 'value' => '0', 'group' => 'integrations'],
            ['key' => 'google_calendar_connected', 'value' => '0', 'group' => 'integrations'],
            ['key' => 'google_connection_status', 'value' => 'disconnected', 'group' => 'integrations'],
            ['key' => 'google_account_email', 'value' => '', 'group' => 'integrations'],
            ['key' => 'google_calendar_id', 'value' => 'primary', 'group' => 'integrations'],
            ['key' => 'google_default_calendar', 'value' => 'Primary Calendar', 'group' => 'integrations'],
            ['key' => 'google_meet_auto_generate', 'value' => '1', 'group' => 'integrations'],
            ['key' => 'google_meet_regenerate_on_reschedule', 'value' => '0', 'group' => 'integrations'],
            ['key' => 'google_calendar_auto_create', 'value' => '1', 'group' => 'integrations'],
            ['key' => 'google_calendar_auto_update', 'value' => '1', 'group' => 'integrations'],
            ['key' => 'google_calendar_auto_delete', 'value' => '1', 'group' => 'integrations'],
            ['key' => 'google_client_id', 'value' => '', 'group' => 'integrations'],
            ['key' => 'google_client_secret', 'value' => '', 'group' => 'integrations'],
            ['key' => 'google_redirect_uri', 'value' => '', 'group' => 'integrations'],
            ['key' => 'session_price', 'value' => '32.00', 'group' => 'session'],
            ['key' => 'session_duration', 'value' => '50', 'group' => 'session'],
            ['key' => 'currency', 'value' => 'USD', 'group' => 'general'],
            ['key' => 'notify_new_registration', 'value' => '1', 'group' => 'notifications'],
            ['key' => 'notify_new_booking', 'value' => '1', 'group' => 'notifications'],
            ['key' => 'notify_payment_received', 'value' => '1', 'group' => 'notifications'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
