@extends('emails.layouts.master')

@section('title', 'Mesajınız Yanıtlandı')

@section('content')
    <div style="text-align: center;">
        <div style="display: inline-block; background-color: #F5F3FF; border-radius: 50%; padding: 16px; margin-bottom: 24px;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#6200EE" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 1 1-7.6-11.7 8.38 8.38 0 0 1 3.8.9L21 3z"></path>
            </svg>
        </div>
        <h1>Mesajınız Yanıtlandı!</h1>
        <p>Destek ekibimiz RezerVist üzerinden gönderdiğiniz mesajı inceledi ve yanıtladı.</p>

        <div class="card">
            <div style="margin-bottom: 24px;">
                <div class="card-title">SİZİN MESAJINIZ</div>
                <p style="margin: 0; color: #64748B; font-style: italic; font-size: 14px;">"{{ $contactMessage->message }}"</p>
            </div>

            <div style="border-top: 1px solid #F1F5F9; padding-top: 24px;">
                <div class="card-title" style="color: #6200EE;">DESTEK EKİBİ YANITI</div>
                <div style="margin: 0; color: #1E293B; font-weight: 500; font-size: 16px; line-height: 1.6;">
                    {{ $reply }}
                </div>
            </div>
        </div>

        <p style="font-size: 14px; margin-bottom: 0;">
            Başka bir sorunuz varsa, bu e-postaya doğrudan yanıt verebilir veya <a href="{{ url('/help') }}" style="color: #6200EE; font-weight: 600; text-decoration: none;">Yardım Merkezi</a>'ni ziyaret edebilirsiniz.
        </p>

        <a href="{{ url('/') }}" class="button">Sisteme Git</a>
    </div>
@endsection
