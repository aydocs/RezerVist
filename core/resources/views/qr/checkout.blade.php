@extends('qr.layout')

@section('title', 'Ödeme')

@section('content')
<div class="px-5 py-12 min-h-[80vh] flex flex-col max-w-lg mx-auto w-full">
    <div class="bg-white rounded-[3rem] p-8 shadow-2xl shadow-purple-500/5 border border-purple-50 relative overflow-hidden text-center">
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-purple-400 via-violet-500 to-indigo-500"></div>
        
        <div class="mb-8">
            <h2 class="text-2xl font-black text-gray-900 tracking-tighter">GÜVENLİ ÖDEME</h2>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-2">Ödeme işleminizi aşağıdan tamamlayabilirsiniz.</p>
        </div>

        <div id="iyzipay-checkout-form" class="responsive"></div>
        {!! $checkoutFormContent !!}
        
        <div class="mt-8 flex flex-col items-center gap-4 opacity-40">
            <div class="w-12 h-0.5 bg-gray-200 rounded-full"></div>
            <p class="text-[9px] text-gray-500 font-black uppercase tracking-[0.3em] text-center">IYZICO GÜVENCESİYLE</p>
        </div>
    </div>
    
    <div class="mt-8 px-2 text-center">
         <a href="{{ route('qr.bill', ['payload' => $payload]) }}" class="text-xs font-black text-gray-400 uppercase tracking-widest hover:text-primary transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Adisyona Geri Dön
        </a>
    </div>
</div>
@endsection
