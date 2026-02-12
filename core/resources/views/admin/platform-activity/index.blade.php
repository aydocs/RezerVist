@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8" x-data="{ 
    selectedActivity: null, 
    showModal: false,
    activeTab: 'all',
    filterActivity(type) {
        // Simple client-side filtering logic or just visual state
        this.activeTab = type;
        const items = document.querySelectorAll('.activity-item');
        items.forEach(item => {
            if (type === 'all' || item.dataset.type === type) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Refined Premium Header -->
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-8">
            <div class="space-y-4">
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition-colors">Panel</a>
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    <span class="text-slate-300">Aktiviteler</span>
                </nav>
                <div>
                    <h1 class="text-5xl font-black text-slate-900 tracking-tight mb-4">
                        Platform Aktiviteleri
                    </h1>
                    <div class="flex items-center gap-4">
                        <span class="w-12 h-1 bg-purple-600 rounded-full"></span>
                        <p class="text-slate-500 font-medium text-lg">Sistemdeki gerçek zamanlı hareketler ve işlem günlükleri</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.platform-activity.export') }}" class="group flex items-center gap-3 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full transition-all duration-300 shadow-lg shadow-emerald-500/20 font-black text-[11px] uppercase tracking-widest active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Dışa Aktar (CSV)</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-5 px-6 py-3.5 bg-white hover:bg-slate-50 text-slate-700 rounded-full transition-all duration-300 shadow-sm border border-slate-100 font-black text-[11px] uppercase tracking-widest active:scale-95">
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-purple-600 transition-all duration-300">
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </div>
                    <span>Geri Dön</span>
                </a>
            </div>
        </div>

        <!-- Refined Tabs -->
        <div class="mb-8 overflow-x-auto no-scrollbar pb-4">
            <div class="bg-slate-100/50 p-1.5 rounded-full border border-slate-200 inline-flex items-center gap-1">
                <button @click="filterActivity('all')" 
                        :class="activeTab === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-5 py-2 rounded-full text-[11px] font-black uppercase tracking-wider transition-all flex items-center gap-2.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    Tümü
                </button>
                <button @click="filterActivity('reservation')" 
                        :class="{ 'bg-purple-600 text-white shadow-md': activeTab === 'reservation', 'text-slate-500 hover:text-slate-700 hover:bg-slate-50': activeTab !== 'reservation' }"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Rezervasyonlar
                </button>
                <button @click="filterActivity('payment')" 
                        :class="{ 'bg-purple-600 text-white shadow-md': activeTab === 'payment', 'text-slate-500 hover:text-slate-700 hover:bg-slate-50': activeTab !== 'payment' }"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Ödemeler
                </button>
                <button @click="filterActivity('system')" 
                        :class="{ 'bg-purple-600 text-white shadow-md': activeTab === 'system', 'text-slate-500 hover:text-slate-700 hover:bg-slate-50': activeTab !== 'system' }"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Sistem
                </button>
            </div>
        </div>

        <!-- Activity Feed -->
        <div class="space-y-4">
            @forelse($paginatedActivities as $activity)
                @php
                    $color = $activity['color'] ?? 'indigo';
                    
                    // Map generic colors to specific Tailwind classes for bg and text
                    // Using specific distinct colors as requested
                    $bgClass = match($color) {
                        'blue' => 'bg-blue-50 text-blue-600',
                        'green', 'emerald' => 'bg-emerald-50 text-emerald-600',
                        'red' => 'bg-red-50 text-red-600',
                        'purple', 'indigo' => 'bg-indigo-50 text-indigo-600',
                        'amber' => 'bg-amber-50 text-amber-600',
                        default => 'bg-slate-50 text-slate-600'
                    };

                    $badgeBgClass = match($color) {
                        'blue' => 'bg-blue-100 text-blue-700',
                        'green', 'emerald' => 'bg-emerald-100 text-emerald-700',
                        'red' => 'bg-red-100 text-red-700',
                        'purple', 'indigo' => 'bg-indigo-100 text-indigo-700',
                        'amber' => 'bg-amber-100 text-amber-700',
                        default => 'bg-slate-100 text-slate-700'
                    };
                    
                    $icon = $activity['icon'] ?? 'clipboard-list';
                    
                    $filterType = 'system';
                    if(str_contains($activity['type'], 'Rezervasyon')) $filterType = 'reservation';
                    if(str_contains($activity['type'], 'Ödeme') || str_contains($activity['message'], 'Cüzdan')) $filterType = 'payment';
                @endphp

                <div class="activity-item group block bg-white rounded-2xl p-5 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-lg transition-all cursor-pointer border border-transparent hover:border-slate-100"
                     data-type="{{ $filterType }}"
                     @click="selectedActivity = {{ json_encode($activity) }}; showModal = true">
                    
                    <div class="flex items-start gap-5">
                        <!-- Squircle Icon -->
                        <div class="flex-shrink-0 pt-1">
                            <div class="w-14 h-14 rounded-2xl {{ $bgClass }} flex items-center justify-center">
                                @if($icon == 'calendar')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @elseif($icon == 'user')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                @elseif($icon == 'credit-card')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                @elseif($icon == 'document-text')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                @elseif($icon == 'exclamation')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                @else
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @endif
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0 pt-1">
                            <h3 class="text-lg font-bold text-slate-800 mb-1 group-hover:text-indigo-600 transition-colors">
                                {{ $activity['message'] }}
                            </h3>
                            <p class="text-sm text-slate-500 font-medium mb-3">
                                {{ $activity['details'] ?? '' }}
                            </p>
                            
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider {{ $badgeBgClass }}">
                                    {{ $activity['type'] }}
                                </span>
                                <span class="text-sm text-slate-400 font-medium">
                                    {{ \Carbon\Carbon::parse($activity['created_at'])->format('d M Y, H:i:s') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-white rounded-2xl shadow-sm border border-slate-200">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Henüz Aktivite Yok</h3>
                    <p class="text-slate-500 mt-2 max-w-sm mx-auto">Sistemde henüz kaydedilmiş bir işlem veya aktivite bulunmuyor.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $paginatedActivities->links() }}
        </div>
    </div>

    <!-- Activity Detail Modal -->
    <div x-show="showModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

        <!-- Panel -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden transform transition-all"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Modal Header -->
                <div class="px-8 pt-8 pb-4 flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900" x-text="selectedActivity?.type || 'Aktivite'"></h3>
                        <p class="text-sm text-slate-400 mt-1" x-text="selectedActivity?.created_at"></p>
                    </div>
                    <button @click="showModal = false" class="text-slate-300 hover:text-slate-500 transition-colors p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="px-8 pb-8 space-y-6">
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">İŞLEM AÇIKLAMASI</h4>
                        <div class="text-lg font-semibold text-slate-800 leading-snug" x-text="selectedActivity?.message"></div>
                        <div class="text-slate-500 mt-2" x-text="selectedActivity?.details"></div>
                    </div>

                    <!-- Technical Details Box -->
                    <template x-if="selectedActivity?.metadata && Object.keys(selectedActivity.metadata).length > 0">
                        <div class="mt-4">
                             <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">TEKNİK DETAYLAR</h4>
                             <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 overflow-x-auto">
                                <pre class="text-xs font-mono text-slate-600 whitespace-pre-wrap" x-text="JSON.stringify(selectedActivity.metadata, null, 2)"></pre>
                             </div>
                        </div>
                    </template>

                    <!-- FULL RAW DATA DUMP (User Request: "Hepsini yaz") -->
                    <div class="mt-6">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">TÜM HAM VERİ (RAW DATA)</h4>
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 overflow-x-auto">
                            <pre class="text-[10px] font-mono text-slate-600 whitespace-pre-wrap leading-relaxed select-all" x-text="JSON.stringify(selectedActivity?.raw_data, null, 4)"></pre>
                        </div>
                    </div>

                    <!-- Close Button Row -->
                    <div class="pt-4 flex justify-end">
                        <button @click="showModal = false" class="px-6 py-2 bg-white border border-slate-200 rounded-xl text-slate-600 font-semibold hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                            Kapat
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
