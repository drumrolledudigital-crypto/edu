<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>@yield('title')</title>
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
            width: 100% !important;
            -webkit-text-size-adjust: none;
        }
        .wrapper {
            background-color: #f8fafc;
            width: 100%;
            padding: 40px 0;
        }
        .content {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            margin: 0 auto;
            max-width: 600px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .header {
            background-color: #FF4D8D;
            padding: 32px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .body {
            padding: 40px;
        }
        .body h2 {
            color: #111827;
            font-size: 20px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 24px;
        }
        .body p {
            color: #4b5563;
            font-size: 16px;
            line-height: 1.6;
            margin: 0 0 24px;
        }
        .info-box {
            background-color: #f3f4f6;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .info-row {
            display: flex;
            margin-bottom: 12px;
        }
        .info-label {
            color: #6b7280;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            width: 120px;
        }
        .info-value {
            color: #111827;
            font-size: 14px;
            font-weight: 600;
            flex: 1;
        }
        .button {
            background-color: #FF4D8D;
            border-radius: 9999px;
            color: #ffffff !important;
            display: inline-block;
            font-size: 16px;
            font-weight: 700;
            padding: 16px 32px;
            text-decoration: none;
            text-align: center;
            transition: opacity 0.2s;
        }
        .footer {
            padding: 32px;
            text-align: center;
        }
        .footer p {
            color: #9ca3af;
            font-size: 12px;
            margin: 0 0 8px;
        }
        @media only screen and (max-width: 600px) {
            .content {
                width: 100% !important;
                border-radius: 0;
            }
            .body {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="content">
            <div class="header">
                @php $eLogo = \App\Models\Setting::get('website_logo'); @endphp
                @if($eLogo)
                <img src="{{ asset($eLogo) }}" alt="{{ \App\Models\Setting::get('platform_name', 'Drumroll') }}" style="max-height:50px;margin:0 auto 10px;display:block;">
                @endif
                <h1>{{ \App\Models\Setting::get('platform_name', 'DRUMROLL') }}</h1>
            </div>
            <div class="body">
                @yield('content')
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('platform_name', 'Drumroll') }}. All rights reserved.</p>
                <p>Designed for the next generation of learners.</p>
            </div>
        </div>
    </div>
</body>
</html>
