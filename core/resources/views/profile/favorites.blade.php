@extends('layouts.app')

@section('title', 'Favorilerim - Rezervist')

@section('content')
@php
    $user = auth()->user();
@endphp

<div class="bg-gray-50/50 min-h-screen py-12 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ photoPreview: '{{ $user->profile_photo_url }}', editingPhoto: false }">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3">
                <div class="sticky top-24">
                    @include('profile.partials.sidebar')
                </div>
            </div>

            <!-- Content -->
            <div class="lg:col-span-9">
                 <div class="mb-8 flex items-end justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Favorilerim</h1>
                        <p class="text-gray-500 mt-2">Beğendiğiniz ve takip ettiğiniz tüm mekanlar burada.</p>
                    </div>
                </div>

                @if($favorites->isEmpty())
                    <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-xl shadow-gray-100/50">
                        <div class="bg-gray-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Henüz favori yeriniz yok</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">Beğendiğiniz mekanları kalp ikonuna tıklayarak buraya ekleyebilir, daha sonra kolayca ulaşabilirsiniz.</p>
                        <a href="/search" class="inline-flex items-center px-8 py-4 bg-primary text-white rounded-2xl font-black border-2 border-white/20 hover:border-white shadow-lg shadow-primary/30 transition hover:scale-105">
                            Mekanları Keşfet
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-6">
                        @foreach($favorites as $favorite)
                            <a href="/business/{{ $favorite->business->id }}" class="group block bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-lg shadow-gray-100/50 hover:shadow-2xl hover:shadow-primary/10 hover:border-primary/20 transition-all duration-300 transform hover:-translate-y-1">
                                <div class="h-48 bg-gray-100 relative overflow-hidden">
                                     <!-- Placeholder Gradient or Image -->
                                    <div class="absolute inset-0 bg-gradient-to-br from-gray-200 to-gray-300 group-hover:scale-110 transition duration-700"></div>
                                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&q=80" alt="{{ $favorite->business->name }}" class="w-full h-full object-cover absolute inset-0 mix-blend-overlay opacity-70 group-hover:opacity-100 transition duration-500">
                                    
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60"></div>
                                    
                                    <span class="absolute top-4 right-4 bg-white/30 backdrop-blur-md rounded-full p-2.5 shadow-lg border border-white/40 hover:bg-white hover:scale-110 transition text-white hover:text-red-500">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                                    </span>
                                    
                                    <div class="absolute bottom-4 left-4 right-4">
                                        <div class="flex items-center gap-2 mb-1">
                                             <span class="bg-primary/90 backdrop-blur-sm text-white text-xs font-bold px-2 py-1 rounded-lg">
                                                {{ $favorite->business->businessCategory->name ?? 'Kategori' }}
                                             </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-xl text-gray-900 mb-2 group-hover:text-primary transition-colors line-clamp-1">{{ $favorite->business->name }}</h3>
                                        @if($favorite->business->is_verified)
                                            <span class="inline-flex items-center justify-center bg-blue-500 text-white rounded-full p-0.5 shadow-sm -mt-2" title="Doğrulanmış">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($favorite->business->address)
                                        <p class="text-sm text-gray-500 flex items-start gap-2 mb-4 line-clamp-2 min-h-[40px]">
                                            <svg class="w-4 h-4 mt-0.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $favorite->business->address }}
                                        </p>
                                    @endif
                                    
                                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                                        <div class="flex text-yellow-500 text-sm">
                                            @for($i=0; $i<5; $i++)
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                        <span class="text-sm font-bold text-primary group-hover:translate-x-1 transition-transform flex items-center">
                                            İncele
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
