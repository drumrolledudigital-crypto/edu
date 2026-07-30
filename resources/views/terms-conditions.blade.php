@extends('layouts.app')

@section('title', 'Terms & Conditions | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<section class="relative pt-20 pb-16 px-4 md:px-12 bg-white overflow-hidden">
    <div class="absolute top-0 right-0 w-1/3 h-full bg-accent/5 rounded-bl-[100px] -z-10"></div>
    <div class="max-w-4xl mx-auto">
        <div class="fade-up">
            <div class="flex items-center gap-3 text-sm font-bold text-gray-400 mb-6">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-primary">Terms & Conditions</span>
            </div>
            <h1 class="text-4xl lg:text-5xl font-black text-secondary leading-tight mb-4">Terms & Conditions</h1>
            <p class="text-gray-500 text-sm">Last updated: {{ date('F d, Y') }}</p>
        </div>
    </div>
</section>

<section class="py-16 px-4 md:px-12 bg-light">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-soft border border-gray-50 p-8 md:p-12 space-y-8 fade-up">

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">1. Acceptance of Terms</h2>
                <p class="text-gray-600 leading-relaxed">By accessing and using {{ \App\Models\Setting::get('platform_name', 'Drumroll') }} ("the Platform"), you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, you may not use our services.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">2. Description of Services</h2>
                <p class="text-gray-600 leading-relaxed">We provide an online student doubt solving platform for students below Class 8, offering personalized tutoring sessions, doubt resolution, and learning resources. Services include scheduled one-on-one sessions, doubt submission, and access to study materials.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">3. User Accounts</h2>
                <p class="text-gray-600 leading-relaxed mb-3">To use our services, you must create an account. You are responsible for:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                    <li>Maintaining the confidentiality of your account credentials.</li>
                    <li>All activities that occur under your account.</li>
                    <li>Providing accurate and complete registration information.</li>
                    <li>Notifying us immediately of any unauthorized use of your account.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">4. Booking & Payments</h2>
                <p class="text-gray-600 leading-relaxed mb-3">Sessions must be booked in advance through the platform. Payment is required at the time of booking via our secure payment gateway (Stripe). All fees are non-refundable unless otherwise stated in our Refund Policy.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">5. Cancellation Policy</h2>
                <p class="text-gray-600 leading-relaxed">Sessions may be cancelled up to 24 hours before the scheduled time for a full credit. Cancellations made less than 24 hours before the session may not be eligible for credit. Please refer to our Cancellation Policy for details.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">6. Intellectual Property</h2>
                <p class="text-gray-600 leading-relaxed">All content, materials, and resources provided through the Platform, including but not limited to text, graphics, logos, and software, are the property of {{ \App\Models\Setting::get('platform_name', 'Drumroll') }} and are protected by intellectual property laws.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">7. Limitation of Liability</h2>
                <p class="text-gray-600 leading-relaxed">{{ \App\Models\Setting::get('platform_name', 'Drumroll') }} shall not be liable for any indirect, incidental, special, or consequential damages resulting from the use or inability to use our services.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">8. Changes to Terms</h2>
                <p class="text-gray-600 leading-relaxed">We reserve the right to modify these Terms at any time. Changes will be effective immediately upon posting on this page. Your continued use of the Platform after any changes constitutes acceptance of the new Terms.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">9. Contact Us</h2>
                <p class="text-gray-600 leading-relaxed">For any questions about these Terms, please contact us at:</p>
                <p class="text-gray-600 mt-2">
                    <strong>Email:</strong> {{ \App\Models\Setting::get('support_email', 'hello@drumroll.com') }}<br>
                    <strong>Phone:</strong> {{ \App\Models\Setting::get('support_phone', '+1 (234) 567-890') }}
                </p>
            </div>

        </div>
    </div>
</section>

@endsection
