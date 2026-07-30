<?php

namespace App\Services;

use App\Models\EmailLog;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class SmtpMailService
{
    public function isConfigured(): bool
    {
        return Setting::get('smtp_enabled', '0') === '1'
            && filled(Setting::get('smtp_host'))
            && filled(Setting::get('smtp_port'))
            && filled(Setting::get('smtp_username'))
            && filled(Setting::get('smtp_password'))
            && filled(Setting::get('smtp_from_address', Setting::get('contact_email')));
    }

    public function missingFields(): array
    {
        $fields = [
            'smtp_enabled' => Setting::get('smtp_enabled', '0') === '1' ? '1' : null,
            'smtp_host' => Setting::get('smtp_host'),
            'smtp_port' => Setting::get('smtp_port'),
            'smtp_username' => Setting::get('smtp_username'),
            'smtp_password' => Setting::get('smtp_password'),
            'smtp_from_address/contact_email' => Setting::get('smtp_from_address', Setting::get('contact_email')),
        ];

        return collect($fields)->filter(fn ($value) => blank($value))->keys()->all();
    }

    public function statusLabel(): string
    {
        return $this->isConfigured() ? 'Configured' : 'SMTP Not Configured';
    }

    public function sendLoggedEmail(EmailLog $log, string $view, array $data = []): EmailLog
    {
        if (!$this->isConfigured()) {
            $message = 'SMTP Not Configured. Missing: ' . implode(', ', $this->missingFields());
            $log->update([
                'status' => 'failed',
                'error_message' => $message,
            ]);

            throw new RuntimeException($message);
        }

        $this->applySettings();

        try {
            Mail::send($view, $data, function ($message) use ($log) {
                $message->to($log->recipient)->subject($log->subject);
            });

            $log->update([
                'status' => 'sent',
                'error_message' => null,
                'sent_at' => now(),
            ]);
        } catch (\Throwable $exception) {
            $log->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }

        return $log->refresh();
    }

    public function applySettings(): void
    {
        Config::set('mail.default', Setting::get('mail_driver', 'smtp'));
        Config::set('mail.mailers.smtp.host', Setting::get('smtp_host'));
        Config::set('mail.mailers.smtp.port', Setting::get('smtp_port'));
        Config::set('mail.mailers.smtp.username', Setting::get('smtp_username'));
        Config::set('mail.mailers.smtp.password', Setting::get('smtp_password'));
        Config::set('mail.mailers.smtp.encryption', Setting::get('smtp_encryption') ?: null);
        Config::set('mail.from.address', Setting::get('smtp_from_address', Setting::get('contact_email')));
        Config::set('mail.from.name', Setting::get('platform_name', config('mail.from.name', 'Drumroll')));
    }
}
