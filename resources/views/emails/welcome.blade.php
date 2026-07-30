@extends('emails.layout')

@section('title', 'Welcome to ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')
    <h2>Welcome aboard, {{ $user->name }}!</h2>
    <p>We are thrilled to have you join our platform. {{ \App\Models\Setting::get('platform_name', 'Drumroll') }} is here to help you solve your academic doubts and excel in your studies.</p>
    
    <p>With your account, you can now:</p>
    <ul style="color: #4b5563; font-size: 16px; line-height: 1.6; margin-bottom: 24px;">
        <li>Submit academic doubts with detailed descriptions and attachments.</li>
        <li>Book 1-on-1 live sessions with our expert educator.</li>
        <li>Manage your learning schedule through a personalized dashboard.</li>
        <li>Access session history and meeting links anytime.</li>
    </ul>

    <div style="text-align: center; margin: 40px 0;">
        <a href="{{ route('student.dashboard') }}" class="button">Go to Student Dashboard</a>
    </div>

    <p>If you have any questions or need assistance, we're just an email away.</p>
    <p>Happy learning!<br>The {{ \App\Models\Setting::get('platform_name', 'Drumroll') }} Team</p>
@endsection
