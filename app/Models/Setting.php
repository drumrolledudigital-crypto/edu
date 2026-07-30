<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'is_encrypted'];

    protected $casts = [
        'is_encrypted' => 'boolean',
    ];

    /**
     * Sensitive keys that should always be encrypted.
     */
    private static array $sensitiveKeys = [
        'stripe_secret_key',
        'stripe_publishable_key',
        'google_client_id',
        'google_client_secret',
        'smtp_password',
        'google_access_token',
        'google_refresh_token',
    ];

    /**
     * Get a setting value, decrypting if needed.
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        if ($setting->is_encrypted && $setting->value) {
            try {
                return Crypt::decryptString($setting->value);
            } catch (\Throwable) {
                return $default;
            }
        }

        return $setting->value;
    }

    /**
     * Set a setting value, encrypting sensitive keys automatically.
     */
    public static function set($key, $value, $group = 'general')
    {
        $shouldEncrypt = in_array($key, self::$sensitiveKeys);
        $storeValue = $shouldEncrypt && $value ? Crypt::encryptString($value) : $value;

        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $storeValue,
                'group' => $group,
                'is_encrypted' => $shouldEncrypt,
            ]
        );
    }

    /**
     * Get a setting with .env fallback.
     */
    public static function getWithFallback($key, $envKey = null, $default = null)
    {
        $value = self::get($key);

        if ($value !== null && $value !== '') {
            return $value;
        }

        if ($envKey && config($envKey)) {
            return config($envKey);
        }

        return $default;
    }

    /**
     * Check if a key is sensitive.
     */
    public static function isSensitiveKey($key): bool
    {
        return in_array($key, self::$sensitiveKeys);
    }
}
