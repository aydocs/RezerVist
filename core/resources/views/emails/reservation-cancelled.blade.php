@extends('emails.layouts.master')

@section('title', 'Rezervasyon İptali')

@section('content')
    <div style="text-align: center; margin-bottom: 32px;">
        <div style="display: inline-block; background-color: #fef2f2; border-radius: 50%; padding: 20px; margin-bottom: 24px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </div>
        <h1>Rezervasyon İptal Edildi</h1>
        <p><strong>{{ $reservation->business->name }}</strong> için yaptığınız rezervasyon maalesef iptal edilmiştir.</p>
    </div>

    <div class="card">
        <div class="card-row">
            <span class="label">İşletme</span>
            <span class="value">{{ $reservation->business->name }}</span>
        </div>
        <div class="card-row">
            <span class="label">Tarih</span>
            <span class="value">{{ $reservation->start_time->locale('tr')->translatedFormat('d F Y, l') }}</span>
        </div>
        <div class="card-row">
            <span class="label">Saat</span>
            <span class="value">{{ $reservation->start_time->format('H:i') }}</span>
        </div>
        @if($reservation->price > 0)
            <div class="card-row" style="border-top: 1px dashed #e2e8f0; margin-top: 8px; padding-top: 16px;">
                <span class="label">İade Edilecek Tutar</span>
                <span class="value" style="color: #10b981;">₺{{ number_format($reservation->price, 2) }}</span>
            </div>
            <p style="font-size: 12px; color: #94a3b8; text-align: right; margin-top: 4px;">* İade işlemi 1-3 iş günü içinde kartınıza yansıyacaktır.</p>
        @endif
    </div>

    <div class="button-container">
        <a href="{{ url('/search') }}" class="button" style="background-color: #0f172a;">Yeni Rezervasyon Yap</a>
    </div>

    <p style="text-align: center; font-size: 14px; color: #64748b; margin-top: 32px;">
        Bir hata olduğunu mu düşünüyorsunuz? <br>
        Lütfen <a href="mailto:{{ $reservation->business->email }}" style="color: #7c3aed; font-weight: 600;">{{ $reservation->business->name }}</a> ile iletişime geçin.
    </p>
@endsection
