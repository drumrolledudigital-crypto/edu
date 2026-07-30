<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking #{{ $appointment->id }} - {{ \App\Models\Setting::get('platform_name', 'Drumroll') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; color: #1a1a1a; padding: 40px; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #2596be; padding-bottom: 20px; }
        .header h1 { font-size: 24px; color: #2596be; margin-bottom: 4px; }
        .header p { font-size: 12px; color: #666; }
        .section { margin-bottom: 24px; }
        .section-title { font-size: 14px; font-weight: 700; color: #2596be; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .field { margin-bottom: 12px; }
        .field-label { font-size: 11px; font-weight: 600; color: #666; text-transform: uppercase; letter-spacing: 0.5px; }
        .field-value { font-size: 14px; font-weight: 600; margin-top: 2px; }
        .badge { display: inline-block; padding: 2px 10px; border-radius: 9999px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .badge-confirmed { background: #d1fae5; color: #065f46; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-scheduled { background: #dbeafe; color: #1e40af; }
        .footer { margin-top: 40px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #e5e7eb; padding-top: 16px; }
        @media print { body { padding: 20px; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ \App\Models\Setting::get('platform_name', 'Drumroll') }}</h1>
        <p>Booking Confirmation #{{ $appointment->id }}</p>
    </div>

    <div class="section">
        <div class="section-title">Session Details</div>
        <div class="grid">
            <div class="field">
                <div class="field-label">Student</div>
                <div class="field-value">{{ $appointment->student->name }}</div>
            </div>
            <div class="field">
                <div class="field-label">Email</div>
                <div class="field-value">{{ $appointment->student->email }}</div>
            </div>
            <div class="field">
                <div class="field-label">Subject</div>
                <div class="field-value">{{ $appointment->subject->name }}</div>
            </div>
            <div class="field">
                <div class="field-label">Duration</div>
                <div class="field-value">{{ $appointment->duration }} Minutes</div>
            </div>
            <div class="field">
                <div class="field-label">Date</div>
                <div class="field-value">{{ $appointment->appointment_date->format('F d, Y') }}</div>
            </div>
            <div class="field">
                <div class="field-label">Time</div>
                <div class="field-value">{{ date('h:i A', strtotime($appointment->start_time)) }} - {{ date('h:i A', strtotime($appointment->end_time)) }}</div>
            </div>
            <div class="field">
                <div class="field-label">Status</div>
                <div class="field-value">
                    <span class="badge badge-{{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($appointment->google_meet_link)
    <div class="section">
        <div class="section-title">Meeting Link</div>
        <div class="field-value">{{ $appointment->google_meet_link }}</div>
    </div>
    @endif

    @if($appointment->doubt)
    <div class="section">
        <div class="section-title">Doubt</div>
        <div class="field">
            <div class="field-label">Title</div>
            <div class="field-value">{{ $appointment->doubt->title }}</div>
        </div>
        <div class="field">
            <div class="field-label">Description</div>
            <div class="field-value" style="font-weight:400; white-space:pre-wrap;">{{ $appointment->doubt->description }}</div>
        </div>
    </div>
    @endif

    @if($appointment->payment)
    <div class="section">
        <div class="section-title">Payment</div>
        <div class="grid">
            <div class="field">
                <div class="field-label">Amount</div>
                <div class="field-value">{{ $appointment->payment->currency }} {{ number_format($appointment->payment->amount, 2) }}</div>
            </div>
            <div class="field">
                <div class="field-label">Status</div>
                <div class="field-value">{{ ucfirst($appointment->payment->payment_status) }}</div>
            </div>
        </div>
    </div>
    @endif

    @if($appointment->admin_notes)
    <div class="section">
        <div class="section-title">Admin Notes</div>
        <div class="field-value" style="font-weight:400; white-space:pre-wrap;">{{ $appointment->admin_notes }}</div>
    </div>
    @endif

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }} &bull; {{ \App\Models\Setting::get('platform_name', 'Drumroll') }}</p>
    </div>

    <script>window.onload = function() { window.print(); };</script>
</body>
</html>
