@extends('layouts.app')

@section('title', 'Sistem Logları - Komuta Merkezi')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12">
    <div class="max-w-[1700px] mx-auto space-y-8 animate-fadeIn">
        
        <!-- Premium Command Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between pb-8 border-b border-slate-200 gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1.5 hover:text-purple-600 transition-colors"><i class="fa-solid fa-bolt-lightning"></i> YÖNETİM</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>SİSTEM LOGLARI</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Olay <span class="text-purple-600">Güncesi</span></h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="px-4 py-2 bg-white border border-slate-200 rounded-xl flex items-center gap-2 shadow-sm">
                    <div class="relative flex">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                    </div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">LIVE STREAM</span>
                </div>
            </div>
        </div>

        <!-- High-Density Intelligence Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-purple-200 transition-all group overflow-hidden relative">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 font-mono">TOTAL EVENTS</p>
                <div class="flex items-center justify-between relative z-10">
                    <h3 class="text-xl font-black text-slate-900">{{ number_format($totalLogs) }}</h3>
                    <i class="fa-solid fa-database text-slate-100 text-xl group-hover:text-purple-100 transition-colors"></i>
                </div>
            </div>
            
            <div class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-emerald-200 transition-all group overflow-hidden relative">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 font-mono">LOGINS</p>
                <div class="flex items-center justify-between relative z-10">
                    <h3 class="text-xl font-black text-emerald-600">{{ number_format($loginAttempts) }}</h3>
                    <i class="fa-solid fa-user-check text-emerald-50 text-xl group-hover:text-emerald-100 transition-colors"></i>
                </div>
            </div>

            <div class="bg-white border border-rose-100 rounded-2xl p-5 hover:border-rose-300 transition-all group overflow-hidden relative">
                <p class="text-[9px] font-black text-rose-400 uppercase tracking-widest mb-2 font-mono">ERRORS / FAILED</p>
                <div class="flex items-center justify-between relative z-10">
                    <h3 class="text-xl font-black text-rose-600">{{ number_format($failedLogins) }}</h3>
                    <i class="fa-solid fa-shield-xmark text-rose-50 text-xl group-hover:text-rose-100 transition-colors"></i>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-blue-200 transition-all group overflow-hidden relative">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 font-mono">SESSIONS CLOSED</p>
                <div class="flex items-center justify-between relative z-10">
                    <h3 class="text-xl font-black text-blue-600">{{ number_format($logouts) }}</h3>
                    <i class="fa-solid fa-power-off text-blue-50 text-xl group-hover:text-blue-100 transition-colors"></i>
                </div>
            </div>
        </div>

        <!-- Command Center Filters -->
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/20 p-8">
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="space-y-8">
                <div class="flex flex-wrap gap-2 pb-6 border-b border-slate-100">
                    <a href="{{ route('admin.activity-logs.index') }}" 
                       class="px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all {{ !request('category') ? 'bg-slate-900 text-white shadow-lg' : 'bg-slate-50 text-slate-400 hover:text-slate-600' }}">
                        TÜMÜ
                    </a>
                    @foreach(['auth' => 'KİMLİK', 'payment' => 'ÖDEMELER', 'reservation' => 'REZERVASYONLAR', 'business' => 'İŞLETME'] as $key => $label)
                        <a href="{{ request()->fullUrlWithQuery(['category' => $key]) }}" 
                           class="px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all {{ request('category') == $key ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/20' : 'bg-slate-50 text-slate-400 hover:text-slate-600' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    
                    <div>
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 font-mono">PROCESS TYPE</label>
                        <select name="type" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-bold text-slate-900 text-[11px] outline-none appearance-none">
                            <option value="">Tümü</option>
                            <optgroup label="Ödemeler">
                                <option value="payment_success" {{ request('type') == 'payment_success' ? 'selected' : '' }}>Başarılı Ödeme</option>
                                <option value="payment_failed" {{ request('type') == 'payment_failed' ? 'selected' : '' }}>Başarısız Ödeme</option>
                                <option value="payment_refunded" {{ request('type') == 'payment_refunded' ? 'selected' : '' }}>İade</option>
                            </optgroup>
                            <optgroup label="Kimlik">
                                <option value="login" {{ request('type') == 'login' ? 'selected' : '' }}>Giriş</option>
                                <option value="logout" {{ request('type') == 'logout' ? 'selected' : '' }}>Çıkış</option>
                                <option value="failed_login" {{ request('type') == 'failed_login' ? 'selected' : '' }}>Hatalı Giriş</option>
                            </optgroup>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 font-mono">TIMEFRAME</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-bold text-slate-900 text-[11px] outline-none">
                    </div>

                    <div>
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 font-mono">QUERY SEARCH</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="IP, İsim veya Email..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-bold text-slate-900 placeholder:text-slate-300 text-[11px] outline-none">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full py-3.5 bg-slate-900 text-white font-black rounded-2xl hover:bg-purple-600 transition-all uppercase tracking-widest text-[10px] shadow-xl shadow-slate-900/20 active:scale-[0.98]">
                            <i class="fa-solid fa-fingerprint mr-2 opacity-50"></i> TARAMAYI BAŞLAT
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Terminal-Style Action Center -->
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] font-mono whitespace-nowrap">TIMESTAMP</th>
                            <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] font-mono whitespace-nowrap">ACTOR / IDENTITY</th>
                            <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] font-mono whitespace-nowrap">EVENT / ACTION</th>
                            <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] font-mono whitespace-nowrap text-center">SOURCE IP</th>
                            <th class="px-4 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] font-mono whitespace-nowrap">AGENT</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition-all group">
                            <td class="px-8 py-5">
                                <div class="text-[11px] font-black text-slate-900 tracking-tighter">{{ $log->created_at->format('d.m.Y') }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-8 py-5">
                                @if($log->user)
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 text-[10px] font-black group-hover:bg-slate-900 group-hover:text-white transition-all">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-slate-800 group-hover:text-purple-600 transition-colors uppercase tracking-tight">{{ $log->user->name }}</p>
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $log->user->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] italic">SYSTEM PROCESS</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                <span class="inline-flex px-2 py-0.5 rounded-lg text-[8px] font-black border uppercase tracking-[0.15em] mb-2
                                    {{ $log->action_type == 'login' ? 'bg-emerald-50 text-emerald-600 border-emerald-100/50' : '' }}
                                    {{ $log->action_type == 'logout' ? 'bg-blue-50 text-blue-600 border-blue-100/50' : '' }}
                                    {{ $log->action_type == 'failed_login' ? 'bg-rose-50 text-rose-600 border-rose-100/50' : '' }}
                                    {{ !in_array($log->action_type, ['login', 'logout', 'failed_login']) ? 'bg-slate-50 text-slate-500 border-slate-200/50' : '' }}
                                ">
                                    {{ str_replace('_', ' ', $log->action_type) }}
                                </span>
                                <div class="text-[11px] font-bold text-slate-600 leading-relaxed uppercase tracking-tight">{{ $log->description }}</div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <code class="px-2.5 py-1 bg-slate-900 text-white rounded-lg text-[9px] font-mono font-black opacity-80 group-hover:opacity-100 transition-opacity">{{ $log->ip_address ?? '0.0.0.0' }}</code>
                            </td>
                            <td class="px-4 py-5 max-w-[200px]">
                                <div class="text-[9px] text-slate-400 font-bold truncate opacity-50 group-hover:opacity-100 transition-opacity uppercase tracking-widest" title="{{ $log->user_agent }}">
                                    {{ $log->user_agent }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-32 text-center">
                                <div class="inline-flex items-center justify-center w-20 h-20 rounded-[2rem] bg-slate-50 border border-slate-100 mb-6">
                                    <i class="fa-solid fa-fingerprint text-3xl text-slate-200"></i>
                                </div>
                                <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.4em]">LOG DATA NOT FOUND</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($logs->hasPages())
        <div class="pt-4">
            {{ $logs->links('vendor.pagination.console') }}
        </div>
        @endif

    </div>
</div>
@endsection
