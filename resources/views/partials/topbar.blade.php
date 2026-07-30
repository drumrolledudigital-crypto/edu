<!-- Top Utility Bar -->
<div class="bg-secondary text-white py-2.5 px-4 md:px-12 hidden sm:block">
    <div class="max-w-7xl mx-auto flex justify-between items-center text-xs font-medium">
        <div class="flex items-center gap-6">
            <a href="tel:{{ \App\Models\Setting::get('support_phone', '+1 (234) 567-890') }}" class="hover:text-primary transition flex items-center gap-2">
                <i class="fas fa-phone text-accent"></i> {{ \App\Models\Setting::get('support_phone', '+1 (234) 567-890') }}
            </a>
            <a href="mailto:{{ \App\Models\Setting::get('support_email', 'hello@drumroll.com') }}" class="hover:text-primary transition flex items-center gap-2">
                <i class="fas fa-envelope text-accent"></i> {{ \App\Models\Setting::get('support_email', 'hello@drumroll.com') }}
            </a>
        </div>
        <div class="flex items-center gap-6">
            <span class="text-gray-400">Follow us:</span>
            <div class="flex items-center gap-4 text-sm">
                @php
                $topSocials = ['social_facebook', 'social_instagram', 'social_twitter', 'social_linkedin', 'social_youtube', 'social_telegram'];
                $topIcons = ['fab fa-facebook-f', 'fab fa-instagram', 'fab fa-twitter', 'fab fa-linkedin-in', 'fab fa-youtube', 'fab fa-telegram-plane'];
                @endphp
                @foreach($topSocials as $i => $key)
                @php $url = \App\Models\Setting::get($key); @endphp
                @if($url)
                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition"><i class="{{ $topIcons[$i] }}"></i></a>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
