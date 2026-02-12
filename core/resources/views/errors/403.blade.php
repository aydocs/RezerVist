@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-red-100 rounded-full mb-6">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m11-3V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-3zM7 11V7a5 5 0 0110 0v4"></path>
                </svg>
            </div>
            <h1 class="text-6xl font-black text-slate-900 mb-4">403</h1>
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Yetkisiz Erişim</h2>
            <p class="text-slate-600">Bu sayfayı veya belgeyi görüntülemek için gerekli yetkiye sahip değilsiniz.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/') }}" class="px-8 py-3 bg-primary text-white font-bold rounded-xl shadow-lg hover:shadow-primary/30 transition transform hover:-translate-y-1">
                Ana Sayfaya Dön
            </a>
            <button onclick="window.history.back()" class="px-8 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition">
                Geri Git
            </button>
        </div>
    </div>
</div>
@endsection
