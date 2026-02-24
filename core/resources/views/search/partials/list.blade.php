@foreach($businesses as $business)
<!-- Business Card -->
<div class="bg-white p-3 sm:p-4 rounded-xl border border-gray-200 shadow-sm flex gap-3 sm:gap-4 hover:shadow-lg transition group relative">
    <!-- Image -->
    <div class="w-32 sm:w-48 lg:w-64 h-32 sm:h-40 lg:h-auto bg-gray-100 rounded-lg overflow-hidden relative flex-shrink-0">
        <!-- Mock Map or Image -->
        <!-- Business Image -->
        <img src="{{ $business->getImageUrl(true) }}" alt="{{ $business->name }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
        
        <!-- Heart Icon (Moved to relative card corner on mobile) -->
        <button onclick="toggleFavorite({{ $business->id }}, this)" class="absolute top-2 right-2 p-1.5 bg-white rounded-full shadow hover:text-red-500 transition text-gray-400 z-10 sm:hidden">
            <svg class="w-4 h-4 {{ $business->isFavoritedBy(auth()->id()) ? 'fill-red-500 text-red-500' : '' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
        </button>

        <div class="absolute top-2 left-2 px-2 py-0.5 rounded text-[8px] sm:text-[10px] font-bold uppercase tracking-wider backdrop-blur-md shadow-sm z-10 
            {{ !$business->hasHours() ? 'bg-gray-500/90 text-white' : ($business->is_open ? 'bg-green-500/90 text-white' : 'bg-red-500/90 text-white') }}">
            {{ $business->status_text }}
        </div>

        @if($business->isBusyMode())
            <div class="absolute top-8 left-2 px-2 py-0.5 rounded text-[8px] sm:text-[10px] font-bold uppercase tracking-wider bg-rose-600 text-white shadow-sm z-10 animate-pulse border border-rose-400">
                YOĞUNUZ
            </div>
        @endif

        @if(isset($business->distance))
            <div class="absolute bottom-2 left-2 bg-black/70 text-white text-[10px] sm:text-xs px-2 py-1 rounded backdrop-blur">
                {{ number_format($business->distance, 1) }} km
            </div>
        @endif
    </div>
    
    <!-- Content -->
    <div class="flex-grow flex flex-col justify-between min-w-0">
        <div>
            <div class="flex justify-between items-start">
                <div class="min-w-0 flex-grow">
                <div class="flex items-center gap-2">
                    <h2 class="text-base sm:text-xl font-bold text-gray-900 group-hover:text-primary transition truncate">{{ $business->name }}</h2>
                    @if($business->is_verified)
                        <span class="inline-flex items-center justify-center bg-blue-500 text-white rounded-full p-0.5 shadow-sm" title="Doğrulanmış">
                            <svg class="w-2.5 h-2.5 sm:w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </span>
                    @endif
                </div>
                    <div class="flex items-center text-xs sm:text-sm text-primary font-medium mt-0.5 mb-1 sm:mb-2 truncate">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="truncate">{{ $business->address }}</span>
                    </div>
                </div>
                <!-- Hide Rating text on smallest mobile to save space, keep score -->
                <div class="flex items-center flex-shrink-0 ml-2">
                    <div class="bg-primary text-white font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-lg text-xs sm:text-sm">{{ $business->rating }}</div>
                    <div class="text-right ml-2 hidden sm:block">
                        <div class="text-[10px] text-gray-500 font-semibold leading-tight">{{ $business->rating >= 4.5 ? __('search.extraordinary') : __('search.very_good') }}</div>
                        <div class="text-[10px] text-gray-400 leading-tight">{{ $business->approvedReviews->count() }} {{ __('search.reviews') }}</div>
                    </div>
                </div>
            </div>
            
            <p class="text-gray-500 text-[11px] sm:text-sm line-clamp-2 mb-2 sm:mb-3">{{ $business->description }}</p>
            
            <!-- Features (Hidden on extra small mobile) -->
            <div class="hidden sm:flex items-center gap-2 mb-4">
                <span class="text-[10px] bg-green-50 text-green-700 px-2 py-0.5 rounded border border-green-100 italic">{{ __('search.free_cancellation') }}</span>
                <span class="text-[10px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded border border-blue-100 italic">{{ __('search.no_prepayment') }}</span>
            </div>
        </div>
        
        <div class="flex items-center justify-between border-t border-gray-100 pt-3 sm:pt-4 mt-auto">
            <div class="min-w-0">
                 @if($business->price_per_person)
                 <span class="text-[10px] text-gray-400 block sm:hidden">{{ __('search.per_person') }}</span>
                 <span class="text-xs sm:text-sm text-gray-400 hidden sm:inline">{{ __('search.per_person') }}:</span>
                 <span class="font-bold text-gray-900 text-sm sm:text-base">{{ $business->price_per_person }} ₺</span>
                 @endif
            </div>
            <div class="flex items-center gap-2">
                <!-- Heart Icon for Desktop -->
                <button onclick="toggleFavorite({{ $business->id }}, this)" class="hidden sm:flex p-2 bg-gray-50 rounded-full hover:text-red-500 transition text-gray-400">
                    <svg class="w-5 h-5 {{ $business->isFavoritedBy(auth()->id()) ? 'fill-red-500 text-red-500' : '' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                </button>
                <a href="{{ route('business.show', $business) }}" class="bg-primary hover:bg-primary/95 text-white px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg sm:rounded-xl font-black text-xs sm:text-sm border-2 border-white/20 hover:border-white shadow-xl shadow-primary/20 transition-all transform active:scale-95">
                    {{ __('search.select') }} ›
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach
