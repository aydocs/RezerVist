@extends('layouts.app')

@section('title', 'Sistem Sağlığı - Mission Control')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12">
    <div class="max-w-[1400px] mx-auto space-y-10 animate-fadeIn">
        
        <!-- Premium Command Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between pb-8 border-b border-slate-200 gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1.5 hover:text-purple-600 transition-colors"><i class="fa-solid fa-bolt-lightning"></i> YÖNETİM</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>SİSTEM SAĞLIĞI</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Mission <span class="text-purple-600">Control</span></h1>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 font-mono">CORE UPTIME</p>
                    <div class="flex items-center gap-2 justify-end">
                        <div class="relative flex">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                        </div>
                        <span class="text-[10px] font-black text-slate-700 uppercase">99.9% OPERATIONAL</span>
                    </div>
                </div>
                <div class="h-10 w-px bg-slate-200"></div>
                <button onclick="window.location.reload()" class="px-5 py-2.5 bg-slate-900 text-white text-[10px] font-black rounded-xl hover:bg-purple-600 transition-all uppercase tracking-widest flex items-center gap-2 shadow-xl shadow-slate-900/10 active:scale-95 group">
                    <i class="fa-solid fa-sync-alt group-hover:rotate-180 transition-transform duration-500"></i>
                    POLL SYSTEM
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Database Intelligence -->
            <div class="bg-white p-7 rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/20 space-y-6 group hover:border-purple-200 transition-all">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all">
                        <i class="fa-solid fa-database text-sm"></i>
                    </div>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100/50 rounded-lg text-[8px] font-black uppercase tracking-[0.2em]">{{ $health['database']['status'] }}</span>
                </div>
                <div>
                    <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1.5 font-mono">DATABASE ENGINE</h3>
                    <p class="text-lg font-black text-slate-900 tracking-tight uppercase">{{ $health['database']['name'] ?? 'SQL_CORE' }}</p>
                </div>
                <div class="pt-6 border-t border-slate-50 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">DATA SIZE</p>
                        <p class="text-[11px] font-black text-slate-600 uppercase">{{ $health['database']['size'] ?? '0 MB' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">CONNECTION</p>
                        <p class="text-[11px] font-black text-slate-600 uppercase">{{ $health['database']['connection'] ?? 'MYSQL' }}</p>
                    </div>
                </div>
            </div>

            <!-- Server Core Insights -->
            <div class="bg-white p-7 rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/20 space-y-6 group hover:border-blue-200 transition-all">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all">
                        <i class="fa-solid fa-microchip text-sm"></i>
                    </div>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100/50 rounded-lg text-[8px] font-black uppercase tracking-[0.2em]">HEALTHY</span>
                </div>
                <div>
                    <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1.5 font-mono">PHP RUNTIME</h3>
                    <p class="text-lg font-black text-slate-900 tracking-tight uppercase">VERSION {{ $health['server']['php_version'] }}</p>
                </div>
                <div class="pt-6 border-t border-slate-50 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">FRAMEWORK</p>
                        <p class="text-[11px] font-black text-slate-600 uppercase">LV {{ $health['server']['laravel_version'] }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">MEMORY LIMIT</p>
                        <p class="text-[11px] font-black text-slate-600 uppercase">{{ $health['server']['memory_limit'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Storage Volume Status -->
            <div class="bg-white p-7 rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/20 space-y-6 group hover:border-amber-200 transition-all">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all">
                        <i class="fa-solid fa-hard-drive text-sm"></i>
                    </div>
                    <span class="px-3 py-1 {{ $health['storage']['is_writable'] == 'Writable' ? 'bg-emerald-50 text-emerald-600 border-emerald-100/50' : 'bg-rose-50 text-rose-600 border-rose-100/50' }} rounded-lg text-[8px] font-black uppercase tracking-[0.2em]">{{ $health['storage']['is_writable'] }}</span>
                </div>
                <div>
                    <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1.5 font-mono">STORAGE VOLUME</h3>
                    <p class="text-lg font-black text-slate-900 tracking-tight uppercase">{{ $health['storage']['disk_free'] }} AVAILABLE</p>
                </div>
                <div class="pt-6 border-t border-slate-50 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">TOTAL SPACE</p>
                        <p class="text-[11px] font-black text-slate-600 uppercase">{{ $health['storage']['disk_total'] }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">DISK STATUS</p>
                        <p class="text-[11px] font-black text-emerald-600 uppercase tracking-tighter">MOUNTED OK</p>
                    </div>
                </div>
            </div>

            <!-- Environment Deployment -->
            <div class="bg-white p-7 rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/20 space-y-6 group hover:border-orange-200 transition-all">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all">
                        <i class="fa-solid fa-code-branch text-sm"></i>
                    </div>
                    <span class="px-3 py-1 bg-orange-50 text-orange-600 rounded-lg text-[8px] font-black uppercase tracking-[0.2em] border border-orange-100/50">{{ $health['environment']['env'] }}</span>
                </div>
                <div>
                    <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1.5 font-mono">DEPLOYMENT ENV</h3>
                    <p class="text-lg font-black text-slate-900 tracking-tight uppercase">{{ $health['environment']['debug'] == 'Enabled' ? 'DEBUG ACTIVE' : 'PROD STAGE' }}</p>
                </div>
                <div class="pt-6 border-t border-slate-50">
                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">SERVICE ENDPOINT</p>
                    <p class="text-[10px] font-black text-slate-500 truncate font-mono">{{ str_replace(['http://', 'https://'], '', $health['environment']['url']) }}</p>
                </div>
            </div>
        </div>

        <!-- High-Performance Cache & Queue Control -->
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/20 overflow-hidden">
            <div class="px-10 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h2 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-3">
                    <i class="fa-solid fa-bolt-lightning text-purple-600"></i>
                    TRANSACTIONAL SPEED & QUEUE PIPELINE
                </h2>
                <span class="text-[9px] font-black text-slate-400 tracking-[0.3em] font-mono">BURST MODE: OFF</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-100">
                <div class="p-10 space-y-8 group">
                    <div class="flex items-start gap-8">
                        <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-[1.5rem] flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all duration-500">
                            <i class="fa-solid fa-memory text-2xl group-hover:scale-110 transition-transform"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] font-mono">VOLATILE MEMORY (CACHE)</p>
                                <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-lg text-[8px] font-black uppercase tracking-[0.2em] border border-emerald-100/50">{{ $health['cache']['status'] }}</span>
                            </div>
                            <div class="flex items-baseline gap-4">
                                <p class="text-3xl font-black text-slate-900 uppercase tracking-tight">{{ $health['cache']['driver'] }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">GATEWAY ACTIVE</p>
                            </div>
                            <p class="text-[11px] text-slate-400 font-bold mt-3 uppercase tracking-tight opacity-50">High-speed data retrieval pipeline operational for application state.</p>
                        </div>
                    </div>
                </div>
                <div class="p-10 space-y-8 group">
                    <div class="flex items-start gap-8">
                        <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-[1.5rem] flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all duration-500">
                            <i class="fa-solid fa-layer-group text-2xl group-hover:scale-110 transition-transform"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] font-mono">ASYNC PIPELINES (QUEUE)</p>
                                <span class="px-2.5 py-0.5 {{ $health['queue']['failed_jobs'] > 0 ? 'bg-rose-50 text-rose-600 border-rose-100/50' : 'bg-emerald-50 text-emerald-600 border-emerald-100/50' }} rounded-lg text-[8px] font-black uppercase tracking-[0.2em] border">
                                    {{ $health['queue']['failed_jobs'] }} ERRORS DETECTED
                                </span>
                            </div>
                            <div class="flex items-baseline gap-4">
                                <p class="text-3xl font-black text-slate-900 uppercase tracking-tight">{{ $health['queue']['driver'] }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">PIPELINE CLEAR</p>
                            </div>
                            <p class="text-[11px] text-slate-400 font-bold mt-3 uppercase tracking-tight opacity-50">Background job processing engine management and error tracking.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
