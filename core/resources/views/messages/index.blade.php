@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Mesajlar</h1>
            <p class="mt-2 text-sm text-slate-500 font-medium">İşletmeler ve müşteriler ile olan tüm görüşmeleriniz.</p>
        </div>

        <div x-data="conversationList()" class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="divide-y divide-slate-100">
                <template x-for="conv in conversations" :key="conv.id">
                    <a :href="'{{ url('messages') }}/' + conv.id" class="flex items-center gap-4 p-6 hover:bg-slate-50 transition-all group">
                        <div class="relative">
                            <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-black text-xl overflow-hidden shadow-sm">
                                <template x-if="conv.profile_photo">
                                    <img :src="conv.profile_photo" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!conv.profile_photo">
                                    <span x-text="conv.initials"></span>
                                </template>
                            </div>
                            <template x-if="conv.unread_count > 0">
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 rounded-full border-2 border-white flex items-center justify-center text-[10px] font-black text-white">
                                    <span x-text="conv.unread_count"></span>
                                </div>
                            </template>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-black text-slate-900 text-lg group-hover:text-primary transition-colors" x-text="conv.name"></h3>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="conv.last_message_time"></span>
                            </div>
                            <p class="text-sm text-slate-500 font-medium truncate" x-text="conv.last_message_content"></p>
                        </div>
                        
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </a>
                </template>

                <div x-show="conversations.length === 0" class="py-24 text-center">
                    <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center text-slate-200 mx-auto mb-6">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2">Henüz Mesajınız Yok</h3>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">İşletmelerle iletişime geçtiğinizde burada görünecektir.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function conversationList() {
        return {
            conversations: @json($conversations),

            init() {
                console.log('Alpine initialising for message list...');
                if (window.Echo) {
                    console.log('Joining chat.{{ Auth::id() }} channel...');
                    window.Echo.private('chat.{{ Auth::id() }}')
                        .on('whisper', (data) => console.log('Whisper:', data)) // Extra debug
                        .listen('.MessageSent', (e) => {
                            console.log('NEW MESSAGE RECEIVED ON INDEX:', e);
                            this.updateConversation(e);
                        });
                } else {
                    console.error('Echo not found globally!');
                }
            },

            updateConversation(e) {
                let index = this.conversations.findIndex(c => c.id === e.sender_id);
                
                if (index !== -1) {
                    // Update existing
                    this.conversations[index].last_message_content = e.content;
                    this.conversations[index].last_message_time = e.diff_for_humans;
                    this.conversations[index].unread_count++;
                    this.conversations[index].timestamp = Math.floor(Date.now() / 1000);
                } else {
                    // Add new conversation
                    this.conversations.unshift({
                        id: e.sender_id,
                        name: e.sender_name,
                        initials: e.sender_initials,
                        profile_photo: e.sender_photo,
                        unread_count: 1,
                        last_message_content: e.content,
                        last_message_time: e.diff_for_humans,
                        timestamp: Math.floor(Date.now() / 1000)
                    });
                }

                // Sort by timestamp
                this.conversations.sort((a, b) => b.timestamp - a.timestamp);
            }
        }
    }
</script>
@endsection
