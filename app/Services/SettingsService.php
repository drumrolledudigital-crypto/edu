<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

/**
 * Centralized settings service.
 * Single source of truth for all application configuration.
 * Falls back to .env for migration period, then DB becomes primary.
 */
class SettingsService
{
    /**
     * Get a setting value (DB primary, .env fallback).
     */
    public static function get($key, $default = null)
    {
        return Setting::getWithFallback($key, null, $default);
    }

    /**
     * Get a setting value with explicit .env fallback key.
     */
    public static function getWithEnvFallback($key, $envKey, $default = null)
    {
        return Setting::getWithFallback($key, $envKey, $default);
    }

    /**
     * Set a setting value.
     */
    public static function set($key, $value, $group = 'general')
    {
        return Setting::set($key, $value, $group);
    }

    /**
     * Bulk set settings.
     */
    public static function setMany(array $settings, $group = 'general')
    {
        foreach ($settings as $key => $value) {
            self::set($key, $value, $group);
        }
    }

    /**
     * Get multiple settings by group.
     */
    public static function getGroup($group): array
    {
        return Setting::where('group', $group)
            ->pluck('value', 'key')
            ->toArray();
    }

    // ─── Google OAuth ────────────────────────────────────────

    public static function getGoogleClientId(): ?string
    {
        return self::getWithEnvFallback('google_client_id', 'GOOGLE_CLIENT_ID');
    }

    public static function getGoogleClientSecret(): ?string
    {
        return self::getWithEnvFallback('google_client_secret', 'GOOGLE_CLIENT_SECRET');
    }

    public static function getGoogleRedirectUri(): ?string
    {
        $uri = self::getWithEnvFallback('google_redirect_uri', 'GOOGLE_REDIRECT_URI');
        return $uri ?: url('/admin/settings/google-callback');
    }

    public static function getGoogleScopes(): array
    {
        $scopes = self::get('google_scopes');
        return $scopes ? json_decode($scopes, true) : [
            'https://www.googleapis.com/auth/calendar',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
        ];
    }

    // ─── Stripe ──────────────────────────────────────────────

    public static function getStripePublishableKey(): ?string
    {
        return self::getWithEnvFallback('stripe_publishable_key', 'STRIPE_KEY');
    }

    public static function getStripeSecretKey(): ?string
    {
        return self::getWithEnvFallback('stripe_secret_key', 'STRIPE_SECRET');
    }

    public static function getStripeMode(): string
    {
        return self::get('stripe_mode', 'test');
    }

    // ─── SMTP ────────────────────────────────────────────────

    public static function isSmtpEnabled(): bool
    {
        return self::get('smtp_enabled', '0') === '1';
    }

    public static function getSmtpHost(): ?string
    {
        return self::getWithEnvFallback('smtp_host', 'MAIL_HOST');
    }

    public static function getSmtpPort(): ?string
    {
        return self::getWithEnvFallback('smtp_port', 'MAIL_PORT');
    }

    public static function getSmtpUsername(): ?string
    {
        return self::getWithEnvFallback('smtp_username', 'MAIL_USERNAME');
    }

    public static function getSmtpPassword(): ?string
    {
        return self::getWithEnvFallback('smtp_password', 'MAIL_PASSWORD');
    }

    public static function getSmtpEncryption(): string
    {
        return self::get('smtp_encryption', 'tls');
    }

    public static function getSmtpFromAddress(): ?string
    {
        return self::getWithEnvFallback('smtp_from_address', 'MAIL_FROM_ADDRESS');
    }

    public static function getSmtpFromName(): string
    {
        return self::getWithEnvFallback('smtp_from_name', 'MAIL_FROM_NAME', 'Drumroll Edu');
    }

    // ─── Google Calendar ─────────────────────────────────────

    public static function getGoogleCalendarId(): string
    {
        return self::get('google_calendar_id', 'primary');
    }

    public static function isAutoCreateCalendarEvent(): bool
    {
        return self::get('google_calendar_auto_create', '1') === '1';
    }

    public static function isAutoUpdateCalendarEvent(): bool
    {
        return self::get('google_calendar_auto_update', '1') === '1';
    }

    public static function isAutoDeleteCalendarEvent(): bool
    {
        return self::get('google_calendar_auto_delete', '1') === '1';
    }

    public static function isAutoGenerateMeetLink(): bool
    {
        return self::get('google_meet_auto_generate', '1') === '1';
    }
}
