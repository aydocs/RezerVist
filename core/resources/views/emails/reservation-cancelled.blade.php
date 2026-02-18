@extends('emails.layouts.master')

@section('title', 'Rezervasyon İptali')

@section('content')
    <div style="text-align: center;">
        <div style="display: inline-block; background-color: #FEF2F2; border-radius: 50%; padding: 16px; margin-bottom: 24px;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </div>
        <h1 style="color: #EF4444;">Rezervasyon İptal Edildi</h1>
        <p><strong>{{ $reservation->business->name }}</strong> için yaptığınız rezervasyon maalesef iptal edilmiştir.</p>

        <div class="card">
            <div class="card-title">İPTAL EDİLEN REZERVASYON</div>
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
                @if($reservation->price > 0)
                <tr>
                    <td style="padding: 12px 0; font-size: 14px; color: #94A3B8;">İade Edilecek Tutar</td>
                    <td style="padding: 12px 0; font-size: 14px; font-weight: 700; color: #10B981; text-align: right;">₺{{ number_format($reservation->price, 2) }}</td>
                </tr>
                @endif
            </table>
            @if($reservation->price > 0)
                <p style="font-size: 12px; color: #94A3B8; text-align: right; margin-top: 8px; margin-bottom: 0;">* İade işlemi 1-3 iş günü içinde kartınıza yansıyacaktır.</p>
            @endif
        </div>

        <a href="{{ url('/search') }}" class="button" style="background-color: #1E293B; box-shadow: 0 10px 15px -3px rgba(30, 41, 59, 0.3);">Yeni Rezervasyon Yap</a>

        <p style="margin-top: 32px; font-size: 14px; color: #64748B;">
            Bir hata olduğunu mu düşünüyorsunuz? <br>
            Lütfen <a href="mailto:{{ $reservation->business->email }}" style="color: #6200EE; font-weight: 600; text-decoration: none;">{{ $reservation->business->name }}</a> ile iletişime geçin.
        </p>
    </div>
@endsection
