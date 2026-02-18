@extends('emails.layouts.master')

@section('title', 'Rezervasyonunuz Onaylandı!')

@section('content')
    <div style="text-align: center;">
        <div style="display: inline-block; background-color: #F0FDF4; border-radius: 50%; padding: 16px; margin-bottom: 24px;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <h1>Rezervasyon Onayı</h1>
        <p>Harika bir haber! <strong>{{ $reservation->business->name }}</strong> randevunuzu onayladı. Sizi ağırlamak için sabırsızlanıyoruz.</p>

        <div class="card">
            <div class="card-title">REZERVASYON BİLGİLERİ</div>
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <td style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px; color: #94A3B8;">İşletme</td>
                    <td style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px; font-weight: 700; color: #1E293B; text-align: right;">{{ $reservation->business->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px; color: #94A3B8;">Tarih</td>
                    <td style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px; font-weight: 700; color: #1E293B; text-align: right;">{{ $reservation->start_time->locale('tr')->translatedFormat('d F Y, l') }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px; color: #94A3B8;">Saat</td>
                    <td style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px; font-weight: 700; color: #1E293B; text-align: right;">{{ $reservation->start_time->format('H:i') }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px; color: #94A3B8;">Kişi Sayısı</td>
                    <td style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px; font-weight: 700; color: #1E293B; text-align: right;">{{ $reservation->guest_count }} Kişi</td>
                </tr>
                @if($reservation->price > 0)
                <tr>
                    <td style="padding: 12px 0; font-size: 14px; color: #94A3B8;">Toplam Tutar</td>
                    <td style="padding: 12px 0; font-size: 14px; font-weight: 700; color: #1E293B; text-align: right;">₺{{ number_format($reservation->price, 2) }}</td>
                </tr>
                @endif
            </table>
        </div>

        <a href="{{ url('/profile/reservations') }}" class="button">Rezervasyonu Görüntüle</a>

        <div style="background-color: #FFFBEB; border-left: 4px solid #F59E0B; padding: 20px; border-radius: 12px; margin-top: 32px; text-align: left;">
            <div style="font-size: 13px; font-weight: 700; color: #B45309; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 6px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                Önemli Not
            </div>
            <p style="margin: 0; font-size: 14px; color: #D97706; line-height: 1.5;">Rezervasyonunuzu iptal etmeniz gerekirse, lütfen en az 24 saat öncesinden sisteme giriş yaparak işleminizi tamamlayın.</p>
        </div>

        <div style="margin-top: 40px; padding-top: 24px; border-top: 1px solid #F1F5F9;">
            <p style="font-size: 14px; color: #94A3B8; margin-bottom: 12px;">Takviminize eklemek ister misiniz?</p>
            <a href="https://www.google.com/calendar/render?action=TEMPLATE&text={{ urlencode($reservation->business->name . ' Rezervasyonu') }}&dates={{ $reservation->start_time->format('Ymd\THis') }}/{{ $reservation->start_time->copy()->addHour()->format('Ymd\THis') }}&details={{ urlencode('Rezervist üzerinden yapılan rezervasyon.') }}&location={{ urlencode($reservation->business->address) }}" style="color: #6200EE; text-decoration: none; font-weight: 700; font-size: 14px;">
                Google Takvim'e Ekle
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-left: 4px;"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
@endsection
