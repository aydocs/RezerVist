@extends('layouts.app')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen pb-20 font-inter pt-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
            <div>
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-xs font-bold uppercase tracking-widest text-slate-400">
                        <li><a href="{{ route('vendor.dashboard') }}" class="hover:text-indigo-600 transition-colors">Panel</a></li>
                        <li><i class="fas fa-chevron-right text-[8px]"></i></li>
                        <li class="text-indigo-600">Doluluk Analizi</li>
                    </ol>
                </nav>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">Doluluk Analizi</h1>
                <p class="text-slate-500 font-medium mt-1">İşletmenizin hangi saatlerde ve günlerde daha yoğun olduğunu keşfedin.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-4">
                <!-- Location Filter -->
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-gray-200 to-gray-300 rounded-xl blur opacity-0 group-hover:opacity-100 transition duration-200"></div>
                    <div class="relative flex items-center">
                        <select id="location_filter" class="bg-white border-0 ring-1 ring-gray-200 rounded-xl pl-4 pr-10 py-3.5 text-sm font-bold text-gray-700 shadow-sm focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer outline-none min-w-[200px] appearance-none">
                            <option value="">Tüm Şubeler</option>
                            <option value="main" {{ request('location_id') == 'main' ? 'selected' : '' }}>Merkez Şube</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                            @endforeach
                        </select>
                        <svg class="w-4 h-4 text-gray-400 absolute right-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Google Finance Style Period Switcher -->
                <div class="bg-white p-1 rounded-xl shadow-sm border border-gray-100 flex items-center gap-1">
                    @foreach(['1d' => '1G', '7d' => '7G', '1m' => '1A', '1y' => '1Y', 'all' => 'Tümü'] as $key => $label)
                    <button onclick="updatePeriod('{{ $key }}')" 
                            data-period="{{ $key }}"
                            class="period-btn px-5 py-2.5 text-xs font-black rounded-lg transition-all duration-300 {{ $key === '7d' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-50' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
             <div class="bg-white p-8 rounded-[32px] shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-bl-[100px] -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">En Yoğun Saat</p>
                <h3 id="hud_max_hour" class="text-3xl font-black text-slate-900 tracking-tight transition-all duration-300">
                    {{ array_search(max($hourlyData), $hourlyData) }}:00
                </h3>
                <p class="text-xs text-indigo-600 font-bold mt-2">Günlük trende göre en yoğun an</p>
             </div>

             <div class="bg-white p-8 rounded-[32px] shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-bl-[100px] -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">En Yoğun Gün</p>
                @php $days = ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar']; @endphp
                <h3 id="hud_max_day" class="text-3xl font-black text-slate-900 tracking-tight transition-all duration-300">
                    {{ $days[array_search(max($dailyData), $dailyData)] }}
                </h3>
                <p class="text-xs text-emerald-600 font-bold mt-2">Haftalık periyottaki zirve</p>
             </div>

             <div class="bg-white p-8 rounded-[32px] shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-bl-[100px] -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Varlık Verimliliği</p>
                <h3 class="text-3xl font-black text-slate-900 tracking-tight">
                    {{ count($resourceStats) > 0 ? $resourceStats->sortByDesc('count')->first()['name'] : '-' }}
                </h3>
                <p class="text-xs text-orange-600 font-bold mt-2">En çok rezerve edilen alan</p>
             </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- 1. Hourly Density Chart -->
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 p-10 group hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-500">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-black text-slate-900 tracking-tight">Saatlik Rezervasyon Dağılımı</h3>
                            <p class="text-sm text-slate-500 font-medium">Gün içindeki doluluk trendi (24 saat)</p>
                        </div>
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                    </div>
                    <div id="hourlyChart" class="min-h-[400px]"></div>
                </div>

                <!-- 2. Daily Dist Bar Chart -->
                <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 p-10">
                     <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-black text-slate-900 tracking-tight">Haftalık Yoğunluk</h3>
                            <p class="text-sm text-slate-500 font-medium">Haftanın günlerine göre performans</p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-calendar-day text-xl"></i>
                        </div>
                    </div>
                    <div id="dailyChart" class="min-h-[350px]"></div>
                </div>
            </div>

            <!-- 3. Resource Breakdown -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 p-8">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6">Masa / Varlık Popülerliği</h3>
                    <div id="resourceChart" class="min-h-[300px] mb-6"></div>
                    
                    <div class="space-y-3">
                        @foreach($resourceStats->sortByDesc('count')->take(5) as $stat)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:border-indigo-200 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-[10px] font-black text-indigo-600">#{{ $loop->iteration }}</span>
                                <span class="text-sm font-bold text-slate-700">{{ $stat['name'] }}</span>
                            </div>
                            <span class="text-xs font-black text-slate-900">{{ $stat['count'] }} Rez.</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tips & Insights -->
                <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-[40px] p-8 text-white relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-md">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <h4 class="text-lg font-black">Yapay Zeka Önerisi</h4>
                        </div>
                        <p id="ai_insight_text" class="text-indigo-100 text-sm leading-relaxed mb-6 font-medium">
                            Verileriniz {{ $days[array_search(max($dailyData), $dailyData)] }} günleri saat {{ array_search(max($hourlyData), $hourlyData) }}:00 civarında maksimum doluluğa ulaştığını gösteriyor. Bu saatlerde ek personel atayarak hizmet kalitesini artırabilirsiniz.
                        </p>
                        <div class="pt-6 border-t border-white/10">
                            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-widest text-indigo-200">
                                <span>Veri Güvenirliği</span>
                                <span>%98</span>
                            </div>
                            <div class="mt-2 h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-400 rounded-full" style="width: 98%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    let hourlyChart;
    let dailyChart;

    document.addEventListener('DOMContentLoaded', function() {
        initCharts();
    });

    function initCharts() {
        // 1. Hourly Density Chart (Area)
        const hourlyOptions = {
            series: [{
                name: 'Rezervasyon Sayısı',
                data: @json($hourlyData)
            }],
            chart: {
                type: 'area',
                height: 400,
                toolbar: { show: true },
                zoom: { enabled: true, type: 'x' },
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#6366f1'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 4 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.02,
                    stops: [0, 100]
                }
            },
            xaxis: {
                categories: Array.from({length: 24}, (_, i) => `${i}:00`),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#94a3b8', fontWeight: 600 } }
            },
            yaxis: {
                min: 0,
                forceNiceScale: true,
                labels: { style: { colors: '#94a3b8', fontWeight: 600 } }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                padding: { left: 20, right: 20 }
            },
            tooltip: { theme: 'dark' }
        };

        hourlyChart = new ApexCharts(document.querySelector("#hourlyChart"), hourlyOptions);
        hourlyChart.render();

        // 2. Daily Dist Chart (Bar)
        const dailyOptions = {
            series: [{
                name: 'Rezervasyon Sayısı',
                data: @json($dailyData)
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            plotOptions: {
                bar: {
                    borderRadius: 12,
                    columnWidth: '55%',
                    distributed: true,
                    dataLabels: { position: 'top' }
                }
            },
            colors: ['#6366f1', '#8b5cf6', '#a855f7', '#d946ef', '#ec4899', '#f43f5e', '#f97316'],
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: { fontSize: '12px', colors: ["#304758"], fontWeight: 'bold' }
            },
            xaxis: {
                categories: ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#94a3b8', fontWeight: 600 } }
            },
            yaxis: { show: false },
            grid: { show: false },
            tooltip: { theme: 'dark' }
        };

        dailyChart = new ApexCharts(document.querySelector("#dailyChart"), dailyOptions);
        dailyChart.render();

        // 3. Resource Pie Chart
        const resourceOptions = {
            series: @json($resourceStats->pluck('count')),
            labels: @json($resourceStats->pluck('name')),
            chart: {
                type: 'donut',
                height: 300,
                fontFamily: 'Inter, sans-serif'
            },
            stroke: { show: false },
            colors: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'],
            legend: { position: 'bottom', labels: { colors: '#94a3b8', fontWeight: 600 } },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            name: { show: true, fontWeight: 800, color: '#1e293b' },
                            value: { show: true, fontWeight: 700, color: '#64748b' }
                        }
                    }
                }
            },
            dataLabels: { enabled: false }
        };

        const resourceChart = new ApexCharts(document.querySelector("#resourceChart"), resourceOptions);
        resourceChart.render();
    }

    async function updatePeriod(period) {
        // Toggle UI
        document.querySelectorAll('.period-btn').forEach(btn => {
            btn.classList.remove('bg-indigo-600', 'text-white', 'shadow-lg', 'shadow-indigo-200');
            btn.classList.add('text-slate-400', 'hover:text-slate-600', 'hover:bg-slate-50');
            if (btn.dataset.period === period) {
                btn.classList.add('bg-indigo-600', 'text-white', 'shadow-lg', 'shadow-indigo-200');
                btn.classList.remove('text-slate-400', 'hover:text-slate-600', 'hover:bg-slate-50');
            }
        });

        const locationId = document.getElementById('location_filter').value;
        const response = await fetch(`{{ route('vendor.analytics.data') }}?type=occupancy&period=${period}&location_id=${locationId}`);
        const result = await response.json();

        // Update Stats with animation
        animateValue("hud_max_hour", result.max_hour);
        animateValue("hud_max_day", result.max_day);
        
        document.getElementById('ai_insight_text').innerText = `Verileriniz ${result.max_day} günleri saat ${result.max_hour} civarında maksimum doluluğa ulaştığını gösteriyor. Bu saatlerde ek personel atayarak hizmet kalitesini artırabilirsiniz.`;

        // Update Charts
        hourlyChart.updateSeries([{ data: result.hourly }]);
        dailyChart.updateSeries([{ data: result.daily }]);
    }

    function animateValue(id, newValue) {
        const el = document.getElementById(id);
        el.style.opacity = '0';
        el.style.transform = 'translateY(-5px)';
        setTimeout(() => {
            el.innerText = newValue;
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 200);
    }

    // Handle location filter change
    document.getElementById('location_filter').addEventListener('change', () => {
        const activePeriod = document.querySelector('.period-btn.bg-indigo-600').dataset.period;
        updatePeriod(activePeriod);
    });
</script>
@endsection
