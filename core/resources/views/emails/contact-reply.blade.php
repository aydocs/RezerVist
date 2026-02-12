@extends('emails.layouts.master')

@section('title', 'Mesajınız Yanıtlandı')

@section('content')
    <div style="text-align: center; margin-bottom: 32px;">
        <div style="display: inline-block; background-color: #f5f3ff; border-radius: 50%; padding: 20px; margin-bottom: 24px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 1 1-7.6-11.7 8.38 8.38 0 0 1 3.8.9L21 3z"></path>
            </svg>
        </div>
        <h1>Mesajınız Yanıtlandı!</h1>
        <p>Destek ekibimiz RezerVist üzerinden gönderdiğiniz mesajı inceledi ve yanıtladı.</p>
    </div>

    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; padding: 32px; margin-bottom: 32px;">
        <div style="margin-bottom: 24px;">
            <h4 style="margin: 0 0 8px 0; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Sizin Mesajınız</h4>
            <p style="margin: 0; color: #475569; font-style: italic;">"{{ $contactMessage->message }}"</p>
        </div>

        <div style="border-top: 1px solid #e2e8f0; padding-top: 24px;">
            <h4 style="margin: 0 0 8px 0; color: #7c3aed; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Destek Ekibi Yanıtı</h4>
            <div style="margin: 0; color: #0f172a; font-weight: 500; font-size: 16px; line-height: 1.6;">
                {{ $reply }}
            </div>
        </div>
    </div>

    <p style="text-align: center; font-size: 14px; color: #64748b;">
        Başka bir sorunuz varsa, bu emaile doğrudan yanıt verebilir <br>
        veya <a href="{{ url('/help') }}" style="color: #7c3aed; font-weight: 600;">Yardım Merkezi</a>'ni ziyaret edebilirsiniz.
    </p>

    <div class="button-container">
        <a href="{{ url('/') }}" class="button">Sisteme Git</a>
    </div>
@endsection
