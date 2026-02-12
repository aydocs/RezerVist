@extends('layouts.app')

@section('title', 'Kullanıcı Sözleşmesi ve Hizmet Koşulları - RezerVist')

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <!-- Unified Hero Section -->
    <div class="bg-gradient-to-r from-violet-900 to-primary text-white pt-24 pb-32">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <h1 class="text-3xl lg:text-5xl font-black tracking-tight mb-4">Kullanıcı Sözleşmesi</h1>
            <p class="text-lg text-violet-100 max-w-2xl mx-auto font-medium">
                RezerVist Platformu Genel Hizmet Koşulları ve Kullanım Şartları
            </p>
            <div class="mt-8 inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                <span class="text-xs font-bold text-white uppercase tracking-widest">Sürüm: 2.1 • Son Güncelleme: {{ date('d.m.Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Content Container -->
    <div class="max-w-5xl mx-auto px-6 lg:px-8 -mt-20 relative z-10">
        <!-- INTRO ALERT (Inside the layout but above the main card) -->
        <div class="bg-blue-600 rounded-2xl shadow-lg border border-white/10 p-4 mb-8 flex items-center gap-4 text-white">
            <i class="fas fa-info-circle text-2xl"></i>
            <p class="text-xs lg:text-sm font-bold m-0 leading-tight">
                LÜTFEN DİKKAT: Platformu kullanarak bu sözleşmenin tüm maddelerini kabul etmiş sayılırsınız.
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 p-8 lg:p-16 prose prose-lg prose-slate max-w-none">
            @include('pages.terms_content')
        </div>

        <!-- Footer for the doc -->
        <div class="mt-8 text-center">
            <p class="text-xs text-gray-400 font-medium">
                © {{ date('Y') }} RezerVist Teknoloji A.Ş. • Tüm Hakları Saklıdır.
            </p>
        </div>
    </div>
</div>
@endsection
