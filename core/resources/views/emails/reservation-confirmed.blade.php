@extends('emails.layouts.master')

@section('title', 'Rezervasyonunuz Onaylandı!')

@section('content')
    <div style="text-align: center; margin-bottom: 32px;">
        <div style="display: inline-block; background-color: #f0fdf4; border-radius: 50%; padding: 20px; margin-bottom: 24px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <h1>Rezervasyonunuz Onaylandı!</h1>
        <p>Harika bir haber! <strong>{{ $reservation->business->name }}</strong> randevunuzu onayladı. Sizi ağırlamak için sabırsızlanıyoruz.</p>
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
        <div class="card-row">
            <span class="label">Kişi Sayısı</span>
            <span class="value">{{ $reservation->guest_count }} Kişi</span>
        </div>
        @if($reservation->price > 0)
            <div class="card-row">
                <span class="label">Toplam Tutar</span>
                <span class="value">₺{{ number_format($reservation->price, 2) }}</span>
            </div>
        @endif
    </div>

    <div class="button-container">
        <a href="{{ url('/profile/reservations') }}" class="button">Rezervasyonu Görüntüle</a>
    </div>

    <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 20px; border-radius: 12px; margin-top: 32px;">
        <h4 style="margin: 0 0 8px 0; color: #92400e; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">💡 Önemli Not</h4>
        <p style="margin: 0; font-size: 14px; color: #b45309;">
            Rezervasyonunuzu iptal etmeniz gerekirse, lütfen en az 24 saat öncesinden sisteme giriş yaparak iptal işlemini gerçekleştiriniz.
        </p>
    </div>

    <div style="margin-top: 40px; text-align: center; border-top: 1px solid #f1f5f9; padding-top: 32px;">
        <p style="font-size: 14px; color: #64748b; margin-bottom: 8px;">Takviminize eklemeyi unutmayın!</p>
        <a href="https://www.google.com/calendar/render?action=TEMPLATE&text={{ urlencode($reservation->business->name . ' Rezervasyonu') }}&dates={{ $reservation->start_time->format('Ymd\THis') }}/{{ $reservation->start_time->addHour()->format('Ymd\THis') }}&details={{ urlencode('Rezervist üzerinden yapılan rezervasyon.') }}&location={{ urlencode($reservation->business->address) }}" target="_blank" style="color: #7c3aed; text-decoration: none; font-weight: 700; font-size: 14px;">
            Google Takvim'e Ekle &rarr;
        </a>
    </div>
@endsection
