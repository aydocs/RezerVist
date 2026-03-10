@extends('layouts.app')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen pb-20 font-inter pt-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Finansal Raporlar</h1>
                <p class="text-slate-500 font-medium mt-1">İşletmenizin gelir analizi ve dönemlik performans.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-4">
                <!-- Location Filter -->
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-gray-200 to-gray-300 rounded-xl blur opacity-0 group-hover:opacity-100 transition duration-200"></div>
                    <div class="relative flex items-center">
                        <select id="location_filter" class="bg-white border-0 ring-1 ring-gray-200 rounded-xl pl-4 pr-10 py-3 text-sm font-bold text-gray-700 shadow-sm focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer outline-none min-w-[170px] appearance-none">
                            <option value="">Tüm Şubeler</option>
                            <option value="main" {{ request('location_id') == 'main' ? 'selected' : '' }}>Merkez Şube</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                            @endforeach
                        </select>
                        <svg class="w-4 h-4 text-gray-400 absolute right-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

            <div class="flex flex-wrap items-center gap-4">
                <!-- Gross/Net Toggle -->
                <div class="bg-slate-100 p-1 rounded-2xl flex items-center gap-1 border border-slate-200 shadow-inner">
                    <button onclick="switchView('net')" id="btn-view-net" class="px-5 py-2.5 text-[10px] font-black rounded-xl transition-all duration-300 bg-white text-indigo-600 shadow-md">
                        NET KAZANÇ
                    </button>
                    <button onclick="switchView('gross')" id="btn-view-gross" class="px-5 py-2.5 text-[10px] font-black rounded-xl transition-all duration-300 text-slate-500 hover:text-slate-700">
                        BRÜT HASILAT
                    </button>
                </div>

                <!-- Google Finance Style Period Switcher -->
                <div class="bg-white p-1 rounded-xl shadow-sm border border-gray-100 flex items-center gap-1">
                    @foreach(['1d' => '1G', '7d' => '7G', '1m' => '1A', '1y' => '1Y', 'all' => 'Tümü'] as $key => $label)
                    <button onclick="updatePeriod('{{ $key }}')" 
                            data-period="{{ $key }}"
                            class="period-btn px-4 py-2 text-xs font-black rounded-lg transition-all duration-300 {{ $key === '7d' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-50' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left: Main Chart & Detailed Stats -->
            <div class="lg:col-span-3 space-y-8">
                 <!-- Stats Grid -->
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Revenue Card -->
                    <div class="bg-white p-6 rounded-[32px] shadow-sm border border-slate-100 relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                        <p id="hud_main_label" class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Cebinize Girecek Net</p>
                        <h2 id="hud_main_value" class="text-2xl font-black text-slate-900 tracking-tight">₺{{ number_format($totalNet, 2, ',', '.') }}</h2>
                        <div class="mt-4 flex items-center gap-2">
                            <span class="text-xs font-bold {{ $growth >= 0 ? 'text-emerald-500 bg-emerald-50' : 'text-rose-500 bg-rose-50' }} px-2 py-0.5 rounded-lg">
                                {{ $growth >= 0 ? '+' : '' }}{{ $growth }}%
                            </span>
                            <span class="text-[10px] text-slate-400 font-medium">geçen döneme göre</span>
                        </div>
                    </div>

                    <!-- AOV Card -->
                    <div class="bg-white p-6 rounded-[32px] shadow-sm border border-slate-100 relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ort. Sepet Tutarı (AOV)</p>
                        <h2 id="hud_aov" class="text-2xl font-black text-slate-900 tracking-tight">₺{{ number_format($aov, 2, ',', '.') }}</h2>
                        <div class="mt-4 flex items-center gap-2">
                            <span class="text-xs font-bold text-blue-500 bg-blue-50 px-2 py-0.5 rounded-lg">Stabil</span>
                            <span class="text-[10px] text-slate-400 font-medium">ortalama değer</span>
                        </div>
                    </div>

                    <!-- Transactions Card -->
                    <div class="bg-white p-6 rounded-[32px] shadow-sm border border-slate-100 relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">İşlem Sayısı</p>
                        <h2 id="hud_transaction_count" class="text-2xl font-black text-slate-900 tracking-tight">{{ count($reservations) }}</h2>
                        <div class="mt-4 flex items-center gap-2">
                            <span class="text-xs font-bold text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded-lg">Canlı</span>
                            <span class="text-[10px] text-slate-400 font-medium">onaylı işlemler</span>
                        </div>
                    </div>
                 </div>
 
                 <!-- Revenue Chart -->
                  <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 p-8 hover:shadow-2xl hover:shadow-indigo-500/5 transition-all duration-500">
                     <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-black text-slate-900 tracking-tighter">Finansal Performans</h3>
                            <p class="text-sm text-slate-500 font-medium">İşlem tarihine göre detaylı gelir akışı</p>
                        </div>
                        <div class="hidden sm:flex items-center gap-4 bg-slate-50 p-1.5 rounded-2xl">
                            <div class="flex items-center gap-2 px-3 py-1 bg-white rounded-xl shadow-sm border border-slate-100">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="text-[10px] font-black text-slate-600">BRÜT</span>
                            </div>
                            <div class="flex items-center gap-2 px-3 py-1">
                                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                <span class="text-[10px] font-black text-slate-400">NET</span>
                            </div>
                        </div>
                     </div>
                     <div id="revenueChart" class="min-h-[400px]"></div>
                     <div id="brushChart" class="h-20 mt-6 px-4 bg-slate-50/30 rounded-3xl border border-slate-100"></div>
                  </div>
            </div>

            <!-- Right: Breakdown Summary -->
            <div class="space-y-6">
                <!-- Wallet Balance Card (NEW) -->
                <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-[32px] shadow-2xl p-6 text-white relative overflow-hidden group">
                    <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-[50px]"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/15 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20">
                                    <i class="fas fa-coins text-sm"></i>
                                </div>
                                <h3 class="text-sm font-black tracking-tight uppercase">Cüzdan Bakiyesi</h3>
                            </div>
                            @if(!$business->iyzico_submerchant_key && (Auth::user()->balance ?? 0) >= 100)
                                <button onclick="document.getElementById('withdrawalModal').classList.remove('hidden')" class="px-4 py-2 bg-white text-emerald-700 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-50 transition-all shadow-lg">
                                    <i class="fa-solid fa-money-bill-transfer mr-1"></i> Para Çek
                                </button>
                            @endif
                        </div>
                        <div class="text-4xl font-black tracking-tighter">₺{{ number_format(Auth::user()->balance ?? 0, 2, ',', '.') }}</div>
                        <p class="text-emerald-100 text-[10px] mt-3 font-medium opacity-80">
                            @if($business->iyzico_submerchant_key)
                                <i class="fas fa-check-circle mr-1"></i> Iyzico Pazaryeri hakedişlerinizi doğrudan banka hesabınıza aktarmaktadır.
                            @else
                                Platform içi kazançlarınız. 100 TL üzeri bakiyenizi çekebilirsiniz.
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Summary Card -->
                <div class="bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-800 rounded-[40px] shadow-2xl p-8 text-white relative overflow-hidden group">
                     <div class="absolute -top-10 -right-10 w-48 h-48 bg-white/10 rounded-full blur-[80px] transition-transform group-hover:scale-125"></div>
                     <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-emerald-400/20 rounded-full blur-[60px]"></div>
                     
                     <div class="flex items-center gap-3 mb-8">
                         <div class="w-10 h-10 bg-white/15 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20">
                             <i class="fas fa-wallet text-sm"></i>
                         </div>
                         <h3 class="text-lg font-black tracking-tight">Hesap Özeti</h3>
                     </div>

                      <div class="space-y-4 relative z-10">
                        <div class="flex justify-between items-center group/item">
                            <span class="text-indigo-100 font-bold text-[10px] uppercase tracking-widest opacity-60">Brüt Hasılat</span>
                            <span class="font-bold text-white text-sm">₺{{ number_format($totalRevenue, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center group/item">
                            <span class="text-indigo-100 font-bold text-[10px] uppercase tracking-widest opacity-60">Platform (%5)</span>
                            <span class="font-bold text-indigo-200 text-sm">- ₺{{ number_format($totalCommission, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center group/item">
                            <span class="text-indigo-100 font-bold text-[10px] uppercase tracking-widest opacity-60">Iyzico Tahmini</span>
                            <span class="font-bold text-indigo-300 text-sm">- ₺{{ number_format($iyzicoEstimate, 2, ',', '.') }}</span>
                        </div>
                        <div class="pt-6 mt-4 border-t border-white/15">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[10px] font-black text-emerald-300 uppercase tracking-[0.2em] leading-none">Net Kazancınız</span>
                                <div class="group relative inline-block">
                                    <i class="fas fa-info-circle text-[10px] text-white/40 cursor-help"></i>
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-48 p-2 bg-slate-900 text-[9px] rounded-lg shadow-xl border border-white/10 leading-relaxed">
                                        Iyzico kesintisi tahmini olup, gerçek dağılım ödeme anında Iyzico panelinde netleşir.
                                    </div>
                                </div>
                            </div>
                            <span id="hud_net_earnings" class="text-4xl font-black text-white drop-shadow-xl inline-block transition-all hover:scale-105">₺{{ number_format($totalNet - $iyzicoEstimate, 2, ',', '.') }}</span>
                        </div>
                     </div>

                     <a href="{{ route('vendor.finance.export', request()->query()) }}" class="block w-full text-center mt-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 rounded-2xl text-xs font-black uppercase tracking-widest transition-all hover:shadow-lg">
                         Tüm İşlemleri İndir (PDF)
                     </a>
                </div>

                 <!-- Iyzico Marketplace Activation (NEW) -->
                <div class="bg-gradient-to-br from-slate-900 to-indigo-900 rounded-[32px] p-8 text-white shadow-2xl relative overflow-hidden group mb-8">
                   <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                   
                   <div class="relative z-10">
                       <div class="flex items-center gap-3 mb-4">
                           <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center backdrop-blur-md">
                               <i class="fas fa-university text-indigo-300"></i>
                           </div>
                           <h3 class="font-black text-lg tracking-tight">Otomatik Ödeme</h3>
                       </div>

                       @if($business->iyzico_submerchant_key)
                           <div class="bg-emerald-500/20 border border-emerald-500/30 rounded-2xl p-4 mb-4">
                               <div class="flex items-center gap-2 text-emerald-300 mb-1">
                                   <i class="fas fa-check-circle text-xs"></i>
                                   <span class="text-[10px] font-black uppercase tracking-widest">AKTİF</span>
                               </div>
                               <p class="text-xs font-medium text-emerald-100/80">Kazançlarınız otomatik olarak banka hesabınıza aktarılıyor.</p>
                           </div>
                           <div class="space-y-2">
                               <p class="text-[10px] font-black text-slate-400 uppercase">IBAN</p>
                               <p class="text-xs font-mono text-indigo-200">{{ $business->iyzico_iban ?? 'TR...' }}</p>
                           </div>
                       @else
                           <p class="text-sm text-slate-300 font-medium mb-6 leading-relaxed">Kazançlarınızın direkt banka hesabınıza yatması için Iyzico Pazaryeri kaydınızı tamamlayın.</p>
                           <button onclick="document.getElementById('iyzicoModal').classList.remove('hidden')" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-900/50">
                               ŞİMDİ AKTİF ET
                           </button>
                       @endif
                   </div>
                </div>

                <!-- Distribution Chart -->
                <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Günlük Talep</h3>
                        <div class="w-8 h-8 bg-slate-50 rounded-xl flex items-center justify-center border border-slate-100">
                            <i class="fas fa-chart-bar text-slate-400 text-[10px]"></i>
                        </div>
                    </div>
                    <div id="distributionChart" class="min-h-[250px]"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    let revenueChart;
    let brushChart;
    let distChart;

    document.addEventListener('DOMContentLoaded', function() {
        initCharts();
    });

    function initCharts() {
        const categories = @json($labels);
        // Map initial series data
        const initialSeries = [
            { name: 'Brüt Gelir', data: @json($seriesGross ?? []) },
            { name: 'Komisyon', data: @json($seriesComm ?? []) },
            { name: 'Net Kazanç', data: @json($seriesNet ?? []) }
        ];

        // 1. Main Premium Multi-Series Chart
        const revenueOptions = {
            series: initialSeries,
            chart: {
                id: 'chart-main',
                type: 'area',
                height: 400,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
                animations: { enabled: true, easing: 'easeinout', speed: 800 },
                zoom: { enabled: true, type: 'x', autoScaleYaxis: true }
            },
            colors: ['#10b981', '#f43f5e', '#6366f1'],
            stroke: { curve: 'smooth', width: [4, 2, 4], dashArray: [0, 5, 0] },
            dataLabels: { enabled: false },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: [0.4, 0.1, 0.5],
                    opacityTo: [0.05, 0.05, 0.1],
                    stops: [0, 90, 100]
                }
            },
            markers: { size: 0, hover: { size: 6 } },
            xaxis: {
                categories: categories,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#94a3b8', fontWeight: 700, fontSize: '10px' } }
            },
            yaxis: {
                labels: {
                    style: { colors: '#94a3b8', fontWeight: 700, fontSize: '10px' },
                    formatter: (value) => '₺' + value.toLocaleString('tr-TR')
                }
            },
            grid: { borderColor: '#f1f5f9', strokeDashArray: 4, padding: { left: 20, right: 20 } },
            tooltip: {
                theme: 'dark',
                shared: true,
                intersect: false,
                y: { formatter: (val) => '₺' + val.toLocaleString('tr-TR') }
            },
            legend: { show: false }
        };

        revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
        revenueChart.render();

        // After render, hide Gross and Commission by default
        revenueChart.hideSeries('Brüt Gelir');
        revenueChart.hideSeries('Komisyon');

        // 2. Brush Navigator
        const brushOptions = {
            series: [initialSeries[0]], // Use gross for navigator
            chart: {
                id: 'chart-brush',
                height: 80,
                type: 'area',
                brush: { target: 'chart-main', enabled: true },
                selection: { 
                    enabled: true,
                    xaxis: {
                        min: Math.max(0, categories.length - 10),
                        max: categories.length
                    }
                },
                toolbar: { show: false }
            },
            colors: ['#cbd5e1'],
            fill: { type: 'solid', opacity: 0.1 },
            stroke: { width: 1 },
            xaxis: { categories: categories, labels: { show: false }, axisBorder: { show: false }, axisTicks: { show: false } },
            yaxis: { show: false },
            grid: { show: false }
        };

        brushChart = new ApexCharts(document.querySelector("#brushChart"), brushOptions);
        brushChart.render();

        // 3. Daily Distribution
        const distributionOptions = {
            series: [{ name: 'Rezervasyon', data: @json($dailyDistribution['data']) }],
            chart: { type: 'bar', height: 250, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
            plotOptions: { bar: { borderRadius: 8, columnWidth: '50%', distributed: true } },
            colors: ['#6366f1', '#8b5cf6', '#a855f7', '#d946ef', '#ec4899', '#f43f5e', '#f97316'],
            dataLabels: { enabled: false },
            legend: { show: false },
            xaxis: { categories: @json($dailyDistribution['labels']), labels: { style: { colors: '#94a3b8', fontWeight: 700, fontSize: '10px' } } },
            grid: { show: false }
        };

        distChart = new ApexCharts(document.querySelector("#distributionChart"), distributionOptions);
        distChart.render();
    }

    async function updatePeriod(period) {
        document.querySelectorAll('.period-btn').forEach(btn => {
            btn.classList.remove('bg-indigo-600', 'text-white', 'shadow-lg', 'shadow-indigo-200');
            btn.classList.add('text-slate-400', 'hover:text-slate-600', 'hover:bg-slate-50');
            if (btn.dataset.period === period) {
                btn.classList.add('bg-indigo-600', 'text-white', 'shadow-lg', 'shadow-indigo-200');
                btn.classList.remove('text-slate-400', 'hover:text-slate-600', 'hover:bg-slate-50');
            }
        });

        const locationId = document.getElementById('location_filter').value;
        const response = await fetch(`{{ route('vendor.analytics.data') }}?type=finance&period=${period}&location_id=${locationId}`);
        const result = await response.json();

        // Update Stats
        animateValue("hud_total_revenue", result.stats.total_revenue);
        animateValue("hud_brut_revenue", result.stats.total_revenue);
        animateValue("hud_commission", '₺' + result.stats.commission);
        animateValue("hud_net_earnings", result.stats.net_earnings);
        animateValue("hud_aov", result.stats.aov);
        animateValue("hud_transaction_count", result.stats.transaction_count);

        // Update Charts
        revenueChart.updateOptions({ xaxis: { categories: result.labels } });
        revenueChart.updateSeries(result.series);

        brushChart.updateOptions({ xaxis: { categories: result.labels } });
        brushChart.updateSeries([result.series[0]]);
    }

    function switchView(mode) {
        const isNet = mode === 'net';
        
        // Update Buttons
        document.getElementById('btn-view-net').className = isNet 
            ? 'px-5 py-2.5 text-[10px] font-black rounded-xl transition-all duration-300 bg-white text-indigo-600 shadow-md'
            : 'px-5 py-2.5 text-[10px] font-black rounded-xl transition-all duration-300 text-slate-500 hover:text-slate-700';
            
        document.getElementById('btn-view-gross').className = !isNet
            ? 'px-5 py-2.5 text-[10px] font-black rounded-xl transition-all duration-300 bg-white text-emerald-600 shadow-md'
            : 'px-5 py-2.5 text-[10px] font-black rounded-xl transition-all duration-300 text-slate-500 hover:text-slate-700';

        // Update Global Labels
        document.getElementById('hud_main_label').innerText = isNet ? 'Cebinize Girecek Net' : 'Toplam Brüt Hasılat';
        animateValue("hud_main_value", isNet ? '₺' + parseFloat(@json($totalNet)).toLocaleString('tr-TR') : '₺' + parseFloat(@json($totalRevenue)).toLocaleString('tr-TR'));

        // Update Charts
        if (isNet) {
            revenueChart.showSeries('Net Kazanç');
            revenueChart.hideSeries('Brüt Gelir');
            revenueChart.hideSeries('Komisyon');
        } else {
            revenueChart.showSeries('Brüt Gelir');
            revenueChart.showSeries('Komisyon');
            revenueChart.hideSeries('Net Kazanç');
        }
    }

    function animateValue(id, newValue) {
        const el = document.getElementById(id);
        if(!el) return;
        el.style.opacity = '0';
        el.style.transform = 'translateY(-5px)';
        setTimeout(() => {
            el.innerText = newValue;
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 150);
    }

    document.getElementById('location_filter').addEventListener('change', () => {
        const activePeriod = document.querySelector('.period-btn.bg-indigo-600').dataset.period;
        updatePeriod(activePeriod);
    });

    // Intercept Ctrl+Scroll for chart zoom
    document.getElementById('revenueChart').addEventListener('wheel', function(e) {
        if (e.ctrlKey) e.preventDefault();
    }, { passive: false });

    // Auto-open Iyzico Modal if requested via URL
    if (new URLSearchParams(window.location.search).get('open_iyzico') === '1') {
        const modal = document.getElementById('iyzicoModal');
        if (modal) modal.classList.remove('hidden');
    }
</script>

<!-- Iyzico Marketplace Modal (NEW) -->
<div id="iyzicoModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('iyzicoModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
            <form action="{{ route('vendor.finance.iyzico.register') }}" method="POST" class="p-8">
                @csrf
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Iyzico Pazaryeri Kaydı</h3>
                    <button type="button" onclick="document.getElementById('iyzicoModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">İŞLETME TİPİ</label>
                        <select name="submerchant_type" required class="w-full bg-slate-50 border-0 ring-1 ring-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all">
                            <option value="PERSONAL">Şahıs (TCKN ile)</option>
                            <option value="PRIVATE_COMPANY">Şahıs Şirketi (Vergi No ile)</option>
                            <option value="LIMITED_OR_JOINT_STOCK_COMPANY">Limited / Anonim Şirketi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">YASAL ŞİRKET ÜNVANI</label>
                        <input type="text" name="legal_company_name" required class="w-full bg-slate-50 border-0 ring-1 ring-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="Örn: ABC Turizm Ltd. Şti.">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">VERGİ DAİRESİ/İL</label>
                            <input type="text" name="tax_office" class="w-full bg-slate-50 border-0 ring-1 ring-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="Beşiktaş V.D.">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">VERGİ/TC NO</label>
                            <input type="text" name="tax_number" class="w-full bg-slate-50 border-0 ring-1 ring-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="10 veya 11 haneli">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">HAKEDİŞ IBAN</label>
                        <input type="text" name="iyzico_iban" id="iyzico_iban" required maxlength="34" class="w-full bg-slate-50 border-0 ring-1 ring-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all font-mono" placeholder="TR00 0000 0000 0000 0000 0000 00">
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                        KAYDI TAMAMLA & AKTİF ET
                    </button>
                    <p class="text-[9px] text-slate-400 text-center mt-4 font-medium leading-relaxed">
                        Kaydı tamamlayarak Iyzico Pazaryeri Alt Üye İşyeri sözleşmesini kabul etmiş sayılırsınız. Bilgiler Iyzico tarafından doğrulanacaktır.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // IBAN Auto-formatter for Iyzico form
    const ibanInput = document.getElementById('iyzico_iban');
    if (ibanInput) {
        ibanInput.addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            if (!value.startsWith('TR')) value = 'TR' + value;
            
            let formatted = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) formatted += ' ';
                formatted += value[i];
            }
            e.target.value = formatted.substring(0, 34);
        });
    }
</script>
<!-- Manual Withdrawal Modal -->
<div id="withdrawalModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('withdrawalModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100">
            <form action="{{ route('vendor.withdrawals.store') }}" method="POST" class="p-8">
                @csrf
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Para Çekme Talebi</h3>
                    <button type="button" onclick="document.getElementById('withdrawalModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 mb-6">
                    <div class="flex gap-3">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                        <p class="text-xs text-blue-700 font-medium">Bakiyeniz talep anında hesabınızdan düşülecek ve yönetici onayının ardından belirttiğiniz IBAN'a aktarılacaktır.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">ÇEKİLECEK TUTAR</label>
                        <div class="relative">
                            <input type="number" name="amount" required min="100" step="0.01" max="{{ Auth::user()->balance ?? 0 }}" class="w-full bg-slate-50 border-0 ring-1 ring-slate-200 rounded-xl pl-4 pr-12 py-3 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all" placeholder="0.00">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">TL</span>
                        </div>
                        <p class="text-[9px] text-slate-400 mt-1 text-right">Kullanılabilir Bakiye: ₺{{ number_format(Auth::user()->balance ?? 0, 2, ',', '.') }}</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">HESAP SAHİBİ (AD SOYAD)</label>
                        <input type="text" name="account_holder" required class="w-full bg-slate-50 border-0 ring-1 ring-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all" value="{{ Auth::user()->name }}">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">IBAN</label>
                        <input type="text" name="iban" id="withdraw_iban" required maxlength="34" class="w-full bg-slate-50 border-0 ring-1 ring-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all font-mono" placeholder="TR00 0000 0000 0000 0000 0000 00" value="{{ $business->iyzico_iban }}">
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">BANKA ADI (Opsiyonel)</label>
                        <input type="text" name="bank_name" class="w-full bg-slate-50 border-0 ring-1 ring-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-emerald-500 transition-all" placeholder="Örn: Ziraat Bankası">
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="w-full bg-emerald-600 text-white py-4 rounded-2xl font-black shadow-lg shadow-emerald-200 hover:bg-emerald-700 hover:-translate-y-0.5 transition-all">
                        TALEBİ GÖNDER
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection
