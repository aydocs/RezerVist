@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-indigo-50/30 to-slate-100 py-8" x-data="reportsIndex()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-4">
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Panel</a>
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    <span class="text-slate-300">İstatistikler</span>
                </nav>
                <div>
                    <h1 class="text-5xl font-black text-slate-900 tracking-tight mb-3">
                        Raporlar
                    </h1>
                    <p class="text-slate-500 font-medium flex items-center gap-3">
                        <span class="w-8 h-[2px] bg-indigo-500 rounded-full"></span>
                        Gelir ve rezervasyon performansınızı detaylıca inceleyin.
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex gap-2 mr-4">
                    <a href="{{ route('admin.reports.export', ['start_date' => $startDate, 'end_date' => $endDate, 'format' => 'pdf']) }}" class="flex items-center gap-2 px-6 py-4 bg-rose-600 hover:bg-rose-700 text-white rounded-[2rem] transition-all shadow-lg font-bold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        PDF İndir
                    </a>
                    <a href="{{ route('admin.reports.export', ['start_date' => $startDate, 'end_date' => $endDate, 'format' => 'csv']) }}" class="flex items-center gap-2 px-6 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-[2rem] transition-all shadow-lg font-bold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        CSV İndir
                    </a>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 px-6 py-4 text-white bg-slate-900 hover:bg-slate-800 rounded-[2rem] transition-all shadow-xl shadow-slate-200 font-bold text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Geri Dön
                </a>
            </div>
        </div>

        <!-- Filters & Premium Calendar -->
        <div class="bg-white/80 backdrop-blur-2xl rounded-[3rem] p-8 mb-10 border border-white/50 shadow-2xl shadow-indigo-100/50 relative z-[100]">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-8 items-end">
                <!-- Date Picker Dropdown -->
                <div class="md:col-span-3 space-y-3 relative">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Tarih Aralığı Seçin</label>
                    <div class="flex items-center gap-3 p-2 bg-slate-100/50 rounded-[2rem] border border-slate-200 focus-within:border-indigo-500 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all">
                        <div class="flex-1 flex items-center gap-2 px-4 py-3 bg-white rounded-[1.5rem] shadow-sm cursor-pointer" @click.stop="showCalendar = !showCalendar">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm font-bold text-slate-700" x-text="dateRangeLabel || 'Tarih seçin...'"></span>
                        </div>
                        <input type="hidden" name="start_date" :value="dateFrom">
                        <input type="hidden" name="end_date" :value="dateTo">
                        
                        <!-- Premium Calendar Modal -->
                        <div x-show="showCalendar" 
                             @click.away="showCalendar = false" 
                             class="absolute top-[120%] left-0 w-full md:w-[600px] bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 z-[9999] p-8" 
                             x-transition:enter="transition ease-out duration-200" 
                             x-transition:enter-start="opacity-0 translate-y-4" 
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;">
                            <div class="grid grid-cols-2 gap-8">
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
                                <div class="flex flex-col gap-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-2">Hızlı Seçim</span>
                                    <button type="button" @click="setRange('today')" class="w-full text-left px-5 py-3 rounded-2xl text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">Bugün</button>
                                    <button type="button" @click="setRange('yesterday')" class="w-full text-left px-5 py-3 rounded-2xl text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">Dün</button>
                                    <button type="button" @click="setRange('last7')" class="w-full text-left px-5 py-3 rounded-2xl text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">Son 7 Gün</button>
                                    <button type="button" @click="setRange('last30')" class="w-full text-left px-5 py-3 rounded-2xl text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">Son 30 Gün</button>
                                    <button type="button" @click="setRange('thisMonth')" class="w-full text-left px-5 py-3 rounded-2xl text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">Bu Ay</button>
                                    <div class="mt-auto flex gap-2">
                                        <button type="button" @click="clearDates" class="flex-1 px-5 py-3 bg-slate-100 text-slate-600 rounded-2xl text-xs font-black uppercase">Temizle</button>
                                        <button type="button" @click="showCalendar = false" class="flex-1 px-5 py-3 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase shadow-lg">Uygula</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 px-8 rounded-[1.8rem] transition-all shadow-xl shadow-indigo-200 transform hover:-translate-y-1 active:translate-y-0 text-sm tracking-widest uppercase">
                        Raporla
                    </button>
                </div>
            </form>
        </div>

        <!-- Dashboard-style Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- Total Volume Card -->
            <div @click="tab = 'reservations'; $nextTick(() => { document.querySelector('#report-tables')?.scrollIntoView({ behavior: 'smooth' }) })"
                 class="relative group bg-white p-5 rounded-[2rem] shadow-xl shadow-indigo-100/30 border border-slate-100 overflow-hidden transition-all duration-500 hover:scale-[1.02] cursor-pointer hover:border-indigo-200">
                <div class="relative flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-slate-500 to-slate-700 rounded-2xl flex items-center justify-center shadow-lg shadow-slate-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">İşlem Hacmi</p>
                        <p class="text-xl font-black text-slate-900 tracking-tight">₺{{ number_format($totalVolume, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Net Commission Card -->
            <div @click="tab = 'financial'; $nextTick(() => { document.querySelector('#report-tables')?.scrollIntoView({ behavior: 'smooth' }) })"
                 class="relative group bg-white p-5 rounded-[2rem] shadow-xl shadow-indigo-100/30 border border-slate-100 overflow-hidden transition-all duration-500 hover:scale-[1.02] cursor-pointer hover:border-emerald-200">
                <div class="relative flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-600/60 uppercase tracking-widest mb-1">Net Komisyon</p>
                        <p class="text-xl font-black text-emerald-600 tracking-tight">₺{{ number_format($netIncome, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Reservations Card -->
            <div @click="tab = 'reservations'; $nextTick(() => { document.querySelector('#report-tables')?.scrollIntoView({ behavior: 'smooth' }) })"
                 class="relative group bg-white p-5 rounded-[2rem] shadow-xl shadow-indigo-100/30 border border-slate-100 overflow-hidden transition-all duration-500 hover:scale-[1.02] cursor-pointer hover:border-indigo-200">
                <div class="relative flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Randevular</p>
                        <p class="text-xl font-black text-slate-900 tracking-tight">{{ number_format($totalReservations) }}</p>
                    </div>
                </div>
            </div>

            <!-- Wallet Payments -->
            <div @click="tab = 'financial'; $nextTick(() => { document.querySelector('#report-tables')?.scrollIntoView({ behavior: 'smooth' }) })"
                 class="relative group bg-white p-5 rounded-[2rem] shadow-xl shadow-indigo-100/30 border border-slate-100 overflow-hidden transition-all duration-500 hover:scale-[1.02] cursor-pointer hover:border-blue-200">
                <div class="relative flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Cüzdan Ödeme</p>
                        <p class="text-xl font-black text-slate-900 tracking-tight">₺{{ number_format($totalWalletPayments, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Topups Card -->
            <div @click="tab = 'financial'; $nextTick(() => { document.querySelector('#report-tables')?.scrollIntoView({ behavior: 'smooth' }) })"
                 class="relative group bg-white p-5 rounded-[2rem] shadow-xl shadow-indigo-100/30 border border-slate-100 overflow-hidden transition-all duration-500 hover:scale-[1.02] cursor-pointer hover:border-amber-200">
                <div class="relative flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-700 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Bakiye Yükleme</p>
                        <p class="text-xl font-black text-slate-900 tracking-tight">₺{{ number_format($totalTopups, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Refunds Card -->
            <div @click="tab = 'financial'; $nextTick(() => { document.querySelector('#report-tables')?.scrollIntoView({ behavior: 'smooth' }) })"
                 class="relative group bg-white p-5 rounded-[2rem] shadow-xl shadow-indigo-100/30 border border-slate-100 overflow-hidden transition-all duration-500 hover:scale-[1.02] cursor-pointer hover:border-red-200">
                <div class="relative flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-700 rounded-2xl flex items-center justify-center shadow-lg shadow-red-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Toplam İade</p>
                        <p class="text-xl font-black text-slate-900 tracking-tight">₺{{ number_format($totalRefunds, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Tab Switcher -->
        <div id="report-tables" class="flex items-center gap-4 mb-6">
            <button @click="tab = 'reservations'" 
                    :class="tab === 'reservations' ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'bg-white text-slate-500 hover:bg-slate-100'"
                    class="px-8 py-3.5 rounded-[1.5rem] font-black text-xs uppercase tracking-widest transition-all">
                Randevu Hareketleri
            </button>
            <button @click="tab = 'financial'" 
                    :class="tab === 'financial' ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'bg-white text-slate-500 hover:bg-slate-100'"
                    class="px-8 py-3.5 rounded-[1.5rem] font-black text-xs uppercase tracking-widest transition-all">
                Finansal Hareketler
            </button>
        </div>

        <!-- Reservations Tab -->
        <div x-show="tab === 'reservations'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-[3rem] shadow-2xl shadow-slate-100 border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">Randevu Raporu</h2>
                    <p class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-wider">Tüm rezervasyon hareketleri</p>
                </div>
            </div>
            <div class="overflow-x-auto min-h-[400px]">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tarih / Saat</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">İşletme</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Müşteri</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kişi</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tutar</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Durum</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($reservations as $reservation)
                        <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs uppercase group-hover:bg-white transition-colors">
                                        {{ $reservation->created_at->format('d.m') }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-900">{{ $reservation->created_at->format('H:i') }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $reservation->created_at->format('Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-slate-900">{{ $reservation->business->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $reservation->business->businessCategory->name ?? 'Genel' }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-slate-900">{{ $reservation->user->name }}</p>
                                <p class="text-[10px] font-bold text-indigo-500 lowercase">{{ $reservation->user->email }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-slate-100 rounded-full text-[10px] font-black text-slate-600 uppercase">
                                    {{ $reservation->guest_count }} KİŞİ
                                </span>
                            </td>
                            <td class="px-8 py-6 text-sm font-black text-slate-900">
                                ₺{{ number_format($reservation->total_amount ?? 0, 2) }}
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest
                                    {{ $reservation->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $reservation->status === 'confirmed' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                    {{ $reservation->status === 'cancelled' ? 'bg-rose-100 text-rose-700' : '' }}
                                    {{ $reservation->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}">
                                    {{ $reservation->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center justify-center gap-4">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center">
                                        <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    </div>
                                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Seçili aralıkta veri bulunamadı</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($reservations->hasPages())
                <div class="p-8 bg-slate-50/50 border-t border-slate-100">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>

        <!-- Financial Tab -->
        <div x-show="tab === 'financial'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-[3rem] shadow-2xl shadow-slate-100 border border-slate-100 overflow-hidden" style="display: none;">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">Finansal Rapor</h2>
                    <p class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-wider">Cüzdan, Bakiye ve Ödeme Hareketleri</p>
                </div>
            </div>
            <div class="overflow-x-auto min-h-[400px]">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tarih / Saat</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">İşlem Tipi</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kullanıcı</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Referans</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tutar</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Durum</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($walletTransactions as $tx)
                        <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs group-hover:bg-white">
                                        {{ $tx->created_at->format('d.m') }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-900">{{ $tx->created_at->format('H:i') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @php
                                    $txConfig = match($tx->type) {
                                        'topup' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Bakiye Yükleme'],
                                        'payment' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'label' => 'Randevu Ödemesi'],
                                        'refund' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'label' => 'İade'],
                                        default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'label' => $tx->type]
                                    };
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $txConfig['bg'] }} {{ $txConfig['text'] }}">
                                    {{ $txConfig['label'] }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-slate-900">{{ $tx->user->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 lowercase">{{ $tx->user->email }}</p>
                            </td>
                            <td class="px-8 py-6 text-xs font-bold text-slate-500">
                                #{{ $tx->reference_id ?? '---' }}
                            </td>
                            <td class="px-8 py-6 text-sm font-black {{ $tx->type == 'topup' ? 'text-emerald-600' : 'text-slate-900' }}">
                                {{ $tx->type == 'topup' ? '+' : '-' }}₺{{ number_format($tx->amount, 2, ',', '.') }}
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase bg-slate-100 text-slate-500">
                                    {{ $tx->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center justify-center gap-4">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center">
                                        <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Finansal hareket bulunamadı</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($walletTransactions->hasPages())
                <div class="p-8 bg-slate-50/50 border-t border-slate-100">
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
        days: ['Pt', 'Sa', 'Ça', 'Pe', 'Cu', 'Ct', 'Pz'],
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
            // Adjust dayOfWeek to start from Monday (0: Monday, ..., 6: Sunday)
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
                this.dateRangeLabel = 'Tarih seçin...';
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
                classes = 'bg-indigo-600 text-white shadow-lg scale-110 z-10';
            } else if (this.dateFrom && this.dateTo && selectedDate > this.dateFrom && selectedDate < this.dateTo) {
                classes = 'bg-indigo-50 text-indigo-600';
            } else {
                classes = 'hover:bg-slate-50 text-slate-700';
            }

            return classes;
        },

        setRange(range) {
            const today = new Date();
            let from = new Date();
            let to = new Date();

            switch(range) {
                case 'today':
                    break;
                case 'yesterday':
                    from.setDate(today.getDate() - 1);
                    to.setDate(today.getDate() - 1);
                    break;
                case 'last7':
                    from.setDate(today.getDate() - 7);
                    break;
                case 'last30':
                    from.setDate(today.getDate() - 30);
                    break;
                case 'thisMonth':
                    from = new Date(today.getFullYear(), today.getMonth(), 1);
                    break;
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
/* Custom Table Styling for better scrollbars */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}

[x-cloak] { display: none !important; }

/* Custom Pagination Styling */
.pagination {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}
.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    font-weight: 700;
    transition: all 0.2s;
    background: white;
    border: 1px solid #e2e8f0;
    color: #475569;
}
.page-item.active .page-link {
    background: #4f46e5;
    border-color: #4f46e5;
    color: white;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
}
.page-link:hover:not(.active) {
    background: #f8fafc;
    border-color: #cbd5e1;
    transform: translateY(-1px);
}
</style>
@endsection
