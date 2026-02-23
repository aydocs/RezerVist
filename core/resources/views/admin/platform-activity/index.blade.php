@extends('layouts.app')

@section('title', 'Platform Aktiviteleri - Canlı Yayın')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12" x-data="{ 
    selectedActivity: null, 
    showModal: false,
    activeTab: 'all',
    filterActivity(type) {
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
    <div class="max-w-[1400px] mx-auto space-y-8 animate-fadeIn">
        
        <!-- Premium Command Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between pb-8 border-b border-slate-200 gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1.5 hover:text-purple-600 transition-colors"><i class="fa-solid fa-bolt-lightning"></i> YÖNETİM</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>PLATFORM AKTİVİTELERİ</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Canlı <span class="text-purple-600">Akış</span></h1>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.platform-activity.export') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-900 text-[10px] font-black rounded-xl hover:bg-slate-900 hover:text-white transition-all uppercase tracking-widest flex items-center gap-2 shadow-sm group">
                    <i class="fa-solid fa-file-export text-slate-400 group-hover:text-white transition-colors"></i>
                    DIŞA AKTAR (CSV)
                </a>
            </div>
        </div>

        <!-- High-Density Intelligence Tabs -->
        <div class="flex flex-wrap items-center bg-white p-1 border border-slate-200 rounded-2xl shadow-sm w-fit max-w-full overflow-x-auto no-scrollbar">
            <button @click="filterActivity('all')" 
                    :class="activeTab === 'all' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600'"
                    class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all outline-none whitespace-nowrap">
                TÜMÜ
            </button>
            <button @click="filterActivity('reservation')" 
                    :class="activeTab === 'reservation' ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/20' : 'text-slate-400 hover:text-slate-600'"
                    class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all outline-none whitespace-nowrap">
                REZERVASYONLAR
            </button>
            <button @click="filterActivity('payment')" 
                    :class="activeTab === 'payment' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-400 hover:text-slate-600'"
                    class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all outline-none whitespace-nowrap">
                ÖDEMELER
            </button>
            <button @click="filterActivity('system')" 
                    :class="activeTab === 'system' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'text-slate-400 hover:text-slate-600'"
                    class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all outline-none whitespace-nowrap">
                SİSTEM
            </button>
        </div>

        <!-- Modern High-Density Activity Feed -->
        <div class="space-y-3">
            @forelse($paginatedActivities as $activity)
                @php
                    $color = $activity['color'] ?? 'slate';
                    $icon = $activity['icon'] ?? 'bolt';
                    
                    $badgeClass = match($color) {
                        'blue' => 'bg-blue-50 text-blue-600 border-blue-100/50',
                        'green', 'emerald' => 'bg-emerald-50 text-emerald-600 border-emerald-100/50',
                        'red' => 'bg-rose-50 text-rose-600 border-rose-100/50',
                        'purple', 'indigo' => 'bg-purple-50 text-purple-600 border-purple-100/50',
                        'amber' => 'bg-amber-50 text-amber-600 border-amber-100/50',
                        default => 'bg-slate-50 text-slate-500 border-slate-200/50'
                    };

                    $filterType = 'system';
                    if(str_contains($activity['type'], 'Rezervasyon')) $filterType = 'reservation';
                    if(str_contains($activity['type'], 'Ödeme') || str_contains($activity['message'], 'Cüzdan')) $filterType = 'payment';
                @endphp

                <div class="activity-item group bg-white border border-slate-200 rounded-[1.5rem] p-5 shadow-sm hover:shadow-xl hover:shadow-slate-200/40 hover:border-purple-200 transition-all cursor-pointer relative overflow-hidden"
                     data-type="{{ $filterType }}"
                     @click="selectedActivity = {{ json_encode($activity) }}; showModal = true">
                    
                    <div class="absolute right-0 top-0 h-full w-1.5 bg-{{ $color }}-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="flex items-center gap-6 relative z-10">
                        <div class="w-12 h-12 rounded-2xl {{ $badgeClass }} flex items-center justify-center border shadow-sm group-hover:bg-slate-900 group-hover:text-white group-hover:border-slate-900 transition-all duration-300">
                            <i class="fa-solid fa-{{ $icon === 'calendar' ? 'calendar-days' : ($icon === 'user' ? 'user' : ($icon === 'credit-card' ? 'credit-card' : ($icon === 'document-text' ? 'file-lines' : ($icon === 'exclamation' ? 'triangle-exclamation' : 'bolt')))) }} text-sm transition-transform group-hover:scale-110"></i>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-2">
                                <h3 class="text-sm font-black text-slate-800 tracking-tight group-hover:text-purple-600 transition-colors uppercase">
                                    {{ $activity['message'] }}
                                </h3>
                                <div class="flex items-center gap-4">
                                    <span class="px-2.5 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-[0.2em] border {{ $badgeClass }}">
                                        {{ $activity['type'] }}
                                    </span>
                                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap">
                                        <i class="fa-regular fa-clock text-[9px]"></i>
                                        {{ \Carbon\Carbon::parse($activity['created_at'])->format('H:i:s') }}
                                        <span class="opacity-30">•</span>
                                        {{ \Carbon\Carbon::parse($activity['created_at'])->format('d.m.Y') }}
                                    </div>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400 font-bold mt-1.5 line-clamp-1 italic uppercase tracking-tight opacity-70 group-hover:opacity-100 transition-opacity">
                                <i class="fa-solid fa-code-branch mr-1.5 text-[9px]"></i>
                                {{ $activity['details'] ?? 'OPERASYON DETAYI MEVCUT DEĞİL' }}
                            </p>
                        </div>

                        <div class="hidden sm:flex items-center justify-center w-8 h-8 rounded-full bg-slate-50 group-hover:bg-purple-50 transition-colors">
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-purple-600 transition-all group-hover:translate-x-0.5"></i>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-32 bg-white rounded-[2rem] border border-dashed border-slate-200">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-[2.5rem] bg-slate-50 mb-6">
                        <i class="fa-solid fa-satellite-dish text-3xl text-slate-200"></i>
                    </div>
                    <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.4em]">SİSTEM AKIŞI BEKLENİYOR</p>
                </div>
            @endforelse
        </div>

        @if($paginatedActivities->hasPages())
        <div class="pt-6">
            {{ $paginatedActivities->links('vendor.pagination.console') }}
        </div>
        @endif
    </div>

    <!-- Premium Activity Insight Modal -->
    <div x-show="showModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak
         style="display: none;">
        
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" @click="showModal = false"></div>

        <div class="relative bg-white rounded-[2.5rem] w-full max-w-2xl shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)] overflow-hidden border border-slate-200">
            <!-- Modal Header -->
            <div class="bg-slate-900 p-8 text-white relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 opacity-10">
                    <i class="fa-solid fa-shield-halved text-9xl"></i>
                </div>
                
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-6">
                        <div class="px-3 py-1 bg-white/10 rounded-lg text-[9px] font-black uppercase tracking-[0.3em] backdrop-blur-sm border border-white/10" x-text="selectedActivity?.type"></div>
                        <button @click="showModal = false" class="text-white/40 hover:text-white transition-colors">
                            <i class="fa-solid fa-circle-xmark text-2xl"></i>
                        </button>
                    </div>
                    <h3 class="text-2xl font-black tracking-tight" x-text="selectedActivity?.message"></h3>
                    <div class="mt-4 flex items-center gap-4 text-[10px] font-black text-white/40 uppercase tracking-widest font-mono">
                         <span class="flex items-center gap-2 text-purple-400"><i class="fa-solid fa-terminal"></i> TRANSACTION ID: #SYS-{{ date('Ymd') }}-RT</span>
                         <span class="flex items-center gap-2" x-text="selectedActivity?.created_at"></span>
                    </div>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-8 space-y-8">
                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.25em] mb-3 block font-mono">ACTIVITY SUMMARY</label>
                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 italic">
                        <p class="text-[13px] font-bold text-slate-700 leading-relaxed" x-text="selectedActivity?.details || 'Daha fazla detay mevcut değil.'"></p>
                    </div>
                </div>

                <div x-show="selectedActivity?.metadata && Object.keys(selectedActivity.metadata).length > 0">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.25em] mb-3 block font-mono">PROCESS METADATA</label>
                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 overflow-hidden">
                        <pre class="text-[10px] font-mono font-bold text-slate-500 whitespace-pre-wrap leading-relaxed" x-text="JSON.stringify(selectedActivity?.metadata, null, 2)"></pre>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.25em] font-mono">RAW DEBUG DATA</label>
                        <span class="text-[8px] font-black text-emerald-500 uppercase tracking-widest">SİSTEM ÇIKTISI</span>
                    </div>
                    <div class="bg-slate-900 rounded-2xl p-6 overflow-x-auto max-h-[250px] custom-scroll border border-slate-800">
                        <pre class="text-[10px] font-mono text-emerald-400 whitespace-pre-wrap opacity-80 leading-relaxed" x-text="JSON.stringify(selectedActivity?.raw_data, null, 2)"></pre>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-4">
                    <button @click="showModal = false" class="px-8 py-3.5 bg-slate-900 text-white text-[10px] font-black rounded-2xl hover:bg-purple-600 transition-all uppercase tracking-[0.2em] shadow-lg shadow-slate-900/10 active:scale-95">
                        DOĞRULANDI VE KAPAT
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
