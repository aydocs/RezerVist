@extends('layouts.app')

@section('content')
<div class="bg-gray-50/50 min-h-screen py-12 font-outfit">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                    <span class="bg-indigo-600 text-white w-10 h-10 rounded-xl flex items-center justify-center text-lg shadow-lg shadow-indigo-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </span>
                    Destek Taleplerim
                </h1>
                <p class="text-gray-500 font-medium mt-2 max-w-xl">Oluşturduğunuz destek taleplerinin durumunu buradan takip edebilir ve ekibimizle anlık mesajlaşabilirsiniz.</p>
            </div>
            
            <a href="{{ route('pages.live-support') }}" class="group relative px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl transition-all shadow-xl shadow-indigo-200 hover:shadow-2xl hover:-translate-y-1 active:scale-95 flex items-center gap-3 overflow-hidden">
                <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700 skew-x-12"></div>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Yeni Talep Oluştur
            </a>
        </div>

        <!-- Main Layout -->
        <div class="bg-white rounded-[32px] border border-gray-100 shadow-2xl shadow-gray-100/50 overflow-hidden flex h-[750px]">
            
            <!-- Sidebar: Ticket List -->
            <div class="w-full md:w-[380px] border-r border-gray-100 flex flex-col bg-gray-50/30">
                <div class="p-6 border-b border-gray-100 bg-white/50 backdrop-blur-md sticky top-0 z-10">
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Aktif Görüşmeler</h2>
                    <!-- Placeholder for search/filter if needed -->
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-3">
                    @forelse($messages as $msg)
                        <a href="{{ route('profile.support', ['id' => $msg->id]) }}" 
                           class="block p-5 rounded-2xl border transition-all duration-300 group relative overflow-hidden {{ isset($selectedMessage) && $selectedMessage && $selectedMessage->id == $msg->id ? 'bg-white border-indigo-200 shadow-lg shadow-indigo-100/50 ring-1 ring-indigo-500/20' : 'bg-white border-gray-100 hover:border-indigo-100 hover:shadow-md' }}">
                            
                            <!-- Status Indicator Line -->
                            <div class="absolute left-0 top-0 bottom-0 w-1 {{ isset($selectedMessage) && $selectedMessage && $selectedMessage->id == $msg->id ? 'bg-indigo-500' : 'bg-transparent group-hover:bg-indigo-500/30' }} transition-colors"></div>

                            <div class="flex justify-between items-start mb-2 pl-3">
                                <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition truncate pr-2 text-sm">{{ $msg->subject }}</h3>
                                <span class="text-[10px] font-bold text-gray-400 shrink-0 whitespace-nowrap">{{ $msg->created_at->diffForHumans(null, true) }}</span>
                            </div>
                            
                            <p class="text-xs text-gray-500 pl-3 line-clamp-2 leading-relaxed mb-3 opacity-80">{{ $msg->message }}</p>
                            
                            <div class="flex items-center justify-between pl-3 mt-auto">
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider 
                                    {{ $msg->status == 'closed' ? 'bg-gray-100 text-gray-400' : 
                                      ($msg->status == 'replied' ? 'bg-green-50 text-green-600 border border-green-100' : 'bg-amber-50 text-amber-600 border border-amber-100') }}">
                                    {{ $msg->status == 'closed' ? 'KAPANDI' : ($msg->status == 'replied' ? 'YANITLANDI' : 'BEKLİYOR') }}
                                </span>
                                <div class="w-6 h-6 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 text-[10px] font-bold border border-gray-100">
                                    #{{ $msg->id }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-12 px-6">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900">Henüz Mesaj Yok</h3>
                            <p class="text-xs text-gray-500 mt-1">Destek ekibimizle iletişim kurmak için yeni bir talep oluşturun.</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="p-4 border-t border-gray-100 bg-white/50 backdrop-blur-sm">
                   {{ $messages->appends(request()->query())->links() }}
                </div>
            </div>

            <!-- Main Chat Area -->
            <div class="flex-1 flex flex-col bg-white relative">
                @if(isset($selectedMessage) && $selectedMessage)
                    <!-- Chat Header -->
                    <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between shrink-0 bg-white/80 backdrop-blur-md z-10 sticky top-0">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-black text-gray-900 leading-none mb-1">{{ $selectedMessage->subject }}</h2>
                                <div class="flex items-center gap-2 text-xs font-bold text-gray-400">
                                    <span>Talep #{{ $selectedMessage->id }}</span>
                                    <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                    <span>{{ $selectedMessage->created_at->format('d F Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        @if($selectedMessage->status == 'closed')
                            <div class="px-4 py-1.5 bg-gray-100 text-gray-500 text-xs font-black rounded-lg uppercase tracking-wider border border-gray-200 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Kapalı
                            </div>
                        @else
                            <div class="px-4 py-1.5 bg-green-50 text-green-600 text-xs font-black rounded-lg uppercase tracking-wider border border-green-100 flex items-center gap-2 animate-pulse">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                Açık
                            </div>
                        @endif
                    </div>

                    <!-- Chat Messages -->
                    <div class="flex-1 overflow-y-auto p-8 space-y-8 bg-slate-50 relative custom-scrollbar scroll-smooth" id="message-container">
                        <!-- Initial User Message -->
                        <div class="flex items-start gap-4 flex-row-reverse group">
                            <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center shrink-0 border-4 border-white shadow-md text-white font-bold text-sm">
                                SZ
                            </div>
                            <div class="flex-1 max-w-2xl flex flex-col items-end">
                                <div class="bg-indigo-600 text-white px-6 py-4 rounded-[24px] rounded-tr-none shadow-xl shadow-indigo-600/10 text-sm font-medium leading-relaxed">
                                    {{ $selectedMessage->message }}
                                </div>
                                <span class="text-[10px] font-bold text-gray-300 mt-2 mr-2">{{ $selectedMessage->created_at->format('H:i') }}</span>
                            </div>
                        </div>

                        <!-- Threaded Replies -->
                        @foreach($selectedMessage->replies as $reply)
                            @if($reply->is_admin)
                                <!-- Admin Reply -->
                                <div class="flex items-start gap-4 group">
                                    <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center shrink-0 border-4 border-white shadow-md">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <div class="flex-1 max-w-2xl">
                                        <div class="flex items-center gap-2 mb-1 ml-2">
                                            <span class="text-[10px] font-black text-indigo-600 uppercase tracking-wider">Destek Ekibi</span>
                                        </div>
                                        <div class="bg-white text-gray-700 px-6 py-4 rounded-[24px] rounded-tl-none shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 text-sm font-medium leading-relaxed">
                                            {!! nl2br(e($reply->message)) !!}
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-300 mt-2 ml-2">{{ $reply->created_at->format('H:i') }}</span>
                                    </div>
                                </div>
                            @else
                                <!-- User Reply (Subsequent) -->
                                <div class="flex items-start gap-4 flex-row-reverse group">
                                    <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center shrink-0 border-4 border-white shadow-md text-white font-bold text-sm">
                                        SZ
                                    </div>
                                    <div class="flex-1 max-w-2xl flex flex-col items-end">
                                        <div class="bg-indigo-600 text-white px-6 py-4 rounded-[24px] rounded-tr-none shadow-xl shadow-indigo-600/10 text-sm font-medium leading-relaxed">
                                            {!! nl2br(e($reply->message)) !!}
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-300 mt-2 mr-2">{{ $reply->created_at->format('H:i') }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Reply Input Area -->
                    @if($selectedMessage->status != 'closed')
                        <div class="p-6 bg-white border-t border-gray-100 sticky bottom-0 z-10 w-full">
                            <form action="{{ route('profile.support.reply', $selectedMessage->id) }}" method="POST" class="relative">
                                @csrf
                                <div class="relative group">
                                    <textarea name="message" rows="3" 
                                        class="w-full pl-6 pr-20 py-4 bg-gray-50 border border-gray-200 rounded-3xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all resize-none text-sm font-medium outline-none shadow-inner" 
                                        placeholder="Yanıtınızı buraya yazın..."></textarea>
                                    
                                    <button type="submit" class="absolute right-3 bottom-3 p-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl shadow-lg shadow-indigo-200 transition-all active:scale-90 flex items-center justify-center group-focus-within:rotate-0 rotate-90 duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="p-8 bg-gray-50 border-t border-gray-200 text-center">
                            <p class="text-sm font-bold text-gray-400">Bu destek talebi kapatılmıştır.</p>
                        </div>
                    @endif

                @else
                    <!-- Empty State for Chat Area -->
                    <div class="flex-1 flex flex-col items-center justify-center p-12 text-center bg-slate-50/50">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-xl shadow-gray-200 mb-6 animate-bounce-slow">
                            <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-black text-gray-900 mb-2 tracking-tight">Görüşme Seçin</h2>
                        <p class="text-gray-500 max-w-xs mx-auto leading-relaxed text-sm">Destek geçmişinizi görüntülemek veya yanıt vermek için sol taraftan bir talep seçin.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Auto scroll bottom
    window.onload = function() {
        const container = document.getElementById('message-container');
        if(container) {
            container.scrollTop = container.scrollHeight;
        }
    }
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap');
    .font-outfit { font-family: 'Outfit', sans-serif; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 20px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection
