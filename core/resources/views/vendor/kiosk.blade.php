@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                    <i class="fas fa-desktop text-primary text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $business->name }}</h1>
                    <p class="text-sm text-gray-500 font-medium">Canlı Doluluk ve Kiosk Ekranı</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden md:block">
                    <p class="text-lg font-bold text-gray-900" x-data="{ time: '' }" x-init="setInterval(() => time = new Date().toLocaleTimeString('tr-TR', {hour: '2-digit', minute:'2-digit'}), 1000)" x-text="time"></p>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ now()->isoFormat('D MMMM YYYY') }}</p>
                </div>
                <a href="{{ route('vendor.dashboard') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium flex items-center gap-2 shadow-sm">
                    <i class="fas fa-arrow-left"></i>
                    <span>Panele Dön</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Occupancy Gauge (Full Height) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 flex flex-col items-center justify-center text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5">
                    <i class="fas fa-chart-pie text-9xl"></i>
                </div>
                
                <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-8">Anlık Doluluk Oranı</h2>
                
                <div class="relative mb-8">
                    <!-- Circular Progress -->
                    <svg class="w-64 h-64 transform -rotate-90">
                        <circle cx="128" cy="128" r="110" stroke="currentColor" stroke-width="20" fill="transparent" class="text-gray-100" />
                        <circle cx="128" cy="128" r="110" stroke="currentColor" stroke-width="20" fill="transparent" 
                            class="transition-all duration-1000 ease-out {{ $business->occupancy_rate >= 85 ? 'text-red-500' : ($business->occupancy_rate >= 60 ? 'text-amber-500' : 'text-emerald-500') }}"
                            stroke-dasharray="{{ 2 * pi() * 110 }}"
                            stroke-dashoffset="{{ 2 * pi() * 110 * (1 - $business->occupancy_rate / 100) }}"
                            stroke-linecap="round"
                        />
                    </svg>
                    
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-6xl font-black text-gray-900 tracking-tight">{{ $business->occupancy_rate }}<span class="text-3xl text-gray-400">%</span></span>
                    </div>
                </div>

                <div class="space-y-2">
                    <p class="text-sm font-medium text-gray-500">Son Güncelleme</p>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 text-xs font-bold text-gray-600">
                        <div class="w-2 h-2 rounded-full {{ $business->last_occupancy_update ? 'bg-green-500 animate-pulse' : 'bg-gray-400' }}"></div>
                        {{ $business->last_occupancy_update ? $business->last_occupancy_update->diffForHumans() : 'Veri yok' }}
                    </div>
                </div>
            </div>

            <!-- Right: Stats Grid -->
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Stat Card 1 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                            <i class="fas fa-chair text-lg"></i>
                        </div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Toplam Kapasite</p>
                        <h3 class="text-3xl font-black text-gray-900 mt-1">{{ $resources->sum('capacity') }} <span class="text-sm font-medium text-gray-500">Kişi</span></h3>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center mb-4">
                            <i class="fas fa-calendar-check text-lg"></i>
                        </div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Bugünkü Rezervasyon</p>
                        <h3 class="text-3xl font-black text-gray-900 mt-1">{{ $stats['reservations_today'] }} <span class="text-sm font-medium text-gray-500">Adet</span></h3>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center mb-4">
                            <i class="fas fa-users text-lg"></i>
                        </div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Tahmini Yoğunluk</p>
                        <h3 class="text-3xl font-black text-gray-900 mt-1">
                            @if($business->occupancy_rate >= 85)
                                <span class="text-red-500">Çok Yüksek</span>
                            @elseif($business->occupancy_rate >= 60)
                                <span class="text-amber-500">Orta</span>
                            @else
                                <span class="text-emerald-500">Düşük</span>
                            @endif
                        </h3>
                    </div>
                </div>

                <!-- Stat Card 4 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center mb-4">
                            <i class="fas fa-link text-lg"></i>
                        </div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Sistem Bağlantısı</p>
                        @php
                            $isPosActive = $business->last_occupancy_update && $business->last_occupancy_update->diffInMinutes(now()) < 5;
                        @endphp
                        <div class="flex items-center gap-2 mt-1">
                            <div class="w-3 h-3 rounded-full {{ $isPosActive ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400' }}"></div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $isPosActive ? 'POS Aktif' : 'POS Çevrimdışı' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer / Info -->
        <div class="mt-8 text-center">
            <p class="text-xs text-gray-400">Bu ekran her 60 saniyede bir otomatik güncellenir.</p>
        </div>

    </div>
</div>

<script>
    // Auto-refresh page every 60 seconds
    setTimeout(function() {
        window.location.reload();
    }, 60000);
</script>
@endsection
