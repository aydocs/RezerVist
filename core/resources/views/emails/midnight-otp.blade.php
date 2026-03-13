@extends('emails.layouts.master')

@section('title', 'Doğrulama Kodunuz')

@section('content')
    <div style="text-align: center;">
        <div style="display: inline-block; background-color: #F5F3FF; color: #7C3AED; font-size: 12px; font-weight: 700; padding: 6px 16px; border-radius: 100px; margin-bottom: 24px; text-transform: uppercase; letter-spacing: 1px;">
            GÜVENLİ DOĞRULAMA
        </div>
        <h1>Doğrulama Kodunuz</h1>
        <p>Merhaba <strong>{{ $user_name }}</strong>,<br>RezerVist ailesine hoş geldiniz! Hesabınızı aktifleştirmek için aşağıdaki 6 haneli güvenlik kodunu kullanın.</p>
        
        <div class="card" style="text-align: center;">
            <div class="card-title">GÜVENLİK KODU</div>
            <div style="display: flex; justify-content: center; gap: 8px;">
                @foreach(str_split($code) as $digit)
                    <span style="display: inline-block; width: 45px; height: 55px; line-height: 55px; background-color: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; font-size: 28px; font-weight: 700; color: #6200EE; text-align: center; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">{{ $digit }}</span>
                @endforeach
            </div>
            <div style="font-size: 13px; font-weight: 500; color: #94A3B8; margin-top: 24px;">Bu kod <strong>3 dakika</strong> boyunca geçerlidir.</div>
        </div>
        
        <p style="font-size: 14px; margin-bottom: 0;">Eğer bu işlemi siz yapmadıysanız, lütfen bu e-postayı dikkate almayın.</p>
    </div>
@endsection
