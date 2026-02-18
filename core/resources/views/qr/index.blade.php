@extends('qr.layout')

@section('title', 'Hoş Geldiniz')

@section('content')
<div class="min-h-[80vh] flex flex-col items-center justify-center px-6 text-center">
    
    {{-- Welcome Icon/Image --}}
    <div class="mb-10 relative group">
        <div class="absolute inset-0 bg-primary/20 rounded-full blur-3xl animate-pulse group-hover:bg-primary/30 transition-all duration-700"></div>
        @if($business->logo)
            <img src="{{ $business->logo }}" class="relative w-36 h-36 rounded-full object-cover ring-[6px] ring-white shadow-2xl mx-auto transform group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="relative w-36 h-36 rounded-full bg-gradient-to-br from-primary to-violet-700 text-white flex items-center justify-center font-black text-5xl shadow-2xl mx-auto border-[6px] border-white transform group-hover:scale-105 transition-transform duration-500">
                {{ substr($business->name, 0, 1) }}
            </div>
        @endif
        <div class="absolute -bottom-3 -right-3 bg-white text-primary text-[11px] font-black px-4 py-1.5 rounded-full border border-gray-100 shadow-xl tracking-wide uppercase">
            {{ $resource->name }}
        </div>
    </div>

    {{-- Text --}}
    <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-4">Hoş Geldiniz <span class="animate-wave inline-block origin-bottom-right">👋</span></h1>
    <p class="text-slate-500 font-medium mb-12 max-w-sm mx-auto text-base leading-relaxed">
        <span class="font-bold text-primary">{{ $business->name }}</span> ayrıcalığını yaşamaya hazır mısınız? Lütfen işleminizi seçin.
    </p>

    {{-- Actions --}}
    <div class="w-full max-w-sm space-y-4">
        
        {{-- View Menu --}}
        <a href="{{ route('qr.menu', ['payload' => $payload]) }}" class="group relative block w-full">
            <div class="absolute inset-0 bg-primary rounded-2xl blur-xl opacity-0 group-hover:opacity-20 transition-opacity duration-500"></div>
            <div class="relative bg-white border border-gray-100 p-6 rounded-2xl flex items-center gap-5 shadow-sm group-hover:shadow-soft group-hover:border-primary/30 group-active:scale-[0.98] transition-all duration-300">
                <div class="w-14 h-14 rounded-2xl bg-primary/5 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div class="text-left flex-1">
                    <h3 class="font-bold text-slate-900 text-lg group-hover:text-primary transition-colors">Menüyü İncele</h3>
                    <p class="text-sm text-slate-400 font-medium mt-1 group-hover:text-slate-500">Lezzetlerimizi keşfedin</p>
                </div>
                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300 group-hover:text-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </a>

        {{-- View Bill --}}
        <a href="{{ route('qr.bill', ['payload' => $payload]) }}" class="group relative block w-full">
            <div class="absolute inset-0 bg-primary rounded-2xl blur-xl opacity-0 group-hover:opacity-20 transition-opacity duration-500"></div>
            <div class="relative bg-white border border-gray-100 p-6 rounded-2xl flex items-center gap-5 shadow-sm group-hover:shadow-soft group-hover:border-primary/30 group-active:scale-[0.98] transition-all duration-300">
                 <div class="w-14 h-14 rounded-2xl bg-primary/5 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="text-left flex-1">
                    <h3 class="font-bold text-slate-900 text-lg group-hover:text-primary transition-colors">Adisyon / Ödeme</h3>
                    <p class="text-sm text-slate-400 font-medium mt-1 group-hover:text-slate-500">Hesabı gör ve öde</p>
                </div>
                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300 group-hover:text-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </a>

    </div>
</div>

<style>
@keyframes wave {
    0% { transform: rotate(0.0deg) }
    10% { transform: rotate(14.0deg) }
    20% { transform: rotate(-8.0deg) }
    30% { transform: rotate(14.0deg) }
    40% { transform: rotate(-4.0deg) }
    50% { transform: rotate(10.0deg) }
    60% { transform: rotate(0.0deg) }
    100% { transform: rotate(0.0deg) }
}
.animate-wave {
    animation: wave 2s infinite;
    transform-origin: 70% 70%;
    display: inline-block;
}
</style>
@endsection
