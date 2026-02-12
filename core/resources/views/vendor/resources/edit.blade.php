@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
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
                        <a href="{{ route('vendor.resources.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary md:ml-2">Masa & Yer Yönetimi</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Düzenle</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Masa/Yer Düzenle</h1>
                <p class="mt-1 text-sm text-gray-500 font-medium">{{ $resource->name }} bilgilerini güncelleyin.</p>
            </div>

            <form action="{{ route('vendor.resources.update', $resource->id) }}" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">KAYNAK ADI</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $resource->name) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all">
                        @error('name') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Location Selection -->
                    <div>
                        <label for="location_id" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ŞUBE</label>
                        <select name="location_id" id="location_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all appearance-none">
                            <option value="">Merkez / Belirtilmedi</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ (old('location_id') ?? $resource->location_id) == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">KATEGORİ</label>
                        <select name="category" id="category" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all appearance-none">
                            <option value="">Seçiniz</option>
                            <option value="Salon" {{ (old('category') ?? $resource->category) == 'Salon' ? 'selected' : '' }}>Salon</option>
                            <option value="Bahçe" {{ (old('category') ?? $resource->category) == 'Bahçe' ? 'selected' : '' }}>Bahçe</option>
                            <option value="VIP" {{ (old('category') ?? $resource->category) == 'VIP' ? 'selected' : '' }}>VIP / Özel Oda</option>
                            <option value="Teras" {{ (old('category') ?? $resource->category) == 'Teras' ? 'selected' : '' }}>Teras</option>
                        </select>
                        @error('category') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">TÜR</label>
                        <select name="type" id="type" required class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all appearance-none">
                            <option value="Masa" {{ $resource->type === 'Masa' ? 'selected' : '' }}>Masa</option>
                            <option value="Sandalye" {{ $resource->type === 'Sandalye' ? 'selected' : '' }}>Sandalye</option>
                            <option value="Loca" {{ $resource->type === 'Loca' ? 'selected' : '' }}>Loca</option>
                            <option value="VİP Oda" {{ $resource->type === 'VİP Oda' ? 'selected' : '' }}>VİP Oda</option>
                            <option value="Bahçe Masası" {{ $resource->type === 'Bahçe Masası' ? 'selected' : '' }}>Bahçe Masası</option>
                        </select>
                        @error('type') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label for="capacity" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">KAPASİTE (KİŞİ)</label>
                        <input type="number" name="capacity" id="capacity" required min="1" value="{{ old('capacity', $resource->capacity) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all">
                        @error('capacity') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Inventory Tracking -->
                    <div class="md:col-span-2 p-6 bg-slate-50 rounded-2xl">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">ENVANTER TAKİBİ</p>
                                <p class="text-sm font-bold text-slate-700 mt-1">Bu kaynak için stok/envanter takibi yapılsın mı?</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="requires_inventory" value="0">
                                <input type="checkbox" name="requires_inventory" value="1" {{ (old('requires_inventory') ?? $resource->requires_inventory) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                <label for="stock" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">STOK ADEDİ</label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock', $resource->stock) }}" class="w-full px-6 py-4 bg-white border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all" placeholder="Örn: 50">
                                @error('stock') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Availability -->
                    <div class="md:col-span-2">
                        <div class="p-6 bg-slate-50 rounded-2xl flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">KULLANIM DURUMU</p>
                                <p class="text-sm font-bold text-slate-700 mt-1">Bu kaynak rezervasyonlara açık mı?</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_available" value="0">
                                <input type="checkbox" name="is_available" value="1" {{ $resource->is_available ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                    <a href="{{ route('vendor.resources.index') }}" class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">Vazgeç</a>
                    <button type="submit" class="px-10 py-4 bg-slate-900 text-white rounded-[2rem] font-black text-sm shadow-xl shadow-slate-200 hover:bg-slate-800 transition-all">
                        Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
