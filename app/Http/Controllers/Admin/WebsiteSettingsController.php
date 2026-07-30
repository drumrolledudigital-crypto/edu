<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class WebsiteSettingsController extends Controller
{
    private const GROUPS = [
        'general' => 'General Settings',
        'branding' => 'Branding',
        'hero' => 'Hero Section',
        'contact' => 'Contact Information',
        'social' => 'Social Media',
        'seo' => 'SEO Settings',
    ];

    private const IMAGE_KEYS = [
        'branding' => ['website_logo', 'favicon'],
    ];

    public function index()
    {
        $settings = Setting::whereIn('group', array_keys(self::GROUPS))
            ->get()
            ->pluck('value', 'key')
            ->toArray();

        return view('admin.website-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $group = $request->input('group', 'general');

        if (!array_key_exists($group, self::GROUPS)) {
            abort(403, 'Invalid settings group.');
        }

        $this->validateRequest($request, $group);

        $data = $request->except(['group', '_token']);

        $htmlKeys = ['custom_css', 'custom_js', 'custom_head_scripts', 'custom_body_scripts', 'custom_footer_scripts', 'schema_json', 'email_signature', 'footer_about', 'website_description'];

        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) {
                $value = $this->uploadImage($request->file($key), $key);
                $this->deleteOldImage($key);
            } elseif (is_string($value) && !in_array($key, $htmlKeys)) {
                $value = strip_tags($value);
            }

            if ($value !== null && $value !== '') {
                Setting::set($key, $value, $group);
            } elseif ($request->input($key) === '') {
                Setting::where('key', $key)->update(['value' => null]);
            }
        }

        $this->clearCache();

        return back()->with('success', ucfirst($group) . ' settings updated successfully.');
    }

    public function uploadImage(\Illuminate\Http\UploadedFile $file, string $key)
    {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowed)) {
            abort(422, 'Invalid image format. Allowed: ' . implode(', ', $allowed));
        }

        $maxSize = in_array($key, ['favicon', 'apple_touch_icon']) ? 512 : 2048;
        if ($file->getSize() > $maxSize * 1024) {
            abort(422, "Image must be less than {$maxSize}KB.");
        }

        $path = $file->store('website/' . $key, 'public');
        return 'storage/' . $path;
    }

    private function deleteOldImage(string $key): void
    {
        $oldValue = Setting::get($key);
        if ($oldValue && str_starts_with($oldValue, 'storage/')) {
            $oldPath = str_replace('storage/', '', $oldValue);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }
    }

    private function clearCache(): void
    {
        Cache::forget('website.settings');
        Cache::forget('admin.dashboard.stats');
    }

    private function validateRequest(Request $request, string $group): void
    {
        $rules = [];

        $emailFields = ['support_email', 'contact_email', 'smtp_from_address', 'reply_to_email'];
        foreach ($emailFields as $field) {
            if ($request->has($field)) {
                $rules[$field] = 'nullable|email';
            }
        }

        $phoneFields = ['support_phone', 'whatsapp_number', 'emergency_contact'];
        foreach ($phoneFields as $field) {
            if ($request->has($field)) {
                $rules[$field] = 'nullable|string|max:20';
            }
        }

        $urlFields = [
            'social_facebook', 'social_instagram', 'social_twitter', 'social_linkedin',
            'social_youtube', 'social_telegram', 'social_discord', 'social_github',
            'social_pinterest', 'social_threads', 'social_tiktok',
            'canonical_url', 'privacy_policy_url', 'terms_of_service_url',
            'refund_policy_url', 'cookie_policy_url', 'disclaimer_url',
            'google_map_url',
        ];
        foreach ($urlFields as $field) {
            if ($request->has($field)) {
                $rules[$field] = 'nullable|url';
            }
        }

        if ($group === 'branding') {
            foreach (self::IMAGE_KEYS['branding'] ?? [] as $imageKey) {
                if ($request->hasFile($imageKey)) {
                    $rules[$imageKey] = 'image|max:2048';
                }
            }
        }

        $request->validate($rules);
    }
}
