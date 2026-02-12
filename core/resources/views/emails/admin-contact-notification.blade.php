<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; color: #1e293b; margin: 0; padding: 0; }
        .wrapper { padding: 40px 20px; }
        .content { max-width: 600px; margin: 0 auto; background: white; border-radius: 24px; padding: 40px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .badge { display: inline-block; background: #e0e7ff; color: #4338ca; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 100px; text-transform: uppercase; margin-bottom: 20px; }
        h1 { font-size: 24px; font-weight: 900; margin-bottom: 24px; color: #0f172a; }
        .field { margin-bottom: 24px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; }
        .label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; display: block; }
        .value { font-size: 15px; font-weight: 600; color: #1e293b; }
        .message-box { background: #f8fafc; border-radius: 16px; padding: 24px; line-height: 1.6; font-size: 15px; color: #334155; border: 1px solid #e2e8f0; }
        .footer { text-align: center; margin-top: 32px; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="content">
            <span class="badge">Yeni İletişim Mesajı</span>
            <h1>Sistem Bildirimi</h1>
            
            <div class="field">
                <span class="label">Gönderen</span>
                <span class="value">{{ $name }} ({{ $email }})</span>
            </div>
            
            <div class="field">
                <span class="label">Konu</span>
                <span class="value">{{ $subject }}</span>
            </div>
            
            <div class="label">Mesaj İçeriği</div>
            <div class="message-box">
                {!! nl2br(e($contact_message)) !!}
            </div>
            
            <div class="footer">
                Rezervist Yönetim Sistemi • Otomatik Bildirim
            </div>
        </div>
    </div>
</body>
</html>
