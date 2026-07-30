@extends('emails.layout')

@section('title', 'Payment Received')

@section('content')
    <h2>Payment Successful!</h2>
    <p>Hello {{ $appointment->student->name }},</p>
    <p>We've successfully received your payment for the session booking. Your appointment is now fully confirmed.</p>

    <div class="info-box">
        <div class="info-row">
            <div class="info-label">Appointment ID</div>
            <div class="info-value">#{{ $appointment->id }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Transaction ID</div>
            <div class="info-value">{{ $payment->stripe_payment_id }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Amount Paid</div>
            <div class="info-value">{{ strtoupper($payment->currency) }} {{ number_format($payment->amount, 2) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date & Time</div>
            <div class="info-value">{{ $appointment->appointment_date->format('F d, Y') }} at {{ date('h:i A', strtotime($appointment->start_time)) }}</div>
        </div>
    </div>

    <div style="text-align: center; margin: 40px 0;">
        <a href="{{ route('student.payments.history') }}" class="button">View Payment History</a>
    </div>

    <p>Thank you for choosing {{ \App\Models\Setting::get('platform_name', 'Drumroll') }}. We look forward to seeing you at the session!</p>
    <p>Best regards,<br>The {{ \App\Models\Setting::get('platform_name', 'Drumroll') }} Team</p>
@endsection
