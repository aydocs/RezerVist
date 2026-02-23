@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12" x-data="loggerIndex()">
    <div class="max-w-[1700px] mx-auto space-y-8">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b pb-8 border-slate-200">
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition-colors">Yönetim</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Denetim Günlükleri</span>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Sistem Hareketleri</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $title }}</h1>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('admin.platform-activity.index') }}" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 hover:text-purple-600 hover:bg-slate-50 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm flex items-center gap-2 group">
                    <i class="fa-solid fa-stream text-[10px]"></i>
                    Genel Akış
                </a>
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 bg-slate-900 text-white hover:bg-purple-600 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-slate-900/10 flex items-center gap-2 group">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Panele Dön
                </a>
            </div>
        </div>

        <!-- Filters & Professional Calendar -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative z-[100]">
            <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div class="md:col-span-2 space-y-2 relative">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Tarih Aralığı Analizi</label>
                    <div class="flex items-center gap-2 p-1.5 bg-slate-50 rounded-xl border border-slate-200 focus-within:border-purple-500 transition-all">
                        <div class="flex-1 flex items-center gap-3 px-4 py-2 bg-white rounded-lg shadow-sm cursor-pointer border border-slate-100" @click.stop="showCalendar = !showCalendar">
                            <i class="fa-solid fa-calendar-days text-purple-600 text-xs"></i>
                            <span class="text-[11px] font-black text-slate-700 uppercase tracking-tight" x-text="dateRangeLabel || 'Tarih Aralığı Seçin...'"></span>
                        </div>
                        <input type="hidden" name="date_from" :value="dateFrom">
                        <input type="hidden" name="date_to" :value="dateTo">
                        
                        <!-- Calendar Dropdown -->
                        <div x-show="showCalendar" 
                             @click.away="showCalendar = false" 
                             class="absolute top-[110%] left-0 w-full md:w-[500px] bg-white rounded-2xl shadow-2xl border border-slate-200 z-[9999] p-6" 
                             x-transition x-cloak>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between px-1">
                                        <button type="button" @click="prevMonth()" class="w-7 h-7 flex items-center justify-center hover:bg-slate-100 rounded-lg transition-colors text-slate-400"><i class="fa-solid fa-chevron-left text-[10px]"></i></button>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-900" x-text="monthNames[month] + ' ' + year"></span>
                                        <button type="button" @click="nextMonth()" class="w-7 h-7 flex items-center justify-center hover:bg-slate-100 rounded-lg transition-colors text-slate-400"><i class="fa-solid fa-chevron-right text-[10px]"></i></button>
                                    </div>
                                    <div class="grid grid-cols-7 text-center gap-1">
                                        <template x-for="day in daysShort">
                                            <div class="text-[8px] font-black text-slate-300 uppercase" x-text="day"></div>
                                        </template>
                                        <template x-for="blank in blankDays"><div></div></template>
                                        <template x-for="date in noOfDays" :key="date">
                                            <div @click="selectDate(date)" 
                                                 class="aspect-square flex items-center justify-center rounded-lg text-[10px] font-black transition-all cursor-pointer"
                                                 :class="getDateClasses(date)"
                                                 x-text="date"></div>
                                        </template>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1.5 border-l border-slate-100 pl-6">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2">Hızlı Filtre</span>
                                    <button type="button" @click="setRange('today')" class="w-full text-left px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-purple-50 hover:text-purple-600 transition-all">Bugün</button>
                                    <button type="button" @click="setRange('yesterday')" class="w-full text-left px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-purple-50 hover:text-purple-600 transition-all">Dün</button>
                                    <button type="button" @click="setRange('last7')" class="w-full text-left px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-purple-50 hover:text-purple-600 transition-all">Son 7 Gün</button>
                                    <button type="button" @click="setRange('last30')" class="w-full text-left px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-purple-50 hover:text-purple-600 transition-all">Son 30 Gün</button>
                                    <div class="mt-auto pt-4 flex gap-2">
                                        <button type="button" @click="clearDates" class="flex-1 px-3 py-2 bg-slate-100 text-slate-600 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">Temizle</button>
                                        <button type="button" @click="showCalendar = false" class="flex-1 px-3 py-2 bg-slate-900 text-white rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-purple-600 transition-colors">Uygula</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 flex gap-3">
                    <button type="submit" class="flex-1 bg-slate-900 hover:bg-purple-600 text-white font-black py-4 px-8 rounded-xl transition-all shadow-lg shadow-slate-900/10 text-[10px] uppercase tracking-widest active:scale-95">
                        <i class="fa-solid fa-filter mr-2"></i>
                        Verileri Filtrele
                    </button>
                    @if(request()->hasAny(['date_from', 'date_to']))
                        <a href="{{ url()->current() }}" class="flex items-center justify-center bg-white border border-slate-200 hover:bg-slate-50 text-slate-500 font-black py-4 px-8 rounded-xl transition-all text-[10px] uppercase tracking-widest">
                            <i class="fa-solid fa-rotate-left mr-2"></i>
                            Sıfırla
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Activity Timeline -->
        <div class="relative">
            <!-- Central Line -->
            <div class="absolute left-10 md:left-14 top-0 bottom-0 w-px bg-slate-200 hidden sm:block"></div>

            <div class="space-y-6">
                @forelse($activities as $activity)
                    <div class="relative flex flex-col sm:flex-row gap-6 md:gap-10 items-start group">
                        <!-- Left: Icon & Time -->
                        <div class="relative z-10 flex-shrink-0">
                            <div class="w-20 md:w-28 h-20 md:h-28 rounded-2xl bg-white border border-slate-200 shadow-sm group-hover:shadow-purple-100 transition-all flex flex-col items-center justify-center gap-2 overflow-hidden group-hover:border-purple-200">
                                <div class="w-10 md:w-12 h-10 md:h-12 rounded-xl bg-slate-50 group-hover:bg-purple-50 flex items-center justify-center transition-colors">
                                    @php
                                        $icon = match($category) {
                                            'auth' => 'fa-shield-halved',
                                            'business' => 'fa-building',
                                            'reservation' => 'fa-calendar-check',
                                            default => 'fa-bolt'
                                        };
                                    @endphp
                                    <i class="fa-solid {{ $icon }} text-slate-400 group-hover:text-purple-600 transition-colors"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase text-slate-400 group-hover:text-purple-600 tracking-widest">{{ $activity->created_at->format('H:i') }}</span>
                            </div>
                        </div>

                        <!-- Content Card -->
                        <div class="flex-1 bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm hover:shadow-xl hover:shadow-slate-100 transition-all group-hover:border-purple-100">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white font-black text-xs">
                                        {{ substr($activity->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ $activity->user->name ?? 'Otomatik Sistem' }}</h3>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $activity->user->role ?? 'Sistem' }} • {{ $activity->created_at->format('d M, Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-100 text-[9px] font-black text-slate-600 uppercase tracking-widest">
                                        {{ str_replace(['_', '-'], ' ', $activity->action_type) }}
                                    </span>
                                </div>
                            </div>
                            
                            <p class="text-sm font-bold text-slate-700 leading-relaxed mb-6">
                                {{ $activity->description }}
                            </p>

                            @if($activity->metadata)
                                <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($activity->metadata as $key => $value)
                                            @if(is_array($value) && isset($value['old'], $value['new']))
                                                <div class="md:col-span-2 space-y-2">
                                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">{{ str_replace('_', ' ', $key) }} MODİFİKASYONU</span>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 relative">
                                                        <div class="p-3 bg-rose-50/50 rounded-lg border border-rose-100">
                                                            <span class="text-[7px] font-black text-rose-400 uppercase block mb-1">Eski Veri</span>
                                                            <span class="text-[10px] font-bold text-rose-700 line-through opacity-60 truncate block">{{ is_string($value['old']) ? $value['old'] : json_encode($value['old']) }}</span>
                                                        </div>
                                                        <div class="p-3 bg-emerald-50/50 rounded-lg border border-emerald-100">
                                                            <span class="text-[7px] font-black text-emerald-400 uppercase block mb-1">Yeni Veri</span>
                                                            <span class="text-[10px] font-bold text-emerald-800 truncate block">{{ is_string($value['new']) ? $value['new'] : json_encode($value['new']) }}</span>
                                                        </div>
                                                        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-6 h-6 bg-white border border-slate-200 rounded-full flex items-center justify-center text-[10px] text-slate-400 hidden md:flex">
                                                            <i class="fa-solid fa-angle-right"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif(is_scalar($value))
                                                <div class="flex flex-col">
                                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.15em]">{{ str_replace('_', ' ', $key) }}</span>
                                                    <span class="text-[11px] font-bold text-slate-600 truncate">{{ $value }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-32 opacity-30">
                        <i class="fa-solid fa-box-open text-4xl mb-4 text-slate-300"></i>
                        <h3 class="text-xl font-black text-slate-900 mb-2 uppercase tracking-tighter">Kayıt Bulunamadı</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Seçili kriterlerde etkinlik kaydı mevcut değil.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-12">
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
            
            init() { this.getDays(); },
            get dateRangeLabel() {
                if (!this.dateFrom && !this.dateTo) return null;
                if (this.dateFrom && this.dateTo) return `${this.dateFrom} - ${this.dateTo}`;
                return this.dateFrom || this.dateTo;
            },
            prevMonth() { if (this.month === 0) { this.month = 11; this.year--; } else { this.month--; } this.getDays(); },
            nextMonth() { if (this.month === 11) { this.month = 0; this.year++; } else { this.month++; } this.getDays(); },
            getDays() {
                let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                let dayOfWeek = new Date(this.year, this.month).getDay();
                let adjustedDay = dayOfWeek === 0 ? 6 : dayOfWeek - 1; 
                this.blankDays = Array.from({length: adjustedDay}, (_, i) => i);
                this.noOfDays = Array.from({length: daysInMonth}, (_, i) => i + 1);
            },
            selectDate(date) {
                const formatted = `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                if (!this.dateFrom || (this.dateFrom && this.dateTo)) { this.dateFrom = formatted; this.dateTo = ''; } 
                else { if (formatted < this.dateFrom) { this.dateTo = this.dateFrom; this.dateFrom = formatted; } else { this.dateTo = formatted; } }
            },
            getDateClasses(date) {
                const formatted = `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                if (formatted === this.dateFrom || formatted === this.dateTo) return 'bg-purple-600 text-white shadow-lg shadow-purple-600/20';
                if (formatted > this.dateFrom && formatted < this.dateTo) return 'bg-purple-50 text-purple-700';
                return 'text-slate-600 hover:bg-slate-50';
            },
            setRange(range) {
                const now = new Date(); let from = new Date(); let to = new Date();
                switch(range) {
                    case 'today': break;
                    case 'yesterday': from.setDate(now.getDate() - 1); to.setDate(now.getDate() - 1); break;
                    case 'last7': from.setDate(now.getDate() - 7); break;
                    case 'last30': from.setDate(now.getDate() - 30); break;
                }
                this.dateFrom = from.toISOString().split('T')[0];
                this.dateTo = to.toISOString().split('T')[0];
                this.showCalendar = false;
            },
            clearDates() { this.dateFrom = ''; this.dateTo = ''; this.showCalendar = false; }
        }));
    });
</script>

<style>
    .pagination { @apply flex items-center justify-center gap-2; }
    .page-item { @apply inline-block; }
    .page-item .page-link { 
        @apply w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-400 text-xs font-black transition-all hover:bg-slate-50 hover:text-slate-900;
    }
    .page-item.active .page-link {
        @apply bg-purple-600 border-purple-600 text-white shadow-lg shadow-purple-600/20;
    }
    .page-item.disabled .page-link { @apply opacity-50 cursor-not-allowed bg-slate-50; }
</style>
@endsection
