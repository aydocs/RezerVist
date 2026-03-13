@extends('layouts.app')

@section('title', 'Sistem Komuta Merkezi - Operasyon Paneli')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12">
    <div class="max-w-[1400px] mx-auto space-y-10 animate-fadeIn">
        
        <!-- Premium Command Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between pb-8 border-b border-slate-200 gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-bolt-lightning text-purple-600"></i> SİSTEM</span>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>KOMUTA MERKEZİ</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Canlı <span class="text-purple-600">Performans</span></h1>
            </div>
            
            <div class="flex items-center gap-8">
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-mono">NETWORK STATUS</p>
                    <div class="flex items-center gap-2 justify-end">
                        <div class="relative flex">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </div>
                        <span class="text-[11px] font-black text-slate-700 uppercase">ONLINE</span>
                    </div>
                </div>
                <div class="h-10 w-px bg-slate-200"></div>
                @if(!auth()->user()->isSupport())
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 font-mono">DAILY VOLUME</p>
                    <p class="text-lg font-black text-slate-900 tracking-tighter">₺{{ number_format($stats['today_revenue'], 0, ',', '.') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- High-Density Intelligence Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            
            <!-- Revenue -->
            @if(!auth()->user()->isSupport())
            <div class="bg-white border border-slate-200 rounded-[2rem] p-6 hover:border-purple-200 hover:shadow-xl hover:shadow-slate-200/40 transition-all group overflow-hidden relative">
                <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                    <i class="fa-solid fa-chart-line text-7xl"></i>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 font-mono">TOTAL REVENUE</p>
                <div class="flex flex-col">
                    <h3 class="text-xl font-black text-slate-900 tracking-tighter group-hover:text-purple-600 transition-colors">₺{{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="px-1.5 py-0.5 {{ $growth['revenue'] >= 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }} border text-[8px] font-black rounded uppercase tracking-widest">
                            {{ $growth['revenue'] >= 0 ? '▲' : '▼' }} {{ abs($growth['revenue']) }}%
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Active Businesses -->
            <div class="bg-white border border-slate-200 rounded-[2rem] p-6 hover:border-purple-200 hover:shadow-xl hover:shadow-slate-200/40 transition-all group overflow-hidden relative">
                <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                    <i class="fa-solid fa-store text-7xl"></i>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 font-mono">BUSINESSES</p>
                <h3 class="text-xl font-black text-slate-900 tracking-tighter">{{ $stats['active_businesses'] + $stats['inactive_businesses'] }}</h3>
                <div class="flex items-center gap-2 mt-2 text-[9px] font-bold text-slate-400 uppercase whitespace-nowrap">
                    <span class="flex items-center gap-1"><div class="w-1 h-1 rounded-full bg-emerald-500"></div> {{ $stats['active_businesses'] }} OK</span>
                    <span class="flex items-center gap-1"><div class="w-1 h-1 rounded-full bg-slate-200"></div> {{ $stats['inactive_businesses'] }} OFF</span>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white border border-slate-200 rounded-[2rem] p-6 hover:border-purple-200 hover:shadow-xl hover:shadow-slate-200/40 transition-all group overflow-hidden relative">
                <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                    <i class="fa-solid fa-users text-7xl"></i>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 font-mono">IDENTITIES</p>
                <h3 class="text-xl font-black text-slate-900 tracking-tighter">{{ number_format($stats['total_users'], 0, ',', '.') }}</h3>
                <div class="flex items-center gap-2 mt-2">
                    <span class="px-1.5 py-0.5 bg-purple-50 text-purple-600 border border-purple-100 text-[8px] font-black rounded uppercase tracking-widest">+{{ $growth['users'] }}% GROWTH</span>
                </div>
            </div>

            <!-- Reservations -->
            <div class="bg-white border border-slate-200 rounded-[2rem] p-6 hover:border-purple-200 hover:shadow-xl hover:shadow-slate-200/40 transition-all group overflow-hidden relative">
                <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                    <i class="fa-solid fa-calendar-check text-7xl"></i>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 font-mono">OPERATIONS</p>
                <h3 class="text-xl font-black text-slate-900 tracking-tighter">{{ number_format($stats['total_reservations'], 0, ',', '.') }}</h3>
                <div class="flex items-center gap-2 mt-2 font-black text-[9px] text-slate-400">
                    <i class="fa-solid fa-arrow-trend-up text-emerald-500"></i>
                    <span class="uppercase tracking-widest">PERFORMING</span>
                </div>
            </div>

            <!-- Mod Queue -->
            <div class="bg-white border {{ $stats['pending_reviews'] > 0 ? 'border-rose-200' : 'border-slate-200' }} rounded-[2rem] p-6 hover:shadow-xl transition-all group overflow-hidden relative">
                <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                    <i class="fa-solid fa-comment-dots text-7xl"></i>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 font-mono">MOD QUEUE</p>
                <h3 class="text-xl font-black {{ $stats['pending_reviews'] > 0 ? 'text-rose-600' : 'text-slate-900' }} tracking-tighter">{{ $stats['pending_reviews'] }}</h3>
                <div class="flex items-center gap-2 mt-2">
                    @if($stats['pending_reviews'] > 0)
                        <span class="flex items-center gap-1.5 text-[8px] font-black text-rose-500 uppercase tracking-[0.2em] animate-pulse">ACTION REQUIRED</span>
                    @else
                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">CLEAN STATE</span>
                    @endif
                </div>
            </div>

            <!-- Application Queue -->
            <div class="bg-white border border-slate-200 rounded-[2rem] p-6 hover:border-purple-200 hover:shadow-xl hover:shadow-slate-200/40 transition-all group overflow-hidden relative">
                <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                    <i class="fa-solid fa-address-card text-7xl"></i>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 font-mono">APPS WAITING</p>
                <h3 class="text-xl font-black text-slate-900 tracking-tighter">{{ $stats['pending_applications'] }}</h3>
                <div class="mt-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                    <span class="text-purple-600 font-black">+{{ $growth['new_apps'] }}</span> WEEKLY
                </div>
            </div>
        </div>

        <!-- Analytical Insights Center -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- Core Analytics -->
            <div class="xl:col-span-2 bg-white border border-slate-200 rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/20">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-[0.25em]">SİSTEMSEL PERFORMANS GRAFİĞİ</h4>
                        <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest">Son 7 günlük veri akışı analizi</p>
                    </div>
                    <div class="flex gap-6">
                        @if(!auth()->user()->isSupport())
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-purple-600"></div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">GELİR KAPASİTESİ</span>
                        </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full border border-slate-300"></div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">İŞLEM ADEDİ</span>
                        </div>
                    </div>
                </div>
                <div style="height: 300px;">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>

            <!-- Distribution & Intelligence -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-slate-900/40 flex flex-col relative overflow-hidden">
                <div class="absolute -right-20 -top-20 opacity-5">
                    <i class="fa-solid fa-chart-pie text-[15rem]"></i>
                </div>
                
                <h4 class="text-[11px] font-black uppercase tracking-[0.25em] mb-8 relative z-10 text-white/60">KATEGORİSEL DAĞILIM</h4>
                
                <div class="flex-1 flex flex-col justify-center relative z-10">
                    <div style="height: 160px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

                <div class="mt-10 space-y-3 relative z-10">
                    @foreach($categoryStats->take(4) as $cat)
                        <div class="flex items-center justify-between p-3 bg-white/5 rounded-2xl border border-white/5 hover:bg-white/10 transition-colors group cursor-default">
                            <div class="flex items-center gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-purple-500 group-hover:scale-150 transition-transform"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-white/70">{{ $cat->name }}</span>
                            </div>
                            <span class="text-xs font-black text-purple-400">{{ $cat->reservations_count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Realtime Events & High Performers -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Events Feed -->
            <div class="bg-white border border-slate-200 rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/20">
                <div class="flex items-center justify-between mb-8 pb-6 border-b border-slate-50">
                    <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-[0.25em]">AKTİVİTE GÜNCESİ</h4>
                    <span class="text-[10px] font-black text-purple-600 px-3 py-1 bg-purple-50 rounded-lg uppercase tracking-widest border border-purple-100 italic">REALTIME FEED</span>
                </div>
                <div class="space-y-6 max-h-[400px] overflow-y-auto custom-scroll pr-4">
                    @forelse($recentActivities as $activity)
                        <div class="flex items-start gap-4 p-4 hover:bg-slate-50 rounded-2xl transition-all group border border-transparent hover:border-slate-100">
                            <div class="w-1.5 h-10 bg-{{ $activity['color'] }}-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity shrink-0 mt-1"></div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[9px] font-black text-{{ $activity['color'] }}-600 uppercase tracking-widest">{{ $activity['type'] }}</span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase">{{ $activity['time'] }}</span>
                                </div>
                                <p class="text-xs font-bold text-slate-700 leading-relaxed">{{ $activity['message'] }}</p>
                                <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-tight">{{ $activity['details'] }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20 text-[10px] font-black text-slate-300 uppercase tracking-widest italic">VERI AKISI BEKLENIYOR...</div>
                    @endforelse
                </div>
            </div>

            <!-- Top Performers -->
            <div class="bg-white border border-slate-200 rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/20">
                <div class="flex items-center justify-between mb-8 pb-6 border-b border-slate-50">
                    <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-[0.25em]">TOP PERFORMERS</h4>
                    <a href="#" class="text-[10px] font-black text-slate-300 hover:text-purple-600 transition-colors uppercase tracking-[0.3em]">ANALİZLERE GİT</a>
                </div>
                <div class="space-y-2">
                    @forelse($topBusinesses as $index => $business)
                        <div class="flex items-center justify-between p-4 rounded-[1.5rem] hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-all flex group cursor-default">
                            <div class="flex items-center gap-5">
                                <span class="text-xs font-black text-slate-300 w-6">#{{ $index + 1 }}</span>
                                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all group-hover:rotate-3">
                                    <i class="fa-solid fa-store text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900 group-hover:text-purple-600 transition-colors">{{ $business->name }}</p>
                                    <div class="flex items-center gap-3 mt-1">
                                        <div class="flex gap-0.5">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="fa-solid fa-star text-[8px] {{ $i <= $business->rating ? 'text-amber-400 shadow-sm' : 'text-slate-100' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $business->reservations_count }} İŞLEM</span>
                                    </div>
                                </div>
                            </div>
                            @if($index == 0)
                                <span class="px-2 py-0.5 bg-purple-50 text-purple-600 border border-purple-100 text-[8px] font-black rounded uppercase tracking-widest shadow-sm">PLATINUM</span>
                            @else
                                <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">AKTİF</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-20 text-[10px] font-black text-slate-300 uppercase tracking-widest italic">REKABET BEKLENIYOR...</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- System Navigation Grid -->
        <div class="border-t border-slate-100 pt-10">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mb-10 text-center">CORE MODULES NAVIGATION</p>
            <div class="flex flex-wrap justify-center gap-4">
                @php
                    $utilities = [
                        ['route' => 'admin.applications.index', 'icon' => 'fa-clipboard-list', 'label' => 'BAŞVURULAR'],
                        ['route' => 'admin.users.index', 'icon' => 'fa-users-gear', 'label' => 'KULLANICILAR'],
                        ['route' => 'admin.reports.index', 'icon' => 'fa-chart-pie', 'label' => 'RAPORLAR'],
                        ['route' => 'admin.settings.index', 'icon' => 'fa-gears', 'label' => 'AYARLAR'],
                        ['route' => 'admin.coupons.index', 'icon' => 'fa-ticket-simple', 'label' => 'KUPONLAR'],
                        ['route' => 'admin.reviews.index', 'icon' => 'fa-comments', 'label' => 'YORUMLAR'],
                    ];
                @endphp

                @foreach($utilities as $util)
                    @if(Route::has($util['route']) && (auth()->user()->isDeveloper() || auth()->user()->isAdmin() || (auth()->user()->isSupport() && in_array($util['route'], ['admin.applications.index', 'admin.reviews.index', 'admin.contact-messages.index']))))
                    <a href="{{ route($util['route']) }}" class="flex flex-col items-center gap-4 group">
                        <div class="w-16 h-16 bg-white border border-slate-200 rounded-[1.5rem] flex items-center justify-center group-hover:bg-slate-900 group-hover:border-slate-900 transition-all duration-300 shadow-sm group-hover:shadow-xl group-hover:shadow-slate-200 group-hover:-translate-y-2">
                            <i class="fa-solid {{ $util['icon'] }} text-slate-400 group-hover:text-white text-lg transition-colors"></i>
                        </div>
                        <span class="text-[9px] font-black text-slate-400 group-hover:text-slate-900 uppercase tracking-widest transition-colors">{{ $util['label'] }}</span>
                    </a>
                    @endif
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
        // Console Chart Defaults
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.font.weight = 'bold';
        Chart.defaults.font.size = 9;
        Chart.defaults.color = '#94a3b8';

        // Performance Intelligence Chart
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        const gradient = growthCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(139, 92, 246, 0.1)');
        gradient.addColorStop(1, 'rgba(139, 92, 246, 0)');

        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dates) !!},
                datasets: [
                    {
                        label: 'GELİR',
                        data: {!! auth()->user()->isSupport() ? '[]' : json_encode($revenueData) !!},
                        borderColor: '#8B5CF6',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#8B5CF6',
                        pointBorderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: gradient
                    },
                    {
                        label: 'İŞLEM',
                        data: {!! json_encode($reservationData) !!},
                        borderColor: '#E2E8F0',
                        borderWidth: 2,
                        pointRadius: 0,
                        tension: 0.4,
                        fill: false,
                        borderDash: [5, 5]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 10, weight: 'bold' },
                        bodyFont: { size: 10 },
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false
                    }
                },
                scales: {
                    x: { 
                        grid: { display: false },
                        ticks: { padding: 10 }
                    },
                    y: { 
                        grid: { color: '#f8fafc' },
                        ticks: { display: false }
                    }
                }
            }
        });

        // Mix Intelligence
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryStats->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($categoryStats->pluck('reservations_count')) !!},
                    backgroundColor: ['#8B5CF6', '#7C3AED', '#6D28D9', '#5B21B6', '#4C1D95'],
                    borderWidth: 0,
                    hoverOffset: 20
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                cutout: '80%',
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    });
</script>
@endpush
