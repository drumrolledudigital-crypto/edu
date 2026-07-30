@extends('layouts.app')

@section('title', 'Privacy Policy | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')

<section class="relative pt-20 pb-16 px-4 md:px-12 bg-white overflow-hidden">
    <div class="absolute top-0 right-0 w-1/3 h-full bg-accent/5 rounded-bl-[100px] -z-10"></div>
    <div class="max-w-4xl mx-auto">
        <div class="fade-up">
            <div class="flex items-center gap-3 text-sm font-bold text-gray-400 mb-6">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-primary">Privacy Policy</span>
            </div>
            <h1 class="text-4xl lg:text-5xl font-black text-secondary leading-tight mb-4">Privacy Policy</h1>
            <p class="text-gray-500 text-sm">Last updated: {{ date('F d, Y') }}</p>
        </div>
    </div>
</section>

<section class="py-16 px-4 md:px-12 bg-light">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-soft border border-gray-50 p-8 md:p-12 space-y-8 fade-up">

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">1. Introduction</h2>
                <p class="text-gray-600 leading-relaxed">Welcome to {{ \App\Models\Setting::get('platform_name', 'Drumroll') }} ("we," "our," or "us"). We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our student doubt solving platform and related services.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">2. Information We Collect</h2>
                <p class="text-gray-600 leading-relaxed mb-3">We may collect information about you in various ways, including:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                    <li><strong>Personal Data:</strong> Name, email address, phone number, and student grade level provided during registration.</li>
                    <li><strong>Usage Data:</strong> Information about how you interact with our platform, including session history and learning progress.</li>
                    <li><strong>Payment Data:</strong> Payment information processed securely through our third-party payment processors (Stripe).</li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">3. How We Use Your Information</h2>
                <p class="text-gray-600 leading-relaxed mb-3">We use the information we collect to:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                    <li>Provide, operate, and maintain our educational platform.</li>
                    <li>Schedule and conduct personalized tutoring sessions.</li>
                    <li>Process payments and send related transaction information.</li>
                    <li>Send administrative notifications, session reminders, and updates.</li>
                    <li>Improve and personalize your learning experience.</li>
                    <li>Ensure the security and integrity of our platform.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">4. Data Security</h2>
                <p class="text-gray-600 leading-relaxed">We implement appropriate technical and organizational security measures to protect your personal information. However, no method of transmission over the Internet or electronic storage is 100% secure, and we cannot guarantee absolute security.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">5. Third-Party Services</h2>
                <p class="text-gray-600 leading-relaxed">We may employ third-party companies and individuals to facilitate our services, provide service on our behalf, or perform service-related tasks. These third parties have access to your personal information only to perform these tasks on our behalf and are obligated not to disclose or use it for any other purpose.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">6. Children's Privacy</h2>
                <p class="text-gray-600 leading-relaxed">Our platform is designed for students below Class 8. We collect information from children only with the consent of their parents or legal guardians. We encourage parents to monitor their children's online activity.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">7. Your Rights</h2>
                <p class="text-gray-600 leading-relaxed">You have the right to access, correct, or delete your personal information. To exercise these rights, please contact us at {{ \App\Models\Setting::get('support_email', 'hello@drumroll.com') }}.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">8. Changes to This Policy</h2>
                <p class="text-gray-600 leading-relaxed">We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last updated" date.</p>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-secondary mb-3">9. Contact Us</h2>
                <p class="text-gray-600 leading-relaxed">If you have any questions about this Privacy Policy, please contact us at:</p>
                <p class="text-gray-600 mt-2">
                    <strong>Email:</strong> {{ \App\Models\Setting::get('support_email', 'hello@drumroll.com') }}<br>
                    <strong>Phone:</strong> {{ \App\Models\Setting::get('support_phone', '+1 (234) 567-890') }}
                </p>
            </div>

        </div>
    </div>
</section>

@endsection
