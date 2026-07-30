@extends('layouts.app')

@section('title', 'Refund Policy | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<section class="relative pt-20 pb-16 px-4 md:px-12 bg-white overflow-hidden">
    <div class="absolute top-0 right-0 w-1/3 h-full bg-accent/5 rounded-bl-[100px] -z-10"></div>
    <div class="max-w-4xl mx-auto">
        <div class="fade-up">
            <div class="flex items-center gap-3 text-sm font-bold text-gray-400 mb-6">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-primary">Refund Policy</span>
            </div>
            <h1 class="text-4xl lg:text-5xl font-black text-secondary leading-tight mb-4">Refund Policy</h1>
            <p class="text-gray-500 text-sm">Last updated: {{ date('F d, Y') }}</p>
        </div>
    </div>
</section>

<section class="py-16 px-4 md:px-12 bg-light">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-soft border border-gray-50 p-8 md:p-12 space-y-8 fade-up">

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">1. Overview</h2>
                <p class="text-gray-600 leading-relaxed">At {{ \App\Models\Setting::get('platform_name', 'Drumroll') }}, we strive to ensure your satisfaction with our educational services. This Refund Policy outlines the conditions under which refunds may be issued for sessions and services purchased through our platform.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">2. Eligibility for Refund</h2>
                <p class="text-gray-600 leading-relaxed mb-3">You may be eligible for a refund in the following situations:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                    <li>The session was cancelled by the tutor or due to a technical issue on our end.</li>
                    <li>The session was not conducted within the scheduled time slot.</li>
                    <li>A duplicate payment was made in error.</li>
                    <li>You have submitted a valid refund request within the specified timeframe.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">3. Refund Timeframe</h2>
                <p class="text-gray-600 leading-relaxed">Refund requests must be submitted within 7 days of the session date or payment date, whichever is applicable. Requests submitted after this period may not be eligible for a refund.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">4. How to Request a Refund</h2>
                <p class="text-gray-600 leading-relaxed mb-3">To request a refund, please follow these steps:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                    <li>Log in to your student dashboard.</li>
                    <li>Navigate to the Payments or Bookings section.</li>
                    <li>Select the relevant session or payment.</li>
                    <li>Click "Request Refund" and provide a reason.</li>
                </ul>
                <p class="text-gray-600 leading-relaxed mt-3">Alternatively, you can contact our support team at {{ \App\Models\Setting::get('support_email', 'hello@drumroll.com') }}.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">5. Refund Processing</h2>
                <p class="text-gray-600 leading-relaxed">Once your refund request is approved, the refund will be processed to the original payment method within 5-10 business days. You will receive an email confirmation when the refund has been issued.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">6. Non-Refundable Items</h2>
                <p class="text-gray-600 leading-relaxed mb-3">The following are not eligible for refunds:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                    <li>Sessions that were attended and completed successfully.</li>
                    <li>Digital study materials or resources that have been accessed or downloaded.</li>
                    <li>Refund requests made after the 7-day timeframe.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">7. Contact Us</h2>
                <p class="text-gray-600 leading-relaxed">If you have any questions about our Refund Policy, please contact us at:</p>
                <p class="text-gray-600 mt-2">
                    <strong>Email:</strong> {{ \App\Models\Setting::get('support_email', 'hello@drumroll.com') }}<br>
                    <strong>Phone:</strong> {{ \App\Models\Setting::get('support_phone', '+1 (234) 567-890') }}
                </p>
            </div>

        </div>
    </div>
</section>

@endsection
