@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12" x-data="chatSystem()">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('messages.index') }}" class="p-2 hover:bg-white rounded-xl transition-colors text-slate-400 hover:text-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-black text-lg overflow-hidden shadow-sm">
                    @if($otherUser->profile_photo_path)
                        <img src="{{ asset('storage/' . $otherUser->profile_photo_path) }}" class="w-full h-full object-cover">
                    @else
                        {{ substr($otherUser->name, 0, 1) }}
                    @endif
                </div>
                <div>
                    <h1 class="text-xl font-black text-slate-900 leading-tight">{{ $otherUser->name }}</h1>
                    <p class="text-[10px] text-emerald-500 font-black uppercase tracking-widest flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Çevrimiçi
                    </p>
                </div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 flex flex-col h-[650px]">
            <!-- Messages Area -->
            <div id="chat-window" class="flex-1 overflow-y-auto p-8 space-y-6 scroll-smooth">
                @foreach($messages as $message)
                <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%] {{ $message->sender_id === Auth::id() ? 'order-1' : 'order-2' }}">
                        <div class="px-5 py-3 rounded-3xl text-sm font-medium shadow-sm {{ $message->sender_id === Auth::id() ? 'bg-primary text-white rounded-tr-none' : 'bg-slate-50 text-slate-700 rounded-tl-none border border-slate-100' }}">
                            {{ $message->content }}
                        </div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1.5 {{ $message->sender_id === Auth::id() ? 'text-right' : 'text-left' }}">
                            {{ $message->created_at->format('H:i') }}
                        </p>
                    </div>
                </div>
                @endforeach
                
                <!-- Dynamic Messages Template -->
                <template x-for="msg in dynamicMessages" :key="msg.id">
                    <div class="flex" :class="msg.sender_id === {{ Auth::id() }} ? 'justify-end' : 'justify-start'">
                        <div class="max-w-[75%] shadow-sm">
                            <div class="px-5 py-3 rounded-3xl text-sm font-medium" 
                                 :class="msg.sender_id === {{ Auth::id() }} ? 'bg-primary text-white rounded-tr-none' : 'bg-slate-50 text-slate-700 rounded-tl-none border border-slate-100'"
                                 x-text="msg.content">
                            </div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1.5" 
                               :class="msg.sender_id === {{ Auth::id() }} ? 'text-right' : 'text-left'"
                               x-text="msg.time">
                            </p>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Input Area -->
            <div class="p-6 border-t border-slate-50 bg-slate-50/30">
                <form @submit.prevent="sendMessage()" class="flex gap-4">
                    <input type="text" x-model="newMessage" :disabled="loading" 
                           class="flex-1 border-none bg-white rounded-2xl px-6 py-4 shadow-sm focus:ring-2 focus:ring-primary/20 text-sm font-medium placeholder:text-slate-300 transition-all" 
                           placeholder="Mesajınızı yazın...">
                    <button type="submit" :disabled="loading || !newMessage.trim()" 
                            class="bg-slate-900 text-white px-8 rounded-2xl font-black text-xs uppercase tracking-widest border-2 border-white/10 hover:border-white transition-all shadow-xl shadow-slate-900/20 active:scale-95 disabled:opacity-50">
                        <span x-show="!loading">Gönder</span>
                        <span x-show="loading" class="flex items-center"><svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function chatSystem() {
        return {
            newMessage: '',
            dynamicMessages: [],
            loading: false,
            init() {
                this.scrollToBottom();
                
                // Real-time Echo Listening
                if (typeof window.Echo !== 'undefined') {
                    window.Echo.private('chat.{{ Auth::id() }}')
                        .listen('.MessageSent', (e) => {
                            if (e.sender_id === {{ $otherUser->id }}) {
                                this.dynamicMessages.push({
                                    id: e.id,
                                    content: e.content,
                                    sender_id: e.sender_id,
                                    time: new Date(e.created_at).toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' })
                                });
                                this.$nextTick(() => this.scrollToBottom());
                            }
                        });
                }
            },
            async sendMessage() {
                if (!this.newMessage.trim()) return;
                
                this.loading = true;
                const content = this.newMessage;
                this.newMessage = '';

                try {
                    const response = await fetch('{{ route("messages.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            receiver_id: {{ $otherUser->id }},
                            content: content
                        })
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        this.dynamicMessages.push({
                            id: data.message.id,
                            content: data.message.content,
                            sender_id: {{ Auth::id() }},
                            time: new Date().toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' })
                        });
                        this.$nextTick(() => this.scrollToBottom());
                    }
                } catch (e) {
                    console.error('Send error:', e);
                } finally {
                    this.loading = false;
                }
            },
            scrollToBottom() {
                const el = document.getElementById('chat-window');
                el.scrollTop = el.scrollHeight;
            }
        }
    }
</script>
@endsection
