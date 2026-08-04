@extends('layouts.admin')

@section('title', 'Website Settings')
@section('page_title', 'Website Settings')

@section('content')
<div class="space-y-6 pb-12">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Website Settings</h2>
            <p class="text-sm text-muted-foreground mt-1">Manage your website branding, SEO, contact info, and more.</p>
        </div>
    </div>

    <div class="bg-card border border-border rounded-xl p-2 shadow-sm overflow-x-auto scrollbar-hide">
        <nav class="flex space-x-1" id="ws-tabs">
            @php
            $tabs = [
                'general' => ['label' => 'General', 'icon' => 'settings'],
                'branding' => ['label' => 'Branding', 'icon' => 'palette'],
                'hero' => ['label' => 'Hero Section', 'icon' => 'layout'],
                'contact' => ['label' => 'Contact', 'icon' => 'phone'],
                'social' => ['label' => 'Social Media', 'icon' => 'share-2'],
                'seo' => ['label' => 'SEO', 'icon' => 'search'],
            ];
            @endphp
            @foreach($tabs as $key => $tab)
            <button onclick="switchTab('{{ $key }}')" class="tab-btn px-3 py-2 text-xs font-semibold rounded-lg {{ $loop->first ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:bg-accent/50 hover:text-foreground' }} transition-all whitespace-nowrap flex items-center gap-1.5">
                <i data-lucide="{{ $tab['icon'] }}" class="w-3.5 h-3.5"></i> {{ $tab['label'] }}
            </button>
            @endforeach
        </nav>
    </div>

    <form action="{{ route('admin.website-settings.update') }}" method="POST" enctype="multipart/form-data" id="ws-form">
        @csrf
        <input type="hidden" name="group" id="active-group" value="general">

        <div id="tab-general" class="ws-tab">
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">General Settings</h3>
                    <p class="text-sm text-muted-foreground">Basic website identity and configuration.</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Website Name</label>
                        <input type="text" name="platform_name" value="{{ $settings['platform_name'] ?? 'Drumroll Edu' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Website Tagline</label>
                        <input type="text" name="website_tagline" value="{{ $settings['website_tagline'] ?? '' }}" placeholder="Premium Kids Learning Platform" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Company Name</label>
                        <input type="text" name="company_name" value="{{ $settings['company_name'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Website Description</label>
                        <textarea name="website_description" rows="3" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none" placeholder="A brief description of your website">{{ $settings['website_description'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Copyright Text</label>
                        <input type="text" name="copyright_text" value="{{ $settings['copyright_text'] ?? '' }}" placeholder="All rights reserved." class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Footer Text</label>
                        <input type="text" name="footer_text" value="{{ $settings['footer_text'] ?? '' }}" placeholder="Empowering children through education." class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Timezone</label>
                        <input type="text" name="timezone" value="{{ $settings['timezone'] ?? 'UTC' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Language</label>
                        <select name="language" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                            <option value="en" {{ ($settings['language'] ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                            <option value="hi" {{ ($settings['language'] ?? '') === 'hi' ? 'selected' : '' }}>Hindi</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Currency</label>
                        <input type="text" name="currency" value="{{ $settings['currency'] ?? 'USD' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-branding" class="ws-tab hidden">
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Branding</h3>
                    <p class="text-sm text-muted-foreground">Website logo and favicon. Logo is used everywhere automatically.</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Website Logo</label>
                        <div class="flex items-center gap-4">
                            <div id="preview-website_logo" class="w-24 h-24 rounded-lg border {{ !empty($settings['website_logo']) ? 'border-border' : 'border-dashed border-border' }} bg-muted/30 flex items-center justify-center overflow-hidden">
                                @if(!empty($settings['website_logo']))
                                <img src="{{ asset($settings['website_logo']) }}" alt="Website Logo" class="max-w-full max-h-full object-contain">
                                @else
                                <i data-lucide="image" class="w-8 h-8 text-muted-foreground/50"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" data-instant-upload="website_logo" accept="image/*" class="w-full text-sm text-muted-foreground file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:cursor-pointer">
                                <p class="text-[10px] text-muted-foreground mt-1">Used in header, footer, login, emails, invoices, and PDFs. Uploads instantly, up to 5MB.</p>
                                <p id="status-website_logo" class="text-[11px] mt-1 font-semibold"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Favicon</label>
                        <div class="flex items-center gap-4">
                            <div id="preview-favicon" class="w-12 h-12 rounded-lg border {{ !empty($settings['favicon']) ? 'border-border' : 'border-dashed border-border' }} bg-muted/30 flex items-center justify-center overflow-hidden">
                                @if(!empty($settings['favicon']))
                                <img src="{{ asset($settings['favicon']) }}" alt="Favicon" class="max-w-full max-h-full object-contain">
                                @else
                                <i data-lucide="image" class="w-5 h-5 text-muted-foreground/50"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" data-instant-upload="favicon" accept="image/x-icon,image/png,image/svg+xml" class="w-full text-sm text-muted-foreground file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:cursor-pointer">
                                <p class="text-[10px] text-muted-foreground mt-1">Browser tab icon. Recommended: 32x32 or 64x64 PNG/ICO. Uploads instantly, up to 512KB.</p>
                                <p id="status-favicon" class="text-[11px] mt-1 font-semibold"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-hero" class="ws-tab hidden">
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Hero Section</h3>
                    <p class="text-sm text-muted-foreground">Manage the homepage hero banner content.</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Badge Text</label>
                        <input type="text" name="hero_badge" value="{{ $settings['hero_badge'] ?? '' }}" placeholder="Trusted by 500+ Parents" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Heading (HTML allowed)</label>
                        <textarea name="hero_heading" rows="2" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none" placeholder="Big Dreams Begin with a <span class='gradient-text'>Drumroll!</span }}">{{ $settings['hero_heading'] ?? '' }}</textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Subheading</label>
                        <textarea name="hero_subheading" rows="2" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none" placeholder="Engaging online tutoring...">{{ $settings['hero_subheading'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Feature 1</label>
                        <input type="text" name="hero_feature_1" value="{{ $settings['hero_feature_1'] ?? '' }}" placeholder="Personalized Learning" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Feature 2</label>
                        <input type="text" name="hero_feature_2" value="{{ $settings['hero_feature_2'] ?? '' }}" placeholder="Expert Teachers" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Feature 3</label>
                        <input type="text" name="hero_feature_3" value="{{ $settings['hero_feature_3'] ?? '' }}" placeholder="Interactive Classes" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Feature 4</label>
                        <input type="text" name="hero_feature_4" value="{{ $settings['hero_feature_4'] ?? '' }}" placeholder="Confidence Building" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">CTA Button Text</label>
                        <input type="text" name="hero_cta_text" value="{{ $settings['hero_cta_text'] ?? '' }}" placeholder="Book Free Consultation" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-contact" class="ws-tab hidden">
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Contact Information</h3>
                    <p class="text-sm text-muted-foreground">Your business contact details.</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Email</label>
                        <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Support Email</label>
                        <input type="email" name="support_email" value="{{ $settings['support_email'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Phone</label>
                        <input type="text" name="support_phone" value="{{ $settings['support_phone'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}" placeholder="+1234567890" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Address</label>
                        <textarea name="address" rows="2" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">{{ $settings['address'] ?? '' }}</textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Google Map Embed URL</label>
                        <input type="url" name="google_map_embed" value="{{ $settings['google_map_embed'] ?? '' }}" placeholder="https://www.google.com/maps/embed?..." class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Google Map URL</label>
                        <input type="url" name="google_map_url" value="{{ $settings['google_map_url'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Business Hours</label>
                        <input type="text" name="business_hours" value="{{ $settings['business_hours'] ?? '' }}" placeholder="Mon-Fri 9AM-6PM" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Emergency Contact</label>
                        <input type="text" name="emergency_contact" value="{{ $settings['emergency_contact'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-social" class="ws-tab hidden">
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Social Media</h3>
                    <p class="text-sm text-muted-foreground">Your social media profile URLs.</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php
                    $socials = [
                        'social_facebook' => ['label' => 'Facebook', 'icon' => 'facebook'],
                        'social_instagram' => ['label' => 'Instagram', 'icon' => 'instagram'],
                        'social_twitter' => ['label' => 'X (Twitter)', 'icon' => 'twitter'],
                        'social_linkedin' => ['label' => 'LinkedIn', 'icon' => 'linkedin'],
                        'social_youtube' => ['label' => 'YouTube', 'icon' => 'youtube'],
                        'social_telegram' => ['label' => 'Telegram', 'icon' => 'send'],
                        'social_discord' => ['label' => 'Discord', 'icon' => 'message-circle'],
                        'social_github' => ['label' => 'GitHub', 'icon' => 'github'],
                    ];
                    @endphp
                    @foreach($socials as $key => $social)
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block flex items-center gap-2">
                            <i data-lucide="{{ $social['icon'] }}" class="w-3.5 h-3.5"></i> {{ $social['label'] }}
                        </label>
                        <input type="url" name="{{ $key }}" value="{{ $settings[$key] ?? '' }}" placeholder="https://..." class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="tab-seo" class="ws-tab hidden">
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">SEO Settings</h3>
                    <p class="text-sm text-muted-foreground">Search engine optimization and meta tags.</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ $settings['meta_title'] ?? '' }}" maxlength="60" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                        <p class="text-[10px] text-muted-foreground mt-1">Recommended: 50-60 characters</p>
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Meta Description</label>
                        <textarea name="meta_description" rows="2" maxlength="160" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">{{ $settings['meta_description'] ?? '' }}</textarea>
                        <p class="text-[10px] text-muted-foreground mt-1">Recommended: 150-160 characters</p>
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Meta Keywords</label>
                        <input type="text" name="meta_keywords" value="{{ $settings['meta_keywords'] ?? '' }}" placeholder="keyword1, keyword2, keyword3" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Canonical URL</label>
                        <input type="url" name="canonical_url" value="{{ $settings['canonical_url'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Robots</label>
                        <select name="robots" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                            <option value="index,follow" {{ ($settings['robots'] ?? 'index,follow') === 'index,follow' ? 'selected' : '' }}>Index, Follow</option>
                            <option value="noindex,follow" {{ ($settings['robots'] ?? '') === 'noindex,follow' ? 'selected' : '' }}>No Index, Follow</option>
                            <option value="index,nofollow" {{ ($settings['robots'] ?? '') === 'index,nofollow' ? 'selected' : '' }}>Index, No Follow</option>
                            <option value="noindex,nofollow" {{ ($settings['robots'] ?? '') === 'noindex,nofollow' ? 'selected' : '' }}>No Index, No Follow</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Author</label>
                        <input type="text" name="seo_author" value="{{ $settings['seo_author'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div class="col-span-2 border-t border-border pt-6">
                        <h4 class="text-sm font-bold text-foreground mb-4">Open Graph</h4>
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">OG Title</label>
                        <input type="text" name="og_title" value="{{ $settings['og_title'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">OG Description</label>
                        <input type="text" name="og_description" value="{{ $settings['og_description'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div class="col-span-2 border-t border-border pt-6">
                        <h4 class="text-sm font-bold text-foreground mb-4">Twitter Card</h4>
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Twitter Title</label>
                        <input type="text" name="twitter_title" value="{{ $settings['twitter_title'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Twitter Description</label>
                        <input type="text" name="twitter_description" value="{{ $settings['twitter_description'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div class="col-span-2 border-t border-border pt-6">
                        <h4 class="text-sm font-bold text-foreground mb-4">Verification Codes</h4>
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Google Site Verification</label>
                        <input type="text" name="google_verification" value="{{ $settings['google_verification'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none font-mono">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Bing Verification</label>
                        <input type="text" name="bing_verification" value="{{ $settings['bing_verification'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none font-mono">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Facebook Verification</label>
                        <input type="text" name="facebook_verification" value="{{ $settings['facebook_verification'] ?? '' }}" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none font-mono">
                    </div>
                    <div class="col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 block">Schema JSON-LD</label>
                        <textarea name="schema_json" rows="5" class="w-full bg-background border border-border rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary outline-none font-mono" placeholder='{"context":"https://schema.org","type":"Organization","name":"..."}'>{{ $settings['schema_json'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-border flex justify-end">
            <button type="submit" class="bg-primary text-primary-foreground hover:bg-primary/90 px-8 py-3 rounded-xl text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Save Settings
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function switchTab(tabId) {
        document.querySelectorAll('.ws-tab').forEach(function(el) { el.classList.add('hidden'); });
        document.querySelectorAll('.tab-btn').forEach(function(el) {
            el.classList.remove('bg-primary/10', 'text-primary');
            el.classList.add('text-muted-foreground', 'hover:bg-accent/50', 'hover:text-foreground');
        });
        document.getElementById('tab-' + tabId).classList.remove('hidden');
        document.getElementById('active-group').value = tabId;
        document.querySelectorAll('.tab-btn').forEach(function(btn) {
            if (btn.getAttribute('onclick') === "switchTab('" + tabId + "')") {
                btn.classList.remove('text-muted-foreground', 'hover:bg-accent/50', 'hover:text-foreground');
                btn.classList.add('bg-primary/10', 'text-primary');
            }
        });
    }

    document.querySelectorAll('[data-instant-upload]').forEach(function(input) {
        input.addEventListener('change', function() {
            const field = input.getAttribute('data-instant-upload');
            const file = input.files[0];
            if (!file) return;

            const statusEl = document.getElementById('status-' + field);
            const previewEl = document.getElementById('preview-' + field);
            statusEl.textContent = 'Uploading...';
            statusEl.className = 'text-[11px] mt-1 font-semibold text-muted-foreground';
            input.disabled = true;

            const formData = new FormData();
            formData.append('field', field);
            formData.append(field, file);

            fetch(@json(route('admin.website-settings.upload-image')), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
            .then(function(result) {
                if (result.ok && result.data.success) {
                    previewEl.innerHTML = '<img src="' + result.data.url + '?t=' + Date.now() + '" alt="' + field + '" class="max-w-full max-h-full object-contain">';
                    previewEl.classList.remove('border-dashed');
                    statusEl.textContent = 'Uploaded';
                    statusEl.className = 'text-[11px] mt-1 font-semibold text-emerald-600';
                    if (window.toast) window.toast.success((field === 'favicon' ? 'Favicon' : 'Website logo') + ' uploaded successfully.');
                } else {
                    const message = (result.data.errors && result.data.errors[field] && result.data.errors[field][0]) || result.data.message || 'Upload failed.';
                    statusEl.textContent = message;
                    statusEl.className = 'text-[11px] mt-1 font-semibold text-rose-600';
                    if (window.toast) window.toast.error(message);
                }
            })
            .catch(function() {
                statusEl.textContent = 'Upload failed. Please try again.';
                statusEl.className = 'text-[11px] mt-1 font-semibold text-rose-600';
                if (window.toast) window.toast.error('Upload failed. Please try again.');
            })
            .finally(function() {
                input.disabled = false;
                input.value = '';
            });
        });
    });
</script>
@endpush
@endsection