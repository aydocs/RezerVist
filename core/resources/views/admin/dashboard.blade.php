@extends('layouts.app')

@section('content')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.2);
        --premium-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
        
        --clr-primary: 262, 83%, 58%;
        --clr-emerald: 161, 94%, 30%;
        --clr-blue: 217, 91%, 60%;
        --clr-amber: 38, 92%, 50%;
        --clr-rose: 350, 89%, 60%;
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border);
        box-shadow: var(--premium-shadow);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glass-card:hover {
        transform: translateY(-5px);
        border-color: rgba(255, 255, 255, 0.5);
        box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.1);
    }

    .stat-glow {
        position: absolute;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.15;
        z-index: 0;
        pointer-events: none;
    }

    .aura-purple { background: hsl(var(--clr-primary)); }
    .aura-emerald { background: hsl(var(--clr-emerald)); }
    .aura-blue { background: hsl(var(--clr-blue)); }
    .aura-amber { background: hsl(var(--clr-amber)); }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }

    .pulse-indicator {
        position: relative;
        display: inline-flex;
    }

    .pulse-indicator::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: inherit;
        border-radius: inherit;
        animation: pulseCustom 2s infinite;
        opacity: 0.6;
    }

    @keyframes pulseCustom {
        0% { transform: scale(1); opacity: 0.8; }
        100% { transform: scale(3); opacity: 0; }
    }
</style>

<div class="min-h-screen bg-[#F8FAFC] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-12">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 animate-fadeInUp">
            <div>
                <h1 class="text-5xl font-black text-slate-900 tracking-tight mb-2">
                    Yönetim <span class="text-purple-600">Paneli</span>
                </h1>
                <p class="text-slate-500 font-medium text-lg">Platformunuzun genel durumu ve canlı analitiği</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="h-10 w-1 bg-purple-600 rounded-full"></div>
                <div class="text-right">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Platform Sağlığı</p>
                    <div class="flex items-center gap-2">
                        <span class="pulse-indicator w-2.5 h-2.5 bg-emerald-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                        <span class="font-bold text-slate-700">Tüm Sistemler Hazır</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            
            <!-- Total Revenue -->
            <div onclick="window.location.href='{{ route('admin.reports.index') }}'" class="glass-card rounded-[2.5rem] p-8 cursor-pointer group overflow-hidden animate-fadeInUp delay-1">
                <div class="stat-glow aura-emerald -top-10 -right-10"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center shadow-[0_15px_30px_rgba(16,185,129,0.3)] group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        @if($growth['revenue'] != 0)
                            <div class="flex flex-col items-end">
                                <span class="px-3 py-1 rounded-full text-xs font-black {{ $growth['revenue'] >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $growth['revenue'] >= 0 ? '+' : '' }}{{ $growth['revenue'] }}%
                                </span>
                            </div>
                        @endif
                    </div>
                    <p class="text-sm font-black text-slate-400 uppercase tracking-widest mb-1">Toplam Gelir</p>
                    <h3 class="text-4xl font-black text-slate-900 mb-2">₺{{ number_format($stats['total_revenue'], 2) }}</h3>
                    <p class="text-xs font-bold text-slate-400">Son 7 günde kümülatif kazanç</p>
                </div>
            </div>

            <!-- Total Reservations -->
            <div onclick="window.location.href='{{ route('admin.activities.reservations') }}'" class="glass-card rounded-[2.5rem] p-8 cursor-pointer group overflow-hidden animate-fadeInUp delay-2">
                <div class="stat-glow aura-blue -top-10 -right-10"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-14 h-14 bg-blue-500 rounded-2xl flex items-center justify-center shadow-[0_15px_30px_rgba(59,130,246,0.3)] group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        @if($growth['reservations'] != 0)
                            <span class="px-3 py-1 rounded-full text-xs font-black {{ $growth['reservations'] >= 0 ? 'bg-blue-100 text-blue-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $growth['reservations'] >= 0 ? '+' : '' }}{{ $growth['reservations'] }}%
                            </span>
                        @endif
                    </div>
                    <p class="text-sm font-black text-slate-400 uppercase tracking-widest mb-1">Rezervasyonlar</p>
                    <h3 class="text-4xl font-black text-slate-900 mb-2">{{ number_format($stats['total_reservations']) }}</h3>
                    <p class="text-xs font-bold text-slate-400">Platform genelinde işlem sayısı</p>
                </div>
            </div>

            <!-- Total Users -->
            <div onclick="window.location.href='{{ route('admin.users.index') }}'" class="glass-card rounded-[2.5rem] p-8 cursor-pointer group overflow-hidden animate-fadeInUp delay-3">
                <div class="stat-glow aura-purple -top-10 -right-10"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-14 h-14 bg-purple-600 rounded-2xl flex items-center justify-center shadow-[0_15px_30px_rgba(147,51,234,0.3)] group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        @if($growth['users'] != 0)
                            <span class="px-3 py-1 rounded-full text-xs font-black {{ $growth['users'] >= 0 ? 'bg-purple-100 text-purple-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $growth['users'] >= 0 ? '+' : '' }}{{ $growth['users'] }}%
                            </span>
                        @endif
                    </div>
                    <p class="text-sm font-black text-slate-400 uppercase tracking-widest mb-1">Toplam Kullanıcı</p>
                    <h3 class="text-4xl font-black text-slate-900 mb-2">{{ number_format($stats['total_users']) }}</h3>
                    <p class="text-xs font-bold text-slate-400">Kayıtlı topluluk büyüklüğü</p>
                </div>
            </div>

            <!-- Pending Review Mod -->
            <div onclick="window.location.href='{{ route('admin.reviews.index') }}'" class="glass-card rounded-[2.5rem] p-8 cursor-pointer group overflow-hidden animate-fadeInUp delay-4">
                <div class="stat-glow aura-amber -top-10 -right-10"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center shadow-[0_15px_30px_rgba(245,158,11,0.3)] group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        </div>
                        @if($stats['pending_reviews'] > 0)
                            <div class="flex items-center gap-2">
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-600"></span>
                                </span>
                                <span class="text-xs font-black text-rose-600 uppercase">Kritik</span>
                            </div>
                        @endif
                    </div>
                    <p class="text-sm font-black text-slate-400 uppercase tracking-widest mb-1">Bekleyen Yorum</p>
                    <h3 class="text-4xl font-black text-slate-900 mb-2">{{ $stats['pending_reviews'] }}</h3>
                    <p class="text-xs font-bold text-slate-400">Moderasyon bekleyen geri bildirimler</p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Platform Growth Chart -->
            <div class="lg:col-span-2 glass-card rounded-[3rem] p-10 animate-fadeInUp">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900">Platform Büyümesi</h3>
                        <p class="text-slate-500 font-medium">Gelir ve Rezervasyon trend analizi</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-purple-600"></span>
                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Gelir</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Adet</span>
                        </div>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>

            <!-- Distribution Chart -->
            <div class="glass-card rounded-[3rem] p-10 animate-fadeInUp">
                <div class="mb-10">
                    <h3 class="text-2xl font-black text-slate-900">Kategorik Dağılım</h3>
                    <p class="text-slate-500 font-medium">İşletme yoğunluğu</p>
                </div>
                <div class="chart-container" style="height: 250px;">
                    <canvas id="categoryChart"></canvas>
                </div>
                <div class="mt-8 space-y-3">
                    @foreach($categoryStats->take(3) as $cat)
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-2.5 h-2.5 rounded-full" style="background: hsl(262, 83%, {{ 70 - ($loop->index * 15) }}%)"></div>
                                <span class="font-bold text-slate-700">{{ $cat->name }}</span>
                            </div>
                            <span class="text-xs font-black text-slate-400">{{ $cat->reservations_count }} Rez.</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Middle Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Activity Timeline -->
            <div class="glass-card rounded-[3rem] p-10 animate-fadeInUp">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900">Sistem Nabzı</h3>
                        <p class="text-slate-500 font-medium">Platformdaki canlı hareketler</p>
                    </div>
                    <a href="{{ route('admin.platform-activity.index') }}" class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center hover:bg-purple-600 group transition-all">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
                <div class="relative space-y-8 before:absolute before:left-[1.75rem] before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100">
                    @forelse($recentActivities as $activity)
                        <div class="relative flex gap-6 group">
                            <div class="relative z-10 w-14 h-14 rounded-2xl bg-{{ $activity['color'] }}-100 flex items-center justify-center shadow-lg shadow-{{ $activity['color'] }}-500/10 group-hover:scale-110 transition-transform">
                                @if($activity['icon'] == 'calendar')
                                    <svg class="w-6 h-6 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @elseif($activity['icon'] == 'user')
                                    <svg class="w-6 h-6 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                @elseif($activity['icon'] == 'credit-card')
                                    <svg class="w-6 h-6 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                @else
                                    <svg class="w-6 h-6 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-black text-slate-900 group-hover:text-purple-600 transition-colors">{{ $activity['message'] }}</p>
                                    <span class="text-xs font-bold text-slate-400 whitespace-nowrap">{{ $activity['time'] }}</span>
                                </div>
                                <span class="bg-{{ $activity['color'] }}-100 text-{{ $activity['color'] }}-700 text-[10px] font-black px-2 py-0.5 rounded-lg uppercase tracking-wider">
                                    {{ $activity['type'] ?? 'Aktivite' }}
                                    @if(isset($activity['details'])) • {{ $activity['details'] }} @endif
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20">
                            <p class="text-slate-400 font-bold italic">Sessizlik hakim...</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Top Businesses -->
            <div class="glass-card rounded-[3rem] p-10 animate-fadeInUp">
                <div class="mb-10">
                    <h3 class="text-2xl font-black text-slate-900">Yıldız İşletmeler</h3>
                    <p class="text-slate-500 font-medium">Performans liderleri</p>
                </div>
                <div class="space-y-4">
                    @forelse($topBusinesses as $index => $business)
                        <div class="group flex items-center gap-6 p-4 rounded-3xl hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">
                            <div class="relative">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-700 flex items-center justify-center text-white font-black text-xl shadow-xl shadow-slate-900/10 group-hover:scale-110 transition-transform">
                                    {{ $index + 1 }}
                                </div>
                                @if($index == 0)
                                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-amber-400 rounded-full flex items-center justify-center shadow-lg">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h4 class="font-black text-slate-900 text-lg group-hover:text-purple-600 transition-all">{{ $business->name }}</h4>
                                <div class="flex items-center gap-4 mt-1">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                        <span class="text-sm font-black text-slate-700">{{ number_format($business->rating, 1) }}</span>
                                    </div>
                                    <div class="h-1 w-1 bg-slate-300 rounded-full"></div>
                                    <span class="text-sm font-bold text-slate-500">{{ $business->reservations_count }} Rezervasyon</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="w-12 h-1 bg-purple-600 rounded-full mb-1 group-hover:w-full transition-all duration-500"></div>
                                <span class="text-[10px] font-black text-purple-600 uppercase tracking-widest">Lider</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20">
                            <p class="text-slate-400">Veri toplanıyor...</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- System Logs / Quick Access -->
        <div class="space-y-10">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight animate-fadeInUp">Hızlı <span class="text-blue-600">Erişim</span></h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                
                @php
                    $quickActions = [
                        ['route' => 'admin.applications.index', 'icon' => 'document-duplicate', 'label' => 'Başvurular', 'color' => 'amber'],
                        ['route' => 'admin.users.index', 'icon' => 'user-group', 'label' => 'Kullanıcılar', 'color' => 'blue'],
                        ['route' => 'admin.reports.index', 'icon' => 'chart-bar', 'label' => 'Raporlar', 'color' => 'emerald'],
                        ['route' => 'admin.settings.index', 'icon' => 'cog-6-tooth', 'label' => 'Ayarlar', 'color' => 'slate'],
                        ['route' => 'admin.coupons.index', 'icon' => 'ticket', 'label' => 'Kuponlar', 'color' => 'indigo'],
                        ['route' => 'admin.reviews.index', 'icon' => 'chat-bubble-left-right', 'label' => 'Yorumlar', 'color' => 'rose'],
                    ];
                @endphp

                @foreach($quickActions as $action)
                    <a href="{{ route($action['route']) }}" class="glass-card rounded-[2rem] p-6 text-center group animate-fadeInUp delay-{{ $loop->index + 1 }}">
                        <div class="w-16 h-16 mx-auto mb-4 bg-{{ $action['color'] }}-100 rounded-2xl flex items-center justify-center group-hover:bg-{{ $action['color'] }}-600 transition-all shadow-lg shadow-{{ $action['color'] }}-500/10">
                            @if($action['icon'] == 'document-duplicate')
                                <svg class="w-8 h-8 text-{{ $action['color'] }}-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            @elseif($action['icon'] == 'user-group')
                                <svg class="w-8 h-8 text-{{ $action['color'] }}-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            @elseif($action['icon'] == 'chart-bar')
                                <svg class="w-8 h-8 text-{{ $action['color'] }}-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            @elseif($action['icon'] == 'cog-6-tooth')
                                <svg class="w-8 h-8 text-{{ $action['color'] }}-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                            @elseif($action['icon'] == 'ticket')
                                <svg class="w-8 h-8 text-{{ $action['color'] }}-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            @elseif($action['icon'] == 'chat-bubble-left-right')
                                <svg class="w-8 h-8 text-{{ $action['color'] }}-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            @endif
                        </div>
                        <h4 class="font-black text-slate-900 text-sm group-hover:text-{{ $action['color'] }}-600 transition-all">{{ $action['label'] }}</h4>
                    </a>
                @endforeach

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        const createGradient = (ctx, color) => {
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, `${color}44`);
            gradient.addColorStop(1, `${color}00`);
            return gradient;
        };

        // Platform Growth Area Chart
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dates) !!},
                datasets: [
                    {
                        label: 'Gelir',
                        data: {!! json_encode($revenueData) !!},
                        borderColor: '#9333ea',
                        backgroundColor: createGradient(growthCtx, '#9333ea'),
                        fill: true,
                        tension: 0.4,
                        borderWidth: 4,
                        pointRadius: 6,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#9333ea',
                        pointBorderWidth: 3
                    },
                    {
                        label: 'Adet',
                        data: {!! json_encode($reservationData) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: createGradient(growthCtx, '#3b82f6'),
                        fill: true,
                        tension: 0.4,
                        borderWidth: 4,
                        pointRadius: 6,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#3b82f6',
                        pointBorderWidth: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        padding: 15,
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 12
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { font: { weight: 'bold' } } }
                }
            }
        });

        // Category Doughnut Chart
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryStats->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($categoryStats->pluck('reservations_count')) !!},
                    backgroundColor: ['#9333ea', '#a855f7', '#c084fc', '#d8b4fe', '#f3e8ff'],
                    borderWidth: 8,
                    borderColor: '#fff',
                    hoverOffset: 20
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                cutout: '80%',
                responsive: true,
                maintainAspectRatio: false,
                animation: { animateRotate: true, animateScale: true }
            }
        });
    });
</script>
@endpush
