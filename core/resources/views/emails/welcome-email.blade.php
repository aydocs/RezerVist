@extends('emails.layouts.master')

@section('title', 'Hoş Geldiniz')

@section('content')
    <div style="text-align: center;">
        <div style="display: inline-block; background-color: #F5F3FF; color: #7C3AED; font-size: 12px; font-weight: 700; padding: 6px 16px; border-radius: 100px; margin-bottom: 24px; text-transform: uppercase; letter-spacing: 1px;">
            ARAMIZA HOŞ GELDİNİZ
        </div>
        <h1>RezerVist'e Hoş Geldin, {{ $user->name }}!</h1>
        <p>Modern rezervasyon dünyasına ilk adımını attın. Seni burada görmek harika! En iyi mekanlarda yerini saniyeler içinde ayırtmaya hazır mısın?</p>

        <div class="card" style="text-align: center; background-color: #F0F9FF; border-color: #BAE6FD;">
            <p style="font-size: 16px; color: #0369A1; margin-bottom: 24px; font-weight: 600;">
                Hemen keşfetmeye başla, mekanlardaki yerini şimdiden ayırt.
            </p>
            <a href="{{ url('/search') }}" class="button" style="background-color: #0284C7; box-shadow: 0 10px 15px -3px rgba(2, 132, 199, 0.3);">
                Keşfetmeye Başla
            </a>
        </div>

        <div style="margin-top: 40px; text-align: left;">
            <div class="card-title">NELER YAPABİLİRSİN?</div>
            
            <div style="background-color: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 16px; padding: 16px; margin-bottom: 12px; display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; background-color: #F5F3FF; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6200EE; flex-shrink: 0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </div>
                <div>
                    <div style="font-weight: 700; color: #1E293B; font-size: 14px;">Mekanları Keşfet</div>
                    <div style="color: #64748B; font-size: 13px;">En popüler kafe ve restoranları anında bul.</div>
                </div>
            </div>

            <div style="background-color: #F8FAFC; border: 1px solid #F1F5F9; border-radius: 16px; padding: 16px; display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; background-color: #F5F3FF; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6200EE; flex-shrink: 0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                </div>
                <div>
                    <div style="font-weight: 700; color: #1E293B; font-size: 14px;">Hızlı Rezervasyon</div>
                    <div style="color: #64748B; font-size: 13px;">Saniyeler içinde masanı ayırt ve onayı bekle.</div>
                </div>
            </div>
        </div>
    </div>
@endsection
