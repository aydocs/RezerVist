@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Yönetim Paneli</h1>
            <p class="text-gray-600">Platformunuzun genel durumunu görüntüleyin</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- Total Revenue -->
            <div onclick="window.location.href='{{ route('admin.reports.index') }}'" class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 overflow-hidden cursor-pointer hover:border-emerald-200">
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-emerald-400/10 to-emerald-600/10 rounded-full blur-3xl transform translate-x-20 -translate-y-20"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        @if($growth['revenue'] != 0)
                            <span class="flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full {{ $growth['revenue'] >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                @if($growth['revenue'] >= 0)
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                @else
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                @endif
                                {{ abs($growth['revenue']) }}%
                            </span>
                        @endif
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Toplam Gelir</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-emerald-600 to-emerald-800 bg-clip-text text-transparent">
                            ₺{{ number_format($stats['total_revenue'], 2) }}
                        </p>
                        <p class="text-xs text-gray-500 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            Son 7 güne göre
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Reservations -->
            <div onclick="window.location.href='{{ route('admin.activities.reservations') }}'" class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 overflow-hidden cursor-pointer hover:border-blue-200">
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-400/10 to-blue-600/10 rounded-full blur-3xl transform translate-x-20 -translate-y-20"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        @if($growth['reservations'] != 0)
                            <span class="flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full {{ $growth['reservations'] >= 0 ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700' }}">
                                @if($growth['reservations'] >= 0)
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                @else
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                @endif
                                {{ abs($growth['reservations']) }}%
                            </span>
                        @endif
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Toplam Rezervasyon</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                            {{ number_format($stats['total_reservations']) }}
                        </p>
                        <p class="text-xs text-gray-500 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Tüm zamanlar
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div onclick="window.location.href='{{ route('admin.users.index') }}'" class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 overflow-hidden cursor-pointer hover:border-purple-200">
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-purple-400/10 to-purple-600/10 rounded-full blur-3xl transform translate-x-20 -translate-y-20"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        @if($growth['users'] != 0)
                            <span class="flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full {{ $growth['users'] >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                @if($growth['users'] >= 0)
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                @else
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                @endif
                                {{ abs($growth['users']) }}%
                            </span>
                        @endif
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Toplam Kullanıcı</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">
                            {{ number_format($stats['total_users']) }}
                        </p>
                        <p class="text-xs text-gray-500 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            Son 7 güne göre
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pending Applications -->
            <div onclick="window.location.href='{{ route('admin.applications.index') }}'" class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 overflow-hidden cursor-pointer hover:border-amber-200">
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-amber-400/10 to-amber-600/10 rounded-full blur-3xl transform translate-x-20 -translate-y-20"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-700 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($growth['new_apps'] > 0)
                                <span class="flex items-center gap-1.5 text-[10px] font-black px-2.5 py-1 rounded-lg bg-amber-100 text-amber-700 uppercase tracking-wider">
                                    +{{ $growth['new_apps'] }} Yeni
                                </span>
                            @endif
                            @if($stats['pending_applications'] > 0)
                                <span class="relative flex h-4 w-4">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 shadow-lg"></span>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Bekleyen Başvuru</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-amber-600 to-amber-800 bg-clip-text text-transparent">
                            {{ $stats['pending_applications'] }}
                        </p>
                        <p class="text-xs text-gray-500 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            İnceleme bekliyor
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            
            <!-- Recent Activity -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Son Aktiviteler</h3>
                        <p class="text-sm text-gray-500 mt-1">Platformdaki son hareketler</p>
                    </div>
                    <a href="{{ route('admin.platform-activity.index') }}" class="text-sm font-semibold text-purple-600 hover:text-purple-700 hover:underline">Tümünü Gör</a>
                </div>
                <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
                    @forelse($recentActivities as $activity)
                        <div class="flex gap-4 items-start group hover:bg-gray-50 p-4 rounded-xl transition-colors border border-transparent hover:border-gray-200">
                            <!-- Icon/Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-{{ $activity['color'] }}-100 flex items-center justify-center">
                                    @if($activity['icon'] == 'calendar')
                                        <svg class="w-5 h-5 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @elseif($activity['icon'] == 'user')
                                        <svg class="w-5 h-5 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    @elseif($activity['icon'] == 'credit-card')
                                        <svg class="w-5 h-5 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    @elseif($activity['icon'] == 'document-text')
                                        <svg class="w-5 h-5 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    @elseif($activity['icon'] == 'login')
                                        <svg class="w-5 h-5 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                    @else
                                        <svg class="w-5 h-5 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    @endif
                                </div>
                            </div>
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">{{ $activity['message'] }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $activity['color'] }}-100 text-{{ $activity['color'] }}-800">
                                        {{ $activity['type'] ?? 'Aktivite' }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $activity['time'] }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <p class="text-sm text-gray-500">Henüz aktivite yok</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Top Businesses -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">En Popüler İşletmeler</h3>
                        <p class="text-sm text-gray-500 mt-1">Rezervasyon sayısına göre sıralama</p>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    @forelse($topBusinesses as $index => $business)
                        <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-purple-500/30">
                                    #{{ $index + 1 }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate group-hover:text-purple-600 transition-colors">{{ $business->name }}</p>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-xs text-gray-500">{{ $business->reservations_count }} rezervasyon</span>
                                    <div class="flex gap-0.5">
                                        @for($i = 0; $i < min(5, $business->rating); $i++)
                                            <svg class="w-3 h-3 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <p class="text-sm text-gray-500">Henüz işletme yok</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- NEW: Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Kategorik Dağılım</h3>
                        <p class="text-sm text-gray-500">Mevcut işletme ve rezervasyonların kategorilere göre oranı</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-1">
                        <canvas id="categoryChart" height="250"></canvas>
                    </div>
                    <div class="md:col-span-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($categoryStats as $cat)
                            <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-purple-500 shadow-sm shadow-purple-500/50"></div>
                                    <span class="font-bold text-gray-900">{{ $cat->name }}</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-gray-500">{{ $cat->businesses_count }} İşletme</p>
                                    <p class="text-sm font-black text-purple-600">{{ $cat->reservations_count }} Rezervasyon</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Hızlı Erişim</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Applications -->
                <a href="{{ route('admin.applications.index') }}" class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/0 to-purple-600/0 group-hover:from-purple-500/5 group-hover:to-purple-600/5 transition-all duration-300"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">Başvurular</h3>
                        <p class="text-sm text-gray-500 mb-4">İşletme başvurularını yönetin</p>
                        <div class="flex items-center text-sm font-semibold text-purple-600 group-hover:gap-2 transition-all">
                            <span>Görüntüle</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>

                <!-- Users -->
                <a href="{{ route('admin.users.index') }}" class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-blue-600/0 group-hover:from-blue-500/5 group-hover:to-blue-600/5 transition-all duration-300"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">Kullanıcılar</h3>
                        <p class="text-sm text-gray-500 mb-4">Kullanıcı hesaplarını yönetin</p>
                        <div class="flex items-center text-sm font-semibold text-blue-600 group-hover:gap-2 transition-all">
                            <span>Görüntüle</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>

                <!-- Reports -->
                <a href="{{ route('admin.reports.index') }}" class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-emerald-600/0 group-hover:from-emerald-500/5 group-hover:to-emerald-600/5 transition-all duration-300"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 group-hover:text-emerald-600 transition-colors">Raporlar</h3>
                        <p class="text-sm text-gray-500 mb-4">Detaylı raporları inceleyin</p>
                        <div class="flex items-center text-sm font-semibold text-emerald-600 group-hover:gap-2 transition-all">
                            <span>Görüntüle</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>

                <!-- Settings -->
                <a href="{{ route('admin.settings.index') }}" class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-500/0 to-gray-600/0 group-hover:from-gray-500/5 group-hover:to-gray-600/5 transition-all duration-300"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-gray-600 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 group-hover:text-gray-700 transition-colors">Ayarlar</h3>
                        <p class="text-sm text-gray-500 mb-4">Sistem ayarlarını düzenleyin</p>
                        <div class="flex items-center text-sm font-semibold text-gray-600 group-hover:gap-2 transition-all">
                            <span>Görüntüle</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>

                <!-- Coupons (NEW) -->
                <a href="{{ route('admin.coupons.index') }}" class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-indigo-600/0 group-hover:from-indigo-500/5 group-hover:to-indigo-600/5 transition-all duration-300"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">Kuponlar</h3>
                        <p class="text-sm text-gray-500 mb-4">Kampanya ve indirimleri yönetin</p>
                        <div class="flex items-center text-sm font-semibold text-indigo-600 group-hover:gap-2 transition-all">
                            <span>Yönet</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>

                <!-- System Health (NEW) -->
                <a href="{{ route('admin.health') }}" class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-rose-500/0 to-rose-600/0 group-hover:from-rose-500/5 group-hover:to-rose-600/5 transition-all duration-300"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-rose-600 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-rose-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 group-hover:text-rose-600 transition-colors">Sistem Sağlığı</h3>
                        <p class="text-sm text-gray-500 mb-4">Sunucu ve veritabanı metrikleri</p>
                        <div class="flex items-center text-sm font-semibold text-rose-600 group-hover:gap-2 transition-all">
                            <span>İncele</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>

                <!-- Activity Logs (NEW) -->
                <a href="{{ route('admin.activity-logs.index') }}" class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-500/0 to-teal-600/0 group-hover:from-teal-500/5 group-hover:to-teal-600/5 transition-all duration-300"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-teal-600 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-teal-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors">Aktivite Kayıtları</h3>
                        <p class="text-sm text-gray-500 mb-4">Tüm sistem hareketlerini inceleyin</p>
                        <div class="flex items-center text-sm font-semibold text-teal-600 group-hover:gap-2 transition-all">
                            <span>Görüntüle</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>

                <!-- Review Moderation (NEW) -->
                <a href="{{ route('admin.reviews.index') }}" class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/0 to-purple-600/0 group-hover:from-purple-500/5 group-hover:to-purple-600/5 transition-all duration-300"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        </div>
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">Yorum Moderasyonu</h3>
                            @if($stats['pending_reviews'] > 0)
                                <span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full animate-pulse">{{ $stats['pending_reviews'] }}</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 mb-4">Gelen yorumları onaylayın veya reddedin</p>
                        <div class="flex items-center text-sm font-semibold text-purple-600 group-hover:gap-2 transition-all">
                            <span>Yönet</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>

                <!-- Popular Businesses (NEW) -->
                <a href="{{ route('admin.popular-businesses.index') }}" class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/0 to-orange-600/0 group-hover:from-orange-500/5 group-hover:to-orange-600/5 transition-all duration-300"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-orange-600 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-orange-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 group-hover:text-orange-600 transition-colors">Popüler İşletmeler</h3>
                        <p class="text-sm text-gray-500 mb-4">En çok tercih edilen mekanları gör</p>
                        <div class="flex items-center text-sm font-semibold text-orange-600 group-hover:gap-2 transition-all">
                            <span>İncele</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>

            </div>
        </div>

        <!-- Log Categories -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 font-primary uppercase tracking-wider">Sistem Günlükleri</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Auth Logs -->
                <a href="{{ route('admin.activities.auth') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-2xl hover:shadow-blue-500/10 transition-all transform hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 transition-all shadow-inner">
                            <svg class="w-7 h-7 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-black text-slate-900 text-lg">Güvenlik</h4>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Giriş & Çıkışlar</p>
                        </div>
                    </div>
                </a>

                <!-- Business Logs -->
                <a href="{{ route('admin.activities.business') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-2xl hover:shadow-amber-500/10 transition-all transform hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center group-hover:bg-amber-600 transition-all shadow-inner">
                            <svg class="w-7 h-7 text-amber-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-black text-slate-900 text-lg">İşletmeler</h4>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Başvuru & Takip</p>
                        </div>
                    </div>
                </a>

                <!-- Reservation Logs -->
                <a href="{{ route('admin.activities.reservations') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-2xl hover:shadow-purple-500/10 transition-all transform hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center group-hover:bg-purple-600 transition-all shadow-inner">
                            <svg class="w-7 h-7 text-purple-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-black text-slate-900 text-lg">Randevular</h4>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Tüm Hareketler</p>
                        </div>
                    </div>
                </a>

                <!-- System Logs -->
                <a href="{{ route('admin.activities.system') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-2xl hover:shadow-slate-500/10 transition-all transform hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center group-hover:bg-slate-900 transition-all shadow-inner">
                            <svg class="w-7 h-7 text-slate-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-black text-slate-900 text-lg">Denetim</h4>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Ayar & Değişim</p>
                        </div>
                    </div>
                </a>

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Category Chart
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryStats->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($categoryStats->pluck('reservations_count')) !!},
                    backgroundColor: ['#6366f1', '#a855f7', '#ec4899', '#f43f5e', '#f59e0b', '#10b981'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                cutout: '70%',
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });

    // Real-time Reservations
    setTimeout(() => {
        if (window.Echo) {
            console.log('Echo is ready, subscribing to admin.dashboard...');
            window.Echo.private('admin.dashboard')
                .listen('ReservationCreated', (e) => {
                    console.log('New Reservation:', e);
                    
                    // Update stats if elements exist
                    // This is a basic example, ideally you'd use Livewire or Vue/Alpine for reactive updates
                    
                    // Show Notification
                    if (typeof showToast === 'function') {
                        showToast('Yeni Rezervasyon!', `${e.customer} (${e.pax} Kişi) - ${e.time}`, 'success');
                    } else {
                        // Fallback
                        alert(`Yeni Rezervasyon!\n${e.customer} - ${e.time}`);
                    }
                    
                    // Optional: Play sound
                    const audio = new Audio('/sounds/notification.mp3'); 
                    audio.play().catch(e => console.log('Audio play failed', e));
                });
        } else {
            console.error('Echo is not defined.');
        }
    }, 1000);
</script>
@endpush
