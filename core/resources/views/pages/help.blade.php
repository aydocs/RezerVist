@extends('layouts.app')

@section('title', 'Yardım Merkezi ve Destek - RezerVist')

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <!-- Unified Hero Section -->
    <div class="bg-gradient-to-r from-violet-900 to-primary text-white pt-24 pb-32">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <h1 class="text-3xl lg:text-5xl font-black tracking-tight mb-4">Yardım Merkezi</h1>
            <p class="text-lg text-violet-100 max-w-2xl mx-auto font-medium">
                Sistem işleyişi hakkında aradığınız tüm cevaplar ve kullanım rehberi.
            </p>
        </div>
    </div>

    <!-- Content Container -->
    <div class="max-w-5xl mx-auto px-6 lg:px-8 -mt-20 relative z-10">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 p-8 lg:p-16 prose prose-lg prose-slate max-w-none">
            @include('pages.help_content')
        </div>

        <!-- Support Box Card (Bottom) -->
        <div class="mt-12 not-prose bg-gradient-to-br from-slate-900 to-indigo-950 rounded-[40px] p-12 text-center relative overflow-hidden group shadow-2xl border border-white/5">
            <div class="absolute top-0 right-0 w-80 h-80 bg-primary/20 rounded-full blur-[100px] -mr-40 -mt-40 group-hover:bg-primary/30 transition duration-1000"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-violet-600/10 rounded-full blur-[100px] -ml-40 -mb-40 group-hover:bg-violet-600/20 transition duration-1000"></div>
            
            <div class="relative z-10">
                <h3 class="text-2xl lg:text-3xl font-black text-white mb-4 tracking-tight">Daha Fazla Destek mi Lazım?</h3>
                <p class="text-slate-400 mb-8 max-w-2xl mx-auto font-medium">Haftanın 7 günü 09:00 - 00:00 arası canlı destek ve müşteri hizmetlerimizle yanınızdayız.</p>
                
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="/contact" class="px-8 py-4 bg-white text-slate-900 rounded-2xl font-black uppercase tracking-wider text-xs hover:bg-gray-100 transition shadow-lg hover:-translate-y-1">
                        Bize Yazın
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
