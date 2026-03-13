@extends('layouts.app')

@section('title', ($globalSettings['site_name'] ?? config('app.name')) . ' | En İyi Online Rezervasyon Platformu | Restoran ve Kafe Rezervasyonu')
@section('meta_description', 'Türkiye\'nin lider online rezervasyon platformu. İstanbul, Ankara, İzmir ve tüm Türkiye\'de restoran ve kafe rezervasyonu için hemen ücretsiz rezervasyon yapın. 7/24 hizmet!')
@section('meta_keywords', 'online rezervasyon, restoran rezervasyon, masa rezervasyonu, cafe rezervasyon, istanbul restoran, ankara cafe, izmir restoran, türkiye rezervasyon, ücretsiz rezervasyon, hemen rezervasyon yap')

@section('content')
<!-- Hero Section -->
<div class="relative min-h-screen flex flex-col justify-center overflow-hidden bg-[#0a0a0b]">
    <!-- Sophisticated Background Elements -->
    <!-- Sophisticated Background Elements -->
    <div class="absolute inset-0 z-0">
        <!-- New "Foot" (Photo) - High End Restaurant/Reservation Vibe -->
        <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?w=1920&q=80" class="w-full h-full object-cover opacity-60 mix-blend-normal" alt="Hero Background">
        <!-- Much reduced gradients for brighter bottom -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/20 to-transparent"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(0,0,0,0.3)_0%,rgba(0,0,0,0.6)_100%)]"></div>
    </div>

    <!-- Animated Glows -->
    <div class="absolute top-1/4 -left-20 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[120px] animate-pulse" style="animation-duration: 4s"></div>
    <div class="absolute bottom-1/4 -right-20 w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-[120px] animate-pulse" style="animation-duration: 6s"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-40 relative z-10 text-center w-full">

        <h1 class="text-3xl sm:text-5xl md:text-7xl lg:text-8xl font-black mb-6 sm:mb-8 tracking-tighter text-white leading-[1.1] sm:leading-[1.1]">
            <span class="inline-block opacity-90 mr-2 lg:mr-4">{{ __('home.hero.title_prefix') }}</span>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-300 drop-shadow-2xl">
                {{ __('home.hero.title_highlight') }}
            </span>
            <span class="block mt-2 opacity-95 text-xl sm:text-3xl md:text-5xl lg:text-6xl font-bold tracking-tight text-white/80">
                {{ __('home.hero.title_suffix') }}
            </span>
        </h1>
        
        <p class="text-base sm:text-lg md:text-xl text-white/50 mb-10 sm:mb-16 max-w-2xl mx-auto font-medium leading-relaxed tracking-wide px-4">
            {{ __('home.hero.subtitle') }}
        </p>
        
        <!-- Advanced OTA Search Box -->
        <div class="max-w-5xl mx-auto -mt-8 relative z-20 px-2 md:px-0">
            <form action="/search" method="GET" class="bg-white p-3 md:p-1 rounded-3xl md:rounded-lg shadow-2xl border border-gray-100 grid grid-cols-1 md:grid-cols-12 gap-2 md:gap-0">
                <!-- Location (with Autocomplete) -->
                <div class="md:col-span-4 bg-white rounded-2xl md:rounded-none flex items-center px-4 py-4 md:py-3 relative group border border-gray-100 md:border-0" x-data="autocomplete" x-cloak>
                    <div class="flex-grow">
                        <label class="block text-[10px] md:text-xs font-bold text-slate-400 mb-1 text-left uppercase tracking-tighter">{{ __('home.search.label_location') }}</label>
                        <input type="text" 
                               name="q" 
                               x-model="searchQuery"
                               @input.debounce.300ms="fetchSuggestions()"
                               @focus="showDropdown = true"
                               placeholder="{{ __('home.search.placeholder_location') }}" 
                               class="w-full text-slate-900 font-black focus:outline-none placeholder-slate-400 text-base md:text-[15px] bg-transparent">
                    </div>
                    <svg class="w-5 h-5 text-primary ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    
                    <!-- Autocomplete Dropdown -->
                    <div x-show="showDropdown && suggestions.length > 0" 
                         @click.away="showDropdown = false"
                         class="absolute top-full left-0 right-0 mt-3 bg-white rounded-2xl shadow-2xl border border-gray-100 z-[60] max-h-96 overflow-y-auto overflow-x-hidden">
                        <template x-for="(item, index) in suggestions" :key="index">
                             <div @click="selectItem(item)" 
                                  class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-50 last:border-0 transition group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center mr-3 group-hover:bg-primary/20 transition flex-shrink-0">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-grow text-left min-w-0">
                                        <div class="font-bold text-gray-900 text-sm truncate" x-text="item.name"></div>
                                        <div class="text-xs text-gray-500 flex items-center mt-0.5">
                                            <svg class="w-3 h-3 mr-1 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            </svg>
                                            <span class="truncate" x-text="item.subtitle"></span>
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </template>
                    </div>
                </div>

                <!-- Date -->
                <div class="md:col-span-3 bg-white rounded-2xl md:rounded-none flex items-center px-4 py-4 md:py-3 relative border border-gray-100 md:border-l md:border-t-0 md:border-y-0" 
                     x-data="datePicker" x-cloak>
                    <div class="flex-grow cursor-pointer" @click="showDatepicker = !showDatepicker">
                         <label class="block text-[10px] md:text-xs font-bold text-slate-400 mb-1 text-left uppercase tracking-tighter">{{ __('home.search.label_date') }}</label>
                         <input type="text" x-model="formattedDate" readonly class="w-full text-slate-900 font-black focus:outline-none cursor-pointer bg-transparent text-base md:text-[15px]" placeholder="{{ __('home.search.placeholder_date') }}">
                         <input type="hidden" name="date" x-model="selectedDate">
                    </div>
                    <svg class="w-5 h-5 text-primary ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>

                    <!-- Calendar Dropdown -->
                    <div x-show="showDatepicker" @click.away="showDatepicker = false" 
                         class="absolute top-full left-0 right-0 md:right-auto md:w-72 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 p-4 mt-2"
                         x-transition>
                        
                        <div class="flex justify-between items-center mb-4">
                            <button type="button" @click="prevMonth()" class="p-2 hover:bg-gray-100 rounded-full"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                            <span x-text="monthNames[month] + ' ' + year" class="font-bold text-gray-800"></span>
                            <button type="button" @click="nextMonth()" class="p-2 hover:bg-gray-100 rounded-full"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                        </div>

                        <div class="grid grid-cols-7 gap-1 mb-2">
                            <template x-for="day in days" :key="day">
                                <div class="text-center text-[10px] text-gray-400 font-bold uppercase" x-text="day"></div>
                            </template>
                        </div>

                        <div class="grid grid-cols-7 gap-1">
                            <template x-for="blank in blankDays">
                                <div class="text-center p-2"></div>
                            </template>
                            <template x-for="date in noOfDays" :key="date">
                                <div @click="!isPast(date) && selectDate(date)" 
                                     class="text-center py-2 rounded-lg text-sm transition font-semibold"
                                     :class="{
                                         'bg-primary text-white shadow-lg shadow-primary/30 cursor-pointer': isSelected(date),
                                         'text-gray-700 hover:bg-gray-100 cursor-pointer': !isSelected(date) && !isPast(date),
                                         'text-gray-300 bg-gray-50 cursor-not-allowed': isPast(date)
                                     }"
                                     x-text="date"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Guests -->
                <div class="md:col-span-3 bg-white rounded-2xl md:rounded-none flex items-center px-4 py-4 md:py-3 relative border border-gray-100 md:border-l md:border-t-0 md:border-y-0"
                     x-data="guestPicker" x-cloak>
                     <div class="flex-grow cursor-pointer" @click="showGuestPicker = !showGuestPicker">
                        <label class="block text-[10px] md:text-xs font-bold text-slate-400 mb-1 text-left uppercase tracking-tighter">{{ __('home.search.label_guests') }}</label>
                        <input type="text" x-model="guestText" readonly class="w-full text-slate-900 font-black focus:outline-none cursor-pointer bg-transparent text-base md:text-[15px]" placeholder="{{ __('home.search.placeholder_guests') }}">
                        <input type="hidden" name="guests" x-model="totalGuests">
                     </div>
                     <svg class="w-5 h-5 text-primary ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>

                     <!-- Guest Picker Dropdown -->
                     <div x-show="showGuestPicker" @click.away="showGuestPicker = false"
                          class="absolute top-full left-0 right-0 md:right-auto md:w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 p-6 mt-2"
                          x-transition>
                          
                         <!-- Adults -->
                         <div class="flex justify-between items-center mb-6 pb-6 border-b border-gray-50">
                             <div>
                                 <div class="font-bold text-gray-900">{{ __('home.search.adult') }}</div>
                                 <div class="text-xs text-gray-400">{{ __('home.search.adult_desc') }}</div>
                             </div>
                             <div class="flex items-center space-x-4">
                                 <button type="button" @click="adults > 1 ? adults-- : null"
                                         class="w-10 h-10 rounded-xl bg-gray-50 text-gray-600 font-bold flex items-center justify-center hover:bg-primary hover:text-white transition disabled:opacity-30 disabled:cursor-not-allowed" :disabled="adults <= 1">−</button>
                                 <span class="w-4 text-center font-bold text-gray-900" x-text="adults"></span>
                                 <button type="button" @click="adults < 20 ? adults++ : null"
                                         class="w-10 h-10 rounded-xl bg-gray-50 text-gray-600 font-bold flex items-center justify-center hover:bg-primary hover:text-white transition disabled:opacity-30 disabled:cursor-not-allowed" :disabled="adults >= 20">+</button>
                             </div>
                         </div>

                         <!-- Children -->
                         <div class="flex justify-between items-center mb-6">
                             <div>
                                 <div class="font-bold text-gray-900">{{ __('home.search.child') }}</div>
                                 <div class="text-xs text-gray-400">{{ __('home.search.child_desc') }}</div>
                             </div>
                             <div class="flex items-center space-x-4">
                                 <button type="button" @click="children > 0 ? children-- : null"
                                         class="w-10 h-10 rounded-xl bg-gray-50 text-gray-600 font-bold flex items-center justify-center hover:bg-primary hover:text-white transition disabled:opacity-30 disabled:cursor-not-allowed" :disabled="children <= 0">−</button>
                                 <span class="w-4 text-center font-bold text-gray-900" x-text="children"></span>
                                 <button type="button" @click="children < 20 ? children++ : null"
                                         class="w-10 h-10 rounded-xl bg-gray-50 text-gray-600 font-bold flex items-center justify-center hover:bg-primary hover:text-white transition disabled:opacity-30 disabled:cursor-not-allowed" :disabled="children >= 20">+</button>
                             </div>
                         </div>

                         <button type="button" @click="showGuestPicker = false"
                                 class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-primary/90 transition shadow-lg shadow-primary/20">
                             {{ __('home.search.apply') }}
                         </button>
                     </div>
                </div>
                <!-- Button -->
                <div class="md:col-span-2">
                     <button type="submit" class="w-full h-[56px] sm:h-[60px] md:h-full bg-primary hover:bg-primary/95 text-white font-black text-lg rounded-2xl md:rounded-r-lg border-2 border-white/30 hover:border-white transition-all flex items-center justify-center gap-3 active:scale-95 shadow-xl shadow-primary/20">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                         <span class="md:hidden lg:inline">{{ __('home.search.button') }}</span>
                         <span class="hidden md:inline lg:hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                         </span>
                     </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Featured Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('home.featured.title') }}</h2>
            <p class="text-gray-500 mt-1 sm:mt-2 text-sm sm:text-base">{{ __('home.featured.subtitle') }}</p>
        </div>
        <a href="/explore" class="text-primary font-semibold hover:text-primary/80 transition flex items-center gap-2">
            {{ __('home.featured.view_all') }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    @if($businesses->isEmpty())
        <div class="p-10 text-center bg-gray-50 rounded-xl border border-gray-200">
            <p class="text-gray-500 text-lg">{{ __('home.featured.empty') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($businesses as $business)
            <a href="/business/{{ $business->id }}" class="group block bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition duration-300">
                <div class="h-48 bg-gray-100 relative">
                    <!-- Business Image -->
                    <img src="{{ $business->getImageUrl(true) }}" alt="{{ $business->name }}" class="w-full h-full object-cover">
                    <div class="absolute top-4 right-4 flex flex-col items-end gap-2">
                        <div class="bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-sm font-bold text-gray-900 flex items-center shadow-sm">
                            <span class="mr-1 text-orange-500">★</span> {{ $business->rating }}
                        </div>
                        <div class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider backdrop-blur-md shadow-sm 
                            {{ !$business->hasHours() ? 'bg-gray-500/90 text-white' : ($business->is_open ? 'bg-green-500/90 text-white' : 'bg-red-500/90 text-white') }}">
                            {{ $business->status_text }}
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-xs font-bold text-primary mb-2 uppercase tracking-wide bg-primary/10 inline-block px-2 py-1 rounded">{{ $business->category }}</div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-primary transition">{{ $business->name }}</h3>
                        @if($business->is_verified)
                            <span class="inline-flex items-center justify-center bg-blue-500 text-white rounded-full p-0.5 shadow-sm -mt-2" title="Doğrulanmış">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-500 text-sm mb-4 line-clamp-2 h-10">{{ $business->description }}</p>
                    <div class="flex items-center text-gray-400 text-sm pt-4 border-t border-gray-100">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $business->address }}
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/home-components.js') }}"></script>
@endsection
