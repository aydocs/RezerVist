@extends('layouts.app')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen pb-20 font-inter pt-28">
    


    <!-- 2. MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Welcome Section -->
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 border-b border-gray-100 pb-6">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight flex items-center gap-3">
                    Merhaba, {{ explode(' ', Auth::user()->name)[0] }}
                    <span class="inline-flex items-center justify-center p-1 rounded-full bg-yellow-100 text-yellow-600">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                </h2>
                <p class="text-slate-500 font-medium mt-1">İşletmenizin bugünkü durumuna göz atalım.</p>
            </div>
             @if($onboarding['percent'] < 100)
             <a href="{{ route('vendor.setup.index') }}" class="group flex items-center gap-4 bg-white border border-gray-200 pl-4 pr-6 py-3 rounded-2xl hover:border-indigo-500 hover:shadow-md transition-all duration-300">
                <div class="relative w-12 h-12 flex items-center justify-center">
                    <svg class="w-12 h-12 absolute text-gray-200" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="8"></circle>
                    </svg>
                    <svg class="w-12 h-12 absolute text-indigo-600 transform -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="8" stroke-dasharray="283" stroke-dashoffset="{{ 283 - (283 * $onboarding['percent'] / 100) }}" stroke-linecap="round"></circle>
                    </svg>
                    <span class="text-xs font-bold text-gray-900 absolute">{{ $onboarding['percent'] }}%</span>
                </div>
                <div class="text-left">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kurulum Devam Ediyor</p>
                    <div class="flex items-center gap-1 text-indigo-600">
                        <span class="text-sm font-bold group-hover:underline">Rehbere Git</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                </div>
            </a>
            @endif
        </div>

        <!-- Quick Actions Navigation -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-4">
            <a href="{{ route('vendor.reservations.index') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-500 hover:shadow-md transition group">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-2 group-hover:bg-blue-600 group-hover:text-white transition">
                    <i class="fas fa-calendar-check text-lg"></i>
                </div>
                <span class="text-xs font-bold text-gray-700">Rezervasyonlar</span>
            </a>
            
             <a href="{{ route('vendor.menus.index') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-500 hover:shadow-md transition group">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center mb-2 group-hover:bg-orange-600 group-hover:text-white transition">
                    <i class="fas fa-utensils text-lg"></i>
                </div>
                <span class="text-xs font-bold text-gray-700">Menü & Ürünler</span>
            </a>

            <a href="{{ route('vendor.coupons.index') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-500 hover:shadow-md transition group relative">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-2 group-hover:bg-purple-600 group-hover:text-white transition">
                    <i class="fas fa-ticket-alt text-lg"></i>
                </div>
                <span class="text-xs font-bold text-gray-700">Kuponlar</span>
                 @if(($stats['active_coupons'] ?? 0) > 0)
                <span class="absolute top-2 right-2 flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-purple-500"></span>
                </span>
                @endif
            </a>

             <a href="{{ route('vendor.staff.index') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-500 hover:shadow-md transition group">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 group-hover:bg-emerald-600 group-hover:text-white transition">
                    <i class="fas fa-users text-lg"></i>
                </div>
                <span class="text-xs font-bold text-gray-700">Ekip</span>
            </a>

             <a href="{{ route('vendor.finance.index') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-500 hover:shadow-md transition group">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-2 group-hover:bg-teal-600 group-hover:text-white transition">
                    <i class="fas fa-wallet text-lg"></i>
                </div>
                <span class="text-xs font-bold text-gray-700">Finans</span>
            </a>
            
            <a href="{{ route('vendor.occupancy.index') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-500 hover:shadow-md transition group">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-2 group-hover:bg-indigo-600 group-hover:text-white transition">
                    <i class="fas fa-chart-line text-lg"></i>
                </div>
                <span class="text-xs font-bold text-gray-700">Doluluk</span>
            </a>

             <a href="{{ route('vendor.business.edit') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-500 hover:shadow-md transition group">
                <div class="w-10 h-10 rounded-xl bg-gray-50 text-gray-600 flex items-center justify-center mb-2 group-hover:bg-gray-600 group-hover:text-white transition">
                    <i class="fas fa-cog text-lg"></i>
                </div>
                <span class="text-xs font-bold text-gray-700">Genel Ayarlar</span>
            </a>

            <a href="{{ route('vendor.settings.index') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 ring-2 ring-primary/10 hover:border-primary hover:shadow-md transition group">
                <div class="w-10 h-10 rounded-xl bg-primary/5 text-primary flex items-center justify-center mb-2 group-hover:bg-primary group-hover:text-white transition">
                    <i class="fas fa-sliders-h text-lg"></i>
                </div>
                <span class="text-xs font-bold text-gray-700">Akıllı Ayarlar</span>
            </a>
        </div>

        <!-- 3. KPI METRICS GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Revenue -->
            <a href="{{ route('vendor.finance.index') }}" class="bg-white p-6 rounded-[24px] shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-indigo-100/40 hover:-translate-y-1 transition-all duration-300 group cursor-pointer block">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-1">{{ __('dashboard.total_revenue') }}</p>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">₺{{ number_format($stats['revenue'], 2, ',', '.') }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black {{ $stats['revenue_trend'] >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                        {{ $stats['revenue_trend'] >= 0 ? '+' : '' }}%{{ abs($stats['revenue_trend']) }}
                    </span>
                    <span class="text-xs text-slate-400 font-bold">geçen döneme göre</span>
                </div>
            </a>

            <!-- Total Reservations -->
            <a href="{{ route('vendor.reservations.index') }}" class="bg-white p-6 rounded-[24px] shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-blue-100/40 hover:-translate-y-1 transition-all duration-300 group cursor-pointer block">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-1">Toplam İşlem</p>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['total_reservations'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                </div>
                <p class="text-xs text-slate-400 font-bold">{{ $stats['approved_reservations'] }} başarıyla tamamlandı</p>
            </a>

            <!-- Waiting Approval -->
            <a href="{{ route('vendor.reservations.index', ['status' => 'pending']) }}" class="bg-white p-6 rounded-[24px] shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-yellow-100/40 hover:-translate-y-1 transition-all duration-300 group cursor-pointer block {{ $stats['pending_reservations'] > 0 ? 'ring-2 ring-yellow-400/50' : '' }}">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-1">Bekleyen Onay</p>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['pending_reservations'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-yellow-50 flex items-center justify-center text-yellow-600 group-hover:bg-yellow-500 group-hover:text-white transition-all {{ $stats['pending_reservations'] > 0 ? 'animate-pulse' : '' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                </div>
                @if($stats['pending_reservations'] > 0)
                <span class="text-xs font-black text-yellow-600 hover:underline uppercase tracking-wide">Talepleri İncele &rarr;</span>
                @else
                <span class="text-xs text-slate-400 font-bold">Her şey yolunda!</span>
                @endif
            </a>

            <!-- Business Rating -->
            <a href="{{ route('vendor.reviews.index') }}" class="bg-white p-6 rounded-[24px] shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-orange-100/40 hover:-translate-y-1 transition-all duration-300 group cursor-pointer block">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-1">İşletme Puanı</p>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['avg_rating'], 1) }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500 group-hover:bg-orange-500 group-hover:text-white transition-all">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                     <span class="text-xs text-slate-400 font-bold">Müşteri memnuniyeti</span>
                </div>
            </a>
        </div>

        <!-- 4. CONTENT GRID (CHART & TABLE & SIDEBAR) -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- LEFT COLUMN: CHART & TABLE -->
            <div class="xl:col-span-2 space-y-8">
                
                <!-- Recent Reservations Table -->
                <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-xl font-black text-slate-900 tracking-tight">Son Talepler</h3>
                            <p class="text-sm text-slate-500 font-medium mt-1">En son gelen rezervasyon istekleri</p>
                        </div>
                        <a href="{{ route('vendor.reservations.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-50 text-slate-700 font-black hover:bg-slate-200 border-2 border-slate-100 hover:border-slate-300 transition text-xs uppercase tracking-wide">
                            Tümünü Gör
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-wider">Müşteri</th>
                                    <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-wider">Tarih</th>
                                    <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-wider">Kişi</th>
                                    <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-wider">Tutar</th>
                                    <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-wider">Durum</th>
                                    <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-wider text-right">Detay</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recentReservations as $reservation)
                                <tr class="hover:bg-slate-50/50 transition-colors duration-200 group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="relative">
                                                <div class="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-sm border border-indigo-100">
                                                    {{ strtoupper(substr($reservation->user->name ?? 'M', 0, 1)) }}
                                                </div>
                                                @if($reservation->user && $reservation->user->reservations_count > 1)
                                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-amber-400 rounded-full border-2 border-white flex items-center justify-center text-[8px] text-white font-bold" title="Sadık Müşteri">
                                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900">{{ $reservation->user->name ?? 'Misafir' }}</div>
                                                <div class="text-xs text-slate-400">{{ $reservation->location->name ?? 'Merkez' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($reservation->start_time)->format('d.m.Y') }}</span>
                                            <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-600">{{ $reservation->guest_count }} Kişi</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-black text-slate-900">₺{{ number_format($reservation->total_amount, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statuses = [
                                                'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Bekliyor'],
                                                'approved' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Onaylandı'],
                                                'rejected' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'label' => 'Reddedildi'],
                                                'cancelled' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => 'İptal'],
                                                'completed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Tamamlandı'],
                                            ];
                                            $st = $statuses[$reservation->status] ?? ['bg'=>'bg-gray-100','text'=>'text-gray-600','label'=>$reservation->status];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wide {{ $st['bg'] }} {{ $st['text'] }}">
                                            {{ $st['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button onclick="document.getElementById('details-{{ $reservation->id }}').showModal()" class="w-8 h-8 rounded-lg border-2 border-slate-100 text-gray-400 hover:text-indigo-600 hover:border-indigo-600 hover:bg-indigo-50 flex items-center justify-center transition-all bg-white shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <p class="text-slate-500 font-medium">Henüz bir talep bulunmuyor.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Revenue Chart -->
                 <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 p-8 hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300">
                     <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                        <div>
                            <h3 class="text-xl font-black text-slate-900 tracking-tight">Gelir Analizi</h3>
                            <p class="text-sm text-slate-500 font-medium mt-1">Dönemsel kazanç grafiği</p>
                        </div>
                        <div class="bg-slate-50 p-1 rounded-xl flex items-center gap-1">
                            @foreach(['1d' => '1G', '7d' => '7G', '1m' => '1A', '1y' => '1Y'] as $key => $label)
                            <button onclick="updateDashboardPeriod('{{ $key }}')" 
                                    data-period="{{ $key }}"
                                    class="dash-period-btn px-3 py-1.5 text-[10px] font-black rounded-lg transition-all duration-300 {{ $key === '1y' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">
                                {{ $label }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                     <div id="revenueChart" class="h-80 w-full relative"></div>
                 </div>
            </div>

            <!-- RIGHT COLUMN: SIDEBAR WIDGETS -->
            <div class="space-y-8">
                
                <!-- Staff Widget -->
                <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 p-6">
                     <div class="flex items-center justify-between mb-6 group cursor-pointer" onclick="window.location='{{ route('vendor.staff.index') }}'">
                        <h3 class="text-lg font-black text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">Personel</h3>
                        <span class="text-[10px] font-black uppercase bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-lg">Top 5</span>
                    </div>
                    <div class="space-y-4">
                        @forelse($stats['staff_stats'] as $staff)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-500 font-bold border border-slate-100">
                                    {{ substr($staff->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $staff->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $staff->total_bookings }} İşlem</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 bg-slate-50 px-2 py-1 rounded-lg">
                                <span class="text-sm font-black text-slate-700">{{ number_format($staff->rating, 1) }}</span>
                                <svg class="w-3 h-3 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-slate-400 italic text-center py-4">Veri yok.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Retention Widget -->
                <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-[32px] shadow-xl shadow-indigo-500/20 p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                    <p class="text-indigo-200 text-xs font-black uppercase tracking-widest mb-2">Sadık Müşteri Oranı</p>
                    <h3 class="text-4xl font-black mb-6">%{{ $stats['repeat_customer_rate'] }}</h3>
                    <div class="relative w-full bg-black/20 rounded-full h-2 mb-4">
                        <div class="absolute top-0 left-0 h-full bg-white rounded-full shadow-[0_0_10px_rgba(255,255,255,0.5)]" style="width: {{ $stats['repeat_customer_rate'] }}%"></div>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-medium text-indigo-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-9 9-4-4-6 6"></path></svg>
                        Geri gelen müşterileriniz artıyor!
                    </div>
                </div>

                <!-- Reviews Widget (Dynamic) -->
                @if(isset($recentReviews) && $recentReviews->count() > 0)
                <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 p-6">
                     <div class="flex items-center justify-between mb-6 group cursor-pointer" onclick="window.location='{{ route('vendor.reviews.index') }}'">
                        <h3 class="text-lg font-black text-slate-900 tracking-tight group-hover:text-yellow-600 transition-colors">Son Yorumlar</h3>
                        <span class="text-[10px] font-black uppercase bg-yellow-50 text-yellow-600 px-2.5 py-1 rounded-lg">Tümü</span>
                    </div>
                    <div class="space-y-4">
                        @foreach($recentReviews as $review)
                        <div class="pb-4 border-b border-gray-50 last:border-0 last:pb-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                        {{ substr($review->user->name ?? 'M', 0, 1) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-900">{{ $review->user->name ?? 'Misafir' }}</span>
                                </div>
                                <div class="flex text-yellow-400">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-2.5 h-2.5 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-500 line-clamp-2 italic">"{{ $review->comment ?? 'Yorum yok.' }}"</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reservation Details Modal -->
@foreach($recentReservations as $reservation)
<dialog id="details-{{ $reservation->id }}" class="modal p-0 rounded-[40px] shadow-2xl backdrop:bg-slate-900/60 font-inter">
    <div class="bg-white w-[95vw] max-w-2xl rounded-[40px] overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="font-black text-xl text-slate-900 tracking-tight">Rezervasyon Detayı</h3>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mt-1">ID: #{{ $reservation->id }}</p>
            </div>
            <form method="dialog">
                <button class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </form>
        </div>
        <div class="p-8 overflow-y-auto">
             <!-- User Card -->
             <div class="bg-gradient-to-br from-indigo-50 to-violet-50 p-6 rounded-3xl flex items-center gap-5 border border-indigo-100/50 mb-8">
                <div class="h-16 w-16 rounded-2xl bg-white shadow-sm flex items-center justify-center text-indigo-600 font-black text-2xl border border-indigo-100">
                    {{ strtoupper(substr($reservation->user->name ?? 'M', 0, 1)) }}
                </div>
                <div>
                    <h4 class="font-black text-xl text-indigo-950">{{ $reservation->user->name ?? 'Misafir' }}</h4>
                    <p class="text-indigo-600/80 font-medium text-sm mt-0.5">{{ $reservation->user->phone ?? 'Telefon Yok' }}</p>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="p-5 rounded-3xl bg-slate-50 border border-slate-100 text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Tarih</p>
                    <p class="font-black text-lg text-slate-900">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d.m.Y') }}</p>
                </div>
                <div class="p-5 rounded-3xl bg-slate-50 border border-slate-100 text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Saat</p>
                    <p class="font-black text-lg text-slate-900">{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}</p>
                </div>
                <div class="p-5 rounded-3xl bg-slate-50 border border-slate-100 text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Kişi</p>
                    <p class="font-black text-lg text-slate-900">{{ $reservation->guest_count }}</p>
                </div>
                <div class="p-5 rounded-3xl bg-slate-50 border border-slate-100 text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Tutar</p>
                    <p class="font-black text-lg text-emerald-600">₺{{ number_format($reservation->total_amount, 2) }}</p>
                </div>
            </div>
            
            @if(isset($reservation->note) && $reservation->note)
            <div class="bg-amber-50 p-6 rounded-3xl border border-amber-100 mb-8">
                <h4 class="text-xs font-black text-amber-700 uppercase tracking-wider mb-2">Not</h4>
                <p class="text-amber-900/80 italic">"{{ $reservation->note }}"</p>
            </div>
            @endif

             @if($reservation->menus->count() > 0)
            <div class="mb-8">
                <h4 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4">Hizmetler</h4>
                <div class="space-y-3">
                    @foreach($reservation->menus as $menu)
                    <div class="flex justify-between items-center p-4 rounded-2xl bg-white border border-gray-100 shadow-sm">
                        <span class="font-bold text-slate-700">{{ $menu->name }}</span>
                        <span class="text-xs bg-indigo-500 text-white px-2 py-1 rounded">{{ $menu->pivot->quantity ?? 1 }} Adet</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        <div class="p-6 border-t border-gray-100 bg-gray-50/50">
             @if($reservation->status === 'pending')
            <div class="flex gap-4">
                <form action="{{ route('vendor.reservations.update', $reservation->id) }}" method="POST" class="flex-1">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    <button class="w-full py-4 rounded-2xl border-2 border-slate-200 text-slate-600 font-bold hover:bg-slate-100">Reddet</button>
                </form>
                <form action="{{ route('vendor.reservations.update', $reservation->id) }}" method="POST" class="flex-1">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="approved">
                    <button class="w-full py-4 rounded-2xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200">Onayla</button>
                </form>
            </div>
            @else
             <form method="dialog"><button class="w-full py-4 rounded-2xl bg-white border border-gray-200 text-slate-700 font-bold hover:bg-gray-50">Kapat</button></form>
            @endif
        </div>
    </div>
</dialog>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    let dashboardChart;

    document.addEventListener('DOMContentLoaded', function() {
        const options = {
            series: @json($chartSeries),
            chart: {
                type: 'area',
                height: 320,
                toolbar: { show: false },
                zoom: { enabled: true, type: 'x' },
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#4f46e5', '#10b981'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: [4, 4] },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100]
                }
            },
            xaxis: {
                categories: @json($labels),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#94a3b8', fontWeight: 600 } }
            },
            yaxis: {
                labels: {
                    style: { colors: '#94a3b8', fontWeight: 600 },
                    formatter: (value) => '₺' + value.toLocaleString('tr-TR')
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                xaxis: { lines: { show: false } }
            },
            tooltip: {
                theme: 'dark',
                shared: true,
                y: { formatter: (value) => '₺' + value.toLocaleString('tr-TR') }
            }
        };

        dashboardChart = new ApexCharts(document.querySelector("#revenueChart"), options);
        dashboardChart.render();
    });

    async function updateDashboardPeriod(period) {
        // Toggle UI
        document.querySelectorAll('.dash-period-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm');
            btn.classList.add('text-slate-400', 'hover:text-slate-600');
            if (btn.dataset.period === period) {
                btn.classList.add('bg-white', 'text-indigo-600', 'shadow-sm');
                btn.classList.remove('text-slate-400', 'hover:text-slate-600');
            }
        });

        const response = await fetch(`{{ route('vendor.analytics.data') }}?type=finance&period=${period}`);
        const result = await response.json();

        dashboardChart.updateOptions({
            xaxis: { categories: result.labels }
        });
        dashboardChart.updateSeries(result.series);
    }

    // Real-time Reservation Updates via Reverb/Echo
    @if(Auth::check() && Auth::user()->role === 'business')
    if (typeof window.Echo !== 'undefined') {
        window.Echo.private('business.{{ Auth::user()->business->id ?? 0 }}')
            .listen('ReservationCreated', (e) => {
                console.log('New reservation received:', e.reservation);
                
                // Show toast notification
                if (typeof showToast === 'function') {
                    showToast('Yeni rezervasyon alındı! #' + e.reservation.id, 'success');
                }
                
                // Reload page to show new reservation
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            });
    }
    @endif
</script>
@endsection
