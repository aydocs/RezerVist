@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Rezervasyonlar</h1>
        </div>

        <!-- Filters -->
        <div class="mb-8 flex flex-wrap items-center gap-3">
            <!-- Branch Filter -->
            <div x-data="{ open: false }" class="relative z-30">
                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 px-4 py-3 bg-white border border-gray-200 rounded-xl shadow-sm hover:border-gray-300 transition-all font-bold text-gray-700 text-sm min-w-[160px] justify-between group">
                    <div class="flex items-center gap-2">
                        @if(request('location_id') == 'main')
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span>Merkez Şube</span>
                        @elseif(request('location_id'))
                             @php $loc = $locations->find(request('location_id')); @endphp
                             <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                             <span>{{ $loc->name ?? 'Şube Seçimi' }}</span>
                        @else
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <span>Tüm Şubeler</span>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 origin-top-left flex flex-col z-[50]" style="display: none;">
                    <a href="{{ request()->fullUrlWithQuery(['location_id' => null]) }}" class="px-4 py-2.5 hover:bg-gray-50 flex items-center gap-3 text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors {{ !request('location_id') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Tüm Şubeler
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['location_id' => 'main']) }}" class="px-4 py-2.5 hover:bg-gray-50 flex items-center gap-3 text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors {{ request('location_id') == 'main' ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Merkez / Ana Şube
                    </a>
                    @foreach($locations as $loc)
                        <a href="{{ request()->fullUrlWithQuery(['location_id' => $loc->id]) }}" class="px-4 py-2.5 hover:bg-gray-50 flex items-center gap-3 text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors {{ request('location_id') == $loc->id ? 'bg-indigo-50 text-indigo-600' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $loc->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Status Filter -->
            <div x-data="{ open: false }" class="relative z-20">
                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 px-4 py-3 bg-white border border-gray-200 rounded-xl shadow-sm hover:border-gray-300 transition-all font-bold text-gray-700 text-sm min-w-[160px] justify-between group">
                    <div class="flex items-center gap-2">
                        @if(request('status') == 'pending')
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Bekleyenler</span>
                        @elseif(request('status') == 'approved')
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Onaylananlar</span>
                        @elseif(request('status') == 'completed')
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-8a2 2 0 012-2h14a2 2 0 012 2v8M10 9H8v5a2 2 0 012 2h4a2 2 0 012-2V9h-2m-4 0V5a2 2 0 114 0v4m-4 0h4"></path></svg>
                            <span>Tamamlananlar</span>
                        @elseif(request('status') == 'cancelled')
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>İptal Edilenler</span>
                        @elseif(request('status') == 'rejected')
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                            <span>Reddedilenler</span>
                        @else
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <span>Tüm Durumlar</span>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 origin-top-left flex flex-col z-[50]" style="display: none;">
                    <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="px-4 py-2.5 hover:bg-gray-50 flex items-center gap-3 text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors {{ !request('status') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Tüm Durumlar
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="px-4 py-2.5 hover:bg-amber-50 flex items-center gap-3 text-sm font-medium text-gray-600 hover:text-amber-600 transition-colors {{ request('status') == 'pending' ? 'bg-amber-50 text-amber-600' : '' }}">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Bekleyenler
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'approved']) }}" class="px-4 py-2.5 hover:bg-emerald-50 flex items-center gap-3 text-sm font-medium text-gray-600 hover:text-emerald-600 transition-colors {{ request('status') == 'approved' ? 'bg-emerald-50 text-emerald-600' : '' }}">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Onaylananlar
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}" class="px-4 py-2.5 hover:bg-blue-50 flex items-center gap-3 text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors {{ request('status') == 'completed' ? 'bg-blue-50 text-blue-600' : '' }}">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-8a2 2 0 012-2h14a2 2 0 012 2v8M10 9H8v5a2 2 0 012 2h4a2 2 0 012-2V9h-2m-4 0V5a2 2 0 114 0v4m-4 0h4"></path></svg>
                        Tamamlananlar
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}" class="px-4 py-2.5 hover:bg-gray-50 flex items-center gap-3 text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors {{ request('status') == 'cancelled' ? 'bg-gray-100 text-gray-900 border-l-2 border-gray-400' : '' }}">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        İptal Edilenler
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'rejected']) }}" class="px-4 py-2.5 hover:bg-red-50 flex items-center gap-3 text-sm font-medium text-gray-600 hover:text-red-600 transition-colors {{ request('status') == 'rejected' ? 'bg-red-50 text-red-600' : '' }}">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        Reddedilenler
                    </a>
                </div>
            </div>

            <!-- Sort Filter -->
            <div x-data="{ open: false }" class="relative z-10">
                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 px-4 py-3 bg-white border border-gray-200 rounded-xl shadow-sm hover:border-gray-300 transition-all font-bold text-gray-700 text-sm min-w-[160px] justify-between group">
                    <div class="flex items-center gap-2">
                        @if(request('sort_by') == 'oldest')
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Son İşlem (En Eski)</span>
                        @else
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span>Son İşlem (En Yeni)</span>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 origin-top-left flex flex-col z-[50]" style="display: none;">
                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'latest']) }}" class="px-4 py-2.5 hover:bg-gray-50 flex items-center gap-3 text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors {{ request('sort_by') != 'oldest' ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        (En Yeni) Son İşlem
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'oldest']) }}" class="px-4 py-2.5 hover:bg-gray-50 flex items-center gap-3 text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors {{ request('sort_by') == 'oldest' ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        (En Eski) Son İşlem
                    </a>
                </div>
            </div>
            
            <!-- Reset Button (visible only if filters are active) -->
            @if(request()->anyFilled(['location_id', 'status', 'sort_by']))
                <a href="{{ route('vendor.reservations.index') }}" class="flex items-center justify-center px-4 py-3 bg-red-50 text-red-500 rounded-xl font-bold text-sm hover:bg-red-100 hover:text-red-600 transition border border-red-100 shadow-sm" title="Filtreleri Temizle">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <span class="ml-1 hidden sm:inline">Temizle</span>
                </a>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Müşteri</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Şube</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tarih/Saat</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kişi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tutar</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Personel</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">İşlem</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reservations as $reservation)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold shrink-0">
                                    {{ substr($reservation->user->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $reservation->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $reservation->user->phone ?? $reservation->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 bg-primary/5 text-primary rounded-lg text-[10px] font-black uppercase tracking-wider">
                                {{ $reservation->location->name ?? 'Merkez' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($reservation->start_time)->format('d.m.Y') }}</div>
                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700 flex items-center font-medium">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                {{ $reservation->guest_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900">₺{{ number_format($reservation->price, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 border bg-yellow-50 border-yellow-200',
                                    'approved' => 'bg-green-100 text-green-800 border bg-green-50 border-green-200',
                                    'rejected' => 'bg-red-100 text-red-800 border bg-red-50 border-red-200',
                                    'cancelled' => 'bg-gray-100 text-gray-800 border bg-gray-50 border-gray-200',
                                    'completed' => 'bg-blue-100 text-blue-800 border bg-blue-50 border-blue-200',
                                    'pending_payment' => 'bg-orange-100 text-orange-800 border bg-orange-50 border-orange-200',
                                ];
                                $statusLabels = [
                                    'pending' => 'Bekliyor',
                                    'approved' => 'Onaylandı',
                                    'rejected' => 'Reddedildi',
                                    'cancelled' => 'İptal Edildi',
                                    'completed' => 'Tamamlandı',
                                    'pending_payment' => 'Ödeme Bekleniyor',
                                ];
                            @endphp
                             <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$reservation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$reservation->status] ?? ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($reservation->staff)
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-[10px]">
                                        {{ substr($reservation->staff->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs font-bold text-gray-700">{{ $reservation->staff->name }}</span>
                                </div>
                            @else
                                <span class="text-xs text-gray-400 font-medium italic">Atanmadı</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                             <button onclick="document.getElementById('details-{{ $reservation->id }}').showModal()" class="text-primary hover:text-purple-900 bg-purple-50 px-4 py-2 rounded-lg text-sm font-semibold transition hover:bg-purple-100 border border-purple-100">
                                İncele
                            </button>

                            <!-- Details Modal -->
                            <dialog id="details-{{ $reservation->id }}" class="modal rounded-3xl shadow-2xl p-0 backdrop:bg-black/50 w-full max-w-2xl !overflow-visible">
                                <div class="bg-white p-6">
                                    <div class="flex justify-between items-center mb-6">
                                        <h3 class="font-bold text-xl text-gray-900">Rezervasyon #{{ $reservation->id }}</h3>
                                        <form method="dialog">
                                            <button class="text-gray-400 hover:text-gray-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="space-y-6">
                                        <!-- Customer Card -->
                                        <div class="bg-indigo-50/50 p-6 rounded-3xl flex items-center space-x-5 border border-indigo-50">
                                            <div class="bg-indigo-100 text-indigo-600 rounded-2xl w-14 h-14 flex items-center justify-center font-bold text-xl shadow-sm">
                                                {{ strtoupper(substr($reservation->user->name ?? 'M', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-lg text-gray-900 font-outfit">{{ $reservation->user->name ?? 'Misafir' }}</p>
                                                <p class="text-gray-500 font-medium">{{ $reservation->user->phone ?? 'Telefon Yok' }}</p>
                                                <p class="text-gray-400 text-sm">{{ $reservation->user->email ?? '' }}</p>
                                            </div>
                                        </div>

                                        <!-- Info Grid -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="p-5 rounded-3xl border border-gray-100 bg-gray-50/50 text-center hover:bg-white hover:shadow-lg hover:shadow-gray-100/50 transition-all duration-300 group">
                                                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Tarih</p>
                                                <p class="font-bold text-xl text-gray-900 group-hover:text-indigo-600 transition-colors">{{ \Carbon\Carbon::parse($reservation->start_time)->format('d.m.Y') }}</p>
                                            </div>
                                            <div class="p-5 rounded-3xl border border-gray-100 bg-gray-50/50 text-center hover:bg-white hover:shadow-lg hover:shadow-gray-100/50 transition-all duration-300 group">
                                                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Saat</p>
                                                <p class="font-bold text-xl text-gray-900 group-hover:text-indigo-600 transition-colors">{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}</p>
                                            </div>
                                            <div class="p-5 rounded-3xl border border-gray-100 bg-gray-50/50 text-center hover:bg-white hover:shadow-lg hover:shadow-gray-100/50 transition-all duration-300 group">
                                                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Kişi Sayısı</p>
                                                <p class="font-bold text-xl text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $reservation->guest_count }} Kişi</p>
                                            </div>
                                            <div class="p-5 rounded-3xl border border-gray-100 bg-gray-50/50 text-center hover:bg-white hover:shadow-lg hover:shadow-gray-100/50 transition-all duration-300 group">
                                                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Tutar</p>
                                                <p class="font-bold text-xl text-emerald-500 group-hover:text-emerald-600 transition-colors">₺{{ number_format($reservation->price, 2) }}</p>
                                            </div>
                                        </div>

                                        <!-- Services/Menu -->
                                        <div>
                                            <div class="flex justify-between items-end mb-4 px-2">
                                                <h4 class="font-bold text-gray-900 text-lg">Seçilen Hizmetler/Menüler</h4>
                                            </div>
                                            @if($reservation->menus->count() > 0)
                                                <div class="space-y-3">
                                                    @foreach($reservation->menus as $menu)
                                                    <div class="flex justify-between items-center p-4 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                                        <span class="font-medium text-gray-700">{{ $menu->name }}</span>
                                                        <span class="text-sm font-bold text-indigo-100 bg-indigo-500 px-3 py-1 rounded-full">x{{ $menu->pivot->quantity ?? 1 }}</span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center p-6 bg-gray-50 rounded-3xl border border-gray-100 text-gray-400 italic">
                                                    Ekstra hizmet seçilmemiş.
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Note -->
                                        @if($reservation->note)
                                        <div class="bg-yellow-50/50 p-6 rounded-3xl border border-yellow-100">
                                            <h4 class="text-xs font-bold text-yellow-800 uppercase mb-2 tracking-wider">Müşteri Notu</h4>
                                            <p class="text-base text-yellow-900 italic">"{{ $reservation->note }}"</p>
                                        </div>
                                        @endif

                                         <!-- Actions -->
                                        @if($reservation->status === 'pending')
                                        <div class="border-t pt-4 flex space-x-3">
                                            <form action="{{ route('vendor.reservations.update', $reservation->id) }}" method="POST" class="flex-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button class="w-full py-2.5 rounded-xl border border-red-200 text-red-600 hover:bg-red-50 font-bold transition">Reddet</button>
                                            </form>
                                            <form action="{{ route('vendor.reservations.update', $reservation->id) }}" method="POST" class="flex-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <div class="mb-4">
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Personel Ata (Opsiyonel)</label>
                                                    <select name="staff_id" class="w-full px-4 py-2 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20">
                                                        <option value="">Seçilmedi</option>
                                                        @foreach($staff as $member)
                                                            <option value="{{ $member->id }}" {{ $reservation->staff_id == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <button class="w-full py-2.5 rounded-xl bg-primary text-white font-bold hover:bg-primary-dark transition shadow-lg shadow-primary/20">Onayla ve Kaydet</button>
                                            </form>
                                        </div>

                                        <div class="mt-4 flex justify-between items-center bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                                </div>
                                                <p class="text-xs font-bold text-indigo-900">Müşteri ile iletişime geçin</p>
                                            </div>
                                            <a href="{{ route('messages.chat', $reservation->user_id) }}" class="px-6 py-2 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-md">Mesaj Gönder</a>
                                        </div>
                                        @elseif($reservation->status === 'approved')
                                        <div class="border-t pt-4">
                                            <form action="{{ route('vendor.reservations.update', $reservation->id) }}" method="POST" class="mb-4">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <div class="flex items-end gap-3">
                                                    <div class="flex-1">
                                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Personeli Değiştir</label>
                                                        <select name="staff_id" class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20">
                                                            <option value="">Seçilmedi</option>
                                                            @foreach($staff as $member)
                                                                <option value="{{ $member->id }}" {{ $reservation->staff_id == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition-all">Güncelle</button>
                                                </div>
                                            </form>
                                            @php
                                                $canCancel = now()->diffInHours($reservation->start_time, false) >= 24;
                                            @endphp
                                            
                                            <div class="border-t pt-4">
                                                @if($canCancel)
                                                    <form action="{{ route('vendor.reservations.update', $reservation->id) }}" method="POST" onsubmit="confirmCancellation(event, this)">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button class="w-full py-4 rounded-3xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white font-bold transition-all duration-300 shadow-sm hover:shadow-red-200 border border-transparent hover:border-red-500">Rezervasyonu İptal Et</button>
                                                    </form>
                                                @else
                                                     <div class="bg-orange-50 p-3 rounded-lg border border-orange-100 flex items-center text-orange-800 text-sm">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        <span>Rezervasyona 24 saatten az kaldığı için iptal edilemez.</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </dialog>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                             <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-lg font-medium text-gray-500">Henüz rezervasyon bulunmuyor.</p>
                                <p class="text-sm text-gray-400">Rezervasyonlar gelmeye başladığında burada listelenecek.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($reservations as $reservation)
                <div class="p-4 hover:bg-gray-50 transition" onclick="document.getElementById('details-{{ $reservation->id }}').showModal()">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 border border-indigo-100 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold shrink-0">
                                {{ substr($reservation->user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-black text-slate-900">{{ $reservation->user->name }}</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} • {{ $reservation->guest_count }} Kişi</div>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wide {{ $statusClasses[$reservation->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$reservation->status] ?? ucfirst($reservation->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-400 font-bold">{{ \Carbon\Carbon::parse($reservation->start_time)->format('d.m.Y') }}</span>
                        <span class="font-black text-slate-900">₺{{ number_format($reservation->price, 2) }}</span>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 italic font-medium">Rezervasyon bulunamadı.</div>
                @endforelse
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $reservations->links() }}
            </div>
        </div>
    </div>
</div>
    <script>
        function confirmCancellation(event, form) {
            event.preventDefault();
            const dialog = form.closest('dialog');
            Swal.fire({
                target: dialog ? dialog : 'body',
                title: 'Emin misiniz?',
                html: "Bu rezervasyonu iptal etmek istediğinize emin misiniz?<br>Müşteriye iptal bildirimi gönderilecektir.",
                icon: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: 'Evet, İptal Et',
                cancelButtonText: 'Vazgeç',
                reverseButtons: true,
                width: '32rem',
                padding: '2rem',
                backdrop: `rgba(0,0,0,0.4)`,
                customClass: {
                    container: 'z-[99999] font-outfit',
                    popup: 'rounded-[2.5rem] shadow-2xl border border-gray-100',
                    title: 'text-2xl font-bold text-gray-900 mb-3 font-outfit',
                    htmlContainer: 'text-gray-500 font-medium font-outfit text-center !m-0 !mb-8 text-[0.95rem]',
                    actions: 'gap-4 w-full flex justify-center',
                    confirmButton: 'w-full sm:w-auto px-8 py-3.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-bold transition-all duration-200 transform hover:scale-[1.02] shadow-lg shadow-red-500/30 flex-1 justify-center',
                    cancelButton: 'w-full sm:w-auto px-8 py-3.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition-all duration-200 hover:scale-[1.02] flex-1 justify-center'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
    </script>
@endsection
