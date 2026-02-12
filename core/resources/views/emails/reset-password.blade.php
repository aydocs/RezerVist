@extends('emails.layouts.master')

@section('avatar', $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=7c3aed&color=fff')

@section('title', 'Şifre Sıfırlama')

@section('content')
    <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 28px; font-weight: 850; color: #0f172a; margin-bottom: 8px;">Şifrenizi mi unuttunuz, {{ $user->name }}?</h1>
        <p style="color: #64748b; font-size: 16px;">Sorun değil! Aşağıdaki butona tıklayarak güvenli bir şekilde yeni şifrenizi belirleyebilirsiniz.</p>
    </div>

    <div class="card" style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 40px;">
        <div class="button-container" style="padding: 0;">
            <a href="{{ $resetUrl ?? '#' }}" class="button" style="background-color: #dc2626; box-shadow: 0 10px 15px -3px rgba(220, 38, 38, 0.3);">
                Şifremi Sıfırla
            </a>
        </div>
        
        <p style="text-align: center; font-size: 13px; color: #94a3b8; margin-top: 24px; margin-bottom: 0;">
            Bu bağlantı 60 dakika süresince geçerlidir.
        </p>
    </div>

    <div style="text-align: center; margin-top: 32px;">
        <p style="font-size: 14px; color: #64748b; line-height: 1.5;">
            Eğer şifre sıfırlama talebinde bulunmadıysanız, bu e-postayı <br>
            güvenle silebilirsiniz. Hesabınız güvende kalacaktır.
        </p>
    </div>
@endsection
