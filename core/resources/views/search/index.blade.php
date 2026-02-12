@extends('layouts.app')

@php
    $searchQuery = request('q');
    $seoTitle = $searchQuery 
        ? $searchQuery . ' için Sonuçlar | ' . ($globalSettings['site_name'] ?? 'Rezervist.com')
        : 'Tüm İşletmeler | Restoran, Kafe, Kuaför Rezervasyonu | ' . ($globalSettings['site_name'] ?? 'Rezervist.com');
    $seoDescription = $searchQuery
        ? $searchQuery . ' araması için ' . $businesses->total() . ' sonuç bulundu. Türkiye\'nin en iyi işletmelerini keşfedin ve online rezervasyon yapın.'
        : 'Türkiye\'nin en popüler restoranları, kafeleri, kuaförleri ve daha fazlasını keşfedin. Hemen online rezervasyon yapın!';
@endphp

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('meta_keywords', ($searchQuery ?? '') . ', rezervasyon, online randevu, masa rezervasyonu, kuaför randevu, türkiye işletmeler')

@section('content')
<div class="bg-gray-50 min-h-screen py-8" x-data="{ showFilters: false, showMap: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Mobile Filter Toggle -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="text-sm text-gray-500 truncate max-w-full">
                <a href="/" class="hover:text-primary">{{ __('common.menu.home') }}</a> / <span class="text-gray-900 font-semibold truncate">{{ __('search.results_title') }}</span>
            </div>
            <div class="lg:hidden w-full sm:w-auto">
                <button @click="showFilters = !showFilters" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-white border-2 border-primary/20 hover:border-primary px-6 py-3 rounded-xl text-primary font-black shadow-sm active:bg-gray-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    <span class="uppercase tracking-tighter text-xs">{{ __('search.sidebar_filter_title') }}</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 relative">
            
            <!-- Sidebar Filters -->
            <div class="lg:block space-y-6" 
                 :class="{ 'fixed inset-0 z-50 bg-white p-6 overflow-y-auto block': showFilters, 'hidden': !showFilters }">
                
                <div class="flex justify-between items-center lg:hidden mb-6">
                    <h2 class="text-xl font-bold">{{ __('search.sidebar_filter_title') }}</h2>
                    <button @click="showFilters = false" class="p-2 text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Map Widget -->
                <div @click="showMap = true" class="flex h-32 bg-gray-200 rounded-lg border border-gray-300 items-center justify-center cursor-pointer hover:bg-gray-300 transition relative overflow-hidden group">
                     <img src="https://maps.googleapis.com/maps/api/staticmap?center=41.0082,28.9784&zoom=10&size=400x200&sensor=false&key={{ config('services.google.maps_api_key') }}" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-110 transition-transform duration-700">
                     <button class="bg-primary text-white px-4 py-2 rounded shadow-lg font-bold relative z-10 hover:bg-primary-dark transition">{{ __('search.show_on_map') }}</button>
                </div>

                <form action="/search" method="GET" id="filterForm">
                    <!-- Preserve Query -->
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <input type="hidden" name="date" value="{{ request('date') }}">
                    <input type="hidden" name="guests" value="{{ request('guests') }}">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">

                    <!-- Distance Filter (Only if location exists) -->
                    @if(request()->hasCookie('user_lat') || request()->has('lat'))
                    <div class="bg-white p-6 lg:p-4 rounded-2xl lg:rounded-lg border border-gray-200 shadow-sm mb-4">
                        <h3 class="font-black text-primary mb-3 text-[10px] uppercase tracking-tighter">Mesafe (KM)</h3>
                        <div class="space-y-4">
                            <input type="range" name="distance_max" min="1" max="50" value="{{ request('distance_max', 10) }}" 
                                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-primary"
                                   oninput="this.nextElementSibling.innerText = this.value + ' km'"
                                   onchange="document.getElementById('filterForm').submit()">
                            <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase">
                                <span>1 km</span>
                                <span class="text-primary text-xs">{{ request('distance_max', 10) }} km</span>
                                <span>50 km</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Price Filter -->
                    <div class="bg-white p-6 lg:p-4 rounded-2xl lg:rounded-lg border border-gray-200 shadow-sm">
                        <h3 class="font-black text-primary mb-3 text-[10px] uppercase tracking-tighter">{{ __('search.budget_label') }}</h3>
                        <div class="space-y-3 lg:space-y-2">
                             <div class="grid grid-cols-2 gap-2 mb-2">
                                <div>
                                    <label class="text-[9px] font-bold text-gray-400 uppercase">Min</label>
                                    <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="0" 
                                           class="w-full text-xs border-gray-200 rounded-lg focus:ring-primary focus:border-primary p-2 bg-gray-50"
                                           onchange="document.getElementById('filterForm').submit()">
                                </div>
                                <div>
                                    <label class="text-[9px] font-bold text-gray-400 uppercase">Max</label>
                                    <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="5000" 
                                           class="w-full text-xs border-gray-200 rounded-lg focus:ring-primary focus:border-primary p-2 bg-gray-50"
                                           onchange="document.getElementById('filterForm').submit()">
                                </div>
                             </div>
                             
                             <div class="pt-2">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="price_range" value="0-500" {{ request('price_min') == '0' && request('price_max') == '500' ? 'checked' : '' }} class="form-radio text-primary rounded-full border-gray-300 h-4 w-4" onclick="this.form.price_min.value='0'; this.form.price_max.value='500'; this.form.submit()">
                                    <span class="ml-3 text-sm text-gray-600 group-hover:text-primary transition">0 - 500 TL</span>
                                </label>
                                <label class="flex items-center cursor-pointer group mt-2">
                                    <input type="radio" name="price_range" value="500-1500" {{ request('price_min') == '500' && request('price_max') == '1500' ? 'checked' : '' }} class="form-radio text-primary rounded-full border-gray-300 h-4 w-4" onclick="this.form.price_min.value='500'; this.form.price_max.value='1500'; this.form.submit()">
                                    <span class="ml-3 text-sm text-gray-600 group-hover:text-primary transition">500 - 1500 TL</span>
                                </label>
                             </div>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="bg-white p-6 lg:p-4 rounded-2xl lg:rounded-lg border border-gray-200 shadow-sm mt-4">
                        <h3 class="font-black text-primary mb-3 text-[10px] uppercase tracking-tighter">{{ __('search.categories_label') }}</h3>
                        <div class="space-y-3 lg:space-y-2">
                            @foreach($categories as $category)
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="category[]" value="{{ $category->id }}" {{ in_array($category->id, (array)request('category', [])) ? 'checked' : '' }} class="form-checkbox text-primary rounded-lg border-gray-300 h-6 w-6 md:h-4 md:w-4" onchange="document.getElementById('filterForm').submit()">
                                <span class="ml-3 text-sm text-gray-700">{{ $category->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Rating Filter -->
                    <div class="bg-white p-6 lg:p-4 rounded-2xl lg:rounded-lg border border-gray-200 shadow-sm mt-4">
                        <h3 class="font-black text-primary mb-3 text-[10px] uppercase tracking-tighter">{{ __('search.rating_label') }}</h3>
                        <div class="space-y-3 lg:space-y-2">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="rating" value="4.5" {{ request('rating') == '4.5' ? 'checked' : '' }} class="form-radio text-primary h-5 w-5 md:h-4 md:w-4 border-gray-300" onchange="document.getElementById('filterForm').submit()">
                                <span class="ml-3 text-sm text-gray-700">{{ __('search.rating_extraordinary') }}</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="rating" value="4" {{ request('rating') == '4' ? 'checked' : '' }} class="form-radio text-primary h-5 w-5 md:h-4 md:w-4 border-gray-300" onchange="document.getElementById('filterForm').submit()">
                                <span class="ml-3 text-sm text-gray-700">{{ __('search.rating_very_good') }}</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="rating" value="3" {{ request('rating') == '3' ? 'checked' : '' }} class="form-radio text-primary h-5 w-5 md:h-4 md:w-4 border-gray-300" onchange="document.getElementById('filterForm').submit()">
                                <span class="ml-3 text-sm text-gray-700">{{ __('search.rating_good') }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Features/Amenities Filter -->
                    <div class="bg-white p-6 lg:p-4 rounded-2xl lg:rounded-lg border border-gray-200 shadow-sm mt-4">
                        <h3 class="font-black text-primary mb-3 text-[10px] uppercase tracking-tighter">{{ __('search.features_label') }}</h3>
                        <div class="space-y-3 lg:space-y-2">
                             @foreach(['wifi' => 'Wi-Fi', 'parking' => 'Otopark', 'outdoor' => 'Dış Mekan', 'credit_card' => 'Kredi Kartı', 'valet' => 'Vale', 'alcohol' => 'Alkollü'] as $key => $label)
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="features[]" value="{{ $key }}" {{ in_array($key, (array)request('features', [])) ? 'checked' : '' }} class="form-checkbox text-primary rounded-lg border-gray-300 h-6 w-6 md:h-4 md:w-4" onchange="document.getElementById('filterForm').submit()">
                                <span class="ml-3 text-sm text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Mobile Apply Button -->
                    <div class="lg:hidden mt-8">
                        <button type="button" @click="showFilters = false" class="w-full bg-primary text-white font-black py-4 rounded-xl shadow-xl shadow-primary/20 border-2 border-white/20 active:scale-95 transition-all">{{ __('search.view_results') }}</button>
                    </div>
                </form>
            </div>

            <!-- Main Results -->
            <div class="lg:col-span-3">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('search.found_results', ['count' => $businesses->total()]) }}</h1>
                    <form action="/search" method="GET" id="sortForm" class="relative inline-block text-left">
                        <!-- Preserve all query params -->
                        @foreach(request()->except('sort') as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach

                        <input type="hidden" name="sort" id="sortInput" value="{{ request('sort', 'recommended') }}">

                        <!-- Button -->
                        <button
                            type="button"
                            id="sortButton"
                            class="inline-flex justify-between w-48 items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-600 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary"
                        >
                            <span id="sortLabel">
                                @switch(request('sort'))
                                    @case('nearest') {{ __('search.sort_nearest') }} @break
                                    @case('rating_high') {{ __('search.sort_rating_high') }} @break
                                    @case('price_low') {{ __('search.sort_price_low') }} @break
                                    @default {{ __('search.sort_recommended') }}
                                @endswitch
                            </span>

                            <svg class="ml-2 h-4 w-4 text-gray-400 transition-transform duration-200" id="sortIcon"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.7a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div
                            id="sortMenu"
                            class="absolute right-0 z-20 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 hidden"
                        >
                            <div class="py-1 text-sm text-gray-700">
                                <button type="button" data-value="recommended"
                                        class="sort-item block w-full px-4 py-2 text-left hover:bg-gray-100">
                                    {{ __('search.sort_recommended') }}
                                </button>

                                <button type="button" data-value="nearest"
                                        class="sort-item block w-full px-4 py-2 text-left hover:bg-gray-100">
                                    {{ __('search.sort_nearest') }}
                                </button>

                                <button type="button" data-value="rating_high"
                                        class="sort-item block w-full px-4 py-2 text-left hover:bg-gray-100">
                                    {{ __('search.sort_rating_high') }}
                                </button>

                                <button type="button" data-value="price_low"
                                        class="sort-item block w-full px-4 py-2 text-left hover:bg-gray-100">
                                    {{ __('search.sort_price_low') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <script>
                        const sortButton = document.getElementById('sortButton');
                        const sortMenu = document.getElementById('sortMenu');
                        const sortInput = document.getElementById('sortInput');
                        const sortLabel = document.getElementById('sortLabel');
                        const sortIcon = document.getElementById('sortIcon');

                        // Toggle dropdown
                        sortButton.addEventListener('click', () => {
                            sortMenu.classList.toggle('hidden');
                            sortIcon.classList.toggle('rotate-180');
                        });

                        // Select option
                        document.querySelectorAll('.sort-item').forEach(item => {
                            item.addEventListener('click', () => {
                                sortInput.value = item.dataset.value;
                                sortLabel.textContent = item.textContent;
                                document.getElementById('sortForm').submit();
                            });
                        });

                        // Close when clicking outside
                        document.addEventListener('click', (e) => {
                            if (!sortButton.contains(e.target) && !sortMenu.contains(e.target)) {
                                sortMenu.classList.add('hidden');
                                sortIcon.classList.remove('rotate-180');
                            }
                        });
                    </script>
                </div>

                @if($businesses->isEmpty())
                <div class="bg-white p-12 text-center rounded-lg border border-gray-200">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <h3 class="text-lg font-medium text-gray-900">{{ __('search.no_results_found') }}</h3>
                    <p class="text-gray-500">{{ __('search.no_results_desc') }}</p>
                </div>
                @else
                
                <div id="results-container" class="space-y-4">
                    @include('search.partials.list', ['businesses' => $businesses])
                </div>

                <!-- Load More Button -->
                @if($businesses->hasMorePages())
                <div class="mt-8 text-center" id="loadMoreContainer">
                    <button onclick="loadMore()" id="loadMoreBtn" class="bg-white border border-gray-300 text-gray-700 px-8 py-3 rounded-full font-semibold shadow-sm hover:bg-gray-50 hover:shadow-md transition-all transform hover:-translate-y-1 flex items-center justify-center mx-auto gap-2">
                        <span>{{ __('search.load_more') }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div id="loadingSkeletons" class="hidden space-y-4">
                        @include('search.partials.skeleton')
                        @include('search.partials.skeleton')
                        @include('search.partials.skeleton')
                    </div>
                </div>
                @endif

                <script>
                    let currentPage = 1;
                    function loadMore() {
                        const btn = document.getElementById('loadMoreBtn');
                        const skeletons = document.getElementById('loadingSkeletons');
                        const container = document.getElementById('results-container');
                        
                        btn.classList.add('hidden');
                        skeletons.classList.remove('hidden');
                        
                        currentPage++;
                        
                        // Get current URL params
                        const urlParams = new URLSearchParams(window.location.search);
                        urlParams.set('page', currentPage);
                        
                        fetch(`${window.location.pathname}?${urlParams.toString()}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            if(html.trim() === '') {
                                document.getElementById('loadMoreContainer').remove();
                                return;
                            }
                            
                            // Create a temp container to parse HTML
                            const temp = document.createElement('div');
                            temp.innerHTML = html;
                            
                            // Append new items
                            while (temp.firstChild) {
                                container.appendChild(temp.firstChild);
                            }
                            
                            skeletons.classList.add('hidden');
                            btn.classList.remove('hidden');
                        })
                        .catch(err => {
                            console.error('Load more error:', err);
                            skeletons.classList.add('hidden');
                            btn.classList.remove('hidden');
                        });
                    }
                </script>
                @endif
            </div>
        </div>
    </div>
    <!-- Map Modal -->
    <div x-show="showMap" 
         style="display: none;"
         class="fixed inset-0 z-[100] bg-black bg-opacity-70 flex items-center justify-center p-4 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white w-full max-w-6xl h-[80vh] rounded-[3rem] overflow-hidden relative shadow-2xl flex flex-col" @click.away="showMap = false">
            
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-white z-10">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('search.map_view') }}</h2>
                    <p class="text-sm text-gray-500">{{ __('search.found_results_map', ['count' => $businesses->total()]) }}</p>
                </div>
                <button @click="showMap = false" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Map Iframe -->
            <div class="flex-1 bg-gray-100 relative">
                <iframe
                    width="100%"
                    height="100%"
                    style="border:0"
                    loading="lazy"
                    allowfullscreen
                    src="https://www.google.com/maps/embed/v1/search?key={{ config('services.google.maps_api_key') }}&q={{ request('q') ? request('q') . ' Istanbul' : 'Istanbul restaurants' }}&zoom=13">
                </iframe>
            </div>
        </div>
    </div>
</div>
@endsection
