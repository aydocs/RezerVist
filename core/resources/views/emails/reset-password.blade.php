@extends('emails.layouts.master')

@section('title', 'Şifre Sıfırlama Talebi')

@section('content')
    <div style="text-align: center;">
        <div style="display: inline-block; background-color: #FEF2F2; color: #DC2626; font-size: 12px; font-weight: 700; padding: 6px 16px; border-radius: 100px; margin-bottom: 24px; text-transform: uppercase; letter-spacing: 1px;">
            ŞİFRE GÜVENLİĞİ
        </div>
        <h1>Şifrenizi mi unuttunuz?</h1>
        <p>Merhaba <strong>{{ $user->name }}</strong>,<br>Sorun değil! Aşağıdaki butona tıklayarak güvenli bir şekilde yeni şifrenizi belirleyebilirsiniz.</p>
        
        <div class="card" style="text-align: center;">
            <a href="{{ $resetUrl ?? '#' }}" class="button" style="background-color: #DC2626; box-shadow: 0 10px 15px -3px rgba(220, 38, 38, 0.3);">
                Şifremi Sıfırla
            </a>
            <div style="font-size: 13px; font-weight: 500; color: #94A3B8; margin-top: 24px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 4px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Bu bağlantı <strong>60 dakika</strong> boyunca geçerlidir.
            </div>
        </div>
        
        <p style="font-size: 14px; margin-bottom: 0;">Eğer şifre sıfırlama talebinde bulunmadıysanız, bu e-postayı güvenle silebilirsiniz. Hesabınız güvende kalacaktır.</p>
    </div>
@endsection
