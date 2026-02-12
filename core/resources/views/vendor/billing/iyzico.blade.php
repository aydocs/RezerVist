@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50/50 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-12 text-center">
            <h1 class="text-4xl font-black text-gray-900 mb-4 tracking-tight">Güvenli Ödeme</h1>
            <p class="text-gray-500 font-medium">Aboneliğinizi yükseltmek için kart bilgilerinizi giriniz.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100 p-8 md:p-12">
            <div id="iyzipay-checkout-form" class="responsive">
                {!! $paymentContent !!}
            </div>
            
            <div class="mt-12 text-center border-t border-gray-50 pt-8">
                <div class="flex items-center justify-center gap-6 opacity-50 grayscale hover:grayscale-0 transition-all">
                    <img src="https://www.iyzico.com/assets/images/logo/iyzico-logo.svg" alt="Iyzico" class="h-6">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-8">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="h-4">
                </div>
                <p class="mt-6 text-[11px] font-bold text-gray-400 uppercase tracking-widest flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    256-BIT SSL GÜVENLİ ÖDEME ALTYAPISI
                </p>
            </div>
        </div>
        
        <div class="mt-8 text-center">
            <a href="{{ route('vendor.billing.index') }}" class="text-sm font-black text-gray-400 hover:text-gray-900 transition-colors uppercase tracking-widest flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Geri Dön
            </a>
        </div>
    </div>
</div>
@endsection
