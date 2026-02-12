@extends('emails.layouts.master')

@section('avatar', $user_avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user_name ?? 'R') . '&background=7c3aed&color=fff')

@section('title', 'Giriş Kodunuz')

@section('content')
    <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 28px; font-weight: 850; color: #0f172a; margin-bottom: 8px;">Tekrar hoş geldiniz!</h1>
        <p style="color: #64748b; font-size: 16px;">RezerVist hesabınıza güvenli geçiş yapmak için aşağıdaki kodu kullanın.</p>
    </div>

    <div class="card" style="background-color: #f5f3ff; border: 2px solid #ddd6fe; padding: 40px; border-radius: 24px;">
        <div style="text-align: center;">
            <div style="display: inline-block; background-color: #7c3aed; color: #ffffff; padding: 24px 48px; border-radius: 20px; font-size: 42px; font-weight: 900; letter-spacing: 6px; box-shadow: 0 10px 15px -3px rgba(124, 58, 237, 0.4);">
                {{ $code }}
            </div>
            
            <p style="margin-top: 24px; font-size: 14px; color: #7c3aed; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                ⏱️ Bu kod 180 saniye sonra geçersiz olacaktır.
            </p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 32px;">
        <p style="font-size: 14px; color: #94a3b8; line-height: 1.6;">
            Bu girişi siz yapmadıysanız lütfen bu e-postayı dikkate almayın. <br>
            Güvenliğiniz bizim için her şeyden önemli.
        </p>
    </div>
@endsection
