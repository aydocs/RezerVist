@extends('emails.layouts.master')

@section('title', 'Rezervasyon Hatırlatması')

@section('content')
    <div style="text-align: center; margin-bottom: 32px;">
        <div style="display: inline-block; background-color: #eff6ff; border-radius: 50%; padding: 20px; margin-bottom: 24px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
        <h1>Rezervasyonunuzu Unutmayın!</h1>
        <p>Küçük bir hatırlatma! Yaklaşan bir rezervasyonunuz var. Sizi ağırlamak için hazırlıklarımızı tamamladık.</p>
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
        <div class="card-row" style="border-top: 1px dashed #e2e8f0; margin-top: 8px; padding-top: 16px;">
            <span class="label">Adres</span>
            <span class="value" style="font-size: 13px;">{{ $reservation->business->address }}</span>
        </div>
    </div>

    <div class="button-container" style="display: flex; justify-content: center; gap: 16px;">
        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($reservation->business->address) }}" class="button" style="background-color: #0f172a;">Yol Tarifi Al</a>
    </div>

    <p style="text-align: center; font-size: 14px; color: #64748b; margin-top: 32px;">
        Gelemiyor musunuz? <br>
        Lütfen önceden <a href="{{ url('/profile/reservations') }}" style="color: #ef4444; font-weight: 600;">iptal ederek</a> başkalarına yer açın.
    </p>

    <div style="margin-top: 40px; padding: 20px; border-radius: 16px; background-color: #f8fafc; border: 1px solid #e2e8f0; text-align: center;">
        <p style="margin: 0; font-size: 14px; color: #475569;">
            Yardıma mı ihtiyacınız var? <a href="tel:{{ $reservation->business->phone }}" style="color: #7c3aed; font-weight: 700;">{{ $reservation->business->phone }}</a>
        </p>
    </div>
@endsection
