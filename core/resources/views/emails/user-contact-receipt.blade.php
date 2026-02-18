@extends('emails.layouts.master')

@section('title', 'Mesajınız Alındı')

@section('content')
    <div style="text-align: center;">
        <div style="display: inline-block; background-color: #F0FDF4; border-radius: 50%; padding: 16px; margin-bottom: 24px;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <h1>Mesajınızı Aldık!</h1>
        <p>Merhaba <strong>{{ $name }}</strong>,<br>Destek ekibimize gönderdiğiniz mesaj başarıyla ulaşmıştır. En kısa sürede sizinle iletişime geçeceğiz.</p>

        <div class="card">
            <div class="card-title">MESAJINIZIN ÖZETİ</div>
            <div style="font-size: 14px; color: #475569; line-height: 1.6; font-style: italic;">
                "{{ Str::limit($contact_message, 150) }}"
            </div>
        </div>

        <p style="font-size: 14px; margin-bottom: 0;">Acele etmeyin, uzman ekibimiz konuyu detaylıca inceliyor.</p>
    </div>
@endsection
