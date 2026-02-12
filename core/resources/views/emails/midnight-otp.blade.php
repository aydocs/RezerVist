<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 60px auto;
            padding: 40px;
            text-align: center;
            background-color: #1e293b;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid #334155;
        }
        .avatar-box {
            margin-bottom: 32px;
        }
        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #7c3aed;
            box-shadow: 0 0 20px rgba(124, 58, 237, 0.4);
        }
        h1 {
            font-size: 26px;
            font-weight: 900;
            letter-spacing: -1px;
            margin-bottom: 8px;
            color: #fff;
        }
        p {
            font-size: 15px;
            line-height: 1.6;
            color: #94a3b8;
            margin-bottom: 40px;
        }
        .otp-container {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid #7c3aed;
            border-radius: 24px;
            padding: 32px;
            margin-bottom: 40px;
            position: relative;
        }
        .code {
            font-size: 40px;
            font-weight: 900;
            letter-spacing: 10px;
            color: #a78bfa;
            text-shadow: 0 0 15px rgba(167, 139, 250, 0.3);
        }
        .expiry-tag {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #7c3aed;
            color: #fff;
            font-size: 10px;
            font-weight: 900;
            padding: 4px 12px;
            border-radius: 100px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .footer {
            margin-top: 40px;
            font-size: 11px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .rezervist-accent {
            color: #7c3aed;
            font-weight: 900;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="avatar-box">
            <img src="{{ $user_avatar }}" alt="User" class="avatar">
        </div>
        
        <h1>Giriş Yapılıyor</h1>
        <p>Hesabına güvenli bir şekilde erişmek için aşağıdaki özel kodu kullan.</p>
        
        <div class="otp-container">
            <div class="expiry-tag">180 Saniye Geçerli</div>
            <div class="code">{{ $code }}</div>
        </div>
        
        <div class="footer">
            Güvenlik Protokolü • <span class="rezervist-accent">REZERVIST</span>
        </div>
    </div>
</body>
</html>
