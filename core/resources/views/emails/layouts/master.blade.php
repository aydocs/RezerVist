<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', 'Inter', -apple-system, sans-serif;
            background-color: #F8FAFC;
            color: #1E293B;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #F8FAFC;
            padding-bottom: 40px;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }
        .header {
            padding: 40px 40px 20px;
            text-align: center;
        }
        .logo {
            font-size: 24px;
            font-weight: 800;
            color: #6200EE;
            letter-spacing: -1px;
            text-transform: uppercase;
            text-decoration: none;
        }
        .content {
            padding: 0 40px 40px;
            text-align: center;
        }
        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1E293B;
            margin: 0 0 16px;
            letter-spacing: -0.5px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            color: #64748B;
            margin: 0 0 24px;
        }
        /* Components */
        .card {
            background-color: #F8FAFC;
            border: 2px solid #F1F5F9;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 32px;
            text-align: left;
        }
        .card-title {
            font-size: 12px;
            font-weight: 700;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 16px;
        }
        .button {
            display: inline-block;
            background-color: #6200EE;
            color: #FFFFFF !important;
            padding: 16px 32px;
            border-radius: 14px;
            font-weight: 700;
            text-decoration: none;
            font-size: 16px;
            box-shadow: 0 10px 15px -3px rgba(98, 0, 238, 0.3);
            margin: 20px 0;
        }
        .divider {
            height: 1px;
            background-color: #F1F5F9;
            margin: 32px 0;
        }
        .footer {
            text-align: center;
            padding: 0 40px 40px;
        }
        .footer-text {
            font-size: 12px;
            color: #94A3B8;
            line-height: 1.5;
        }
        .digit {
            display: inline-block;
            width: 45px;
            height: 55px;
            line-height: 55px;
            background-color: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            font-size: 28px;
            font-weight: 700;
            color: #6200EE;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin: 0 4px;
        }
        .social-links {
            margin-bottom: 20px;
        }
        .social-link {
            display: inline-block;
            margin: 0 8px;
            color: #CBD5E1;
            text-decoration: none;
        }
        @media screen and (max-width: 600px) {
            .container { margin: 20px auto; border-radius: 0; }
            .content { padding: 0 24px 32px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <a href="{{ config('app.url') }}" class="logo">{{ $globalSettings['site_name'] ?? config('app.name') }}</a>
            </div>

            <div class="content">
                @yield('content')
            </div>

            <div class="divider"></div>

            <div class="footer">
                <div class="social-links">
                    <a href="#" class="social-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #64748B;"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                    </a>
                    <a href="#" class="social-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #64748B;"><path d="M4 4l11.733 16h4.267l-11.733 -16z"/><path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"/></svg>
                    </a>
                    <a href="#" class="social-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #64748B;"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                </div>
                <div class="footer-text">
                    &copy; {{ date('Y') }} {{ $globalSettings['site_name'] ?? config('app.name') }}. Tüm hakları saklıdır.<br>
                    Premium Rezervasyon Deneyimi
                </div>
            </div>
        </div>
    </div>
</body>
</html>
