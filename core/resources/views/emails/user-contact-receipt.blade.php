<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Inter', -apple-system, sans-serif; background-color: #ffffff; color: #1a1a1a; margin: 0; padding: 0; }
        .container { max-width: 500px; margin: 60px auto; padding: 0 20px; text-align: center; }
        .icon-box { margin-bottom: 32px; color: #7c3aed; }
        h1 { font-size: 28px; font-weight: 800; letter-spacing: -0.8px; margin-bottom: 20px; line-height: 1.2; }
        p { font-size: 16px; line-height: 1.6; color: #64748b; margin-bottom: 32px; }
        .receipt-card { background-color: #f8fafc; border-radius: 20px; padding: 32px; text-align: left; border: 1px solid #f1f5f9; margin-bottom: 32px; }
        .label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; display: block; }
        .message-preview { font-size: 14px; color: #475569; font-style: italic; }
        .footer { margin-top: 60px; padding-top: 30px; border-top: 1px solid #f1f5f9; font-size: 12px; color: #94a3b8; }
        .logo-text { font-weight: 900; color: #000; font-size: 14px; letter-spacing: -0.5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-box">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        
        <h1>Mesajınızı <br>Aldık!</h1>
        <p>Merhaba {{ $name }}, destek ekibimize gönderdiğiniz mesaj başarıyla ulaşmıştır. En kısa sürede sizinle iletişime geçeceğiz.</p>
        
        <div class="receipt-card">
            <span class="label">Mesajınızın Özeti</span>
            <div class="message-preview">
                "{{ Str::limit($contact_message, 150) }}"
            </div>
        </div>
        
        <p style="font-size: 13px;">Acele etmeyin, uzman ekibimiz konuyu detaylıca inceliyor.</p>
        
        <div class="footer">
            <div class="logo-text">REZERVIST</div>
            <p style="margin-top: 8px;">Müşteri Memnuniyet Ekibi</p>
        </div>
    </div>
</body>
</html>
