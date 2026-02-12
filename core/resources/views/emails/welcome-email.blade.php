@extends('emails.layouts.master')

@section('avatar', $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=7c3aed&color=fff')

@section('title', 'Hoş Geldiniz')

@section('content')
    <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 32px; font-weight: 900; color: #0f172a; margin-bottom: 8px;">RezerVist'e Hoş Geldin, {{ $user->name }}!</h1>
        <p style="color: #64748b; font-size: 18px;">Modern rezervasyon dünyasına ilk adımını attın. Seni burada görmek harika!</p>
    </div>

    <div class="card" style="background-color: #f0f9ff; border: 1px solid #bae6fd; padding: 40px; text-align: center;">
        <p style="font-size: 16px; color: #0369a1; margin-bottom: 24px; font-weight: 500;">
            Hemen keşfetmeye başla, en iyi mekanlarda yerini şimdiden ayırt.
        </p>
        
        <div class="button-container" style="padding: 0;">
            <a href="{{ url('/search') }}" class="button" style="background-color: #0284c7; box-shadow: 0 10px 15px -3px rgba(2, 132, 199, 0.3);">
                Keşfetmeye Başla
            </a>
        </div>
    </div>

    <div style="margin-top: 40px; border-top: 1px solid #f1f5f9; padding-top: 32px;">
        <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 16px;">Neler Yapabilirsin?</h3>
        
        <div style="display: flex; gap: 16px; margin-bottom: 16px;">
            <div style="background: #f1f5f9; border-radius: 12px; padding: 16px; flex: 1;">
                <h4 style="margin: 0 0 4px 0; font-size: 14px; color: #1e293b;">🔍 Keşfet</h4>
                <p style="margin: 0; font-size: 12px; color: #64748b;">En popüler kafe ve restoranları bul.</p>
            </div>
            <div style="background: #f1f5f9; border-radius: 12px; padding: 16px; flex: 1;">
                <h4 style="margin: 0 0 4px 0; font-size: 14px; color: #1e293b;">⚡ Hızlı Rezervasyon</h4>
                <p style="margin: 0; font-size: 12px; color: #64748b;">Saniyeler içinde masanı ayırt.</p>
            </div>
        </div>
    </div>
@endsection
