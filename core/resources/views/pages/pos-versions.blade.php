@extends('layouts.app')

@section('title', 'Sürüm Notları - RezerVist POS')

@section('content')
<div class="bg-slate-50 min-h-screen py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">
            <div class="text-center mb-16">
                <h2 class="text-base font-semibold leading-7 text-primary">Yenilikler & Güncellemeler</h2>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Sürüm Geçmişi</h1>
                <p class="mt-4 text-lg leading-8 text-gray-600">RezerVist POS sistemindeki en son geliştirmeler ve düzeltmeler.</p>
            </div>

            <div class="space-y-12">
                <!-- Version 2.4.0 -->
                <div class="relative pl-16 group">
                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-white font-bold shadow-lg shadow-primary/25">V2</div>
                    <div class="absolute left-5 top-10 bottom-[-48px] w-px bg-gray-200 group-last:hidden"></div>
                    
                    <div class="bg-white rounded-3xl p-8 shadow-sm ring-1 ring-gray-900/5">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <h3 class="text-xl font-bold text-gray-900">Masa Yönetimi 2.0</h3>
                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Yeni</span>
                        </div>
                        <p class="text-gray-500 text-sm mb-6">30 Ocak 2026</p>
                        <ul class="space-y-3">
                            <li class="flex gap-3 text-sm text-gray-600">
                                <svg class="h-5 w-5 flex-none text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span>Sürükle & Bırak masa düzenleme özelliği eklendi.</span>
                            </li>
                            <li class="flex gap-3 text-sm text-gray-600">
                                <svg class="h-5 w-5 flex-none text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span>Canlı sipariş takibi için WebSocket optimizasyonları yapıldı.</span>
                            </li>
                            <li class="flex gap-3 text-sm text-gray-600">
                                <svg class="h-5 w-5 flex-none text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span>Karanlık mod desteği geliştirildi.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Version 2.3.1 -->
                <div class="relative pl-16 group">
                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-gray-600 font-bold border border-gray-200">2.3</div>
                    <div class="absolute left-5 top-10 bottom-[-48px] w-px bg-gray-200 group-last:hidden"></div>
                    
                    <div class="bg-white rounded-3xl p-8 shadow-sm ring-1 ring-gray-900/5">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <h3 class="text-xl font-bold text-gray-900">Performans İyileştirmeleri</h3>
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Güncelleme</span>
                        </div>
                        <p class="text-gray-500 text-sm mb-6">15 Ocak 2026</p>
                        <ul class="space-y-3">
                            <li class="flex gap-3 text-sm text-gray-600">
                                <svg class="h-5 w-5 flex-none text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span>Veritabanı sorguları optimize edildi (%40 hız artışı).</span>
                            </li>
                            <li class="flex gap-3 text-sm text-gray-600">
                                <svg class="h-5 w-5 flex-none text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span>Mobil uyumluluk sorunları giderildi.</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                 <!-- Version 1.0.0 -->
                <div class="relative pl-16 group">
                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-gray-600 font-bold border border-gray-200">1.0</div>
                    <div class="bg-white rounded-3xl p-8 shadow-sm ring-1 ring-gray-900/5">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <h3 class="text-xl font-bold text-gray-900">İlk Sürüm</h3>
                            <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Release</span>
                        </div>
                        <p class="text-gray-500 text-sm mb-6">1 Ocak 2024</p>
                        <div class="text-sm text-gray-600">
                            RezerVist POS sisteminin ilk kararlı sürümü yayınlandı.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
