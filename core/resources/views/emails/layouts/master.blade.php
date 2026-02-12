<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding-bottom: 40px;
        }

        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            font-family: inherit;
            color: #1e293b;
            border-radius: 24px;
            overflow: hidden;
            margin-top: 40px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            padding: 48px 32px;
            text-align: center;
        }

        .header.auth-header {
            background: #ffffff;
            padding: 40px 32px 20px 32px;
        }

        .avatar-container {
            margin-bottom: 24px;
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #f1f5f9;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 24px;
            font-weight: 900;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: -1px;
        }

        /* Content */
        .content {
            padding: 48px 32px;
        }

        h1 {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 16px 0;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            margin: 0 0 24px 0;
            color: #475569;
        }

        /* Card Component */
        .card {
            background-color: #f1f5f9;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 32px;
        }

        .card-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-row:last-child {
            border-bottom: none;
        }

        .label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .value {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            text-align: right;
        }

        /* Button */
        .button-container {
            text-align: center;
            padding: 24px 0;
        }

        .button {
            display: inline-block;
            background-color: #7c3aed;
            color: #ffffff !important;
            padding: 16px 40px;
            border-radius: 16px;
            font-weight: 700;
            text-decoration: none;
            font-size: 16px;
            box-shadow: 0 10px 15px -3px rgba(124, 58, 237, 0.3);
            transition: all 0.2s;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding-top: 32px;
            color: #94a3b8;
            font-size: 12px;
        }

        .footer a {
            color: #7c3aed;
            text-decoration: none;
            font-weight: 600;
        }

        .social-links {
            margin-top: 16px;
        }

        .social-links a {
            display: inline-block;
            margin: 0 8px;
            opacity: 0.5;
        }

        /* Mobile Adjustments */
        @media screen and (max-width: 600px) {
            .main {
                margin-top: 0;
                border-radius: 0;
            }
            .content {
                padding: 32px 20px;
            }
            .card {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    @hasSection('avatar')
                        <div class="header auth-header">
                            <div class="avatar-container">
                                <img src="@yield('avatar')" alt="Profile" class="avatar">
                            </div>
                        </div>
                    @else
                        <div class="header">
                            <a href="{{ config('app.url') }}" class="logo">REZERVIST</a>
                        </div>
                    @endif

                    <div class="content">
                        @yield('content')
                    </div>

                    <div class="footer">
                        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tüm hakları saklıdır.</p>
                        <p>Sorularınız mı var? <a href="mailto:destek@rezervist.com">destek@rezervist.com</a></p>
                        <p style="margin-top: 8px;">rezervist.com • Modern Rezervasyon Platformu</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
