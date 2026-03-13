@extends('emails.layouts.master')

@section('title', 'Giriş Kodunuz')

@section('content')
    <div style="text-align: center;">
        <div style="display: inline-block; background-color: #F0F9FF; color: #0284C7; font-size: 12px; font-weight: 700; padding: 6px 16px; border-radius: 100px; margin-bottom: 24px; text-transform: uppercase; letter-spacing: 1px;">
            GÜVENLİ GİRİŞ
        </div>
        <h1>Tekrar hoş geldiniz!</h1>
        <p>RezerVist hesabınıza güvenli geçiş yapmak için aşağıdaki kodu kullanın.</p>
        
        <div class="card" style="text-align: center;">
            <div class="card-title">GİRİŞ KODU</div>
            <div style="display: flex; justify-content: center; gap: 8px;">
                @foreach(str_split($code) as $digit)
                    <span style="display: inline-block; width: 45px; height: 55px; line-height: 55px; background-color: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; font-size: 28px; font-weight: 700; color: #6200EE; text-align: center; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">{{ $digit }}</span>
                @endforeach
            </div>
            <div style="font-size: 13px; font-weight: 500; color: #94A3B8; margin-top: 24px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 4px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Bu kod <strong>180 saniye</strong> boyunca geçerlidir.
            </div>
        </div>
        
        <p style="font-size: 14px; margin-bottom: 0;">Bu girişi siz yapmadıysanız lütfen bu e-postayı dikkate almayın.</p>
    </div>
@endsection
