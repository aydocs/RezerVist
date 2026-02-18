@extends('layouts.app')

@section('title', $business->name . ' - ' . ($business->city ?? 'Türkiye') . ' | Online Rezervasyon | Rezervist')
@section('meta_description', Str::limit(strip_tags($business->description), 155) . ' ' . $business->address . ' - Hemen online rezervasyon yapın!')
@section('meta_keywords', $business->name . ', ' . ($business->businessCategory->name ?? $business->category) . ', ' . ($business->city ?? '') . ' rezervasyon, online masa rezervasyonu, ' . ($business->businessCategory->name ?? '') . ' ' . ($business->city ?? ''))
@section('meta_image', $business->images->first()?->image_path ? asset('storage/' . $business->images->first()->image_path) : asset('images/default-business.jpg'))
@section('og_type', 'business.business')

@section('structured_data')
<script type="application/ld+json">
{
    "{{ '@' }}context": "https://schema.org",
    "{{ '@' }}type": "{{ in_array($business->businessCategory->slug ?? '', ['restoran', 'kafe', 'bar', 'kahvalti']) ? 'Restaurant' : 'LocalBusiness' }}",
    "{{ '@' }}id": "{{ url()->current() }}",
    "name": "{{ $business->name }}",
    "description": "{{ Str::limit(strip_tags($business->description), 200) }}",
    "url": "{{ url()->current() }}",
    "telephone": "{{ $business->phone }}",
    "image": "{{ $business->images->first()?->image_path ? asset('storage/' . $business->images->first()->image_path) : asset('images/default-business.jpg') }}",
    "address": {
        "{{ '@' }}type": "PostalAddress",
        "streetAddress": "{{ $business->address }}",
        "addressLocality": "{{ $business->city ?? 'İstanbul' }}",
        "addressCountry": "TR"
    },
    "geo": {
        "{{ '@' }}type": "GeoCoordinates",
        "latitude": "{{ $business->latitude }}",
        "longitude": "{{ $business->longitude }}"
    },
    @if($business->rating > 0)
    "aggregateRating": {
        "{{ '@' }}type": "AggregateRating",
        "ratingValue": "{{ $business->rating }}",
        "reviewCount": "{{ $business->reviews->count() }}",
        "bestRating": "5",
        "worstRating": "1"
    },
    @endif
    @if($business->reviews->count() > 0)
    "review": [
        @foreach($business->reviews->take(5) as $review)
        {
            "{{ '@' }}type": "Review",
            "author": {
                "{{ '@' }}type": "Person",
                "name": "{{ $review->user->name }}"
            },
            "datePublished": "{{ $review->created_at->format('Y-m-d') }}",
            "reviewBody": "{{ Str::limit($review->comment, 150) }}",
            "reviewRating": {
                "{{ '@' }}type": "Rating",
                "ratingValue": "{{ $review->rating }}",
                "bestRating": "5"
            }
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ],
    @endif
    "priceRange": "₺₺",
    "servesCuisine": "{{ $business->businessCategory->name ?? 'Çeşitli' }}",
    @if($business->hasHours())
    "openingHoursSpecification": [
        @foreach($business->hours as $hour)
        @if(!$hour->is_closed)
        {
            "{{ '@' }}type": "OpeningHoursSpecification",
            "dayOfWeek": "{{ ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$hour->day_of_week] }}",
            "opens": "{{ \Carbon\Carbon::parse($hour->open_time)->format('H:i') }}",
            "closes": "{{ \Carbon\Carbon::parse($hour->close_time)->format('H:i') }}"
        }{{ !$loop->last ? ',' : '' }}
        @endif
        @endforeach
    ],
    @endif
    "acceptsReservations": "True",
    "potentialAction": {
        "{{ '@' }}type": "ReserveAction",
        "target": {
            "{{ '@' }}type": "EntryPoint",
            "urlTemplate": "{{ url('/business/' . $business->id) }}",
            "actionPlatform": [
                "http://schema.org/DesktopWebPlatform",
                "http://schema.org/MobileWebPlatform"
            ]
        },
        "result": {
            "{{ '@' }}type": "Reservation",
            "name": "{{ $business->name }} Rezervasyonu"
        }
    }
}
</script>
<script type="application/ld+json">
{
    "{{ '@' }}context": "https://schema.org",
    "{{ '@' }}type": "BreadcrumbList",
    "itemListElement": [
        {
            "{{ '@' }}type": "ListItem",
            "position": 1,
            "name": "Ana Sayfa",
            "item": "{{ config('app.url') }}"
        },
        {
            "{{ '@' }}type": "ListItem",
            "position": 2,
            "name": "{{ $business->businessCategory->name ?? 'İşletmeler' }}",
            "item": "{{ config('app.url') }}/search?category={{ urlencode($business->businessCategory->slug ?? '') }}"
        },
        {
            "{{ '@' }}type": "ListItem",
            "position": 3,
            "name": "{{ $business->name }}",
            "item": "{{ url()->current() }}"
        }
    ]
}
</script>
@endsection

@section('content')
<style>
    @media (max-width: 768px) {
        footer {
            padding-bottom: 140px !important;
        }
    }
</style>
<!-- Business Banner (Hero) -->
<div class="relative h-96 lg:h-[500px]">
    <div class="absolute inset-0">
        @if($business->images->count() > 0)
            <!-- Dynamically use uploaded image -->
             <img src="{{ Storage::url($business->images->first()->image_path) }}" alt="{{ $business->name }}" class="w-full h-full object-cover">
        @else
             <!-- Fallback -->
             <img src="https://source.unsplash.com/random/1600x900/?restaurant,interior&sig={{ $business->id }}" alt="{{ $business->name }}" class="w-full h-full object-cover">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>
    </div>
    
    <div class="absolute bottom-0 left-0 right-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 z-20">
        <div class="flex flex-col md:flex-row md:items-end justify-between">
            <div class="w-full">
                <!-- Category Badge -->
                <div class="mb-4">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest text-white bg-white/20 backdrop-blur-md border border-white/10 shadow-sm">
                        {{ $business->businessCategory->name ?? $business->category }}
                    </span>
                </div>

                <!-- Title -->
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white mb-6 leading-tight drop-shadow-lg tracking-tight flex items-center gap-2 md:gap-4">
                    {{ $business->name }}
                    @if($business->is_verified)
                        <span class="inline-flex items-center justify-center bg-blue-500 text-white rounded-full p-1.5 shadow-lg shadow-blue-500/30 group/verified relative cursor-help" title="Doğrulanmış İşletme">
                            <svg class="w-4 h-4 md:w-6 md:h-6 lg:w-8 lg:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <!-- Tooltip -->
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-3 px-3 py-2 bg-gray-900 text-white text-[10px] md:text-xs font-bold rounded-lg opacity-0 invisible group-hover/verified:opacity-100 group-hover/verified:visible transition-all whitespace-nowrap z-50 shadow-2xl">
                                {{ __('Rezervist Tarafından Doğrulanmış İşletme') }}
                                <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 border-4 border-transparent border-t-gray-900"></div>
                            </div>
                        </span>
                    @endif
                </h1>

                <!-- Mobile Header Area (Aligning Rating and Favorite) -->
                <div class="flex md:hidden flex-col gap-4 mb-6">
                    <div class="flex items-center justify-between w-full">
                        <!-- Rating Badge -->
                        <div class="flex items-center bg-black/40 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10 text-white shadow-lg h-12 flex-1 mr-3">
                            <svg class="w-4 h-4 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> 
                            <span class="font-bold text-lg mr-1">{{ $business->rating }}</span>
                            <span class="text-white/60 text-xs ml-1 border-l border-white/20 pl-2">{{ $business->reviews->count() }} Değerlendirme</span>
                        </div>

                        <!-- Favorite Button -->
                        <button 
                            onclick="toggleFavorite({{ $business->id }}, this)" 
                            class="bg-white/90 backdrop-blur-sm text-gray-900 p-3 rounded-xl hover:bg-white transition shadow-xl h-12 w-12 flex items-center justify-center shrink-0"
                            aria-label="Favorilere ekle">
                            <svg class="w-7 h-7 {{ auth()->check() && auth()->user()->favorites()->where('business_id', $business->id)->exists() ? 'fill-red-500 text-red-500' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Location & Status Badge Row (Mobile) -->
                    <div class="flex flex-col gap-2">
                        <!-- Address -->
                        <div class="flex items-center bg-black/40 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10 text-gray-100 shadow-lg w-fit">
                            <svg class="w-4 h-4 mr-2 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> 
                            <span class="text-xs">{{ $business->address }}</span>
                        </div>

                        <!-- Status -->
                        <div class="flex items-center px-4 py-2 rounded-xl backdrop-blur-md border shadow-lg w-fit {{ !$business->hasHours() ? 'bg-gray-800/80 text-gray-300 border-white/10' : ($business->is_open ? 'bg-emerald-500/90 text-white border-emerald-400/30' : 'bg-rose-500/90 text-white border-rose-400/30') }}">
                            <span class="relative flex h-2 w-2 mr-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $business->is_open ? 'bg-white' : 'hidden' }}"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 {{ $business->is_open ? 'bg-white' : 'bg-white/80' }}"></span>
                            </span>
                            <span class="uppercase tracking-wider text-[10px] font-bold">{{ $business->status_text }}</span>
                        </div>
                    </div>
                </div>

                <!-- Desktop Info Badges -->
                <div class="hidden md:flex flex-wrap items-center gap-3 text-sm font-medium">
                    <!-- Rating -->
                    <div class="flex items-center bg-black/40 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10 text-white shadow-lg">
                        <svg class="w-4 h-4 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> 
                        <span class="font-bold text-lg mr-1">{{ $business->rating }}</span>
                        <span class="text-white/60 text-xs ml-1 border-l border-white/20 pl-2">{{ $business->reviews->count() }} Değerlendirme</span>
                    </div>

                    <!-- Address -->
                    <div class="flex items-center bg-black/40 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10 text-gray-100 shadow-lg font-bold">
                        <svg class="w-4 h-4 mr-2 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> 
                        {{ Str::limit($business->address, 30) }}
                    </div>

                    <!-- Status -->
                    <div class="flex items-center px-4 py-2 rounded-xl backdrop-blur-md border shadow-lg {{ !$business->hasHours() ? 'bg-gray-800/80 text-gray-300 border-white/10' : ($business->is_open ? 'bg-emerald-500/90 text-white border-emerald-400/30' : 'bg-rose-500/90 text-white border-rose-400/30') }}">
                        <span class="relative flex h-2 w-2 mr-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $business->is_open ? 'bg-white' : 'hidden' }}"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 {{ $business->is_open ? 'bg-white' : 'bg-white/80' }}"></span>
                        </span>
                        <span class="uppercase tracking-wider text-xs font-bold">{{ $business->status_text }}</span>
                    </div>
                </div>
            </div>
            <div class="hidden md:flex mt-6 md:mt-0 items-center gap-4">
                <!-- Favorite Button (Desktop) -->
                <button 
                    onclick="toggleFavorite({{ $business->id }}, this)" 
                    class="bg-white/90 backdrop-blur-sm text-gray-900 h-14 w-14 rounded-2xl hover:bg-white transition-all shadow-xl flex items-center justify-center border border-gray-100/50 active:scale-90"
                    aria-label="Favorilere ekle">
                    <svg class="w-7 h-7 {{ auth()->check() && auth()->user()->favorites()->where('business_id', $business->id)->exists() ? 'fill-red-500 text-red-500' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
                
                <!-- Reservation Button (Desktop) -->
                <button onclick="document.getElementById('reservation-section').scrollIntoView({behavior: 'smooth'})" 
                        class="bg-gradient-to-r from-primary to-purple-600 text-white font-black px-10 h-14 rounded-2xl hover:shadow-primary/30 shadow-xl transition-all active:scale-[0.98] flex items-center justify-center gap-3 whitespace-nowrap uppercase tracking-wider text-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ __('common.reservation.make_reservation') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Sticky Reservation Button -->
<div x-data="{ show: true }" 
     x-init="
        const observer = new IntersectionObserver(entries => {
            show = !entries[0].isIntersecting;
        }, { threshold: 0.1 });
        if(document.getElementById('reservation-section')) {
            observer.observe(document.getElementById('reservation-section'));
        }
     "
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-y-full opacity-0"
     x-transition:enter-end="translate-y-0 opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="translate-y-0 opacity-100"
     x-transition:leave-end="translate-y-full opacity-0"
     class="fixed bottom-0 left-0 right-0 p-4 bg-white/80 backdrop-blur-xl border-t border-gray-100 md:hidden z-[100] flex items-center gap-3 shadow-[0_-10px_40px_rgba(0,0,0,0.1)]">
    <!-- Mobile Favorite Button -->
    <button 
        onclick="toggleFavorite({{ $business->id }}, this)" 
        class="bg-gray-100/80 backdrop-blur-sm text-gray-900 p-4 rounded-2xl hover:bg-white active:scale-90 transition-all shadow-sm border border-gray-200/50 flex items-center justify-center shrink-0 w-14 h-14"
        aria-label="Favorilere ekle">
        <svg class="w-6 h-6 {{ auth()->check() && auth()->user()->favorites()->where('business_id', $business->id)->exists() ? 'fill-red-500 text-red-500' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
        </svg>
    </button>

    <!-- Mobile Reservation Button -->
    <button @click="document.getElementById('reservation-section').scrollIntoView({behavior: 'smooth'})" 
            class="flex-1 bg-gradient-to-r from-primary to-purple-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-primary/20 active:scale-[0.98] transition-all flex flex-row items-center justify-center gap-2 group overflow-hidden relative h-14 flex-nowrap">
        <div class="absolute inset-0 bg-white/10 translate-y-full group-active:translate-y-0 transition-transform"></div>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        <span class="text-[13px] uppercase tracking-wider whitespace-nowrap">{{ __('common.reservation.make_reservation') }}</span>
    </button>
</div>

<!-- Content continues... -->

<!-- Tabs & Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24 lg:pb-12" x-data="{ tab: 'details' }">
    <!-- Tabs Navigation -->
    <!-- Tabs Navigation -->
    <div class="flex w-full border-b border-gray-200 mb-8">
        <button @click="tab = 'details'" :class="{ 'border-primary text-primary': tab === 'details', 'border-transparent text-gray-500 hover:text-gray-800': tab !== 'details' }" class="flex-1 px-1 py-3 font-medium text-sm md:text-lg border-b-2 transition whitespace-nowrap text-center">{{ __('common.categories.about') }}</button>
        <button @click="tab = 'menu'" :class="{ 'border-primary text-primary': tab === 'menu', 'border-transparent text-gray-500 hover:text-gray-800': tab !== 'menu' }" class="flex-1 px-1 py-3 font-medium text-sm md:text-lg border-b-2 transition whitespace-nowrap text-center">{{ __('common.categories.menu_services') }}</button>
        <button @click="tab = 'gallery'" :class="{ 'border-primary text-primary': tab === 'gallery', 'border-transparent text-gray-500 hover:text-gray-800': tab !== 'gallery' }" class="flex-1 px-1 py-3 font-medium text-sm md:text-lg border-b-2 transition whitespace-nowrap text-center">{{ __('common.categories.gallery') }}</button>
        <button @click="tab = 'reviews'" :class="{ 'border-primary text-primary': tab === 'reviews', 'border-transparent text-gray-500 hover:text-gray-800': tab !== 'reviews' }" class="flex-1 px-1 py-3 font-medium text-sm md:text-lg border-b-2 transition whitespace-nowrap text-center">{{ __('common.categories.reviews') }}</button>
    </div>

    <!-- Details Tab -->
    <!-- Details Tab -->
    <div x-show="tab === 'details'" class="flex flex-col gap-8">
        <div class="space-y-6">
            <!-- About Section -->
            <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-full -mr-16 -mt-16"></div>
                <h2 class="text-2xl font-black text-gray-900 mb-4 flex items-center">
                    <span class="w-2 h-8 bg-primary rounded-full mr-3"></span>
                    {{ __('common.categories.about') }}
                </h2>
                <div class="prose prose-slate max-w-none">
                    <p class="text-gray-600 leading-relaxed text-lg">
                        {{ $business->description }}
                    </p>
                </div>
                
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <div class="flex items-center bg-gray-100/80 px-4 py-2 rounded-xl">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mr-3">{{ __('common.categories.category_label') }}</span>
                        <span class="text-primary font-black uppercase text-sm">{{ $business->businessCategory->name ?? $business->category }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Highlights -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-8 h-8 bg-primary/10 text-primary rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </span>
                    Öne Çıkan Özellikler
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @php
                        $icons = [
                            'wifi' => '<svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>',
                            'bahce' => '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                            'kredi-karti' => '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>',
                            'vale' => '<svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>',
                            'canli-muzik' => '<svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>',
                            'cocuk-dostu' => '<svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                        ];
                    @endphp
                    @forelse($business->tags as $tag)
                        <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition duration-200">
                            <span class="mr-3">{!! $icons[$tag->slug] ?? '<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>' !!}</span>
                            <span class="text-sm font-bold text-gray-700">{{ $tag->name }}</span>
                        </div>
                    @empty
                        <div class="col-span-full py-4 text-center text-sm text-gray-400 italic bg-gray-50 rounded-xl border border-dashed border-gray-200">{{ __('common.categories.no_features') }}</div>
                    @endforelse
                </div>
            </div>



            <div class="grid md:grid-cols-2 gap-6">
                <!-- Payment Methods -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h4 class="text-sm font-black text-gray-900 mb-4 uppercase tracking-widest flex items-center">
                        <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Ödeme Yöntemleri
                    </h4>
                    <div class="flex flex-wrap gap-3">
                        <div class="flex items-center bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">
                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            <span class="text-xs font-bold text-gray-600">Kredi Kartı</span>
                        </div>
                        <div class="flex items-center bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">
                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-xs font-bold text-gray-600">Bakiyem / Cüzdan</span>
                        </div>
                        <div class="flex items-center bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">
                            <svg class="w-4 h-4 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span class="text-xs font-bold text-gray-600">Online Onay</span>
                        </div>
                    </div>
                </div>

                <!-- Reservation Policy -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h4 class="text-sm font-black text-gray-900 mb-4 uppercase tracking-widest flex items-center">
                        <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Rezervasyon Politikası
                    </h4>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-emerald-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-xs text-gray-600 font-medium">Son 2 saat kalaya kadar ücretsiz iptal.</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-emerald-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-xs text-gray-600 font-medium">Anında onaylanan rezervasyon garantisi.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Reservation Widget (Centered/Stacked) -->
        <div class="w-full">
            <div id="reservation-section" class="bg-white p-8 rounded-2xl border border-gray-100 shadow-xl relative w-full" 
                 x-data="reservationWidget()">
                
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary via-purple-500 to-pink-500 rounded-t-2xl"></div>

                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ __('common.reservation.make_reservation') }}
                </h3>

                <!-- Loading Overlay -->
                <div x-show="isSubmitting" class="absolute inset-0 bg-white/80 backdrop-blur-sm z-50 flex items-center justify-center rounded-2xl">
                    <div class="flex flex-col items-center">
                        <svg class="animate-spin h-8 w-8 text-primary mb-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span class="text-sm font-bold text-gray-600">İşleminiz Yapılıyor...</span>
                    </div>
                </div>

                <form @submit.prevent="submitReservation">
                    <!-- Step Indicator -->
                    <div class="flex items-center justify-between mb-6 text-xs font-semibold text-gray-400">
                        <div :class="step >= 1 ? 'text-primary' : ''">1. {{ __('common.reservation.details_title') }}</div>
                        <div :class="step >= 2 ? 'text-primary' : ''">2. Hizmet</div>
                        <div :class="step >= 3 ? 'text-primary' : ''">3. Ödeme</div>
                    </div>
                    
                    <!-- STEP 1: Details (Date, Time, Guest) -->
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                        
                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <!-- Date Selection (Custom Calendar) -->
                            <div class="relative group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tarih Seçin</label>
                                
                                <div class="relative" @click.away="showDatePicker = false">
                                    <div @click="showDatePicker = !showDatePicker" 
                                         class="w-full px-4 py-3 rounded-xl border-2 transition-all duration-200 cursor-pointer flex items-center justify-between"
                                         :class="showDatePicker ? 'border-primary ring-4 ring-primary/10 bg-white' : 'border-gray-200 bg-gray-50 hover:bg-white hover:border-gray-300'">
                                        <span class="font-bold text-gray-800" x-text="selectedDateFormatted || 'Tarih Seçiniz...'"></span>
                                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="showDatePicker ? 'rotate-180 text-primary' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>

                                    <!-- Flawless Calendar Dropdown -->
                                    <div x-show="showDatePicker" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         class="absolute z-50 mt-3 top-full left-0 w-full bg-white rounded-2xl shadow-2xl border border-gray-100 p-5 origin-top-left"
                                         style="min-width: 320px;">
                                        
                                        <div class="flex items-center justify-between mb-4">
                                            <button type="button" @click="prevMonth()" class="p-2 hover:bg-gray-100 rounded-full text-gray-600 transition hover:text-primary">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                            </button>
                                            <span class="text-lg font-bold text-gray-800" x-text="monthNames[month] + ' ' + year"></span>
                                            <button type="button" @click="nextMonth()" class="p-2 hover:bg-gray-100 rounded-full text-gray-600 transition hover:text-primary">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-7 mb-2 border-b border-gray-100 pb-2">
                                            <template x-for="day in days">
                                                <div class="text-center text-[10px] uppercase font-bold text-gray-400 tracking-wider" x-text="day"></div>
                                            </template>
                                        </div>

                                        <div class="grid grid-cols-7 gap-1">
                                            <template x-for="blank in blankDays">
                                                <div class="h-10"></div>
                                            </template>
                                            <template x-for="date in noOfDays">
                                                <div @click="getDateValue(date)"
                                                     class="h-10 flex items-center justify-center text-sm font-medium rounded-lg cursor-pointer transition-all duration-200 relative group"
                                                     :class="{
                                                         'bg-primary text-white shadow-lg shadow-primary/30 scale-105 z-10 font-bold': isSelected(date),
                                                         'hover:bg-gray-50 text-gray-700 hover:text-primary hover:scale-105': !isSelected(date) && !isPast(date),
                                                         'text-gray-300 cursor-not-allowed decoration-slice': isPast(date)
                                                     }">
                                                    <span x-text="date" class="relative z-10"></span>
                                                </div>
                                            </template>
                                        </div>
                                        
                                        <div class="mt-4 pt-3 border-t border-gray-100 flex justify-end">
                                            <button @click="showDatePicker = false" class="text-xs text-gray-400 hover:text-gray-600 font-medium transition">Kapat</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Guest Count (Homepage Style Popover) -->
                            <div class="relative">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kişi Sayısı</label>
                                
                                <div @click="showGuestPicker = !showGuestPicker" 
                                     class="w-full px-4 py-3 rounded-xl border-2 transition-all duration-200 cursor-pointer flex items-center justify-between"
                                     :class="showGuestPicker ? 'border-primary ring-4 ring-primary/10 bg-white' : 'border-gray-200 bg-gray-50 hover:bg-white hover:border-gray-300'">
                                    <div class="flex-grow text-left">
                                        <span class="font-black text-gray-900 text-base" x-text="guestText"></span>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="showGuestPicker ? 'rotate-180 text-primary' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>

                                <!-- Guest Picker Dropdown (Full width matching trigger) -->
                                <div x-show="showGuestPicker" 
                                     @click.away="showGuestPicker = false"
                                     x-transition
                                     class="absolute z-50 mt-3 top-full left-0 w-full bg-white rounded-2xl shadow-2xl border border-gray-100 p-6 origin-top"
                                     style="min-width: auto;">
                                     
                                     <!-- Adults -->
                                     <div class="flex justify-between items-center mb-6 pb-6 border-b border-gray-50">
                                         <div>
                                             <div class="font-bold text-gray-900">Yetişkin</div>
                                             <div class="text-xs text-gray-400">18 yaş ve üzeri</div>
                                         </div>
                                         <div class="flex items-center space-x-4">
                                             <button type="button" @click="adults > 1 ? adults-- : null"
                                                     class="w-10 h-10 rounded-xl bg-gray-50 text-gray-600 font-bold flex items-center justify-center hover:bg-primary hover:text-white transition disabled:opacity-30" :disabled="adults <= 1">−</button>
                                             <span class="w-4 text-center font-bold text-gray-900" x-text="adults"></span>
                                             <button type="button" @click="adults < 20 ? adults++ : null"
                                                     class="w-10 h-10 rounded-xl bg-gray-50 text-gray-600 font-bold flex items-center justify-center hover:bg-primary hover:text-white transition disabled:opacity-30" :disabled="adults >= 20">+</button>
                                         </div>
                                     </div>

                                     <!-- Children -->
                                     <div class="flex justify-between items-center mb-6">
                                         <div>
                                             <div class="font-bold text-gray-900">Çocuk</div>
                                             <div class="text-xs text-gray-400">0-17 yaş arası</div>
                                         </div>
                                         <div class="flex items-center space-x-4">
                                             <button type="button" @click="children > 0 ? children-- : null"
                                                     class="w-10 h-10 rounded-xl bg-gray-50 text-gray-600 font-bold flex items-center justify-center hover:bg-primary hover:text-white transition disabled:opacity-30" :disabled="children <= 0">−</button>
                                             <span class="w-4 text-center font-bold text-gray-900" x-text="children"></span>
                                             <button type="button" @click="children < 20 ? children++ : null"
                                                     class="w-10 h-10 rounded-xl bg-gray-50 text-gray-600 font-bold flex items-center justify-center hover:bg-primary hover:text-white transition disabled:opacity-30" :disabled="children >= 20">+</button>
                                         </div>
                                     </div>

                                     <button type="button" @click="showGuestPicker = false"
                                             class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-primary/90 transition shadow-lg shadow-primary/20">
                                         Uygula
                                     </button>
                                </div>
                            </div>
                        </div>

                        <!-- Time Slots Block -->
                        <div class="relative min-h-[180px] mb-8">
                            
                            <!-- Loading State -->
                            <div x-show="isLoadingSlots" class="absolute inset-0 flex flex-col items-center justify-center bg-white/80 z-20 backdrop-blur-sm rounded-xl transition-opacity">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mb-3"></div>
                                <span class="text-sm font-medium text-gray-500 tracking-wide">Müsaitlik durumu kontrol ediliyor...</span>
                            </div>

                            <!-- Business Closed State -->
                            <div x-show="!isLoadingSlots && businessIsClosed" 
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="flex flex-col items-center justify-center py-10 px-6 bg-red-50 rounded-2xl border border-red-100 text-center shadow-inner">
                                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mb-4 text-red-500 shadow-sm">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                </div>
                                <h4 class="text-lg font-bold text-red-800 mb-1">İşletme Kapalı</h4>
                                <p class="text-red-500 text-sm font-medium">Seçtiğiniz tarihte işletmemiz hizmet vermemektedir.<br>Lütfen farklı bir tarih seçiniz.</p>
                            </div>

                            <!-- Error Message (Generic) -->
                            <div x-show="!isLoadingSlots && !businessIsClosed && slotMessage && slots.length === 0" 
                                 class="flex flex-col items-center justify-center py-12 text-center text-gray-500 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                 <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                 <p x-text="slotMessage" class="font-medium text-gray-600"></p>
                            </div>

                            <!-- Slots Grid -->
                            <div x-show="!isLoadingSlots && !businessIsClosed && slots.length > 0">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Saat Seçin</label>
                                    <span class="text-xs font-bold text-primary bg-primary/5 px-2 py-1 rounded-md" x-text="slots.length + ' Saat Müsait'"></span>
                                </div>
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    <template x-for="slot in slots" :key="slot">
                                        <button type="button" 
                                                @click="selectedTime = slot"
                                                :class="selectedTime === slot ? 'bg-primary text-white shadow-lg shadow-primary/30 ring-2 ring-primary ring-offset-2 scale-105 z-10' : 'bg-white text-gray-700 border-gray-200 hover:border-gray-800 hover:bg-gray-50'"
                                                class="py-3 px-2 rounded-xl border text-sm font-bold transition-all duration-200 focus:outline-none relative overflow-hidden" 
                                                x-text="slot">
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Initial State (No Date Selected) -->
                            <div x-show="!selectedDate && !isLoadingSlots" class="flex flex-col items-center justify-center py-12 text-gray-400 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
                                <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-sm font-medium">Saatleri görmek için tarih seçiniz</span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="flex justify-end">
                            <button type="button" 
                                    @click="nextStep()"
                                     :disabled="!selectedTime || businessIsClosed"
                                     :class="(!selectedTime || businessIsClosed) ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-primary text-white hover:bg-primary/90 shadow-lg shadow-primary/20 transition-all active:scale-95'"
                                     class="w-full sm:w-auto flex items-center justify-center py-4 px-10 rounded-xl font-bold text-lg">
                                <span>Devam Et</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: Services -->
                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                        
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                            <h4 class="font-bold text-gray-900 text-lg">Ekstra Hizmetler</h4>
                            
                            <!-- Search Bar -->
                            <div class="relative w-full sm:w-64">
                                <input type="text" 
                                       x-model="searchQuery" 
                                       placeholder="Hizmetlerde ara..." 
                                       class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="space-y-4 mb-8 max-h-[450px] overflow-y-auto custom-scrollbar pr-2">
                            @php
                                $groupedMenus = $business->menus->groupBy('category')->toArray();
                            @endphp

                            <div x-data="{ 
                                menus: @js($business->menus),
                                get filteredGroups() {
                                    if (!this.searchQuery) return @js($groupedMenus);
                                    
                                    const search = this.searchQuery.toLowerCase();
                                    const filtered = {};
                                    
                                    Object.keys(@js($groupedMenus)).forEach(category => {
                                        const items = @js($groupedMenus)[category].filter(item => 
                                            item.name.toLowerCase().includes(search) || 
                                            (item.description && item.description.toLowerCase().includes(search))
                                        );
                                        if (items.length > 0) filtered[category] = items;
                                    });
                                    return filtered;
                                }
                            }">
                                <template x-if="Object.keys(filteredGroups).length === 0">
                                    <div class="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-100">
                                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 shadow-sm">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">Aradığınız kriterlere uygun hizmet bulunamadı.</p>
                                    </div>
                                </template>

                                <template x-for="(items, category) in filteredGroups" :key="category">
                                    <div class="bg-gray-50/50 rounded-2xl border border-gray-100 overflow-hidden mb-4">
                                        <!-- Category Header -->
                                        <button type="button" 
                                                @click="openCategories.includes(category) ? openCategories = openCategories.filter(c => c !== category) : openCategories.push(category)"
                                                class="w-full flex items-center justify-between p-4 bg-white hover:bg-gray-50 transition-all font-bold text-gray-800 text-sm border-b border-gray-100">
                                            <div class="flex items-center">
                                                <span class="w-2 h-2 bg-primary rounded-full mr-3 shadow-[0_0_8px_rgba(124,58,237,0.5)]"></span>
                                                <span x-text="category || 'Diğer'"></span>
                                            </div>
                                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" 
                                                 :class="!openCategories.includes(category) ? 'rotate-180' : ''" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        </button>

                                        <!-- Category Items -->
                                        <div x-show="openCategories.includes(category)" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 -translate-y-2"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             class="p-3 space-y-2">
                                            <template x-for="item in items" :key="item.id">
                                                    <div @click="updateServiceQuantity(item.id, item.price, 1)" 
                                                         class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-2xl hover:border-primary/30 transition-all shadow-sm group cursor-pointer select-none active:scale-[0.99]">
                                                        <div class="flex-1 min-w-0 pr-4">
                                                            <div class="font-bold text-gray-900 text-sm truncate" x-text="item.name"></div>
                                                            <div class="font-black text-primary text-sm whitespace-nowrap" x-text="formatPrice(item.price)"></div>
                                                            <p class="text-xs text-gray-500 mt-1 line-clamp-1" x-text="item.description"></p>
                                                        </div>
                                                        
                                                        <div class="flex items-center bg-gray-50 rounded-xl p-1 border border-gray-200">
                                                            <!-- Decrease Button -->
                                                            <button type="button" 
                                                                    @click.stop="updateServiceQuantity(item.id, item.price, -1)"
                                                                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white hover:shadow-sm transition-all text-gray-400 hover:text-red-500 disabled:opacity-30 disabled:hover:bg-transparent"
                                                                    :class="getServiceQuantity(item.id) > 0 ? 'text-gray-600' : 'opacity-30 pointer-events-none'">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                                            </button>

                            <!-- Quantity Input (Triggers Keypad) -->
                                                            <div @click.stop="openKeypad(item.id, getServiceQuantity(item.id))"
                                                                 class="w-10 h-8 flex items-center justify-center cursor-pointer hover:bg-gray-100 rounded-lg transition-colors">
                                                                <span class="font-black text-gray-900 text-sm" x-text="getServiceQuantity(item.id)"></span>
                                                            </div>

                                                            <!-- Increase Button -->
                                                            <button type="button" 
                                                                    @click.stop="updateServiceQuantity(item.id, item.price, 1)"
                                                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-white shadow-sm text-primary hover:bg-primary hover:text-white transition-all">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" @click="step--" class="py-3 px-4 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Geri</button>
                            <button type="button" @click="step++" class="py-3 px-4 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition shadow-lg shadow-primary/20">İlerle</button>
                        </div>
                    </div>

                    <!-- STEP 3: Payment -->
                    <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                        
                        <!-- Summary -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Rezervasyon:</span>
                                <span class="font-bold text-gray-900" x-text="selectedDate + ' ' + selectedTime"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Kişi:</span>
                                <span class="font-bold text-gray-900" x-text="guests + ' Kişi'"></span>
                            </div>

                            <!-- Selected Services Summary -->
                            <template x-if="Object.keys(selectedServices).length > 0">
                                <div class="border-t border-gray-200 pt-2 mt-2">
                                    <div class="text-xs font-bold text-gray-400 uppercase mb-1">Seçilen Hizmetler</div>
                                    <template x-for="(service, id) in selectedServices" :key="id">
                                        <div class="flex justify-between text-xs py-0.5">
                                            <span class="text-gray-600" x-text="(menus.find(m => m.id == id)?.name || 'Hizmet') + ' (x' + service.quantity + ')'"></span>
                                            <span class="font-medium text-gray-900" x-text="formatPrice(service.price * service.quantity)"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="discountAmount > 0">
                                <div class="flex justify-between text-green-600">
                                    <span>İndirim (Kupon):</span>
                                    <span class="font-bold" x-text="'-' + formatPrice(discountAmount)"></span>
                                </div>
                            </template>
                            <div class="flex justify-between text-base border-t border-gray-200 pt-2 mt-2">
                                <span class="font-bold text-gray-900">Toplam Tutar:</span>
                                <span class="font-black text-primary text-lg" x-text="formatPrice(finalAmount)"></span>
                            </div>
                        </div>

                        <!-- Coupon Code -->
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kupon Kodu</label>
                            <div class="flex gap-2">
                                <input type="text" x-model="couponCode" placeholder="REZERVIST10" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all uppercase">
                                <button type="button" @click="applyCoupon()" class="px-4 py-2 bg-gray-800 text-white font-bold rounded-xl hover:bg-gray-900 transition whitespace-nowrap">Uygula</button>
                            </div>
                            <p x-show="couponMessage" class="text-xs font-bold mt-2" :class="couponValid ? 'text-green-600' : 'text-red-500'" x-text="couponMessage"></p>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-8">
                             <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Ödeme Yöntemi</label>
                             <div class="grid grid-cols-1 gap-3">
                                <label class="flex items-center p-4 border rounded-xl cursor-pointer transition-all" :class="paymentMethod === 'card' ? 'border-primary bg-primary/5 ring-1 ring-primary' : 'border-gray-200 hover:border-gray-300'">
                                    <input type="radio" name="payment_method" value="card" x-model="paymentMethod" class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                                    <div class="ml-3 flex-1">
                                        <div class="font-bold text-gray-900 text-sm">Kredi Kartı ile Öde</div>
                                        <div class="text-xs text-gray-500">Güvenli online ödeme</div>
                                    </div>
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </label>
                                
                                <label class="flex items-center p-4 border rounded-xl cursor-pointer transition-all" :class="paymentMethod === 'wallet' ? 'border-primary bg-primary/5 ring-1 ring-primary' : 'border-gray-200 hover:border-gray-300'">
                                    <input type="radio" name="payment_method" value="wallet" x-model="paymentMethod" class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                                    <div class="ml-3 flex-1">
                                        <div class="font-bold text-gray-900 text-sm">Cüzdan Bakiyesi</div>
                                        <div class="text-xs text-gray-500">Mevcut Bakiye: {{ Auth::user()->wallet_balance ?? 0 }} TL</div>
                                    </div>
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </label>
                             </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" @click="step--" class="py-3 px-4 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Geri</button>
                            <button type="submit" 
                                    :disabled="isSubmitting"
                                    class="py-3 px-4 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-600/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                                <span x-show="!isSubmitting">Rezervasyonu Tamamla</span>
                                <svg x-show="isSubmitting" class="animate-spin h-5 w-5 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                </form>

                <!-- Custom Numeric Keypad (Bottom Sheet) -->
                <div x-show="showKeypad" 
                     @click.self="closeKeypad"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-full"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-full"
                     class="fixed inset-0 z-[100] flex items-end justify-center bg-black/20 backdrop-blur-[2px] sm:items-center"
                     style="display: none;">
                    
                    <div class="w-full sm:w-[320px] bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden pb-6 sm:pb-0">
                        <!-- Keypad Header / Display -->
                        <div class="bg-gray-50 border-b border-gray-100 p-4 flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Miktar Giriniz</span>
                            <button @click="closeKeypad" class="p-2 hover:bg-gray-200 rounded-full transition">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Active Value Display -->
                        <div class="py-6 flex justify-center bg-white">
                            <span class="text-5xl font-black text-primary tracking-tight" x-text="keypadValue || '0'"></span>
                        </div>

                        <!-- Keypad Grid -->
                        <div class="grid grid-cols-3 gap-px bg-gray-100 border-t border-gray-100">
                            <template x-for="num in [1, 2, 3, 4, 5, 6, 7, 8, 9]" :key="num">
                                <button @click="onKeypadInput(num)" class="h-16 bg-white hover:bg-gray-50 active:bg-gray-100 flex items-center justify-center text-2xl font-bold text-gray-800 transition select-none">
                                    <span x-text="num"></span>
                                </button>
                            </template>
                            
                            <!-- Bottom Row -->
                            <button class="h-16 bg-white hover:bg-gray-50 flex items-center justify-center" @click="closeKeypad"> <!-- Placeholder/Cancel -->
                                <span class="text-xs font-bold text-gray-400">İPTAL</span>
                            </button>
                            <button @click="onKeypadInput(0)" class="h-16 bg-white hover:bg-gray-50 active:bg-gray-100 flex items-center justify-center text-2xl font-bold text-gray-800 transition select-none">0</button>
                            <button @click="onKeypadBackspace" class="h-16 bg-white hover:bg-gray-50 active:bg-gray-100 flex items-center justify-center text-gray-500 transition select-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"></path></svg>
                            </button>
                        </div>

                        <!-- Submit Button -->
                         <div class="p-4 bg-white border-t border-gray-100">
                            <button @click="onKeypadSubmit" class="w-full py-4 bg-primary text-white font-bold rounded-2xl text-lg shadow-lg shadow-primary/30 active:scale-[0.98] transition-transform">
                                Tamam
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Contact / Map Widget (Moved Down) -->

            <!-- FAQ Section -->
            <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-8 h-8 bg-primary/10 text-primary rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                    Sıkça Sorulan Sorular
                </h3>
                <div class="space-y-4" x-data="{ openFaq: null }">
                    <div class="border border-gray-100 rounded-xl overflow-hidden">
                        <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full flex items-center justify-between p-4 bg-gray-50/50 hover:bg-gray-50 transition text-left">
                            <span class="font-bold text-gray-700 text-sm">Rezervasyon için ön ödeme gerekiyor mu?</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="openFaq === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="openFaq === 1" x-collapse class="p-4 bg-white text-xs text-gray-500 leading-relaxed">
                            Evet, sistemimiz 100% güvenli online ödeme altyapısı ile çalışmaktadır. Rezervasyonunuzu kesinleştirmek için ödemenizi Kredi Kartı veya Cüzdan bakiyenizle yapmanız gerekmektedir.
                        </div>
                    </div>
                    <div class="border border-gray-100 rounded-xl overflow-hidden">
                        <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full flex items-center justify-between p-4 bg-gray-50/50 hover:bg-gray-50 transition text-left">
                            <span class="font-bold text-gray-700 text-sm">Rezervasyonumu nasıl iptal edebilirim?</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="openFaq === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="openFaq === 2" x-collapse class="p-4 bg-white text-xs text-gray-500 leading-relaxed">
                            Profilinizdeki "Rezervasyonlarım" sekmesinden veya işletmeyi arayarak kolayca iptal edebilirsiniz. Son 2 saate kadar ücretsiz iptal hakkınız bulunmaktadır.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Tab -->
    <div x-show="tab === 'gallery'" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" x-data="{ lightboxOpen: false, activeImage: '' }">
        @forelse($business->images as $image)
            <div class="relative group cursor-pointer overflow-hidden rounded-xl aspect-square" @click="lightboxOpen = true; activeImage = '{{ Storage::url($image->image_path) }}'">
                <img src="{{ Storage::url($image->image_path) }}" alt="{{ $business->name }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-200 flex flex-col items-center">
                <div class="w-20 h-20 bg-white rounded-full shadow-sm flex items-center justify-center mb-4 text-gray-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-gray-900 font-black text-lg">Görsel Henüz Eklenmedi</p>
                <p class="text-gray-400 text-sm mt-1 max-w-xs mx-auto">İşletme henüz galeri görseli paylaşmadı, çok yakında burada olacak.</p>
            </div>
        @endforelse

        <!-- Simple Lightbox -->
        <div x-show="lightboxOpen" 
             x-transition.opacity
             @keydown.escape.window="lightboxOpen = false"
             class="fixed inset-0 z-[100] bg-black/90 flex items-center justify-center p-4 backdrop-blur-sm">
            <button @click="lightboxOpen = false" class="absolute top-6 right-6 text-white/80 hover:text-white transition">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <img :src="activeImage" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl" @click.outside="lightboxOpen = false">
        </div>
    </div>

    <!-- Menu Content -->
    <!-- Menu Content (New Purple & White Theme) -->
    <div x-show="tab === 'menu'" 
         x-data="{ 
            activeCategory: null, 
            showModal: false,
            selectedItem: null,
            init() {
                // Wait for Alpine to initialize and tab to be visible
                this.$watch('tab', value => {
                    if (value === 'menu') {
                        setTimeout(() => this.initObserver(), 100);
                    }
                });
                if (this.tab === 'menu') this.initObserver();
            },
            initObserver() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.activeCategory = entry.target.id.replace('cat-', '');
                            const navItem = document.getElementById('nav-' + this.activeCategory);
                            if (navItem) {
                                navItem.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                            }
                        }
                    });
                }, { rootMargin: '-100px 0px -50% 0px' });

                document.querySelectorAll('.category-section').forEach(section => {
                    observer.observe(section);
                });
            },
            openItem(item) {
                this.selectedItem = item;
                this.showModal = true;
                document.body.style.overflow = 'hidden';
            },
            closeModal() {
                this.showModal = false;
                setTimeout(() => this.selectedItem = null, 300);
                document.body.style.overflow = '';
            }
         }" class="space-y-6">

        @if($business->menus->isEmpty())
             <div class="py-20 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-200 flex flex-col items-center">
                <div class="w-20 h-20 bg-white rounded-full shadow-sm flex items-center justify-center mb-4 text-purple-200">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <p class="text-gray-900 font-black text-lg">Menü henüz eklenmedi</p>
                <p class="text-gray-400 text-sm mt-1 max-w-xs mx-auto">İşletme tarafından tanımlanmış bir menü veya hizmet bulunmuyor.</p>
            </div>
        @else
            
            {{-- Sticky Category Navigation --}}
            <div class="sticky top-[76px] z-30 bg-white/95 backdrop-blur-md border-b border-purple-100 shadow-sm py-3 px-4 -mx-4 mb-6">
                <div class="flex gap-3 overflow-x-auto hide-scrollbar pb-1 snap-x">
                    <button 
                        @click="activeCategory = null; window.scrollTo({top: 0, behavior: 'smooth'})"
                        class="px-5 py-2.5 rounded-full text-sm font-bold whitespace-nowrap transition-all duration-300 border snap-start outline-none focus:ring-2 focus:ring-primary/50"
                        :class="activeCategory === null 
                            ? 'bg-primary text-white border-primary shadow-lg shadow-primary/25 scale-105' 
                            : 'bg-white text-gray-500 border-gray-100 hover:border-purple-200 hover:bg-purple-50'">
                        Tümü
                    </button>
                    @foreach($groupedMenus as $category => $items)
                    <button 
                        id="nav-{{ Str::slug($category) }}"
                        @click="activeCategory = '{{ Str::slug($category) }}'; document.getElementById('cat-{{ Str::slug($category) }}').scrollIntoView({behavior: 'smooth', block: 'center'})"
                        class="px-5 py-2.5 rounded-full text-sm font-bold whitespace-nowrap transition-all duration-300 border snap-start outline-none focus:ring-2 focus:ring-primary/50"
                        :class="activeCategory === '{{ Str::slug($category) }}' 
                            ? 'bg-primary text-white border-primary shadow-lg shadow-primary/25 scale-105' 
                            : 'bg-white text-gray-500 border-gray-100 hover:border-purple-200 hover:bg-purple-50'">
                        {{ $category }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Menu Grid --}}
            <div class="space-y-12">
                @foreach($groupedMenus as $category => $items)
                <div id="cat-{{ Str::slug($category) }}" class="category-section scroll-mt-40">
                    
                    {{-- Category Header --}}
                    <div class="flex items-center gap-4 mb-6">
                        <h2 class="text-2xl font-black text-slate-800 tracking-tight">{{ $category }}</h2>
                        <div class="h-px bg-gradient-to-r from-purple-200 to-transparent flex-1"></div>
                    </div>

                    {{-- Items Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($items as $item)
                        <div @click="openItem({{ json_encode($item) }})" 
                             class="group bg-white rounded-2xl p-3 border border-gray-100 shadow-sm hover:shadow-soft hover:border-purple-100 transition-all duration-300 cursor-pointer active:scale-[0.98] flex gap-4 h-full relative overflow-hidden">
                            
                            {{-- Hover Glow --}}
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-500/0 via-purple-500/0 to-purple-500/0 group-hover:from-purple-50/50 group-hover:to-transparent transition-all duration-500"></div>

                            {{-- Image --}}
                            <div class="w-28 h-28 flex-shrink-0 relative rounded-xl overflow-hidden bg-gray-50">
                                @if($item->image)
                                    <img src="{{ Storage::url($item->image) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-purple-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 ring-1 ring-black/5 rounded-xl"></div>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 flex flex-col justify-between py-1 relative">
                                <div>
                                    <h3 class="font-bold text-gray-900 leading-tight mb-1 group-hover:text-primary transition-colors">{{ $item->name }}</h3>
                                    <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ $item->description }}</p>
                                </div>
                                <div class="flex items-center justify-between mt-3">
                                    <span class="font-black text-lg text-primary tracking-tight">{{ number_format($item->price, 2) }}₺</span>
                                    <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-300 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Product Modal (Alpine.js) --}}
            <div x-show="showModal" 
                 style="display: none;"
                 class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center pointer-events-none">
                
                {{-- Backdrop --}}
                <div x-show="showModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="closeModal"
                     class="absolute inset-0 bg-black/60 backdrop-blur-sm pointer-events-auto"></div>

                {{-- Modal Card --}}
                <div x-show="showModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="translate-y-full sm:scale-95 sm:opacity-0"
                     x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="translate-y-0 sm:scale-100 sm:opacity-100"
                     x-transition:leave-end="translate-y-full sm:scale-95 sm:opacity-0"
                     class="bg-white w-full max-w-lg rounded-t-[2rem] sm:rounded-[2rem] shadow-2xl overflow-hidden pointer-events-auto max-h-[90vh] flex flex-col relative m-0 sm:m-4">
                    
                    {{-- Close Button --}}
                    <button @click="closeModal" class="absolute top-4 right-4 z-10 w-9 h-9 bg-white/80 backdrop-blur rounded-full flex items-center justify-center shadow-sm text-gray-500 hover:text-gray-900 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    {{-- Modal Image --}}
                    <div class="h-64 sm:h-72 bg-gray-100 relative shrink-0">
                        <template x-if="selectedItem?.image">
                            <img :src="'/storage/' + selectedItem.image" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!selectedItem?.image">
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </template>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    </div>

                    {{-- Modal Content --}}
                    <div class="p-6 overflow-y-auto">
                        <h3 x-text="selectedItem?.name" class="text-2xl font-black text-gray-900 leading-tight mb-2"></h3>
                        <span x-text="selectedItem ? new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(selectedItem.price) : ''" class="text-3xl font-bold text-primary block mb-4"></span>
                        <p x-text="selectedItem?.description" class="text-gray-600 leading-relaxed text-base"></p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Reviews Tab -->
    <div x-show="tab === 'reviews'" class="space-y-6">
        @auth
            @if($canReview)
                <div class="mt-8 bg-gray-50 rounded-2xl p-8 border border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('common.reviews.share_experience') }}</h3>
                    <form id="reviewForm" class="space-y-6">
                        <input type="hidden" name="business_id" value="{{ $business->id }}">
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('common.reviews.your_rating') }}</label>
                            <div class="flex items-center space-x-2" x-data="{ hover: 0, rating: 0 }">
                                <template x-for="i in 5">
                                    <button type="button" 
                                            @click="rating = i; $refs.ratingInput.value = i"
                                            @mouseenter="hover = i"
                                            @mouseleave="hover = 0"
                                            class="p-1 focus:outline-none transition-transform hover:scale-125">
                                        <svg class="w-10 h-10" :class="(hover || rating) >= i ? 'text-yellow-400 fill-current' : 'text-gray-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.518 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                    </button>
                                </template>
                                <input type="hidden" name="rating" x-ref="ratingInput" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('common.reviews.your_comment') }}</label>
                            <textarea name="comment" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition" placeholder="{{ __('common.reviews.comment_placeholder') }}"></textarea>
                        </div>

                        <button type="submit" class="bg-primary text-white font-bold px-8 py-3 rounded-xl hover:bg-opacity-90 transition shadow-lg shadow-primary/20">
                            {{ __('common.reviews.submit_review') }}
                        </button>
                    </form>
                </div>
            @else
                <div class="mt-8 bg-amber-50 rounded-xl p-6 text-center border border-amber-200">
                    <p class="text-amber-800 font-medium">Bu işletmeyi değerlendirmek için tamamlanmış bir rezervasyonunuzun olması gerekmektedir.</p>
                </div>
            @endif
        @else
            <div class="mt-8 bg-gray-50 rounded-xl p-6 text-center border border-dashed border-gray-300">
                <p class="text-gray-600">{{ __('common.reviews.please_login_to_review_pre') }} <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">{{ __('common.reviews.login_link') }}</a>.</p>
            </div>
        @endauth
        @if($business->approvedReviews->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-xl">
                <p class="text-gray-500">{{ __('common.reviews.no_reviews') }}</p>
                @if(auth()->check() && $canReview)
                    <button class="mt-4 text-primary font-bold" @click="document.getElementById('reviewForm').scrollIntoView({behavior: 'smooth'})">{{ __('common.reviews.write_review') }}</button>
                @endif
            </div>
        @else
            @foreach($business->approvedReviews as $review)
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold mr-3">
                            {{ substr($review->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">{{ $review->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div class="bg-yellow-50 px-2 py-1 rounded text-yellow-600 font-bold text-sm border border-yellow-100">
                        ★ {{ $review->rating }}
                    </div>
                </div>
                <p class="text-gray-600">{{ $review->comment }}</p>
            </div>
            @endforeach
        @endif
    </div>
</div>



<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('reservationWidget', () => ({
            step: 1,
            showGuestPicker: false,
            
            // Calendar Data
            showDatePicker: false,
            selectedDate: '',
            selectedDateFormatted: '',
            month: new Date().getMonth(),
            year: new Date().getFullYear(),
            noOfDays: [],
            blankDays: [],
            days: ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'],
            monthNames: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],

            get guestText() {
                let text = `${this.adults} Yetişkin`;
                if (this.children > 0) {
                    text += `, ${this.children} Çocuk`;
                }
                return text;
            },
            selectedTime: '',
            adults: 2,
            children: 0,
            get guests() {
                return this.adults + this.children;
            },
            slots: [],
            slotMessage: '',
            isLoadingSlots: false,
            businessIsClosed: false,
            
            // Services (Quantity Map: { id: { price: 100, quantity: 1 } })
            selectedServices: {},

            // Pricing
            basePrice: 0,
            totalAmount: 0, 
            finalAmount: 0,
            discountAmount: 0,
            couponCode: '',
            couponValid: false,
            couponMessage: '',
            paymentMethod: 'card',
            searchQuery: '',
            openCategories: [],

            isSubmitting: false,
            minReservationAmount: {{ $business->min_reservation_amount ?? 0 }},

            init() {
                // Default to Tomorrow
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                
                this.month = tomorrow.getMonth();
                this.year = tomorrow.getFullYear();
                
                // Manually select tomorrow
                const y = tomorrow.getFullYear();
                const m = String(tomorrow.getMonth() + 1).padStart(2, '0');
                const d = String(tomorrow.getDate()).padStart(2, '0');
                
                this.selectedDate = `${y}-${m}-${d}`;
                this.selectedDateFormatted = `${tomorrow.getDate()} ${this.monthNames[this.month]} ${this.year}`;
                
                this.getDays();
                this.fetchSlots(); // Force fetch explicitly

                this.getDays();
                this.calculateTotal();
                
                // Initialize all categories as open
                const categories = @js($business->menus->pluck('category')->unique()->toArray());
                this.openCategories = categories.map(c => c || '');

                this.$watch('searchQuery', (val) => {
                    // Open all categories if searching, close if empty (initial state)
                    if (val) {
                        this.openCategories = Object.keys(@js($business->menus->groupBy('category')->toArray()));
                    }
                });
                this.$watch('selectedDate', (val) => {
                    if(val) this.fetchSlots();
                });
                this.$watch('adults', (val) => {
                    if(this.selectedDate) this.fetchSlots();
                });
                this.$watch('children', (val) => {
                    if(this.selectedDate) this.fetchSlots();
                });
            },
            
            formatPrice(value) {
                return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY', minimumFractionDigits: 2 }).format(value);
            },

            // Calendar Methods
            getDays() {
                let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                let dayOfWeek = new Date(this.year, this.month).getDay();
                let blankdaysArray = [];
                let adjustedDay = dayOfWeek === 0 ? 6 : dayOfWeek - 1; 

                for (var i = 1; i <= adjustedDay; i++) {
                    blankdaysArray.push(i);
                }

                let daysArray = [];
                for (var i = 1; i <= daysInMonth; i++) {
                    daysArray.push(i);
                }

                this.blankDays = blankdaysArray;
                this.noOfDays = daysArray;
            },

            prevMonth() {
                if (this.month === 0) {
                    this.month = 11;
                    this.year--;
                } else {
                    this.month--;
                }
                this.getDays();
            },

            nextMonth() {
                if (this.month === 11) {
                    this.month = 0;
                    this.year++;
                } else {
                    this.month++;
                }
                this.getDays();
            },

            // Service Logic
            menus: @js($business->menus), // Make menus available globally in the component

            getServiceQuantity(id) {
                return this.selectedServices[id] ? this.selectedServices[id].quantity : 0;
            },

            setServiceQuantity(id, price, val) {
                const qty = parseInt(val);
                if (isNaN(qty) || qty < 0) return;
                
                if (qty === 0) {
                    delete this.selectedServices[id];
                } else {
                    this.selectedServices[id] = { price: price, quantity: qty };
                }
                // Trigger reactivity
                this.selectedServices = { ...this.selectedServices };
                this.calculateTotal();
            },

            updateServiceQuantity(id, price, delta) {
                if (!this.selectedServices[id]) {
                    if (delta > 0) {
                        this.selectedServices[id] = { price: price, quantity: delta };
                    }
                } else {
                    this.selectedServices[id].quantity += delta;
                    if (this.selectedServices[id].quantity <= 0) {
                        delete this.selectedServices[id];
                    }
                }
                // Determine layout update trigger
                this.selectedServices = { ...this.selectedServices };
                this.calculateTotal();
            },

            isPast(date) {
                const d = new Date(this.year, this.month, date);
                const today = new Date();
                today.setHours(0,0,0,0);
                return d < today;
            },

            isSelected(date) {
                if (!this.selectedDate) return false;
                const d = new Date(this.year, this.month, date);
                const sel = new Date(this.selectedDate);
                return d.getFullYear() === sel.getFullYear() && 
                       d.getMonth() === sel.getMonth() && 
                       d.getDate() === sel.getDate();
            },

            // Custom Keypad Logic
            showKeypad: false,
            activeKeypadItem: null,
            keypadValue: '',

            openKeypad(itemId, currentQty) {
                this.activeKeypadItem = itemId;
                this.keypadValue = currentQty > 0 ? String(currentQty) : '';
                this.showKeypad = true;
                if (window.navigator.vibrate) try { window.navigator.vibrate(10); } catch(e) {}
            },

            onKeypadInput(num) {
                if (this.keypadValue.length >= 3) return; // Max 3 digits
                this.keypadValue += num;
                if (window.navigator.vibrate) try { window.navigator.vibrate(5); } catch(e) {}
            },

            onKeypadBackspace() {
                this.keypadValue = this.keypadValue.slice(0, -1);
                if (window.navigator.vibrate) try { window.navigator.vibrate(5); } catch(e) {}
            },

            onKeypadSubmit() {
                if (this.activeKeypadItem) {
                    const qty = parseInt(this.keypadValue) || 0;
                    
                    // Find item in the global menus list
                    const item = this.menus.find(i => i.id === this.activeKeypadItem);
                    
                    if (item) {
                        const price = parseFloat(item.price);
                        this.setServiceQuantity(this.activeKeypadItem, price, qty);
                    }
                }
                this.closeKeypad();
            },

            closeKeypad() {
                this.showKeypad = false;
                this.activeKeypadItem = null;
                this.keypadValue = '';
            },

            getDateValue(date) {
                if(this.isPast(date)) return;

                let d = new Date(this.year, this.month, date);
                const y = d.getFullYear();
                const m = String(d.getMonth() + 1).padStart(2, '0');
                const dayStr = String(d.getDate()).padStart(2, '0');
                this.selectedDate = `${y}-${m}-${dayStr}`;
                
                this.selectedDateFormatted = `${date} ${this.monthNames[this.month]} ${this.year}`;
                this.showDatePicker = false;
                
                this.fetchSlots();
            },

            async fetchSlots() {
                if (!this.selectedDate) return;
                
                this.isLoadingSlots = true;
                this.slots = [];
                this.selectedTime = '';
                this.slotMessage = '';
                this.businessIsClosed = false;

                try {
                    const response = await fetch(`/business/{{ $business->slug }}/slots?date=${this.selectedDate}&guest_count=${this.guests}`);
                    const data = await response.json();
                    
                    if (data.is_closed) {
                        this.businessIsClosed = true;
                        this.slots = [];
                        this.slotMessage = data.message || 'İşletme seçilen tarihte kapalıdır.';
                    } else {
                        this.businessIsClosed = false;
                        this.slots = data.slots || [];
                        this.basePrice = data.total_base_price || 0;
                        if (this.slots.length === 0) {
                            this.slotMessage = data.message || 'Bu tarihte uygun saat bulunamadı.';
                        }
                    }
                    this.calculateTotal();
                } catch (e) {
                    console.error('Error fetching slots:', e);
                    this.slotMessage = 'Saatler yüklenirken bir hata oluştu.';
                } finally {
                    this.isLoadingSlots = false;
                }
            },

            // Service Logic
            getServiceQuantity(id) {
                return this.selectedServices[id] ? this.selectedServices[id].quantity : 0;
            },

            updateServiceQuantity(id, price, delta) {
                if (!this.selectedServices[id]) {
                    if (delta > 0) {
                        this.selectedServices[id] = { price: price, quantity: delta };
                    }
                } else {
                    this.selectedServices[id].quantity += delta;
                    if (this.selectedServices[id].quantity <= 0) {
                        delete this.selectedServices[id];
                    }
                }
                // Determine layout update trigger
                this.selectedServices = { ...this.selectedServices };
                this.calculateTotal();
            },

            calculateTotal() {
                let serviceTotal = 0;
                Object.values(this.selectedServices).forEach(item => {
                    serviceTotal += (item.price * item.quantity);
                });
                this.totalAmount = this.basePrice + serviceTotal;
                this.applyDiscount();
            },
            
            async applyCoupon() {
                if(!this.couponCode) return;

                try {
                    const response = await fetch('{{ route('coupons.check') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            code: this.couponCode,
                            amount: this.totalAmount
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.valid) {
                        this.couponValid = true;
                        this.couponMessage = data.message;
                        this.discountAmount = data.discount_amount;
                        this.applyDiscount();
                    } else {
                        this.couponValid = false;
                        this.couponMessage = data.message;
                        this.discountAmount = 0;
                        this.applyDiscount();
                    }

                } catch (e) {
                    this.couponMessage = 'Kupon sorgulanırken hata oluştu.';
                }
            },

            applyDiscount() {
                this.finalAmount = Math.max(0, this.totalAmount - this.discountAmount);
            },

            nextStep() {
                if(this.step === 1) {
                    if (this.businessIsClosed) {
                         showToast('İşletme seçilen tarihte kapalıdır.', 'error');
                         return;
                    }
                    if(!this.selectedDate || !this.selectedTime) {
                        showToast('Lütfen tarih ve saat seçiniz.', 'warning');
                        return;
                    }
                }
                this.step++;
            },

            async submitReservation() {
                if (this.isSubmitting) return;
                this.isSubmitting = true;

                // Transform selectedServices to array
                const servicesPayload = Object.entries(this.selectedServices).map(([id, item]) => ({
                    id: id,
                    quantity: item.quantity
                }));

                const payload = {
                    business_id: {{ $business->id }},
                    reservation_date: this.selectedDate,
                    reservation_time: this.selectedTime,
                    guest_count: this.guests,
                    services: servicesPayload,
                    coupon_code: this.couponCode,
                    payment_method: this.paymentMethod
                };

                try {
                    const response = await fetch('{{ route('reservations.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        showToast('Rezervasyon başarıyla oluşturuldu! Yönlendiriliyorsunuz...', 'success');
                        setTimeout(() => {
                             window.location.href = result.payment_url || '/reservations'; 
                        }, 1500);
                    } else {
                        showToast(result.message || 'Bir hata oluştu.', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    showToast('Beklenmedik bir hata oluştu.', 'error');
                } finally {
                    this.isSubmitting = false;
                }
            }
        }));
    });
</script>

<script>
    document.getElementById('reviewForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            business_id: formData.get('business_id'),
            rating: parseInt(formData.get('rating')),
            comment: formData.get('comment')
        };
        
        if (!data.rating) {
            showToast('{{ __('common.reviews.rating_required') }}', 'warning');
            return;
        }
        
        try {
            const response = await fetch('/business/{{ $business->slug }}/reviews', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + (window.localStorage.getItem('auth_token') || '') // Fallback for API
                },
                credentials: 'same-origin',
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (response.ok) {
                showToast('{{ __('common.reviews.success') }}', 'success');
                this.reset();
                setTimeout(() => location.reload(), 1500);
            } else if (response.status === 401) {
                showToast(result.message || 'Lütfen önce giriş yapın.', 'warning');
                setTimeout(() => window.location.href = '/login', 2000);
            } else {
                showToast(result.message || 'Bir hata oluştu', 'error');
            }
        } catch (error) {
            console.error('Review submission error:', error);
            showToast('Bağlantı hatası', 'error');
        }
    });
</script>

@endsection
{{-- Force Recompile --}}

