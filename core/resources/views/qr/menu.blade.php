@extends('qr.layout')

@section('title', 'Menü')

@section('content')
<div x-data="{ 
    activeCategory: null, 
    showModal: false,
    selectedItem: null,
    init() {
        // Scroll spy integration
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.activeCategory = entry.target.id.replace('cat-', '');
                    // Scroll nav into view
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
}" class="pb-20">

    @if($business->menus->isEmpty())
         <div class="py-20 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-200 flex flex-col items-center mt-12 px-6">
            <div class="w-20 h-20 bg-white rounded-full shadow-sm flex items-center justify-center mb-4 text-purple-200">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <p class="text-gray-900 font-black text-lg">Menü henüz eklenmedi</p>
            <p class="text-gray-400 text-sm mt-1 max-w-xs mx-auto text-center">İşletme tarafından tanımlanmış bir menü veya hizmet bulunmuyor.</p>
        </div>
    @else
        
        {{-- Sticky Category Navigation --}}
        <div class="sticky top-[76px] z-30 bg-white/95 backdrop-blur-md border-b border-purple-100 shadow-sm py-3 px-4 -mx-4 mb-6">
            <div class="flex gap-3 overflow-x-auto hide-scrollbar pb-1 snap-x">
                <button 
                    @click="activeCategory = null; window.scrollTo({top: 0, behavior: 'smooth'})"
                    class="px-5 py-2.5 rounded-full text-sm font-bold whitespace-nowrap transition-all duration-300 border snap-start outline-none focus:ring-2 focus:ring-primary/50 hover:bg-purple-50 hover:border-purple-200 hover:text-primary active:scale-95 hover:-translate-y-0.5"
                    :class="activeCategory === null 
                        ? 'bg-primary text-white border-primary shadow-lg shadow-primary/25 scale-105' 
                        : 'bg-white text-gray-500 border-gray-100'">
                    Tümü
                </button>
                @foreach($groupedMenus as $category => $items)
                <button 
                    id="nav-{{ Str::slug($category) }}"
                    @click="activeCategory = '{{ Str::slug($category) }}'; document.getElementById('cat-{{ Str::slug($category) }}').scrollIntoView({behavior: 'smooth', block: 'start'})"
                    class="px-5 py-2.5 rounded-full text-sm font-bold whitespace-nowrap transition-all duration-300 border snap-start outline-none focus:ring-2 focus:ring-primary/50 hover:bg-purple-50 hover:border-purple-200 hover:text-primary active:scale-95 hover:-translate-y-0.5"
                    :class="activeCategory === '{{ Str::slug($category) }}' 
                        ? 'bg-primary text-white border-primary shadow-lg shadow-primary/25 scale-105' 
                        : 'bg-white text-gray-500 border-gray-100'">
                    {{ $category }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- Menu Grid --}}
        <div class="space-y-12 px-2">
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
                                <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" loading="lazy">
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
                                <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed font-medium">{{ $item->description }}</p>
                            </div>
                            <div class="flex items-center justify-between mt-3">
                                <span class="font-black text-lg text-primary tracking-tight">{{ number_format($item->price, 2) }}₺</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        {{-- Enhanced Detail Modal (Matching System Aesthetics) --}}
        <div x-show="showModal" 
             style="display: none;"
             class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center pointer-events-none p-0 sm:p-4">
            
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
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="translate-y-full opacity-0 sm:scale-95"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="translate-y-0 opacity-100 sm:scale-100"
                 x-transition:leave-end="translate-y-full opacity-0 sm:scale-95"
                 class="bg-white w-full max-w-lg rounded-t-[3rem] sm:rounded-[2.5rem] shadow-2xl overflow-hidden pointer-events-auto max-h-[92vh] flex flex-col relative">
                
                {{-- Close Button --}}
                <button @click="closeModal" class="absolute top-5 right-5 z-20 w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-lg text-gray-500 hover:text-gray-900 transition-all active:scale-90 border border-gray-100/50">
                    <i class="fa-solid fa-xmark"></i>
                </button>

                {{-- Modal Header Image --}}
                <div class="h-80 sm:h-96 bg-gray-50 relative shrink-0 overflow-hidden">
                    <template x-if="selectedItem?.image">
                        <img :src="'/storage/' + selectedItem.image" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!selectedItem?.image">
                        <div class="w-full h-full flex items-center justify-center text-purple-100">
                            <i class="fa-solid fa-utensils text-7xl"></i>
                        </div>
                    </template>
                    <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-white via-white/40 to-transparent"></div>
                </div>

                {{-- Modal Content --}}
                <div class="px-8 pb-10 -mt-10 relative z-10 flex-1 overflow-y-auto">
                    <div class="bg-white rounded-3xl p-1 inline-block mb-4 shadow-sm border border-gray-50">
                         <span class="px-3 py-1 text-[10px] font-black text-primary uppercase tracking-widest" x-text="selectedItem?.category_name || 'Menü'"></span>
                    </div>

                    <h3 x-text="selectedItem?.name" class="text-4xl font-black text-gray-900 leading-[1.1] mb-2 tracking-tighter"></h3>
                    <div class="flex items-baseline gap-2 mb-8">
                         <span x-text="selectedItem ? new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(selectedItem.price) : ''" class="text-5xl font-black text-primary tracking-tighter"></span>
                         <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">KDV Dahil</span>
                    </div>

                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div>
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Açıklama</h4>
                            <p x-text="selectedItem?.description || 'Bu ürün için özel bir açıklama bulunmuyor.'" class="text-gray-600 text-lg leading-relaxed font-medium"></p>
                        </div>

                        {{-- Feature Pills Mockup --}}
                        <div class="flex flex-wrap gap-2 pt-4">
                            <div class="px-4 py-2 bg-gray-50 rounded-xl flex items-center gap-2 text-xs font-bold text-gray-500 border border-gray-100">
                                <i class="fa-solid fa-fire text-amber-500"></i>
                                <span>Popüler</span>
                            </div>
                            <div class="px-4 py-2 bg-gray-50 rounded-xl flex items-center gap-2 text-xs font-bold text-gray-500 border border-gray-100">
                                <i class="fa-solid fa-leaf text-green-500"></i>
                                <span>Taze</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
/* Custom sticky handling to account for navbar */
.override-sticky {
    top: 76px !important;
}
</style>
@endsection
