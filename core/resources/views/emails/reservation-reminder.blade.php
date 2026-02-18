@extends('emails.layouts.master')

@section('title', 'Rezervasyon Hatırlatması')

@section('content')
    <div style="text-align: center;">
        <div style="display: inline-block; background-color: #EFF6FF; border-radius: 50%; padding: 16px; margin-bottom: 24px;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
        <h1>Rezervasyonunuz Yaklaşıyor!</h1>
        <p>Küçük bir hatırlatma! Yaklaşan bir rezervasyonunuz var. Sizi ağırlamak için hazırlıklarımızı tamamladık.</p>

        <div class="card">
            <div class="card-title">REZERVASYON DETAYLARI</div>
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
                    <td style="padding: 12px 0; font-size: 14px; color: #94A3B8;">Adres</td>
                    <td style="padding: 12px 0; font-size: 14px; font-weight: 700; color: #1E293B; text-align: right;">{{ $reservation->business->address }}</td>
                </tr>
            </table>
        </div>

        <div style="display: flex; justify-content: center; gap: 12px;">
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($reservation->business->address) }}" class="button" style="background-color: #1E293B; box-shadow: 0 10px 15px -3px rgba(30, 41, 59, 0.3);">Yol Tarifi Al</a>
        </div>

        <p style="margin-top: 32px; font-size: 14px; color: #64748B;">
            Gelemiyor musunuz? <br>
            Lütfen <a href="{{ url('/profile/reservations') }}" style="color: #EF4444; font-weight: 600; text-decoration: none;">iptal ederek</a> başkalarına yer açın.
        </p>

        <div style="margin-top: 32px; padding: 20px; border-radius: 16px; background-color: #F8FAFC; border: 1px solid #E2E8F0;">
            <p style="margin: 0; font-size: 13px; color: #475569;">
                Yardıma mı ihtiyacınız var? <a href="tel:{{ $reservation->business->phone }}" style="color: #6200EE; font-weight: 700; text-decoration: none;">{{ $reservation->business->phone }}</a>
            </p>
        </div>
    </div>
@endsection
