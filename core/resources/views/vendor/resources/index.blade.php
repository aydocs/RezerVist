@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="{ 
    selectedIds: [], 
    allIds: {{ $resources->pluck('id') }},
    toggleAll() {
        if (this.selectedIds.length === this.allIds.length) {
            this.selectedIds = [];
        } else {
            this.selectedIds = [...this.allIds];
        }
    },
    submitBulkAction(action) {
        if (action === 'delete') {
            if (!confirm('Seçili ' + this.selectedIds.length + ' kaynağı silmek istediğinize emin misiniz?')) return;
        }
        $refs.bulkActionInput.value = action;
        $refs.bulkForm.submit();
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('vendor.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary transition-colors">
                        Panel
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Masa & Yer Yönetimi</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Masa & Yer Yönetimi</h1>
                <p class="mt-2 text-sm text-gray-600 font-medium">İşletmenizdeki masaları, locaları veya alanları tanımlayın ve kapasiteleri yönetin.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('vendor.resources.create') }}" class="inline-flex items-center px-6 py-4 bg-primary text-white rounded-[2rem] font-black text-sm shadow-xl shadow-purple-200 hover:bg-purple-700 transition-all hover:scale-105 active:scale-95">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Yeni Ekle
                </a>
                <a href="{{ route('vendor.resources.create-bulk') }}" class="inline-flex items-center px-6 py-4 bg-slate-800 text-white rounded-[2rem] font-black text-sm shadow-xl shadow-slate-200 hover:bg-slate-700 transition-all hover:scale-105 active:scale-95">
                    <i class="fas fa-layer-group mr-2"></i>
                    Toplu Ekle
                </a>
            </div>
        </div>

        <!-- Filters & Bulk Select Row -->
        <div class="mb-6" x-data="{ 
            openDropdown: null,
            toggleDropdown(name) {
                this.openDropdown = this.openDropdown === name ? null : name;
            },
            closeDropdowns() {
                this.openDropdown = null;
            }
        }" @click.away="closeDropdowns()">
            <div class="flex flex-wrap items-center gap-3">
                <!-- Select All Button -->
                <button @click="toggleAll()" class="px-6 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
                    <span x-text="selectedIds.length === allIds.length ? 'Seçimi Kaldır' : 'Tümünü Seç'"></span>
                </button>

                <!-- Search Button/Input -->
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Masa/Alan ara..." 
                           @input="if($event.target.value.length > 2 || $event.target.value.length === 0) { $event.target.form.submit(); }"
                           form="filter-form"
                           class="w-64 bg-white border border-slate-200 rounded-2xl pl-11 pr-4 py-3 text-sm font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-primary/20 transition-all outline-none">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>

                <!-- Location Filter Button -->
                <div class="relative">
                    <button @click.stop="toggleDropdown('location')" 
                            type="button"
                            class="px-6 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all flex items-center gap-2">
                        <span>{{ request('location_id') ? ($locations->firstWhere('id', request('location_id'))->name ?? (request('location_id') == 'main' ? 'Merkez' : 'Tüm Şubeler')) : 'Tüm Şubeler' }}</span>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="openDropdown === 'location' ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="openDropdown === 'location'" 
                         x-transition
                         class="absolute top-full left-0 mt-2 w-64 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 py-2">
                        <a href="?{{ http_build_query(array_merge(request()->except('location_id'), [])) }}" 
                           class="block px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors {{ !request('location_id') ? 'bg-primary/5 text-primary' : '' }}">
                            Tüm Şubeler
                        </a>
                        <a href="?{{ http_build_query(array_merge(request()->except('location_id'), ['location_id' => 'main'])) }}" 
                           class="block px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors {{ request('location_id') == 'main' ? 'bg-primary/5 text-primary' : '' }}">
                            Merkez / Ana Şube
                        </a>
                        @foreach($locations as $loc)
                            <a href="?{{ http_build_query(array_merge(request()->except('location_id'), ['location_id' => $loc->id])) }}" 
                               class="block px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors {{ request('location_id') == $loc->id ? 'bg-primary/5 text-primary' : '' }}">
                                {{ $loc->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Category Filter Button -->
                <div class="relative">
                    <button @click.stop="toggleDropdown('category')" 
                            type="button"
                            class="px-6 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all flex items-center gap-2">
                        <span>{{ request('category') ?: 'Salon' }}</span>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="openDropdown === 'category' ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="openDropdown === 'category'" 
                         x-transition
                         class="absolute top-full left-0 mt-2 w-56 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 py-2">
                        <a href="?{{ http_build_query(array_merge(request()->except('category'), [])) }}" 
                           class="block px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors {{ !request('category') ? 'bg-primary/5 text-primary' : '' }}">
                            Tüm Kategoriler
                        </a>
                        @foreach($categories as $cat)
                            <a href="?{{ http_build_query(array_merge(request()->except('category'), ['category' => $cat])) }}" 
                               class="block px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors {{ request('category') == $cat ? 'bg-primary/5 text-primary' : '' }}">
                                {{ $cat }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Status Filter Button -->
                <div class="relative">
                    <button @click.stop="toggleDropdown('status')" 
                            type="button"
                            class="px-6 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all flex items-center gap-2">
                        <span>{{ request('status') == 'active' ? 'Aktif / Kullanımda' : (request('status') == 'passive' ? 'Pasif' : 'Tüm Durumlar') }}</span>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="openDropdown === 'status' ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="openDropdown === 'status'" 
                         x-transition
                         class="absolute top-full left-0 mt-2 w-56 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 py-2">
                        <a href="?{{ http_build_query(array_merge(request()->except('status'), [])) }}" 
                           class="block px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors {{ !request('status') ? 'bg-primary/5 text-primary' : '' }}">
                            Tüm Durumlar
                        </a>
                        <a href="?{{ http_build_query(array_merge(request()->except('status'), ['status' => 'active'])) }}" 
                           class="block px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors {{ request('status') == 'active' ? 'bg-primary/5 text-primary' : '' }}">
                            Aktif / Kullanımda
                        </a>
                        <a href="?{{ http_build_query(array_merge(request()->except('status'), ['status' => 'passive'])) }}" 
                           class="block px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors {{ request('status') == 'passive' ? 'bg-primary/5 text-primary' : '' }}">
                            Pasif
                        </a>
                    </div>
                </div>

                <!-- Sort Filter Button -->
                <div class="relative">
                    <button @click.stop="toggleDropdown('sort')" 
                            type="button"
                            class="px-6 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all flex items-center gap-2">
                        <span>
                            @if(request('sort') == 'name_asc') İsim (A-Z)
                            @elseif(request('sort') == 'name_desc') İsim (Z-A)
                            @elseif(request('sort') == 'capacity_asc') Kapasite (Artan)
                            @elseif(request('sort') == 'capacity_desc') Kapasite (Azalan)
                            @else Sıralama (Varsayılan)
                            @endif
                        </span>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="openDropdown === 'sort' ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="openDropdown === 'sort'" 
                         x-transition
                         class="absolute top-full left-0 mt-2 w-64 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 py-2">
                        <a href="?{{ http_build_query(array_merge(request()->except('sort'), [])) }}" 
                           class="block px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors {{ !request('sort') ? 'bg-primary/5 text-primary' : '' }}">
                            Sıralama (Varsayılan)
                        </a>
                        <a href="?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'name_asc'])) }}" 
                           class="block px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors {{ request('sort') == 'name_asc' ? 'bg-primary/5 text-primary' : '' }}">
                            İsim (A-Z)
                        </a>
                        <a href="?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'name_desc'])) }}" 
                           class="block px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors {{ request('sort') == 'name_desc' ? 'bg-primary/5 text-primary' : '' }}">
                            İsim (Z-A)
                        </a>
                        <a href="?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'capacity_asc'])) }}" 
                           class="block px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors {{ request('sort') == 'capacity_asc' ? 'bg-primary/5 text-primary' : '' }}">
                            Kapasite (Artan)
                        </a>
                        <a href="?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'capacity_desc'])) }}" 
                           class="block px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors {{ request('sort') == 'capacity_desc' ? 'bg-primary/5 text-primary' : '' }}">
                            Kapasite (Azalan)
                        </a>
                    </div>
                </div>

                <!-- Clear Filters Button -->
                @if(request('location_id') || request('category') || request('status') || request('search') || request('sort'))
                    <a href="{{ route('vendor.resources.index') }}" class="px-6 py-3 bg-rose-50 border border-rose-200 text-rose-600 rounded-2xl text-sm font-bold hover:bg-rose-100 transition-all">
                        Filtreleri Temizle
                    </a>
                @endif
            </div>

            <!-- Hidden form for search submission -->
            <form id="filter-form" action="{{ route('vendor.resources.index') }}" method="GET" class="hidden">
                <input type="hidden" name="location_id" value="{{ request('location_id') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
            @forelse($resources as $resource)
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden group hover:border-primary/30 transition-all duration-300 relative"
                 :class="selectedIds.includes({{ $resource->id }}) ? 'border-primary ring-2 ring-primary/10' : ''">
                
                <!-- Selection Checkbox -->
                <div class="absolute top-6 left-6 z-10">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" :value="{{ $resource->id }}" x-model="selectedIds" class="w-6 h-6 rounded-lg border-slate-200 text-primary focus:ring-primary/20 transition-all cursor-pointer">
                    </label>
                </div>

                <div class="p-8 pt-16">
                    <div class="flex justify-between items-start mb-6">
                        <div class="p-4 bg-slate-50 rounded-2xl text-slate-400 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                            @if($resource->type === 'Table' || $resource->type === 'Masa')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            @else
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            @endif
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="px-3 py-1 rounded-lg {{ $resource->is_available ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-400' }} text-[10px] font-black uppercase tracking-widest leading-none">
                                {{ $resource->is_available ? 'Kullanımda' : 'Pasif' }}
                            </span>
                        </div>
                    </div>

                    <h3 class="text-xl font-black text-slate-900 mb-2 leading-tight group-hover:text-primary transition-colors">{{ $resource->name }}</h3>
                    <div class="flex items-center gap-2 mb-6 text-xs text-slate-400 font-bold uppercase tracking-widest">
                        <span>{{ $resource->type }}</span>
                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                        <span class="text-slate-900"><svg class="w-3 h-3 inline-block -mt-1 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>{{ $resource->capacity }} Kişilik</span>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-6">
                        @if($resource->category)
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                {{ $resource->category }}
                            </span>
                        @endif
                        <span class="px-3 py-1 bg-primary/5 text-primary rounded-lg text-[10px] font-black uppercase tracking-wider">
                            Şube: {{ $resource->location->name ?? 'Merkez' }}
                        </span>
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                        <div class="flex gap-3">
                            <a href="{{ route('vendor.resources.edit', $resource->id) }}" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] hover:text-primary transition-colors">Düzenle</a>
                            <a href="{{ \App\Http\Controllers\CustomerQrController::generateLink($resource->business_id, $resource->id) }}" target="_blank" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] hover:text-black transition-colors flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 6h6v6H6V6zm12 0h6v6h-6V6zm-6 12h6v6h-6v-6z"></path></svg>
                                QR
                            </a>
                        </div>
                        <form action="{{ route('vendor.resources.destroy', $resource->id) }}" method="POST" onsubmit="return confirm('Bu kaynağı silmek istediğinize emin misiniz?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] hover:text-rose-600 transition-colors">Sil</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-[2.5rem] p-16 text-center shadow-xl shadow-slate-200/50 border border-slate-100">
                <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-200 mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">Henüz Masa/Alan Eklenmedi</h3>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mb-8">İşletmenizdeki kapasiteyi yönetmek için ilk masanızı ekleyin.</p>
                <a href="{{ route('vendor.resources.create') }}" class="inline-flex items-center px-8 py-4 bg-slate-900 text-white rounded-[2rem] font-black text-sm hover:bg-slate-800 transition-all">
                    Yeni Masa Ekle
                </a>
            </div>
            @endforelse
        </div>

        @if($resources->hasPages())
        <div class="mt-8">
            {{ $resources->links() }}
        </div>
        @endif

        <!-- Floating Action Bar -->
        <div x-show="selectedIds.length > 0" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-10"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-10"
             class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 w-full max-w-2xl px-4"
             style="display: none;">
            <div class="bg-slate-900 rounded-[2rem] p-4 shadow-2xl shadow-slate-900/40 border border-slate-800 flex items-center justify-between gap-4">
                <div class="pl-4">
                    <span class="text-white font-black text-lg" x-text="selectedIds.length"></span>
                    <span class="text-slate-400 font-bold text-sm ml-1 uppercase tracking-widest">Seçili</span>
                </div>
                
                <form x-ref="bulkForm" action="{{ route('vendor.resources.bulk-action') }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="action" x-ref="bulkActionInput">
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                </form>

                <div class="flex items-center gap-2">
                    <button @click="submitBulkAction('activate')" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all">
                        Aktif Et
                    </button>
                    <button @click="submitBulkAction('deactivate')" class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all">
                        Pasif Et
                    </button>
                    <button @click="submitBulkAction('delete')" class="px-6 py-3 bg-rose-500 hover:bg-rose-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all">
                        Sil
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
