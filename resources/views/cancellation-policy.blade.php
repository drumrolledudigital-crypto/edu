@extends('layouts.app')

@section('title', 'Cancellation Policy | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<section class="relative pt-20 pb-16 px-4 md:px-12 bg-white overflow-hidden">
    <div class="absolute top-0 right-0 w-1/3 h-full bg-accent/5 rounded-bl-[100px] -z-10"></div>
    <div class="max-w-4xl mx-auto">
        <div class="fade-up">
            <div class="flex items-center gap-3 text-sm font-bold text-gray-400 mb-6">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-primary">Cancellation Policy</span>
            </div>
            <h1 class="text-4xl lg:text-5xl font-black text-secondary leading-tight mb-4">Cancellation Policy</h1>
            <p class="text-gray-500 text-sm">Last updated: {{ date('F d, Y') }}</p>
        </div>
    </div>
</section>

<section class="py-16 px-4 md:px-12 bg-light">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-soft border border-gray-50 p-8 md:p-12 space-y-8 fade-up">

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">1. Overview</h2>
                <p class="text-gray-600 leading-relaxed">At {{ \App\Models\Setting::get('platform_name', 'Drumroll') }}, we understand that schedules can change. This Cancellation Policy explains how to cancel a booked session and what to expect in terms of credits or refunds.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">2. How to Cancel a Session</h2>
                <p class="text-gray-600 leading-relaxed mb-3">To cancel a booked session:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                    <li>Log in to your student dashboard.</li>
                    <li>Navigate to "My Bookings" or "Upcoming Sessions."</li>
                    <li>Select the session you wish to cancel.</li>
                    <li>Click "Cancel Session" and confirm your cancellation.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">3. Cancellation Timeframes</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-gray-600 border-collapse">
                        <thead>
                            <tr class="bg-light">
                                <th class="text-left p-3 font-bold text-secondary">When You Cancel</th>
                                <th class="text-left p-3 font-bold text-secondary">Credit/Refund</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t border-gray-100">
                                <td class="p-3">More than 24 hours before session</td>
                                <td class="p-3">Full session credit</td>
                            </tr>
                            <tr class="border-t border-gray-100">
                                <td class="p-3">Between 12-24 hours before session</td>
                                <td class="p-3">50% session credit</td>
                            </tr>
                            <tr class="border-t border-gray-100">
                                <td class="p-3">Less than 12 hours before session</td>
                                <td class="p-3">No credit (session forfeited)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">4. No-Show Policy</h2>
                <p class="text-gray-600 leading-relaxed">If you do not attend a scheduled session without prior cancellation, the session will be marked as a "No-Show" and no credit or refund will be issued. The tutor will wait for 15 minutes before the session is considered a no-show.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">5. Tutor-Initiated Cancellation</h2>
                <p class="text-gray-600 leading-relaxed">If a tutor cancels a session, you will receive a full session credit or refund. We will make every effort to reschedule the session with an alternative tutor at your convenience.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">6. How Credits Work</h2>
                <p class="text-gray-600 leading-relaxed">Session credits are applied to your account balance and can be used for future bookings. Credits do not expire and are fully transferable to other sessions of the same or lower duration.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">7. Contact Us</h2>
                <p class="text-gray-600 leading-relaxed">If you have any questions about our Cancellation Policy, please contact us at:</p>
                <p class="text-gray-600 mt-2">
                    <strong>Email:</strong> {{ \App\Models\Setting::get('support_email', 'hello@drumroll.com') }}<br>
                    <strong>Phone:</strong> {{ \App\Models\Setting::get('support_phone', '+1 (234) 567-890') }}
                </p>
            </div>

        </div>
    </div>
</section>

@endsection
