@extends('qr.layout')

@section('title', 'Adisyon')

@section('content')
<div class="px-5 py-8 min-h-[85vh] flex flex-col max-w-lg mx-auto w-full">
    
    <div class="flex-1">
        {{-- Premium Bill Card --}}
        <div class="bg-white rounded-[3rem] p-8 shadow-2xl shadow-purple-500/5 border border-purple-50 relative overflow-hidden">
            {{-- Ticket Glass Header --}}
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-purple-400 via-violet-500 to-indigo-500"></div>
            
            <div class="text-center mb-10 pt-4">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-50 rounded-2xl mb-4">
                    <i class="fa-solid fa-receipt text-2xl text-primary"></i>
                </div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tighter">ADİSYON</h2>
                {{-- Table & Date Info --}}
                <div class="flex items-center justify-center gap-2 mt-2">
                    <span class="px-3 py-1 bg-gray-900 text-white text-[10px] font-black rounded-lg uppercase tracking-widest">{{ $resource->name }}</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ now()->format('d.m.Y H:i') }}</span>
                </div>
            </div>

            @if($order && $order->items->count() > 0)
                <div class="space-y-5 mb-10">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-[10px] font-black text-gray-400 border border-gray-100 group-hover:bg-purple-50 group-hover:text-primary group-hover:border-purple-100 transition-colors">
                                @if(fmod($item->quantity, 1) == 0)
                                    {{ (int)$item->quantity }}x
                                @else
                                    {{ number_format($item->quantity, 2, ',', '.') }}
                                @endif
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-900 leading-none mb-1">{{ $item->name }}</span>
                                <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Ürün Detayı</span>
                            </div>
                        </div>
                        <div class="text-right">
                             <span class="font-black text-gray-900 tracking-tight">₺{{ number_format($item->total_price, 2) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Totals Section --}}
                <div class="border-t-2 border-dashed border-purple-50 pt-8 space-y-4">
                    <div class="flex justify-between items-center text-gray-400 text-xs font-bold uppercase tracking-widest">
                        <span>Ara Toplam</span>
                        <span class="text-gray-600">₺{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    
                    @if($order->paid_amount > 0)
                    <div class="flex justify-between items-center text-emerald-500 text-xs font-bold uppercase tracking-widest">
                        <span>Ödenen Tutar</span>
                        <span>-₺{{ number_format($order->paid_amount, 2) }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between items-center pt-4">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-1">GÜNCEL BORÇ</span>
                            <span class="text-sm font-bold text-gray-300 line-through">₺{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-4xl font-black text-gray-900 tracking-tighter">₺{{ number_format($order->total_amount - $order->paid_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Footer Info --}}
                <div class="mt-12 flex flex-col items-center gap-4 opacity-40">
                    <div class="w-12 h-0.5 bg-gray-200 rounded-full"></div>
                    <p class="text-[9px] text-gray-500 font-black uppercase tracking-[0.3em] text-center">HİZMET BEDELİ DAHİLDİR &bull; REZERVIST</p>
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-gray-200">
                        <i class="fa-solid fa-receipt text-4xl"></i>
                    </div>
                    <p class="font-black text-gray-900 text-xl tracking-tight">Adisyon Bulunamadı</p>
                    <p class="text-sm font-medium text-gray-400 mt-2 max-w-[200px] mx-auto">Bu masa için şu anda aktif bir siparişiniz bulunmuyor.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Action Floating Buttons --}}
    @if($order && ($order->total_amount - $order->paid_amount) > 0)
    <div class="mt-8 space-y-4 px-2">
        @if(!auth()->check())
            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 flex gap-3 items-center mb-4">
                <i class="fa-solid fa-circle-info text-amber-500"></i>
                <p class="text-xs font-bold text-amber-700 leading-relaxed">Ödeme yapabilmek için giriş yapmanız gerekmektedir.</p>
            </div>
        @endif

        <form action="{{ route('qr.payment.init', ['payload' => $payload]) }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <button type="submit" 
                    :disabled="loading"
                    class="w-full bg-primary text-white py-6 rounded-[2rem] font-black text-lg shadow-2xl shadow-primary/40 active:scale-95 transition-all flex items-center justify-center gap-3 group disabled:opacity-70 disabled:cursor-not-allowed">
                <template x-if="!loading">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-credit-card text-xl group-hover:rotate-12 transition-transform"></i>
                        <span>Kredi Kartı ile Öde</span>
                    </div>
                </template>
                <template x-if="loading">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-notch animate-spin text-xl"></i>
                        <span>İşleniyor...</span>
                    </div>
                </template>
            </button>
        </form>

        <button class="w-full bg-white text-gray-900 border border-gray-100 py-6 rounded-[2rem] font-black text-lg shadow-xl shadow-gray-500/5 active:scale-95 transition-all flex items-center justify-center gap-3">
            <i class="fa-solid fa-bell text-xl"></i>
            <span>Garson Çağır</span>
        </button>
    </div>
    @else
        <div class="mt-8 px-2">
             <a href="{{ route('qr.menu', ['payload' => $payload]) }}" class="w-full bg-white text-gray-900 border border-gray-100 py-6 rounded-[2rem] font-black text-lg shadow-xl shadow-gray-500/5 active:scale-95 transition-all flex items-center justify-center gap-3">
                <i class="fa-solid fa-utensils text-xl"></i>
                <span>Menüye Dön</span>
            </a>
        </div>
    @endif
</div>
@endsection
