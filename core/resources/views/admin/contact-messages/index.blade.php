@extends('layouts.app')

@section('title', 'CRM & Destek - Operasyon Paneli')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12">
    <div class="max-w-[1700px] mx-auto">
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/50 overflow-hidden flex flex-col h-[850px] animate-in fade-in slide-in-from-bottom-8 duration-700" x-data="{ sidePanel: true }">
            
            <!-- Header -->
            <div class="border-b border-slate-200 px-10 py-6 shrink-0 bg-white relative z-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-12">
                        <div>
                            <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition-colors">YÖNETİM</a>
                                <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                                <span>MÜŞTERİ DENEYİMİ (CRM)</span>
                            </nav>
                            <h1 class="text-3xl font-black text-slate-900 tracking-tight">İletişim <span class="text-purple-600">Merkezi</span></h1>
                        </div>

                        <div class="hidden lg:flex items-center gap-10 border-l border-slate-100 pl-10">
                            <div class="group cursor-default">
                                <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1.5 group-hover:text-amber-500 transition-colors">AKTİF TALEPLER</span>
                                <div class="flex items-center gap-2.5">
                                    <span class="text-2xl font-black text-slate-900 leading-none tracking-tighter">{{ $stats['pending'] }}</span>
                                    <span class="flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-amber-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="group cursor-default">
                                <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1.5 group-hover:text-purple-500 transition-colors">BUGÜN GELEN</span>
                                <span class="text-2xl font-black text-purple-600 leading-none tracking-tighter">{{ $stats['today'] }}</span>
                            </div>
                            <div class="group cursor-default">
                                <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1.5 group-hover:text-slate-600 transition-colors">ARŞİVLENMİŞ</span>
                                <span class="text-2xl font-black text-slate-400 leading-none tracking-tighter">{{ $stats['closed'] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center bg-slate-50 p-1 rounded-2xl border border-slate-200 shadow-inner">
                            <a href="{{ route('admin.contact-messages.index', ['status' => 'all']) }}" 
                                class="px-6 py-2.5 text-[9px] font-black rounded-xl transition-all uppercase tracking-widest {{ request('status', 'all') == 'all' ? 'bg-white text-slate-900 shadow-md ring-1 ring-slate-200' : 'text-slate-400 hover:text-slate-600' }}">İNDEKS</a>
                            <a href="{{ route('admin.contact-messages.index', ['status' => 'pending']) }}" 
                                class="px-6 py-2.5 text-[9px] font-black rounded-xl transition-all uppercase tracking-widest {{ request('status') == 'pending' ? 'bg-white text-amber-600 shadow-md ring-1 ring-slate-200' : 'text-slate-400 hover:text-slate-600' }}">BEKLEYEN</a>
                            <a href="{{ route('admin.contact-messages.index', ['status' => 'closed']) }}" 
                                class="px-6 py-2.5 text-[9px] font-black rounded-xl transition-all uppercase tracking-widest {{ request('status') == 'closed' ? 'bg-white text-emerald-600 shadow-md ring-1 ring-slate-200' : 'text-slate-400 hover:text-slate-600' }}">ARŞİV</a>
                        </div>
                        <button @click="sidePanel = !sidePanel" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-slate-200 text-slate-400 hover:text-purple-600 hover:border-purple-200 transition-all shadow-sm active:scale-95 group">
                            <i class="fa-solid fa-sidebar text-lg group-hover:rotate-12 transition-transform"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex-1 flex overflow-hidden">
                <!-- Message List -->
                <div class="w-80 lg:w-[420px] bg-white border-r border-slate-200 flex flex-col shrink-0 relative z-0">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/20">
                        <form action="{{ route('admin.contact-messages.index') }}" method="GET" class="relative group">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Müşteri veya konu ara..." 
                                class="w-full pl-12 pr-4 py-3.5 bg-white border border-slate-200 rounded-[1.25rem] text-[11px] font-black text-slate-900 placeholder:text-slate-300 focus:ring-4 focus:ring-purple-500/5 focus:border-purple-200 transition-all outline-none">
                            <i class="fa-solid fa-magnifying-glass absolute left-5 top-4.5 text-slate-400 text-xs group-focus-within:text-purple-600 transition-colors"></i>
                        </form>
                    </div>

                    <div class="flex-1 overflow-y-auto custom-scrollbar bg-white">
                        @forelse($messages as $msg)
                            <a href="{{ route('admin.contact-messages.index', array_merge(request()->query(), ['id' => $msg->id])) }}" 
                               class="group block p-7 border-b border-slate-50 transition-all relative overflow-hidden {{ $selectedMessage && $selectedMessage->id == $msg->id ? 'bg-slate-50 shadow-[inset_4px_0_0_0_#8B5CF6,inset_0_2px_15px_rgba(0,0,0,0.02)]' : 'hover:bg-slate-50/50' }}">
                                
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] text-slate-500 font-black uppercase group-hover:bg-slate-900 group-hover:text-white transition-all duration-300">
                                            {{ substr($msg->name, 0, 1) }}
                                        </div>
                                        <span class="text-[11px] font-black text-slate-900 tracking-tight truncate max-w-[180px]">{{ $msg->name }}</span>
                                    </div>
                                    <span class="text-[8px] font-black text-slate-300 group-hover:text-slate-400 uppercase tracking-widest shrink-0 mt-1">{{ $msg->created_at->diffForHumans(null, true) }}</span>
                                </div>
                                
                                <div class="text-[10px] font-black text-slate-600 tracking-tight truncate mb-2 group-hover:text-purple-600 transition-colors">{{ $msg->subject }}</div>
                                <p class="text-[10px] text-slate-400 line-clamp-2 leading-relaxed mb-4 font-medium opacity-80">{{ $msg->message }}</p>
                                
                                <div class="flex items-center justify-between pointer-events-none">
                                    <div class="flex gap-1.5">
                                        @if($msg->status == 'closed')
                                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-400 text-[7px] font-black uppercase tracking-widest border border-slate-200/50">ARŞİV</span>
                                        @elseif($msg->replied_at)
                                            <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-600 text-[7px] font-black uppercase tracking-widest border border-emerald-100">YANITLANDI</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-600 text-[7px] font-black uppercase tracking-widest border border-amber-100 animate-pulse">BEKLİYOR</span>
                                        @endif
                                        
                                        @if($msg->priority == 'urgent' || $msg->priority == 'high')
                                            <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-500 text-[7px] font-black uppercase tracking-widest border border-rose-100">KRİTİK</span>
                                        @endif
                                    </div>
                                    @if(!$msg->replied_at && $msg->status != 'closed')
                                        <div class="w-1.5 h-1.5 rounded-full bg-purple-600 shadow-sm shadow-purple-600/50"></div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="p-20 text-center opacity-10">
                                <i class="fa-solid fa-inbox text-6xl mb-4"></i>
                                <div class="text-[9px] font-black uppercase tracking-[0.3em]">MESAJ KUTUSU BOŞ</div>
                            </div>
                        @endforelse
                        
                        <div class="p-6">
                            {{ $messages->appends(request()->query())->links('vendor.pagination.simple-tailwind') }}
                        </div>
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="flex-1 flex flex-col bg-white overflow-hidden relative">
                    @if($selectedMessage)
                        <div class="px-10 py-8 border-b border-slate-100 flex items-center justify-between bg-white relative z-10 shadow-sm">
                            <div class="flex items-center gap-6">
                                <div class="w-14 h-14 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center text-white font-black text-2xl shadow-lg ring-4 ring-slate-50">
                                    {{ substr($selectedMessage->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-3 mb-1.5">
                                        <h2 class="text-xl font-black text-slate-900 leading-none tracking-tight">{{ $selectedMessage->name }}</h2>
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-400 text-[8px] font-black rounded-md tracking-widest">ID #{{ $selectedMessage->id }}</span>
                                    </div>
                                    <div class="flex items-center gap-4 text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                        <a href="mailto:{{ $selectedMessage->email }}" class="flex items-center gap-1.5 hover:text-purple-600 transition-colors">
                                            <i class="fa-solid fa-envelope-open text-[9px] opacity-50"></i>
                                            {{ $selectedMessage->email }}
                                        </a>
                                        <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                                        <span class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-clock-rotate-left text-[9px] opacity-50"></i>
                                            {{ $selectedMessage->created_at->format('H:i') }} GELİŞ
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                @if($selectedMessage->status != 'closed')
                                    <form action="{{ route('admin.contact-messages.close', $selectedMessage->id) }}" method="POST">
                                        @csrf
                                        <button class="px-8 py-3.5 bg-slate-900 hover:bg-purple-600 text-white font-black text-[10px] rounded-2xl transition-all uppercase tracking-[0.15em] flex items-center gap-2.5 shadow-xl shadow-slate-900/10 active:scale-95">
                                            <i class="fa-solid fa-check-double text-xs opacity-50"></i>
                                            TALEBİ ARŞİVLE
                                        </button>
                                    </form>
                                @else
                                    <div class="px-8 py-3.5 bg-emerald-50 text-emerald-600 font-black text-[10px] rounded-2xl border border-emerald-100 uppercase tracking-[0.15em] flex items-center gap-2.5">
                                        <i class="fa-solid fa-box-archive text-xs"></i>
                                        ARŞİVLENMİŞ GÖRÜŞME
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div id="chat-feed" class="flex-1 overflow-y-auto p-12 space-y-10 bg-[#F9FBFF]/30 custom-scrollbar scroll-smooth">
                            <div class="flex items-center justify-center gap-6 opacity-20">
                                <div class="h-px bg-slate-900 flex-1"></div>
                                <span class="text-[9px] font-black uppercase tracking-[0.4em] text-slate-900">{{ $selectedMessage->created_at->format('d F Y') }}</span>
                                <div class="h-px bg-slate-900 flex-1"></div>
                            </div>

                            <!-- Initial Message -->
                            <div class="flex items-start gap-5 animate-in slide-in-from-left-4 duration-500">
                                <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm relative group overflow-hidden">
                                    <div class="absolute inset-0 bg-slate-900 scale-0 group-hover:scale-110 transition-transform duration-500 opacity-0 group-hover:opacity-100"></div>
                                    <i class="fa-solid fa-user-tie text-slate-300 group-hover:text-white transition-colors relative z-10"></i>
                                </div>
                                <div class="max-w-3xl bg-white p-8 rounded-[2rem] rounded-tl-none border border-slate-100 shadow-xl shadow-slate-200/20 relative group">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="text-[12px] font-black text-purple-600 uppercase tracking-widest leading-none">{{ $selectedMessage->subject }}</div>
                                        <div class="text-[9px] font-black text-slate-300 uppercase tracking-widest">{{ $selectedMessage->created_at->format('H:i') }}</div>
                                    </div>
                                    <div class="text-[13px] text-slate-600 leading-relaxed whitespace-pre-wrap font-medium">{{ $selectedMessage->message }}</div>
                                </div>
                            </div>

                            @foreach($selectedMessage->replies as $reply)
                                <div class="flex items-start gap-5 {{ $reply->is_admin ? 'flex-row-reverse animate-in slide-in-from-right-4' : 'animate-in slide-in-from-left-4' }} duration-500">
                                    <div class="w-12 h-12 rounded-2xl {{ $reply->is_admin ? 'bg-purple-600 shadow-purple-600/20' : 'bg-white border border-slate-200' }} border flex items-center justify-center shrink-0 shadow-lg {{ $reply->is_admin ? 'border-purple-500' : 'border-slate-100' }}">
                                        <i class="fa-solid {{ $reply->is_admin ? 'fa-shield-halved text-white' : 'fa-user text-slate-300' }} text-lg"></i>
                                    </div>
                                    <div class="max-w-3xl {{ $reply->is_admin ? 'bg-purple-600 text-white border-purple-500 rounded-tr-none shadow-purple-600/10' : 'bg-white text-slate-600 border-slate-100 rounded-tl-none shadow-slate-200/20' }} p-8 rounded-[2rem] border shadow-xl flex flex-col group">
                                        <div class="flex items-center justify-between mb-3 {{ $reply->is_admin ? 'text-white/60' : 'text-slate-400' }}">
                                            <div class="text-[11px] font-black uppercase tracking-widest leading-none">{{ $reply->is_admin ? 'DESTEK DİREKTÖRLÜĞÜ' : $selectedMessage->name }}</div>
                                            <div class="text-[9px] font-black uppercase tracking-widest">{{ $reply->created_at->format('H:i') }}</div>
                                        </div>
                                        <div class="text-[13px] leading-relaxed whitespace-pre-wrap font-medium overflow-hidden">{{ $reply->message }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($selectedMessage->status != 'closed')
                            <div class="px-10 py-10 border-t border-slate-100 bg-white relative z-10 shadow-[0_-10px_30px_rgba(0,0,0,0.02)]">
                                <form action="{{ route('admin.contact-messages.reply', $selectedMessage->id) }}" method="POST" x-data="{ sending: false }" @submit="sending = true">
                                    @csrf
                                    <div class="space-y-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <i class="fa-solid fa-flag text-[10px] text-purple-600"></i>
                                                <select name="priority" class="bg-transparent text-[10px] font-black uppercase tracking-[0.15em] outline-none focus:text-purple-600 transition-colors cursor-pointer border-none p-0 ring-0">
                                                    <option value="low" {{ $selectedMessage->priority == 'low' ? 'selected' : '' }}>Düşük Öncelik</option>
                                                    <option value="normal" {{ $selectedMessage->priority == 'normal' || !$selectedMessage->priority ? 'selected' : '' }}>Standart Prosedür</option>
                                                    <option value="high" {{ $selectedMessage->priority == 'high' ? 'selected' : '' }}>Yüksek Öncelik</option>
                                                    <option value="urgent" {{ $selectedMessage->priority == 'urgent' ? 'selected' : '' }}>KRİTİK / ACİL</option>
                                                </select>
                                            </div>
                                            <div class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Shift + Enter ile hızlı gönderim</div>
                                        </div>
                                        <div class="relative">
                                            <textarea name="reply" rows="5" 
                                                class="w-full p-8 bg-slate-50 border border-slate-200 rounded-[2rem] focus:bg-white focus:border-purple-500 focus:ring-8 focus:ring-purple-500/5 transition-all resize-none text-[13px] text-slate-800 font-medium outline-none placeholder:text-slate-300 shadow-inner" 
                                                placeholder="Resmi ve çözüm odaklı bir yanıt oluşturun..." required></textarea>
                                            <div class="absolute right-6 bottom-6 flex items-center gap-4">
                                                <button type="submit" :disabled="sending" class="px-10 py-4 bg-slate-900 hover:bg-purple-600 disabled:bg-slate-400 text-white font-black rounded-2xl transition-all shadow-2xl shadow-slate-900/20 active:scale-95 text-[11px] uppercase tracking-[0.2em] flex items-center gap-3">
                                                    <span x-text="sending ? 'İletiliyor...' : 'Gönder'">Yanıtla (Gönder)</span>
                                                    <i class="fa-solid fa-paper-plane text-xs opacity-50" :class="sending ? 'animate-ping' : ''"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="p-16 border-t border-slate-100 bg-slate-50/40 text-center flex flex-col items-center justify-center animate-in fade-in duration-500">
                                <div class="w-16 h-16 rounded-full bg-white shadow-xl flex items-center justify-center text-slate-200 mb-6 border border-slate-100">
                                    <i class="fa-solid fa-lock text-2xl"></i>
                                </div>
                                <h3 class="text-sm font-black text-slate-400 uppercase tracking-[0.3em] mb-2">OTURUM SONLANDIRILDI</h3>
                                <p class="text-[10px] text-slate-300 font-bold uppercase tracking-widest italic leading-relaxed">Bu görüşme arşive kaldırılmıştır. Yeni bir mesaj gelene kadar yanıt verilemez.</p>
                            </div>
                        @endif
                    @else
                        <div class="flex-1 flex flex-col items-center justify-center p-20 text-center bg-[#F9FBFF]/30">
                            <div class="relative mb-10">
                                <div class="absolute -inset-10 bg-purple-600/5 rounded-full blur-3xl animate-pulse"></div>
                                <i class="fa-solid fa-tower-broadcast text-7xl text-slate-100 relative"></i>
                            </div>
                            <h2 class="text-2xl font-black text-slate-900 mb-3 tracking-tight">İletişim Paneli</h2>
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-[280px] leading-relaxed">Sol listeden bir müşteri talebi seçerek operasyona başlayın.</p>
                        </div>
                    @endif
                </div>

                <!-- Right Panel -->
                <div class="w-80 lg:w-[360px] bg-[#FDFEFE] border-l border-slate-200 overflow-y-auto shrink-0 custom-scrollbar relative z-20" x-show="sidePanel" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
                    @if($selectedMessage)
                        <div class="p-8 space-y-12">
                            <section>
                                <div class="flex items-center justify-between mb-5">
                                    <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.25em]">OPERASYONEL NOTLAR</h3>
                                    <i class="fa-solid fa-note-sticky text-[10px] text-purple-600/30"></i>
                                </div>
                                <form action="{{ route('admin.contact-messages.notes', $selectedMessage->id) }}" method="POST">
                                    @csrf
                                    <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-xl shadow-slate-200/20">
                                        <textarea name="admin_notes" rows="4" 
                                            class="w-full text-[11px] font-bold text-slate-600 bg-slate-50 p-4 rounded-xl border-none outline-none focus:ring-2 focus:ring-purple-500/10 placeholder:text-slate-200 mb-4 transition-all resize-none" 
                                            placeholder="Diğer moderatörler için notlar...">{{ $selectedMessage->admin_notes }}</textarea>
                                        <button class="w-full py-3 bg-slate-900 text-white text-[10px] font-black rounded-2xl hover:bg-purple-600 transition-all uppercase tracking-[0.2em] shadow-lg shadow-slate-900/10">NOTU GÜNCELLE</button>
                                    </div>
                                </form>
                            </section>

                            <section>
                                <div class="flex items-center justify-between mb-5">
                                    <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.25em]">CİHAZ & ÜYE PROFİLİ</h3>
                                    <i class="fa-solid fa-fingerprint text-[10px] text-purple-600/30"></i>
                                </div>
                                @if($selectedMessage->user)
                                    <div class="bg-slate-900 rounded-[2rem] p-7 text-white shadow-2xl shadow-slate-900/30 relative overflow-hidden group">
                                        <div class="absolute -right-10 -bottom-10 opacity-10 group-hover:scale-110 transition-transform duration-700">
                                            <i class="fa-solid fa-id-card-clip text-9xl"></i>
                                        </div>
                                        <div class="flex items-center gap-4 mb-8">
                                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center font-black text-lg shadow-xl shadow-purple-500/20">
                                                {{ $selectedMessage->user->initials }}
                                            </div>
                                            <div>
                                                <div class="text-[13px] font-black text-white leading-tight mb-1">{{ $selectedMessage->user->name }}</div>
                                                <div class="inline-flex items-center px-2 py-0.5 bg-purple-500/20 text-purple-400 text-[8px] font-black rounded uppercase tracking-widest border border-purple-500/30">{{ $selectedMessage->user->role }}</div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-6 relative z-10">
                                            <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                                                <div class="text-[8px] font-black text-white/40 uppercase tracking-widest mb-1.5 font-mono">REZERVASYON</div>
                                                <div class="text-base font-black text-white tracking-tighter">{{ $selectedMessage->user->reservations_count }}</div>
                                            </div>
                                            <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                                                <div class="text-[8px] font-black text-white/40 uppercase tracking-widest mb-1.5 font-mono">CÜZDAN</div>
                                                <div class="text-base font-black text-white tracking-tighter">₺{{ number_format($selectedMessage->user->balance, 0) }}</div>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.users.edit', $selectedMessage->user_id) }}" class="mt-8 block w-full py-3.5 bg-white text-slate-900 text-center text-[10px] font-black rounded-2xl hover:bg-purple-600 hover:text-white transition-all uppercase tracking-[0.2em] shadow-lg relative z-10">PROFİLİ YÖNET</a>
                                    </div>
                                @else
                                    <div class="p-10 rounded-[2rem] bg-white border border-slate-100 text-center shadow-xl shadow-slate-200/10 group">
                                        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4 border border-slate-100 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-user-secret text-slate-200 text-3xl"></i>
                                        </div>
                                        <div class="text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">KAYITSIZ MÜŞTERİ</div>
                                        <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest opacity-60">Misafir oturumu ile ulaşıldı.</div>
                                    </div>
                                @endif
                            </section>

                            <section>
                                <div class="flex items-center justify-between mb-5">
                                    <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.25em]">İLETİŞİM GEÇMİŞİ</h3>
                                    <i class="fa-solid fa-history text-[10px] text-purple-600/30"></i>
                                </div>
                                <div class="space-y-4">
                                    @forelse($history as $h)
                                        <a href="{{ route('admin.contact-messages.index', ['id' => $h->id]) }}" class="block group relative">
                                            <div class="bg-white p-4 rounded-2xl border border-slate-100 hover:border-purple-200 transition-all shadow-sm hover:shadow-lg hover:shadow-purple-600/5">
                                                <div class="flex justify-between items-center mb-2">
                                                    <span class="text-[8px] font-black text-slate-400 group-hover:text-purple-600 uppercase tracking-widest transition-colors">{{ $h->created_at->format('d M Y') }}</span>
                                                    <div class="flex h-1.5 w-1.5">
                                                        <div class="relative inline-flex rounded-full h-1.5 w-1.5 {{ $h->status == 'closed' ? 'bg-slate-300' : 'bg-emerald-500' }}"></div>
                                                    </div>
                                                </div>
                                                <div class="text-[10px] font-black text-slate-700 truncate tracking-tight">{{ $h->subject }}</div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="text-[9px] text-slate-300 font-bold uppercase tracking-[0.2em] text-center p-8 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200 italic">Kayıt Bulunamadı</div>
                                    @endforelse
                                </div>
                            </section>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; transition: all 0.3s; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #8B5CF6; }
    
    .pagination { @apply flex items-center justify-center gap-1.5; }
    .page-item .page-link { 
        @apply w-8 h-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 text-[9px] font-black transition-all hover:bg-slate-50 hover:text-slate-900;
    }
    .page-item.active .page-link {
        @apply bg-purple-600 border-purple-600 text-white shadow-lg shadow-purple-600/20;
    }
    .page-item.disabled .page-link { @apply opacity-30 cursor-not-allowed; }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const feed = document.getElementById('chat-feed');
        if (feed) {
            feed.scrollTop = feed.scrollHeight;
            const images = feed.getElementsByTagName('img');
            if (images.length > 0) {
                let loaded = 0;
                Array.from(images).forEach(img => {
                    img.addEventListener('load', () => {
                        loaded++;
                        if (loaded === images.length) feed.scrollTop = feed.scrollHeight;
                    });
                });
            }
        }
    });

    // Handle smooth transitions between messages
    document.body.addEventListener('htmx:afterOnLoad', function() {
        const feed = document.getElementById('chat-feed');
        if (feed) feed.scrollTop = feed.scrollHeight;
    });
</script>
@endsection
