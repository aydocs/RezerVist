@extends('layouts.app')

@section('title', 'Support Intelligence Dashboard - Yönetim Paneli')

@section('content')
<div class="max-w-[1700px] mx-auto px-4 sm:px-6 lg:px-12 py-10 font-outfit">
    <div class="bg-white/80 backdrop-blur-3xl rounded-[48px] border border-white/40 shadow-[0_40px_100px_-20px_rgba(0,0,0,0.1)] overflow-hidden flex flex-col h-[850px] relative" x-data="{ sidePanel: true }">

    <!-- Top Intelligence Bar: Premium Command Center -->
    <div class="bg-white/90 backdrop-blur-md border-b border-gray-200 px-8 py-5 shrink-0 shadow-sm z-20">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-12">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tighter flex items-center gap-4">
                        <div class="relative">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-600 to-indigo-900 flex items-center justify-center text-white shadow-2xl shadow-indigo-100 border border-indigo-400/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            </div>
                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full shadow-sm"></div>
                        </div>
                        <div class="flex flex-col">
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-indigo-700 leading-none">Destek Merkezi</span>
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1">Müşterİ İlişkİlerİ</span>
                        </div>
                    </h1>
                </div>
                
                <div class="h-10 w-px bg-gray-100 mx-2"></div>
                
                <!-- Command Center Metrics -->
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 shadow-[inset_0_0_20px_rgba(245,158,11,0.1)] border border-amber-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-amber-500/60 uppercase tracking-widest leading-none mb-1">Bekleyen Görev</span>
                            <div class="flex items-center gap-2">
                                <span class="text-2xl font-black text-amber-600 tracking-tighter leading-none">{{ $stats['pending'] }}</span>
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                            </div>
                        </div>
                    </div>

                    <div class="h-8 w-px bg-gray-100"></div>

                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500 shadow-[inset_0_0_20px_rgba(79,70,229,0.1)] border border-indigo-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-indigo-500/60 uppercase tracking-widest leading-none mb-1">Bugünkü Akış</span>
                            <span class="text-2xl font-black text-indigo-700 tracking-tighter leading-none">{{ $stats['today'] }}</span>
                        </div>
                    </div>

                    <div class="h-8 w-px bg-gray-100"></div>

                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-500 shadow-[inset_0_0_20px_rgba(34,197,94,0.1)] border border-green-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-green-500/60 uppercase tracking-widest leading-none mb-1">Kapatılan</span>
                            <span class="text-2xl font-black text-green-600 tracking-tighter leading-none">{{ $stats['closed'] }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center bg-gray-100 rounded-xl p-1.5 border border-gray-200 shadow-inner">
                    <a href="{{ route('admin.contact-messages.index', ['status' => 'all']) }}" 
                        class="px-6 py-2.5 text-[10px] font-black rounded-lg transition-all {{ request('status', 'all') == 'all' ? 'bg-white text-indigo-600 shadow-md ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}">TÜMÜ</a>
                    <a href="{{ route('admin.contact-messages.index', ['status' => 'pending']) }}" 
                        class="px-6 py-2.5 text-[10px] font-black rounded-lg transition-all {{ request('status') == 'pending' ? 'bg-white text-amber-600 shadow-md ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}">BEKLEYEN</a>
                    <a href="{{ route('admin.contact-messages.index', ['status' => 'closed']) }}" 
                        class="px-6 py-2.5 text-[10px] font-black rounded-lg transition-all {{ request('status') == 'closed' ? 'bg-white text-green-600 shadow-md ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}">ARŞİV</a>
                </div>
                <button @click="sidePanel = !sidePanel" class="p-3 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-indigo-600 hover:shadow-xl transition-all active:scale-95 group relative">
                    <svg class="w-6 h-6 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                </button>
            </div>
        </div>
    </div>


    <!-- Main Workspace -->
    <div class="flex-1 flex overflow-hidden">
        <!-- Sidebar: Intelligent Inbox -->
        <div class="w-80 lg:w-[400px] bg-white border-r border-gray-200 flex flex-col shrink-0">
            <div class="p-6 border-b border-gray-100 bg-white">
                <form action="{{ route('admin.contact-messages.index') }}" method="GET" class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Müşteri ara..." 
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all outline-none font-medium">
                    <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3.5 group-focus-within:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </form>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar bg-gray-50/20">
                @if($messages->isEmpty())
                    <div class="flex flex-col items-center justify-center p-12 text-center opacity-40">
                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        <p class="text-sm font-bold uppercase tracking-widest">Kayıt Bulunamadı</p>
                    </div>
                @else
                    @foreach($messages as $msg)
                        <a href="{{ route('admin.contact-messages.index', array_merge(request()->query(), ['id' => $msg->id])) }}" 
                           class="group block p-6 hover:bg-white border-b border-gray-100 transition-all relative {{ $selectedMessage && $selectedMessage->id == $msg->id ? 'bg-white shadow-[inset_4px_0_0_#4f46e5]' : '' }}">
                            
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-black text-gray-900 group-hover:text-indigo-600 transition truncate pr-4">{{ $msg->name }}</span>
                                <span class="text-[10px] font-bold text-gray-400 shrink-0">{{ $msg->created_at->diffForHumans(null, true) }}</span>
                            </div>
                            
                            <div class="flex items-center gap-2 mb-2">
                                <h4 class="text-[11px] font-bold text-indigo-600/80 truncate max-w-full">{{ $msg->subject }}</h4>
                            </div>

                            <p class="text-[11px] text-gray-500 line-clamp-2 leading-relaxed mb-4 min-h-[32px]">{{ $msg->message }}</p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    @if($msg->status == 'closed')
                                        <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-400 text-[9px] font-black uppercase tracking-wider">ARŞİV</span>
                                    @elseif($msg->replied_at)
                                        <span class="px-2 py-0.5 rounded-md bg-green-50 text-green-600 text-[9px] font-black uppercase tracking-wider border border-green-100">YANITLANDI</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-600 text-[9px] font-black uppercase tracking-wider border border-amber-100">BEKLEMEDE</span>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    @if($msg->priority == 'urgent' || $msg->priority == 'high')
                                        <div class="w-5 h-5 rounded-full bg-red-50 border-2 border-white flex items-center justify-center -mr-1 z-10 shadow-sm">
                                            <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    @endif
                                    <div class="w-7 h-7 rounded-full bg-indigo-50 border-2 border-white flex items-center justify-center text-[10px] text-indigo-600 font-black shadow-sm uppercase">
                                        {{ substr($msg->name, 0, 1) }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                    <div class="p-6">
                        {{ $messages->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Detail Area: The Chat Engine -->
        <div class="flex-1 flex flex-col bg-white overflow-hidden relative shadow-2xl">
            @if($selectedMessage)
                <!-- Detailed Chat Header -->
                <div class="px-10 py-6 border-b border-gray-100 flex items-center justify-between bg-white z-10">
                    <div class="flex items-center gap-5">
                        <div class="relative">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-indigo-100 transition-all duration-300">
                                {{ substr($selectedMessage->name, 0, 1) }}
                            </div>
                            @if($selectedMessage->user_id)
                                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full flex items-center justify-center shadow-sm" title="Üye Müşteri">
                                    <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h2 class="text-lg font-black text-gray-900 leading-none">{{ $selectedMessage->name }}</h2>
                                <span class="px-2 py-0.5 rounded text-[9px] font-black {{ $selectedMessage->priority == 'urgent' ? 'bg-red-50 text-red-500 border border-red-100' : ($selectedMessage->priority == 'high' ? 'bg-orange-50 text-orange-500 border border-orange-100' : 'bg-gray-50 text-gray-400 border border-gray-100') }} uppercase tracking-tighter">
                                    {{ strtoupper($selectedMessage->priority) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-sm text-gray-400">
                                <a href="mailto:{{ $selectedMessage->email }}" class="hover:text-indigo-600 transition flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    {{ $selectedMessage->email }}
                                </a>
                                <span class="w-1 h-1 rounded-full bg-gray-200"></span>
                                <span class="flex items-center gap-1 font-bold text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                                    ID: #{{ str_pad($selectedMessage->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        @if($selectedMessage->status != 'closed')
                            <form id="close-ticket-form" action="{{ route('admin.contact-messages.close', $selectedMessage->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white font-black text-[10px] rounded-xl hover:bg-black transition shadow-lg shadow-gray-200 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    Talebİ Kapat
                                </button>
                            </form>

                        @else
                            <div class="px-6 py-2.5 bg-green-50 text-green-600 font-bold text-[10px] rounded-xl border border-green-100 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                ARŞİVLENMİŞ
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Chat Feed -->
                <div id="chat-feed" class="flex-1 overflow-y-auto p-12 space-y-12 bg-gray-50/20 custom-scrollbar scroll-smooth">

                    <!-- Date Separator -->
                    <div class="flex items-center justify-center gap-4 opacity-30">
                        <div class="h-px bg-gray-300 flex-1"></div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] whitespace-nowrap">{{ $selectedMessage->created_at->format('d F Y') }}</span>
                        <div class="h-px bg-gray-300 flex-1"></div>
                    </div>

                    <!-- Initial Client Message Bubble -->
                    <div class="flex items-start gap-6 group">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center shrink-0 border border-indigo-100">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div class="flex-1 max-w-2xl">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-[10px] font-black text-gray-900 tracking-wider uppercase underline decoration-indigo-200 decoration-2 underline-offset-4">{{ $selectedMessage->name }}</span>
                                <span class="text-[10px] text-gray-300">{{ $selectedMessage->created_at->format('H:i') }}</span>
                            </div>
                            <div class="bg-white p-7 rounded-[32px] rounded-tl-none shadow-[20px_20px_40px_rgba(0,0,0,0.02)] border border-indigo-50 ring-8 ring-transparent group-hover:ring-indigo-50/30 transition-all duration-500">
                                <div class="text-indigo-600 font-black text-sm mb-4 pb-2 border-b border-indigo-50 uppercase tracking-tight">{{ $selectedMessage->subject }}</div>
                                <div class="text-[15px] text-gray-700 leading-[1.8] whitespace-pre-wrap font-medium">{{ $selectedMessage->message }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Threaded Replies -->
                    @foreach($selectedMessage->replies as $reply)
                        @if($reply->is_admin)
                            <!-- Admin Expert Bubble -->
                            <div class="flex items-start gap-6 flex-row-reverse group">
                                <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center shrink-0 shadow-xl shadow-gray-200 border-4 border-white">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </div>
                                <div class="flex-1 flex flex-col items-end max-w-2xl">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-[10px] text-gray-300">{{ $reply->created_at->format('H:i') }}</span>
                                        <span class="text-[10px] font-black text-indigo-600 tracking-wider uppercase">DESTEK UZMANI</span>
                                    </div>
                                    <div class="bg-indigo-600 p-7 rounded-[32px] rounded-tr-none shadow-[20px_20px_40px_rgba(79,70,229,0.1)] group-hover:bg-indigo-700 transition-all duration-500">
                                        <div class="text-[15px] text-white leading-[1.8] whitespace-pre-wrap font-medium">{{ $reply->message }}</div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- User Reply Bubble (Customer) -->
                            <div class="flex items-start gap-6 group">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center shrink-0 border border-indigo-100">
                                    @if($selectedMessage->user)
                                         <div class="text-xs font-black text-indigo-600">{{ $selectedMessage->user->initials }}</div>
                                    @else
                                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    @endif
                                </div>
                                <div class="flex-1 max-w-2xl">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-[10px] font-black text-gray-900 tracking-wider uppercase">{{ $selectedMessage->name }}</span>
                                        <span class="text-[10px] text-gray-300">{{ $reply->created_at->format('H:i') }}</span>
                                    </div>
                                    <div class="bg-white p-7 rounded-[32px] rounded-tl-none shadow-[20px_20px_40px_rgba(0,0,0,0.02)] border border-indigo-50 ring-8 ring-transparent group-hover:ring-indigo-50/30 transition-all duration-500">
                                        <div class="text-[15px] text-gray-700 leading-[1.8] whitespace-pre-wrap font-medium">{{ $reply->message }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Reply Composer Terminal -->
                @if($selectedMessage->status != 'closed')
                    <div id="reply-composer" class="p-10 border-t border-gray-100 bg-white">
                        <form id="reply-form" action="{{ route('admin.contact-messages.reply', $selectedMessage->id) }}" method="POST" class="space-y-6">

                            @csrf
                            <div class="space-y-4">
                                <!-- Fast Actions Toggle -->
                                <div class="flex items-center gap-4">
                                    <div class="flex-1 flex items-center gap-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Yanıt Öncelİğİ:</label>
                                        <select name="priority" class="px-3 py-1 bg-gray-50 border-gray-200 rounded-lg text-[10px] font-bold outline-none focus:border-indigo-400 transition cursor-pointer">
                                            <option value="low" {{ $selectedMessage->priority == 'low' ? 'selected' : '' }}>DÜŞÜK</option>
                                            <option value="normal" {{ $selectedMessage->priority == 'normal' ? 'selected' : '' }}>NORMAL</option>
                                            <option value="high" {{ $selectedMessage->priority == 'high' ? 'selected' : '' }}>YÜKSEK</option>
                                            <option value="urgent" {{ $selectedMessage->priority == 'urgent' ? 'selected' : '' }}>ACİL 🚨</option>
                                        </select>
                                    </div>
                                    <div class="h-4 w-px bg-gray-100"></div>
                                    <button type="button" class="text-[10px] font-black text-indigo-500 hover:text-indigo-700 transition uppercase tracking-widest">Hazır Yanıtlar ⚡</button>
                                </div>
                                
                                <div class="relative group">
                                    <textarea name="reply" id="reply_area" rows="6" 
                                        class="w-full p-8 bg-gray-50 border-2 {{ $errors->has('reply') ? 'border-red-300 ring-4 ring-red-500/5' : 'border-gray-100' }} rounded-[32px] focus:bg-white focus:ring-8 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all resize-none text-[15px] text-gray-800 leading-relaxed font-medium outline-none" 
                                        placeholder="Kullanıcıya özel çözüm önerinizi yazın..." required minlength="10">{{ old('reply') }}</textarea>
                                    @error('reply')
                                        <div class="px-6 py-2 mt-2">
                                            <p class="text-[10px] font-black text-red-500 uppercase flex items-center gap-2">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                {{ $message }}
                                            </p>
                                        </div>
                                    @enderror
                                    
                                    <div class="absolute right-6 bottom-6 flex items-center gap-4">
                                        <div class="text-[10px] font-bold text-gray-400 mr-2 opacity-0 group-focus-within:opacity-100 transition">Enter + Ctrl ile gönder</div>
                                        <button type="submit" class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl transition shadow-2xl shadow-indigo-600/40 flex items-center gap-3 active:scale-[0.97]">
                                            <span>YANITLA & BİLDİR</span>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @elseif($selectedMessage->status == 'closed')
                    <div class="p-10 border-t border-gray-100 bg-gray-50/50 flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 mb-4 scale-125">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">TALEP KAPATILDI</h4>
                        <p class="text-xs text-gray-400 mt-2 max-w-xs leading-relaxed">Bu görüşme sonuçlandırılmış ve arşive kaldırılmıştır. Yeni bir yanıt gönderilemez.</p>
                    </div>
                @endif
            @else
                <!-- Zen Mode Selection -->
                <div class="flex-1 flex flex-col items-center justify-center p-20 text-center bg-white">
                    <div class="relative mb-12">
                        <div class="w-48 h-48 bg-indigo-50/50 rounded-full flex items-center justify-center animate-pulse duration-[3000ms]">
                            <svg class="w-24 h-24 text-indigo-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 mb-4 tracking-tighter">İletişime Başla</h2>
                    <p class="text-gray-400 max-w-md mx-auto leading-loose text-sm font-medium">
                        Bekleyen destek taleplerini çözüme kavuşturmak için sol taraftan bir müşteri seçin. Kullanıcı geçmişi ve tüm detaylar otomatik yüklenecektir.
                    </p>
                </div>
            @endif
        </div>

        <!-- Right Side: Intelligence & History Panel -->
        <div class="w-80 lg:w-[350px] bg-gray-50 border-l border-gray-200 overflow-y-auto shrink-0 transition-all duration-700" 
             x-show="sidePanel" x-transition:enter="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="translate-x-0" x-transition:leave-end="translate-x-full">
            @if($selectedMessage)
                <div class="p-8 space-y-12">
                    <!-- Internal Context Panel -->
                    <section>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                            Admin Notları
                        </h3>
                        <form id="notes-form" action="{{ route('admin.contact-messages.notes', $selectedMessage->id) }}" method="POST">
                            @csrf

                            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-[0_10px_30px_rgba(0,0,0,0.03)] space-y-4">
                                <textarea name="admin_notes" rows="4" 
                                    class="w-full text-xs font-bold text-gray-600 bg-gray-50 p-4 rounded-xl border-dashed border-2 border-gray-100 outline-none focus:border-indigo-400 transition" 
                                    placeholder="Buraya kendin için notlar alabilirsin... (Kullanıcı görmez)">{{ $selectedMessage->admin_notes }}</textarea>
                                <div class="flex items-center justify-between gap-3 pt-2">
                                    <select name="priority" class="bg-gray-50 border-none text-[10px] font-black rounded-lg px-2 py-1 outline-none text-gray-500 cursor-pointer">
                                        <option value="low" {{ $selectedMessage->priority == 'low' ? 'selected' : '' }}>DÜŞÜK</option>
                                        <option value="normal" {{ $selectedMessage->priority == 'normal' ? 'selected' : '' }}>NORMAL</option>
                                        <option value="high" {{ $selectedMessage->priority == 'high' ? 'selected' : '' }}>YÜKSEK</option>
                                        <option value="urgent" {{ $selectedMessage->priority == 'urgent' ? 'selected' : '' }}>ACİL</option>
                                    </select>
                                    <button type="submit" class="px-4 py-1.5 bg-gray-900 text-white text-[10px] font-black rounded-lg hover:bg-indigo-600 transition shadow-lg shadow-gray-200">KAYDET</button>
                                </div>
                            </div>
                        </form>
                    </section>

                    <!-- Profile Intelligence -->
                    <section>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                            Müşterİ Profİlİ
                        </h3>
                        @if($selectedMessage->user)
                            <div class="bg-indigo-600 rounded-[32px] p-7 text-white shadow-2xl shadow-indigo-100 relative overflow-hidden group">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-[1.5] transition-transform duration-1000"></div>
                                <div class="relative z-10 flex items-center gap-4 mb-8">
                                    <div class="relative">
                                        <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-xl font-black">{{ $selectedMessage->user->initials }}</div>
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-white rounded-full flex items-center justify-center shadow-lg">
                                            <svg class="w-2.5 h-2.5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-base font-black leading-tight">{{ $selectedMessage->user->name }}</div>
                                        <div class="text-[9px] font-black text-indigo-200 uppercase tracking-[0.2em] mt-0.5">{{ $selectedMessage->user->role }}</div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-6 relative z-10 pt-6 border-t border-white/10">
                                    <div>
                                        <div class="text-[9px] font-black text-indigo-100 uppercase mb-1 opacity-60">Rezervasyon</div>
                                        <div class="text-xl font-black">{{ $selectedMessage->user->reservations_count }}</div>
                                    </div>
                                    <div>
                                        <div class="text-[9px] font-black text-indigo-100 uppercase mb-1 opacity-60">Bakiye</div>
                                        <div class="text-xl font-black">{{ number_format($selectedMessage->user->balance, 2) }}₺</div>
                                    </div>
                                </div>
                                <a href="{{ route('admin.users.edit', $selectedMessage->user_id) }}" class="mt-8 block w-full py-3 bg-white text-indigo-600 text-center text-[10px] font-black rounded-2xl hover:bg-gray-50 transition shadow-xl active:scale-[0.98]">ÜYEYİ YÖNET</a>
                            </div>
                        @else
                            <div class="p-6 rounded-3xl bg-gray-100/50 border-2 border-dashed border-gray-200 text-center">
                                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div class="text-[10px] font-black text-gray-500 uppercase">Misafir Kullanıcı</div>
                                <div class="text-[9px] text-gray-400 mt-1">Platformda kayıtlı bir üyeliği bulunamadı.</div>
                            </div>
                        @endif
                    </section>

                    <!-- Legacy History: AI Insights -->
                    <section>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                            Geçmİş Talepler
                        </h3>
                        <div class="space-y-4">
                            @forelse($history as $h)
                                <a href="{{ route('admin.contact-messages.index', ['id' => $h->id]) }}" class="block bg-white p-4 rounded-2xl border border-gray-100 hover:border-indigo-200 hover:shadow-lg transition">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-[9px] font-black text-gray-400 uppercase">{{ $h->created_at->format('d M Y') }}</span>
                                        @if($h->status == 'closed')
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_5px_rgba(34,197,94,0.5)]"></span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] font-black text-gray-800 leading-tight line-clamp-1 mb-1">{{ $h->subject }}</div>
                                    <div class="text-[9px] text-gray-400 line-clamp-1 italic">"{{ Str::limit($h->message, 40) }}"</div>
                                </a>
                            @empty
                                <div class="text-[10px] text-gray-400 italic text-center py-4 bg-white/50 rounded-2xl border border-dashed border-gray-200">Daha önce bir talebi bulunmamaktadır.</div>
                            @endforelse
                        </div>
                    </section>
                </div>
            @endif
        </div>
    </div>
</div>


<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap');
    
    .font-outfit { font-family: 'Outfit', sans-serif; }

    .glass-stats { background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.4); box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
    .stat-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 12px 24px -10px rgba(79, 70, 229, 0.1); }
    
    @keyframes pulse-slow {
        0%, 100% { opacity: 1; filter: drop-shadow(0 0 5px rgba(255,255,255,0.4)); }
        50% { opacity: 0.8; filter: drop-shadow(0 0 15px rgba(255,255,255,0.7)); }
    }
    .animate-pulse-slow { animation: pulse-slow 3s infinite ease-in-out; }


    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 20px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
    
    /* Animation for chat list */
    [x-show="sidePanel"] {
        width: 350px !important;
    }
</style>

<script>
    // Shortcut for sending reply: Ctrl + Enter
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'Enter') {
            const replyForm = document.querySelector('form[action*="reply"]');
            if (replyForm) replyForm.submit();
        }
    });

    // Auto scroll bottom for chat feed
    window.onload = function() {
        const feed = document.querySelector('.overflow-y-auto.p-12');
        if (feed) feed.scrollTop = feed.scrollHeight;
    };
</script>
@endsection
