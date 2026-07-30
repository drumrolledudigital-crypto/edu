<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            width: 100%;
            margin-bottom: 40px;
        }
        .header td {
            vertical-align: top;
        }
        .company-details {
            text-align: left;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #FF4D8D;
            margin-bottom: 5px;
        }
        .invoice-details {
            text-align: right;
        }
        .invoice-title {
            font-size: 32px;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        .divider {
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 30px;
        }
        .info-section {
            width: 100%;
            margin-bottom: 40px;
        }
        .info-section td {
            width: 50%;
            vertical-align: top;
        }
        .info-heading {
            font-size: 12px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .info-text {
            font-size: 14px;
            color: #111827;
            margin-bottom: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .items-table th {
            background-color: #f8fafc;
            color: #4b5563;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            color: #111827;
        }
        .items-table .amount {
            text-align: right;
            font-weight: bold;
        }
        .total-section {
            width: 100%;
        }
        .total-section td {
            padding: 10px 15px;
        }
        .total-label {
            text-align: right;
            font-weight: bold;
            color: #4b5563;
        }
        .total-amount {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #FF4D8D;
            width: 120px;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            background-color: #d1fae5;
            color: #059669;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td class="company-details">
                <div class="company-name">{{ $platformName }}</div>
                <div>{{ \App\Models\Setting::get('platform_tagline', 'Live Doubt Solving Platform') }}</div>
                <div>{{ $contactEmail }}</div>
            </td>
            <td class="invoice-details">
                <div class="invoice-title">INVOICE</div>
                <div><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</div>
                <div><strong>Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}</div>
                @if($invoice->payment && $invoice->payment->payment_status === 'successful')
                <div style="margin-top: 10px;"><span class="badge">PAID</span></div>
                @endif
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="info-section">
        <tr>
            <td>
                <div class="info-heading">Billed To</div>
                <div class="info-text"><strong>{{ $invoice->student->name }}</strong></div>
                <div class="info-text">{{ $invoice->student->email }}</div>
                <div class="info-text">Year {{ $invoice->student->student_class }}</div>
            </td>
            <td>
                <div class="info-heading">Payment Details</div>
                <div class="info-text"><strong>Transaction ID:</strong> {{ $invoice->payment->transaction_id ?? $invoice->payment->stripe_payment_id ?? 'N/A' }}</div>
                <div class="info-text"><strong>Status:</strong> {{ ucfirst($invoice->payment->payment_status ?? 'Pending') }}</div>
                <div class="info-text"><strong>Payment Date:</strong> {{ $invoice->payment->payment_date ? \Carbon\Carbon::parse($invoice->payment->payment_date)->format('M d, Y h:i A') : 'N/A' }}</div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Session Date</th>
                <th>Duration</th>
                <th class="amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div style="font-weight: bold; margin-bottom: 4px;">1-on-1 {{ \App\Models\Setting::get('session_duration', 50) }}-Minute Live Session</div>
                    <div style="font-size: 12px; color: #6b7280;">Subject: {{ $invoice->appointment->subject->name ?? 'N/A' }}</div>
                    <div style="font-size: 12px; color: #6b7280;">Appointment #{{ $invoice->appointment->id ?? 'N/A' }}</div>
                </td>
                <td>
                    @if($invoice->appointment)
                        {{ $invoice->appointment->appointment_date->format('M d, Y') }}<br>
                        <span style="font-size: 12px; color: #6b7280;">{{ date('h:i A', strtotime($invoice->appointment->start_time)) }}</span>
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ \App\Models\Setting::get('session_duration', 50) }} Mins</td>
                <td class="amount">{{ strtoupper($invoice->currency) }} {{ number_format($invoice->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="total-section">
        <tr>
            <td class="total-label">Subtotal:</td>
            <td class="total-amount">{{ strtoupper($invoice->currency) }} {{ number_format($invoice->amount, 2) }}</td>
        </tr>
        <tr>
            <td class="total-label" style="font-size: 18px; color: #111827;">Total Paid:</td>
            <td class="total-amount" style="border-top: 2px solid #e5e7eb; padding-top: 15px;">{{ strtoupper($invoice->currency) }} {{ number_format($invoice->amount, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Thank you for choosing {{ $platformName }} for your educational journey!</p>
        <p>This is a computer-generated document and does not require a physical signature.</p>
    </div>
</body>
</html>
