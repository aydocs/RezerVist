@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12" x-data="reportsIndex()" x-init="init()">
    <div class="max-w-[1700px] mx-auto space-y-8">
        
        <!-- Header & Top Nav -->
        <div class="flex flex-col md:flex-row md:items-center justify-between border-b pb-8 border-slate-200">
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition-colors">Yönetim</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Analitik</span>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Sistem Raporları</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Performans <span class="text-purple-600">Analitiği</span></h1>
            </div>

            <div class="flex flex-wrap items-center gap-3 mt-4 md:mt-0">
                <a href="{{ route('admin.reports.export', ['start_date' => $startDate, 'end_date' => $endDate, 'format' => 'pdf']) }}" class="px-6 py-2.5 bg-rose-600 text-white text-[10px] font-black rounded-xl hover:bg-rose-700 transition-all uppercase tracking-widest flex items-center gap-2 shadow-lg shadow-rose-600/10">
                    <i class="fa-solid fa-file-pdf"></i>
                    PDF Raporu
                </a>
                <a href="{{ route('admin.reports.export', ['start_date' => $startDate, 'end_date' => $endDate, 'format' => 'csv']) }}" class="px-6 py-2.5 bg-emerald-600 text-white text-[10px] font-black rounded-xl hover:bg-emerald-700 transition-all uppercase tracking-widest flex items-center gap-2 shadow-lg shadow-emerald-600/10">
                    <i class="fa-solid fa-file-csv"></i>
                    CSV Verisi
                </a>
            </div>
        </div>

        <!-- Filters & Premium Calendar -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative z-[100]">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div class="md:col-span-3 space-y-2 relative">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Zaman Dilimi Analizi</label>
                    <div class="flex items-center gap-2 p-1.5 bg-slate-50 rounded-xl border border-slate-200 focus-within:border-purple-500 transition-all">
                        <div class="flex-1 flex items-center gap-3 px-4 py-2.5 bg-white rounded-lg shadow-sm cursor-pointer border border-slate-100" @click.stop="showCalendar = !showCalendar">
                            <i class="fa-solid fa-calendar-days text-purple-600 text-xs"></i>
                            <span class="text-[10px] font-black text-slate-700 uppercase tracking-tight" x-text="dateRangeLabel || 'Tarih Aralığı Seçin...'"></span>
                        </div>
                        <input type="hidden" name="start_date" :value="dateFrom">
                        <input type="hidden" name="end_date" :value="dateTo">
                        
                        <!-- Premium Calendar Modal -->
                        <div x-show="showCalendar" 
                             @click.away="showCalendar = false" 
                             class="absolute top-[110%] left-0 w-full md:w-[600px] bg-white rounded-2xl shadow-2xl border border-slate-200 z-[9999] p-6" 
                             x-transition:enter="transition ease-out duration-200" 
                             x-transition:enter-start="opacity-0 translate-y-4" 
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;">
                            <div class="grid grid-cols-2 gap-8">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between px-2">
                                        <button type="button" @click="prevMonth()" class="p-2 hover:bg-slate-50 rounded-lg transition-colors text-slate-400 hover:text-purple-600"><i class="fa-solid fa-chevron-left text-xs"></i></button>
                                        <span class="text-[10px] font-black uppercase text-slate-900 tracking-widest" x-text="monthNames[month] + ' ' + year"></span>
                                        <button type="button" @click="nextMonth()" class="p-2 hover:bg-slate-50 rounded-lg transition-colors text-slate-400 hover:text-purple-600"><i class="fa-solid fa-chevron-right text-xs"></i></button>
                                    </div>
                                    <div class="grid grid-cols-7 text-center gap-1">
                                        <template x-for="day in daysShort">
                                            <div class="text-[8px] font-black text-slate-300 uppercase tracking-widest" x-text="day"></div>
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
                                <div class="flex flex-col gap-1.5 border-l border-slate-100 pl-8">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2 pb-2 border-b border-slate-50">Hızlı Filtre</span>
                                    <button type="button" @click="setRange('today')" class="w-full text-left px-4 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-purple-50 hover:text-purple-600 transition-all">Bugün</button>
                                    <button type="button" @click="setRange('yesterday')" class="w-full text-left px-4 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-purple-50 hover:text-purple-600 transition-all">Dün</button>
                                    <button type="button" @click="setRange('last7')" class="w-full text-left px-4 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-purple-50 hover:text-purple-600 transition-all">Son 7 Gün</button>
                                    <button type="button" @click="setRange('last30')" class="w-full text-left px-4 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-purple-50 hover:text-purple-600 transition-all">Son 30 Gün</button>
                                    <button type="button" @click="setRange('thisMonth')" class="w-full text-left px-4 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-purple-50 hover:text-purple-600 transition-all">Bu Ay</button>
                                    <div class="mt-auto flex gap-2 pt-6">
                                        <button type="button" @click="clearDates" class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-500 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">Temizle</button>
                                        <button type="button" @click="showCalendar = false" class="flex-1 px-4 py-2.5 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-purple-600 transition-colors shadow-lg">Uygula</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex">
                    <button type="submit" class="w-full bg-slate-900 hover:bg-purple-600 text-white font-black py-4 px-10 rounded-xl transition-all shadow-lg shadow-slate-900/10 active:scale-95 text-[10px] tracking-widest uppercase">
                        <i class="fa-solid fa-arrows-rotate mr-2"></i>
                        Verileri Getir
                    </button>
                </div>
            </form>
        </div>

        <!-- Sleek Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div @click="tab = 'reservations'" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm cursor-pointer hover:border-purple-200 transition-all group overflow-hidden relative">
                <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                    <i class="fa-solid fa-chart-line text-7xl"></i>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">İşlem Hacmi</p>
                <div class="flex items-end justify-between">
                    <p class="text-xl font-black text-slate-900 tracking-tighter group-hover:text-purple-600 transition-colors">₺{{ number_format($totalVolume, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <div @click="tab = 'financial'" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm cursor-pointer hover:border-emerald-200 transition-all group relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity text-emerald-600">
                    <i class="fa-solid fa-sack-dollar text-7xl"></i>
                </div>
                <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-2">Net Komisyon</p>
                <div class="flex items-end justify-between">
                    <p class="text-xl font-black text-emerald-600 tracking-tighter">₺{{ number_format($netIncome, 0, ',', '.') }}</p>
                </div>
                <div class="mt-2 h-1 w-12 bg-emerald-500 rounded-full"></div>
            </div>

            <div @click="tab = 'reservations'" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm cursor-pointer hover:border-purple-200 transition-all group relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                    <i class="fa-solid fa-calendar-check text-7xl"></i>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Randevular</p>
                <div class="flex items-end justify-between">
                    <p class="text-xl font-black text-slate-900 tracking-tighter group-hover:text-purple-600 transition-colors">{{ number_format($totalReservations) }}</p>
                </div>
            </div>

            <div @click="tab = 'financial'" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm cursor-pointer hover:border-blue-200 transition-all group relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity text-blue-600">
                    <i class="fa-solid fa-wallet text-7xl"></i>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Cüzdan Ödeme</p>
                <div class="flex items-end justify-between">
                    <p class="text-xl font-black text-slate-900 tracking-tighter group-hover:text-blue-600 transition-colors">₺{{ number_format($totalWalletPayments, 0, ',', '.') }}</p>
                </div>
            </div>

            <div @click="tab = 'financial'" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm cursor-pointer hover:border-amber-200 transition-all group relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity text-amber-600">
                    <i class="fa-solid fa-circle-plus text-7xl"></i>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Bakiye Yükleme</p>
                <div class="flex items-end justify-between">
                    <p class="text-xl font-black text-slate-900 tracking-tighter group-hover:text-amber-600 transition-colors">₺{{ number_format($totalTopups, 0, ',', '.') }}</p>
                </div>
            </div>

            <div @click="tab = 'financial'" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm cursor-pointer hover:border-rose-200 transition-all group relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity text-rose-600">
                    <i class="fa-solid fa-undo text-7xl"></i>
                </div>
                <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mb-2">Toplam İade</p>
                <div class="flex items-end justify-between">
                    <p class="text-xl font-black text-rose-600 tracking-tighter">₺{{ number_format($totalRefunds, 0, ',', '.') }}</p>
                </div>
                <div class="mt-2 h-1 w-12 bg-rose-500 rounded-full"></div>
            </div>
        </div>

        <!-- Density Tab Switcher -->
        <div class="flex items-center gap-2 p-1 bg-white border border-slate-200 rounded-xl w-fit shadow-sm">
            <button @click="tab = 'reservations'" 
                    :class="tab === 'reservations' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                    class="px-6 py-2 rounded-lg font-black text-[9px] uppercase tracking-[0.15em] transition-all outline-none">
                RANDEVU AKIŞI
            </button>
            <button @click="tab = 'financial'" 
                    :class="tab === 'financial' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                    class="px-6 py-2 rounded-lg font-black text-[9px] uppercase tracking-[0.15em] transition-all outline-none">
                FİNANSAL ANALİZ
            </button>
        </div>

        <!-- Reservations Tab Content -->
        <div x-show="tab === 'reservations'" x-transition x-cloak class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-in fade-in duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Tarih / Saat</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">İşletme Profili</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Müşteri Verisi</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center whitespace-nowrap">Kapasite</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Brüt Tutar</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">İşlem Durumu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($reservations as $reservation)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-4">
                                <div class="text-[10px] font-black text-slate-900">{{ $reservation->created_at->format('d.m.Y') }}</div>
                                <div class="text-[8px] font-bold text-slate-400 uppercase tracking-tight">{{ $reservation->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-900 group-hover:text-purple-600 transition-colors">{{ $reservation->business->name }}</span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase">{{ $reservation->business->businessCategory->name ?? 'Genel' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-900">{{ $reservation->user->name }}</span>
                                    <span class="text-[8px] font-bold text-purple-600/60 lowercase tracking-tight">{{ $reservation->user->email }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-center">
                                <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-[8px] font-black text-slate-600 uppercase tracking-tighter">
                                    {{ $reservation->guest_count }} PAX
                                </span>
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-[10px] font-black text-slate-900 tracking-tighter">₺{{ number_format($reservation->total_amount ?? 0, 2, ',', '.') }}</span>
                            </td>
                            <td class="px-8 py-4">
                                @php
                                    $statusBadge = match($reservation->status) {
                                        'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'confirmed' => 'bg-purple-50 text-purple-600 border-purple-100',
                                        'cancelled' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        default => 'bg-slate-50 text-slate-600 border-slate-100'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-md text-[8px] font-black uppercase tracking-[0.15em] border {{ $statusBadge }}">
                                    {{ $reservation->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center opacity-20">
                                <i class="fa-solid fa-calendar-day text-5xl mb-4"></i>
                                <p class="text-[9px] font-black uppercase tracking-[0.2em]">Veri seti bulunamadı</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($reservations->hasPages())
                <div class="px-8 py-4 bg-slate-50/50 border-t border-slate-100">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>

        <!-- Financial Tab Content -->
        <div x-show="tab === 'financial'" x-transition x-cloak class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-in fade-in duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Tarih</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">İşlem Tipi</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Aktör</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Referans ID</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Net Tutar</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Validasyon</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($walletTransactions as $tx)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-4">
                                <div class="text-[10px] font-black text-slate-900">{{ $tx->created_at->format('d.m.Y') }}</div>
                                <div class="text-[8px] font-bold text-slate-400 uppercase tracking-tight">{{ $tx->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-8 py-4">
                                @php
                                    $txConfig = match($tx->type) {
                                        'topup' => ['bg' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'label' => 'Bakiye Yükleme'],
                                        'payment' => ['bg' => 'bg-purple-50 text-purple-600 border-purple-100', 'label' => 'Hizmet Ödemesi'],
                                        'refund' => ['bg' => 'bg-rose-50 text-rose-600 border-rose-100', 'label' => 'Sistem İadesi'],
                                        default => ['bg' => 'bg-slate-50 text-slate-600 border-slate-100', 'label' => $tx->type]
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest border {{ $txConfig['bg'] }}">
                                    {{ $txConfig['label'] }}
                                </span>
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-900">{{ $tx->user->name }}</span>
                                    <span class="text-[8px] font-bold text-slate-400 lowercase">{{ $tx->user->email }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-[9px] font-mono font-black text-slate-300">
                                #{{ $tx->reference_id ?? '---' }}
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-[10px] font-black tracking-tighter {{ $tx->type == 'topup' ? 'text-emerald-600' : 'text-slate-900' }}">
                                    {{ $tx->type == 'topup' ? '+' : '-' }}₺{{ number_format($tx->amount, 2, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-1.5 opacity-60">
                                    <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                                    <span class="text-[8px] font-black text-slate-500 uppercase tracking-[0.2em]">
                                        {{ strtoupper($tx->status) }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center opacity-20">
                                <i class="fa-solid fa-coins text-5xl mb-4"></i>
                                <p class="text-[9px] font-black uppercase tracking-[0.2em]">İşlem havuzu boş</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($walletTransactions->hasPages())
                <div class="px-8 py-4 bg-slate-50/50 border-t border-slate-100">
                    {{ $walletTransactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function reportsIndex() {
    return {
        showCalendar: false,
        month: new Date().getMonth(),
        year: new Date().getFullYear(),
        noOfDays: [],
        blankDays: [],
        daysShort: ['Pt', 'Sa', 'Ça', 'Pe', 'Cu', 'Ct', 'Pz'],
        monthNames: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],
        
        dateFrom: '{{ $startDate }}',
        dateTo: '{{ $endDate }}',
        dateRangeLabel: '',
        tab: '{{ request()->has('fin_page') ? 'financial' : 'reservations' }}',

        init() {
            this.getNoOfDays();
            this.updateLabel();
        },

        getNoOfDays() {
            let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
            let dayOfWeek = new Date(this.year, this.month).getDay();
            dayOfWeek = (dayOfWeek === 0) ? 6 : dayOfWeek - 1;

            let blankdaysArray = [];
            for (var i = 1; i <= dayOfWeek; i++) {
                blankdaysArray.push(i);
            }

            let daysArray = [];
            for (var i = 1; i <= daysInMonth; i++) {
                daysArray.push(i);
            }

            this.blankDays = blankdaysArray;
            this.noOfDays = daysArray;
        },

        prevMonth() {
            if (this.month === 0) {
                this.month = 11;
                this.year--;
            } else {
                this.month--;
            }
            this.getNoOfDays();
        },

        nextMonth() {
            if (this.month === 11) {
                this.month = 0;
                this.year++;
            } else {
                this.month++;
            }
            this.getNoOfDays();
        },

        selectDate(date) {
            let selectedDate = new Date(this.year, this.month, date);
            let dateStr = selectedDate.toISOString().split('T')[0];

            if (!this.dateFrom || (this.dateFrom && this.dateTo)) {
                this.dateFrom = dateStr;
                this.dateTo = null;
            } else if (this.dateFrom && !this.dateTo) {
                if (dateStr < this.dateFrom) {
                    this.dateTo = this.dateFrom;
                    this.dateFrom = dateStr;
                } else {
                    this.dateTo = dateStr;
                }
            }
            this.updateLabel();
        },

        updateLabel() {
            if (this.dateFrom && this.dateTo) {
                this.dateRangeLabel = this.formatDate(this.dateFrom) + ' - ' + this.formatDate(this.dateTo);
            } else if (this.dateFrom) {
                this.dateRangeLabel = this.formatDate(this.dateFrom) + ' - ...';
            } else {
                this.dateRangeLabel = 'Zaman Dilimi Seçin...';
            }
        },

        formatDate(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            return d.getDate().toString().padStart(2, '0') + '.' + (d.getMonth() + 1).toString().padStart(2, '0') + '.' + d.getFullYear();
        },

        getDateClasses(date) {
            let selectedDate = new Date(this.year, this.month, date).toISOString().split('T')[0];
            let classes = '';

            if (selectedDate === this.dateFrom || selectedDate === this.dateTo) {
                classes = 'bg-purple-600 text-white shadow-lg shadow-purple-600/20';
            } else if (this.dateFrom && this.dateTo && selectedDate > this.dateFrom && selectedDate < this.dateTo) {
                classes = 'bg-purple-50 text-purple-700';
            } else {
                classes = 'text-slate-600 hover:bg-slate-50';
            }

            return classes;
        },

        setRange(range) {
            const today = new Date();
            let from = new Date();
            let to = new Date();

            switch(range) {
                case 'today': break;
                case 'yesterday': from.setDate(today.getDate() - 1); to.setDate(today.getDate() - 1); break;
                case 'last7': from.setDate(today.getDate() - 7); break;
                case 'last30': from.setDate(today.getDate() - 30); break;
                case 'thisMonth': from = new Date(today.getFullYear(), today.getMonth(), 1); break;
            }

            this.dateFrom = from.toISOString().split('T')[0];
            this.dateTo = to.toISOString().split('T')[0];
            this.updateLabel();
            this.showCalendar = false;
        },

        clearDates() {
            this.dateFrom = null;
            this.dateTo = null;
            this.updateLabel();
        }
    }
}
</script>

<style>
[x-cloak] { display: none !important; }
.pagination { @apply flex items-center justify-center gap-2; }
.page-item .page-link { 
    @apply w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-400 text-xs font-black transition-all hover:bg-slate-50 hover:text-slate-900 shadow-sm shadow-slate-900/5;
}
.page-item.active .page-link {
    @apply bg-purple-600 border-purple-600 text-white shadow-lg shadow-purple-600/20;
}
.page-item.disabled .page-link { @apply opacity-50 cursor-not-allowed bg-slate-50; }
</style>
@endsection
