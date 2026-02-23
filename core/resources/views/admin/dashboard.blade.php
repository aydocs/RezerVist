@extends('layouts.app')

@section('content')
<style>
    :root {
        --minimal-bg: #F8FAFC;
        --card-bg: #FFFFFF;
        --card-border: #E2E8F0;
        --text-main: #0F172A;
        --text-muted: #64748B;
        --accent: 262, 83%, 58%;
        --emerald: 161, 94%, 30%;
        --blue: 217, 91%, 60%;
        --amber: 38, 92%, 50%;
        --rose: 350, 89%, 60%;
    }

    .compact-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 1rem;
        padding: 1.25rem;
        transition: all 0.2s ease;
    }

    .compact-card:hover {
        border-color: hsl(var(--accent));
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }

    .dense-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
    }

    .sparkline-box {
        height: 30px;
        width: 60px;
        opacity: 0.6;
    }

    .mini-badge {
        font-size: 0.65rem;
        padding: 0.125rem 0.375rem;
        border-radius: 9999px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .activity-row {
        padding: 0.75rem 0;
        border-bottom: 1px solid #F1F5F9;
    }

    .activity-row:last-child { border-bottom: none; }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .animate-fadeIn { animation: fadeIn 0.4s ease forwards; }

    /* Custom Scrollbar for Dense Feeds */
    .custom-scroll::-webkit-scrollbar { width: 4px; }
    .custom-scroll::-webkit-scrollbar-track { background: transparent; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>

<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8 animate-fadeIn">
        
        <!-- Top Compact Header -->
        <div class="flex items-center justify-between border-b pb-6 border-slate-200">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">
                    Admin <span class="text-purple-600">Console</span>
                    <span class="ml-2 text-xs bg-slate-100 text-slate-500 px-2 py-1 rounded-md uppercase tracking-widest font-bold">Veri Yoğunluklu Mod</span>
                </h1>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sistem Durumu</p>
                    <div class="flex items-center gap-1.5 justify-end">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                        <span class="text-sm font-bold text-slate-700">Aktif</span>
                    </div>
                </div>
                <div class="h-8 w-px bg-slate-200"></div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Bugünkü Gelir</p>
                    <p class="text-sm font-black text-emerald-600">₺{{ number_format($stats['today_revenue'], 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Dense Stats Grid -->
        <div class="dense-grid">
            
            <!-- Revenue -->
            <div class="compact-card group relative">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kümülatif Gelir</p>
                    <div class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center font-bold text-xs">₺</div>
                </div>
                <h3 class="text-xl font-black text-slate-900">₺{{ number_format($stats['total_revenue'], 0) }}</h3>
                <div class="flex items-center gap-2 mt-2">
                    <span class="mini-badge {{ $growth['revenue'] >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                        {{ $growth['revenue'] >= 0 ? '↑' : '↓' }} {{ abs($growth['revenue']) }}%
                    </span>
                    <span class="text-[10px] font-bold text-slate-400">7G Değişim</span>
                </div>
            </div>

            <!-- Businesses -->
            <div class="compact-card group relative">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">İşletmeler</p>
                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4m0 10V4m-4 6h4m-4 4h4m-4 4h4"></path></svg>
                    </div>
                </div>
                <h3 class="text-xl font-black text-slate-900">{{ $stats['active_businesses'] + $stats['inactive_businesses'] }}</h3>
                <div class="flex items-center gap-3 mt-2">
                    <div class="flex items-center gap-1">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                        <span class="text-[10px] font-bold text-slate-500">{{ $stats['active_businesses'] }} Aktif</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                        <span class="text-[10px] font-bold text-slate-500">{{ $stats['inactive_businesses'] }} Pasif</span>
                    </div>
                </div>
            </div>

            <!-- Users -->
            <div class="compact-card group relative">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kullanıcılar</p>
                    <div class="w-8 h-8 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-xl font-black text-slate-900">{{ number_format($stats['total_users']) }}</h3>
                <div class="flex items-center gap-3 mt-2">
                    <div class="flex items-center gap-1">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                        <span class="text-[10px] font-bold text-slate-500">{{ $stats['verified_users'] }} Onaylı</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="mini-badge bg-purple-100 text-purple-700">+{{ $growth['users'] }}%</span>
                    </div>
                </div>
            </div>

            <!-- Reservations -->
            <div class="compact-card group relative">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rezervasyonlar</p>
                    <div class="w-8 h-8 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <h3 class="text-xl font-black text-slate-900">{{ number_format($stats['total_reservations']) }}</h3>
                <div class="flex items-center gap-2 mt-2">
                    <span class="mini-badge bg-amber-100 text-amber-700">{{ $growth['reservations'] >= 0 ? '+' : '' }}{{ $growth['reservations'] }}%</span>
                    <span class="text-[10px] font-bold text-slate-400">Trend</span>
                </div>
            </div>

            <!-- Reviews -->
            <div class="compact-card group relative border-rose-100">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Mod. Bekleyen</p>
                    <div class="w-8 h-8 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                </div>
                <h3 class="text-xl font-black {{ $stats['pending_reviews'] > 0 ? 'text-rose-600' : 'text-slate-900' }}">{{ $stats['pending_reviews'] }}</h3>
                <div class="flex items-center gap-2 mt-2">
                    @if($stats['pending_reviews'] > 0)
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                        <span class="text-[10px] font-black text-rose-600 uppercase">Eylem Gerekli</span>
                    @else
                        <span class="text-[10px] font-bold text-slate-400">Temiz</span>
                    @endif
                </div>
            </div>

            <!-- Applications -->
            <div class="compact-card group relative">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Yen Başvurular</p>
                    <div class="w-8 h-8 bg-slate-50 text-slate-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>
                <h3 class="text-xl font-black text-slate-900">{{ $stats['pending_applications'] }}</h3>
                <div class="mt-2">
                    <span class="mini-badge bg-slate-100 text-slate-600">+{{ $growth['new_apps'] }} Bu Hafta</span>
                </div>
            </div>
        </div>

        <!-- Middle Multi-Column Content -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- Dual Analytics Chart (Compact style) -->
            <div class="xl:col-span-2 compact-card overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-wider">Haftalık Performans</h4>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Gelir</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">İşlem</span>
                        </div>
                    </div>
                </div>
                <div style="height: 220px; width: 100%;">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>

            <!-- Category Mix (Compact style) -->
            <div class="compact-card flex flex-col">
                <h4 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4">Kategori Dağılımı</h4>
                <div class="flex-1 flex flex-col justify-center">
                    <div style="height: 140px; width: 100%;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2">
                    @foreach($categoryStats->take(4) as $cat)
                        <div class="flex items-center justify-between px-2 py-1.5 bg-slate-50 rounded-lg">
                            <span class="text-[10px] font-bold text-slate-600 truncate mr-2">{{ $cat->name }}</span>
                            <span class="text-[10px] font-black text-slate-400">{{ $cat->reservations_count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Bottom Detail Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Dense Activity Heartbeat -->
            <div class="compact-card">
                <div class="flex items-center justify-between mb-4 border-b pb-4 border-slate-100">
                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-wider italic">Sistem Nabzı</h4>
                    <span class="text-[10px] font-black text-slate-400 px-2 py-0.5 bg-slate-100 rounded">Canlı Akış</span>
                </div>
                <div class="max-h-[350px] overflow-y-auto custom-scroll pr-2">
                    @forelse($recentActivities as $activity)
                        <div class="activity-row group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-6 bg-{{ $activity['color'] }}-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-700 leading-tight">{{ $activity['message'] }}</p>
                                        <p class="text-[9px] font-black text-{{ $activity['color'] }}-600 uppercase mt-0.5">{{ $activity['type'] }} • {{ $activity['details'] }}</p>
                                    </div>
                                </div>
                                <span class="text-[9px] font-bold text-slate-400 whitespace-nowrap">{{ $activity['time'] }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center py-10 text-xs text-slate-400">Veri bekleniyor...</p>
                    @endforelse
                </div>
            </div>

            <!-- Leaderboard (Simplified Table) -->
            <div class="compact-card">
                <div class="flex items-center justify-between mb-4 border-b pb-4 border-slate-100">
                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-wider font-serif">Yıldız İşletmeler</h4>
                    <a href="#" class="text-[10px] font-black text-purple-600 hover:underline">TÜMÜ</a>
                </div>
                <div class="space-y-1">
                    @forelse($topBusinesses as $index => $business)
                        <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="text-[10px] font-black text-slate-300 w-4">0{{ $index + 1 }}</span>
                                <div>
                                    <p class="text-xs font-black text-slate-800">{{ $business->name }}</p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <div class="flex">
                                            @for($i=1; $i<=5; $i++)
                                                <svg class="w-2.5 h-2.5 {{ $i <= $business->rating ? 'text-amber-400' : 'text-slate-200' }} fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                            @endfor
                                        </div>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase">{{ $business->reservations_count }} İşlem</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-black {{ $index == 0 ? 'text-emerald-600' : 'text-slate-400' }}">
                                    {{ $index == 0 ? 'PLATINUM' : 'AKTİF' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center py-10 text-xs text-slate-400 font-bold italic">Rekabet bekleniyor...</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Utility Access Grid (Minimal Icon-Only) -->
        <div class="border-t pt-8 border-slate-100">
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 text-center">Yönetim Araçları</h4>
            <div class="flex flex-wrap justify-center gap-4">
                @php
                    $utilities = [
                        ['route' => 'admin.applications.index', 'icon' => 'document-duplicate', 'label' => 'Başvurular', 'color' => 'amber'],
                        ['route' => 'admin.users.index', 'icon' => 'user-group', 'label' => 'Kullanıcılar', 'color' => 'blue'],
                        ['route' => 'admin.reports.index', 'icon' => 'chart-bar', 'label' => 'Raporlar', 'color' => 'emerald'],
                        ['route' => 'admin.settings.index', 'icon' => 'cog-6-tooth', 'label' => 'Ayarlar', 'color' => 'slate'],
                        ['route' => 'admin.coupons.index', 'icon' => 'ticket', 'label' => 'Kuponlar', 'color' => 'indigo'],
                        ['route' => 'admin.reviews.index', 'icon' => 'chat-bubble-left-right', 'label' => 'Yorumlar', 'color' => 'rose'],
                    ];
                @endphp

                @foreach($utilities as $util)
                    <a href="{{ route($util['route']) }}" title="{{ $util['label'] }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center group-hover:bg-slate-900 group-hover:border-slate-900 transition-all group-hover:-translate-y-1">
                            @if($util['icon'] == 'document-duplicate')
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            @elseif($util['icon'] == 'user-group')
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            @elseif($util['icon'] == 'chart-bar')
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            @elseif($util['icon'] == 'cog-6-tooth')
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                            @elseif($util['icon'] == 'ticket')
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            @elseif($util['icon'] == 'chat-bubble-left-right')
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            @endif
                        </div>
                        <span class="text-[9px] font-black text-slate-400 group-hover:text-slate-900 transition-colors">{{ $util['label'] }}</span>
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
        // Minimal Chart Defaults
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.font.weight = 'bold';
        Chart.defaults.font.size = 10;
        Chart.defaults.color = '#94a3b8';

        // Performans Line Chart
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
                        borderWidth: 2,
                        pointRadius: 0,
                        tension: 0.3,
                        fill: false
                    },
                    {
                        label: 'İşlem',
                        data: {!! json_encode($reservationData) !!},
                        borderColor: '#3b82f6',
                        borderWidth: 2,
                        pointRadius: 0,
                        tension: 0.3,
                        fill: false,
                        borderDash: [5, 5]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { display: false } }
                }
            }
        });

        // Category Doughnut (Compact)
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryStats->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($categoryStats->pluck('reservations_count')) !!},
                    backgroundColor: ['#9333ea', '#a855f7', '#c084fc', '#d8b4fe'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                cutout: '85%',
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
@endpush
