@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Bildirimler</h1>
                <p class="text-gray-500 mt-1">Tüm bildirimleriniz ve güncellemeleriniz buradan takip edebilirsiniz.</p>
            </div>
            @if(Auth::user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm font-bold text-primary hover:underline flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Tümünü Okundu İşaretle
                    </button>
                </form>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="divide-y divide-gray-50">
                @forelse($notifications as $notification)
                    <div class="p-6 transition {{ $notification->read_at ? 'bg-white' : 'bg-primary/5' }}">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full {{ $notification->read_at ? 'bg-gray-100' : 'bg-primary/20' }} flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 {{ $notification->read_at ? 'text-gray-400' : 'text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-gray-900 font-medium leading-relaxed">
                                            {!! $notification->data['message'] ?? 'Yeni bildiriminiz var' !!}
                                        </p>
                                        <p class="text-sm text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.markRead', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold text-primary hover:text-primary-dark transition">Okundu İşaretle</button>
                                        </form>
                                    @endif
                                </div>
                                @if(isset($notification->data['url']))
                                    <a href="{{ route('notifications.markRead', $notification->id) }}" class="mt-4 inline-flex items-center text-sm font-bold text-primary hover:underline">
                                        Detayları Görüntüle
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-20 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Henüz bildirim yok</h3>
                        <p class="text-gray-500 max-w-xs mx-auto mt-2">Sizinle ilgili yeni bir gelişme olduğunda buradan sizi bilgilendireceğiz.</p>
                        <a href="{{ route('search.index') }}" class="mt-6 inline-block bg-primary text-white px-6 py-2 rounded-xl font-bold hover:bg-primary-dark transition shadow-lg shadow-primary/20">Mekanları Keşfet</a>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
