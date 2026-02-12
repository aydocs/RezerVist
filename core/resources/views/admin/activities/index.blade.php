@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-indigo-50/30 to-slate-100 py-8" x-data="loggerIndex()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-4">
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Panel</a>
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    <span class="text-slate-300">Denetim</span>
                </nav>
                <div>
                    <h1 class="text-5xl font-black text-slate-900 tracking-tight mb-3">
                        {{ $title }}
                    </h1>
                    <p class="text-slate-500 font-medium flex items-center gap-3">
                        <span class="w-8 h-[2px] bg-indigo-500 rounded-full"></span>
                        Platform üzerindeki tüm {{ strtolower($title) }} hareketlerini inceleyin.
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.platform-activity.index') }}" class="group flex items-center gap-3 px-6 py-4 text-slate-700 bg-white hover:bg-slate-50 rounded-[2rem] transition-all shadow-sm border border-slate-200 font-bold text-sm">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Genel Akış
                </a>
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 px-6 py-4 text-white bg-slate-900 hover:bg-slate-800 rounded-[2rem] transition-all shadow-xl shadow-slate-200 font-bold text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Geri Dön
                </a>
            </div>
        </div>

        <!-- Filters & Premium Calendar -->
        <div class="bg-white/80 backdrop-blur-2xl rounded-[3rem] p-8 mb-10 border border-white/50 shadow-2xl shadow-indigo-100/50 relative z-[100]">
            <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-8 items-end">
                <!-- Date Picker Dropdown -->
                <div class="md:col-span-2 space-y-3 relative">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Tarih Aralığı Seçin</label>
                    <div class="flex items-center gap-3 p-2 bg-slate-100/50 rounded-[2rem] border border-slate-200 focus-within:border-indigo-500 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all">
                        <div class="flex-1 flex items-center gap-2 px-4 py-3 bg-white rounded-[1.5rem] shadow-sm cursor-pointer" @click.stop="showCalendar = !showCalendar">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm font-bold text-slate-700" x-text="dateRangeLabel || 'Tarih seçin...'"></span>
                        </div>
                        <input type="hidden" name="date_from" :value="dateFrom">
                        <input type="hidden" name="date_to" :value="dateTo">
                        
                        <!-- Premium Calendar Modal -->
                        <div x-show="showCalendar" 
                             @click.away="showCalendar = false" 
                             class="absolute top-[120%] left-0 w-full md:w-[600px] bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 z-[9999] p-8" 
                             x-transition:enter="transition ease-out duration-200" 
                             x-transition:enter-start="opacity-0 translate-y-4" 
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;">
                            <div class="grid grid-cols-2 gap-8">
                                <!-- Calendar Grid 1 (Custom Implementation) -->
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between px-2">
                                        <button type="button" @click="prevMonth()" class="p-2 hover:bg-slate-50 rounded-full transition-colors text-slate-400 hover:text-indigo-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg></button>
                                        <span class="text-xs font-black uppercase text-slate-900" x-text="monthNames[month] + ' ' + year"></span>
                                        <button type="button" @click="nextMonth()" class="p-2 hover:bg-slate-50 rounded-full transition-colors text-slate-400 hover:text-indigo-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg></button>
                                    </div>
                                    <div class="grid grid-cols-7 text-center gap-1">
                                        <template x-for="day in daysShort">
                                            <div class="text-[10px] font-black text-slate-300 uppercase" x-text="day"></div>
                                        </template>
                                        <template x-for="blank in blankDays"><div></div></template>
                                        <template x-for="date in noOfDays" :key="date">
                                            <div @click="selectDate(date)" 
                                                 class="aspect-square flex items-center justify-center rounded-xl text-xs font-bold transition-all cursor-pointer"
                                                 :class="getDateClasses(date)"
                                                 x-text="date"></div>
                                        </template>
                                    </div>
                                </div>
                                <!-- Quick Selects -->
                                <div class="flex flex-col gap-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-2">Hızlı Seçim</span>
                                    <button type="button" @click="setRange('today')" class="w-full text-left px-5 py-3 rounded-2xl text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600 transition-all transition-all duration-200">Bugün</button>
                                    <button type="button" @click="setRange('yesterday')" class="w-full text-left px-5 py-3 rounded-2xl text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600 transition-all transition-all duration-200">Dün</button>
                                    <button type="button" @click="setRange('last7')" class="w-full text-left px-5 py-3 rounded-2xl text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600 transition-all transition-all duration-200">Son 7 Gün</button>
                                    <button type="button" @click="setRange('last30')" class="w-full text-left px-5 py-3 rounded-2xl text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600 transition-all transition-all duration-200">Son 30 Gün</button>
                                    <button type="button" @click="setRange('thisMonth')" class="w-full text-left px-5 py-3 rounded-2xl text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600 transition-all transition-all duration-200">Bu Ay</button>
                                    <div class="mt-auto flex gap-2">
                                        <button type="button" @click="clearDates" class="flex-1 px-5 py-3 bg-slate-100 text-slate-600 rounded-2xl text-xs font-black uppercase">Temizle</button>
                                        <button type="button" @click="showCalendar = false" class="flex-1 px-5 py-3 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase shadow-lg">Uygula</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 flex gap-4">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 px-8 rounded-[1.8rem] transition-all shadow-xl shadow-indigo-200 transform hover:-translate-y-1 active:translate-y-0 text-sm tracking-widest">
                        GÜNLÜKLERİ FİLTRELE
                    </button>
                    @if(request()->hasAny(['date_from', 'date_to']))
                        <a href="{{ url()->current() }}" class="flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-black py-4 px-8 rounded-[1.8rem] transition-all text-sm">
                            SIFIRLA
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Activity Timeline -->
        <div class="relative z-0">
            <!-- Timeline Line -->
            <div class="absolute left-12 top-0 bottom-0 w-[2px] bg-slate-100 hidden sm:block -z-10"></div>

            <div class="space-y-6">
                @forelse($activities as $activity)
                    <div class="relative flex flex-col sm:flex-row gap-8 items-start group">
                        <!-- Left: Icon & Connector -->
                        <div class="relative z-10 flex-shrink-0">
                            <div class="w-24 h-24 rounded-[2rem] bg-white border border-slate-100 shadow-xl group-hover:shadow-indigo-100 transition-all flex flex-col items-center justify-center gap-1 overflow-hidden">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    @php
                                        $iconSvg = match($category) {
                                            'auth' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>',
                                            'business' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>',
                                            'reservation' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>',
                                            default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>'
                                        };
                                    @endphp
                                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $iconSvg !!}</svg>
                                </div>
                                <span class="text-[9px] font-black uppercase text-indigo-600 tracking-tighter">{{ $activity->created_at->format('H:i') }}</span>
                            </div>
                        </div>

                        <!-- Content Card -->
                        <div class="flex-1 bg-white/70 backdrop-blur-xl border border-white hover:border-indigo-100 rounded-[2.5rem] p-8 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all group-hover:-translate-y-1">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-900 flex items-center justify-center text-white font-black text-xs shadow-lg shadow-slate-200">
                                        {{ substr($activity->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="font-black text-slate-900">{{ $activity->user->name ?? 'Otomatik Sistem' }}</h3>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $activity->user->role ?? 'System' }} • {{ $activity->created_at->format('d M / Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-4 py-2 rounded-2xl bg-slate-50 border border-slate-100 text-[10px] font-black text-slate-600 uppercase tracking-widest whitespace-nowrap">
                                        {{ str_replace(['_', '-'], ' ', $activity->action_type) }}
                                    </span>
                                </div>
                            </div>
                            
                            <p class="text-lg font-bold text-slate-800 leading-normal mb-6">
                                {{ $activity->description }}
                            </p>

                            @if($activity->metadata)
                                <div class="bg-slate-50/50 rounded-3xl p-6 border border-slate-100">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                                        @foreach($activity->metadata as $key => $value)
                                            @if(is_array($value) && isset($value['old'], $value['new']))
                                                <!-- Diff Display -->
                                                <div class="sm:col-span-2 md:col-span-3 space-y-3">
                                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ str_replace('_', ' ', $key) }}</span>
                                                    <div class="flex flex-col md:flex-row items-center gap-4">
                                                        <div class="w-full md:flex-1 p-4 bg-red-50 rounded-2xl border border-red-100/50">
                                                            <span class="text-[8px] font-black text-red-400 uppercase block mb-1">Eski Değer</span>
                                                            <span class="text-xs font-bold text-red-700 line-through truncate block">{{ is_string($value['old']) ? $value['old'] : json_encode($value['old']) }}</span>
                                                        </div>
                                                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-md flex-shrink-0">
                                                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                                                        </div>
                                                        <div class="w-full md:flex-1 p-4 bg-emerald-50 rounded-2xl border border-emerald-100/50">
                                                            <span class="text-[8px] font-black text-emerald-400 uppercase block mb-1">Yeni Değer</span>
                                                            <span class="text-xs font-bold text-emerald-700 truncate block">{{ is_string($value['new']) ? $value['new'] : json_encode($value['new']) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif(is_scalar($value))
                                                <div class="space-y-1">
                                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ str_replace('_', ' ', $key) }}</span>
                                                    <p class="text-sm font-black text-slate-700 truncate">{{ $value }}</p>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="text-center py-40 relative z-0">
                        <div class="w-40 h-40 bg-white rounded-[3rem] flex items-center justify-center mx-auto mb-10 shadow-2xl shadow-indigo-100 border border-slate-50 group hover:rotate-12 transition-transform relative">
                            <svg class="w-16 h-16 text-slate-200 group-hover:text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 mb-3 relative">Hiç Kayıt Bulunamadı</h3>
                        <p class="text-slate-500 font-medium max-w-sm mx-auto relative">Seçtiğiniz tarihlerde veya bu kategoride henüz bir platform hareketi gerçekleşmedi.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-20">
            {{ $activities->links() }}
        </div>

    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('loggerIndex', () => ({
            showCalendar: false,
            dateFrom: '{{ request('date_from') }}',
            dateTo: '{{ request('date_to') }}',
            month: new Date().getMonth(),
            year: new Date().getFullYear(),
            noOfDays: [],
            blankDays: [],
            monthNames: ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"],
            daysShort: ["PT", "SA", "ÇR", "PR", "CU", "CT", "PZ"],
            
            init() {
                this.getDays();
            },

            get dateRangeLabel() {
                if (!this.dateFrom && !this.dateTo) return null;
                if (this.dateFrom && this.dateTo) return `${this.dateFrom} - ${this.dateTo}`;
                return this.dateFrom || this.dateTo;
            },

            prevMonth() {
                if (this.month === 0) { this.month = 11; this.year--; } else { this.month--; }
                this.getDays();
            },

            nextMonth() {
                if (this.month === 11) { this.month = 0; this.year++; } else { this.month++; }
                this.getDays();
            },

            getDays() {
                let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                let dayOfWeek = new Date(this.year, this.month).getDay();
                let adjustedDay = dayOfWeek === 0 ? 6 : dayOfWeek - 1; 
                this.blankDays = Array.from({length: adjustedDay}, (_, i) => i);
                this.noOfDays = Array.from({length: daysInMonth}, (_, i) => i + 1);
            },

            selectDate(date) {
                const formatted = `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                if (!this.dateFrom || (this.dateFrom && this.dateTo)) {
                    this.dateFrom = formatted;
                    this.dateTo = '';
                } else {
                    if (formatted < this.dateFrom) {
                        this.dateTo = this.dateFrom;
                        this.dateFrom = formatted;
                    } else {
                        this.dateTo = formatted;
                    }
                }
            },

            getDateClasses(date) {
                const formatted = `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                if (formatted === this.dateFrom || formatted === this.dateTo) return 'bg-indigo-600 text-white shadow-lg shadow-indigo-100';
                if (formatted > this.dateFrom && formatted < this.dateTo) return 'bg-indigo-50 text-indigo-700';
                return 'text-slate-600 hover:bg-slate-50';
            },

            setRange(range) {
                const now = new Date();
                let from = new Date();
                let to = new Date();

                switch(range) {
                    case 'today': break;
                    case 'yesterday': 
                        from.setDate(now.getDate() - 1);
                        to.setDate(now.getDate() - 1);
                        break;
                    case 'last7': from.setDate(now.getDate() - 7); break;
                    case 'last30': from.setDate(now.getDate() - 30); break;
                    case 'thisMonth': from = new Date(now.getFullYear(), now.getMonth(), 1); break;
                }

                this.dateFrom = from.toISOString().split('T')[0];
                this.dateTo = to.toISOString().split('T')[0];
                this.showCalendar = false;
            },

            clearDates() {
                this.dateFrom = '';
                this.dateTo = '';
                this.showCalendar = false;
            }
        }));
    });
</script>

<style>
    .pagination { @apply flex items-center justify-center gap-2; }
    .page-item { @apply rounded-2xl overflow-hidden; }
    .page-link { @apply flex items-center justify-center w-12 h-12 bg-white text-sm font-black text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all border border-slate-100; }
    .page-item.active .page-link { @apply bg-indigo-600 border-indigo-600 text-white shadow-xl shadow-indigo-100 scale-110; }
    .page-item.disabled .page-link { @apply opacity-30 cursor-not-allowed grayscale; }
</style>
@endsection
