@extends('emails.layout')

@section('title', $subject)

@section('content')
    <h2>{{ $title }}</h2>
    <p>{{ $greeting }}</p>
    <p>{{ $intro }}</p>

    <div class="info-box">
        <div class="info-row">
            <div class="info-label">Appointment ID</div>
            <div class="info-value">#{{ $appointment->id }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Subject</div>
            <div class="info-value">{{ $appointment->subject?->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Doubt Title</div>
            <div class="info-value">{{ $appointment->doubt?->title }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date</div>
            <div class="info-value">{{ $appointment->appointment_date->format('F d, Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Time</div>
            <div class="info-value">{{ date('h:i A', strtotime($appointment->start_time)) }} - {{ date('h:i A', strtotime($appointment->end_time)) }}</div>
        </div>
        @if($appointment->google_meet_link)
        <div class="info-row">
            <div class="info-label">Meeting Link</div>
            <div class="info-value"><a href="{{ $appointment->google_meet_link }}" style="color: #2596be;">Join Meeting</a></div>
        </div>
        @endif
    </div>

    @if(isset($button_url))
    <div style="text-align: center; margin-top: 32px;">
        <a href="{{ $button_url }}" class="button">{{ $button_text ?? 'View Dashboard' }}</a>
    </div>
    @endif

    <p style="margin-top: 32px;">If you have any questions, feel free to reply to this email or contact us through the student dashboard.</p>
    <p>Best regards,<br>The {{ \App\Models\Setting::get('platform_name', 'Drumroll') }} Team</p>
@endsection
