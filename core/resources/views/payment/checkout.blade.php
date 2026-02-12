@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 bg-primary text-white text-center">
                <h2 class="text-2xl font-bold">Güvenli Ödeme</h2>
                <p class="opacity-90 mt-2">İşleminizi tamamlamak için lütfen ödemeyi gerçekleştirin.</p>
            </div>
            
            <div class="p-8">
                <!-- Iyzico Iframe -->
                <div class="iyzico-form-container">
                    {!! $iframeEnv !!}
                </div>
            </div>
        </div>
        
        <div class="text-center mt-6">
            <a href="{{ route('profile.reservations') }}" class="text-gray-500 hover:text-gray-900 text-sm font-medium">Bu işlemi iptal et ve rezervasyonlara dön</a>
        </div>
    </div>
</div>

<div id="iyzipay-checkout-form" class="responsive"></div>
@endsection
