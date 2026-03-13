@extends('emails.layouts.master')

@section('title', 'Yeni İletişim Mesajı')

@section('content')
    <div style="text-align: center;">
        <div style="display: inline-block; background-color: #E0E7FF; color: #4338CA; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 100px; text-transform: uppercase; margin-bottom: 20px;">
            YENİ İLETİŞİM MESAJI
        </div>
        <h1>Sistem Bildirimi</h1>
        <p>Yönetim Paneli üzerinden yeni bir iletişim formu gönderildi.</p>

        <div class="card">
            <div class="card-title">MESAJ DETAYLARI</div>
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <td style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px; color: #94A3B8;">Gönderen</td>
                    <td style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px; font-weight: 700; color: #1E293B; text-align: right;">{{ $name }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px; color: #94A3B8;">E-posta</td>
                    <td style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px; font-weight: 700; color: #1E293B; text-align: right;">{{ $email }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px; color: #94A3B8;">Konu</td>
                    <td style="padding: 12px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px; font-weight: 700; color: #1E293B; text-align: right;">{{ $subject }}</td>
                </tr>
            </table>

            <div style="margin-top: 24px;">
                <div class="card-title">MESAJ İÇERİĞİ</div>
                <div style="background-color: #FFFFFF; border: 1px solid #F1F5F9; border-radius: 12px; padding: 16px; font-size: 14px; color: #475569; line-height: 1.6; text-align: left;">
                    {!! nl2br(e($contact_message)) !!}
                </div>
            </div>
        </div>

        <p style="font-size: 12px; color: #94A3B8; margin-top: 32px;">RezerVist Yönetim Sistemi • Otomatik Bildirim</p>
    </div>
@endsection
