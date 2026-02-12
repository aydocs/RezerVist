<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background-color: #ffffff;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 60px auto;
            padding: 0 20px;
            text-align: center;
        }
        .avatar-box {
            margin-bottom: 40px;
        }
        .avatar {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
            border: 1px solid #f0f0f0;
        }
        h1 {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.8px;
            margin-bottom: 16px;
            line-height: 1.2;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            color: #666;
            margin-bottom: 32px;
        }
        .otp-display {
            background-color: #f8f8f8;
            border-radius: 20px;
            padding: 24px;
            display: inline-block;
            margin-bottom: 32px;
        }
        .code {
            font-size: 32px;
            font-weight: 900;
            letter-spacing: 6px;
            color: #7c3aed;
        }
        .timer {
            font-size: 12px;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 12px;
        }
        .footer {
            margin-top: 60px;
            padding-top: 30px;
            border-top: 1px solid #f0f0f0;
            font-size: 13px;
            color: #ccc;
        }
        .logo-text {
            font-weight: 900;
            color: #000;
            letter-spacing: -1px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="avatar-box">
            <img src="{{ $user_avatar }}" alt="User" class="avatar">
        </div>
        
        <h1>Şifrenizi mi <br>unuttunuz?</h1>
        <p>Merhaba {{ $user_name }}, endişelenmeyin! Aşağıdaki tek kullanımlık kod ile şifrenizi güvenli bir şekilde sıfırlayabilirsiniz.</p>
        
        <div class="otp-display">
            <div class="code">{{ $code }}</div>
            <div class="timer">⏱️ 3 dakika geçerli</div>
        </div>
        
        <p style="font-size: 13px; color: #aaa;">Eğer bu talebi siz yapmadıysanız lütfen bu adımı görmezden gelin.</p>
        
        <div class="footer">
            <div class="logo-text">REZERVIST</div>
            <p style="margin-top: 5px;">Modern Rezervasyonun Merkezi</p>
        </div>
    </div>
</body>
</html>
