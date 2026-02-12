@extends('layouts.app')

@section('content')
<div class="h-[calc(100vh-64px)] overflow-hidden flex flex-col bg-gray-50/50">
    <div class="flex-1 overflow-y-auto custom-scrollbar">
        <div class="container-maximum px-4 sm:px-8 py-8 lg:py-12">
            <!-- Header -->
            <div class="max-w-3xl mx-auto mb-8 relative">
                <a href="{{ route('vendor.resources.index') }}" class="absolute -left-16 top-1/2 -translate-y-1/2 p-3 rounded-xl bg-white text-gray-400 border border-gray-100 shadow-sm hover:text-indigo-600 hover:border-indigo-100 hover:shadow-md transition-all group lg:block hidden">
                    <i class="fas fa-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                </a>
                
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Toplu Masa Ekleme</h1>
                    <p class="text-gray-500 mt-2 text-lg">Hızlıca birden fazla masa veya alan oluşturun.</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="max-w-3xl mx-auto">
                {{-- Error Alert --}}
                @if($errors->any())
                <div class="rounded-2xl bg-red-50 border border-red-100 p-6 mb-8 flex items-start gap-4">
                    <div class="shrink-0 p-3 bg-red-100 rounded-xl">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-red-900 font-bold mb-1">Bir sorun oluştu</h3>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                
                <form action="{{ route('vendor.resources.store-bulk') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/50 overflow-hidden">
                        {{-- Section Header --}}
                        <div class="bg-gray-50/50 border-b border-gray-100 p-6 sm:p-8">
                            <h2 class="text-lg font-bold text-gray-900">Oluşturma Kuralları</h2>
                            <p class="text-sm text-gray-500 mt-1">Sistem, girdiğiniz başlangıç ve bitiş numaralarına göre masaları otomatik isimlendirir.</p>
                        </div>

                        <div class="p-6 sm:p-8 space-y-8">
                            
                            {{-- Isimlendirme --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-gray-900 mb-2">
                                        Masa/Alan İsmi (Opsiyonel)
                                        <span class="ml-2 px-2 py-0.5 rounded text-[10px] bg-gray-100 text-gray-500 font-normal">Varsayılan: "Masa"</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-tag text-gray-300 group-focus-within:text-indigo-500 transition-colors"></i>
                                        </div>
                                        <input type="text" name="name_prefix" value="{{ old('name_prefix') }}" placeholder="Örn: Bahçe Masası, VIP Lounge..." 
                                            class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-gray-900 font-medium placeholder-gray-400">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2 ml-1">Örn: "Bahçe" yazarsanız -> "Bahçe 1", "Bahçe 2" şeklinde oluşur.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">Başlangıç No</label>
                                    <input type="number" name="start_number" value="{{ old('start_number', 1) }}" min="1" 
                                        class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-gray-900 font-bold">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">Bitiş No</label>
                                    <input type="number" name="end_number" value="{{ old('end_number', 10) }}" min="1" 
                                        class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-gray-900 font-bold">
                                </div>
                            </div>

                            <hr class="border-gray-100 border-dashed">

                            {{-- Detaylar --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">Kapasite (Kişi Başı)</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-users text-gray-300 group-focus-within:text-indigo-500 transition-colors"></i>
                                        </div>
                                        <input type="number" name="capacity" value="{{ old('capacity', 4) }}" min="1" 
                                            class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-gray-900 font-bold">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">Konum (Opsiyonel)</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-map-marker-alt text-gray-300 group-focus-within:text-indigo-500 transition-colors"></i>
                                        </div>
                                        <select name="location_id" class="w-full pl-11 pr-10 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-gray-900 font-medium appearance-none">
                                            <option value="">Genel Alan (Konumsuz)</option>
                                            @foreach($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">Kategori</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-th-large text-gray-300 group-focus-within:text-indigo-500 transition-colors"></i>
                                        </div>
                                        <select name="category" class="w-full pl-11 pr-10 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-gray-900 font-medium appearance-none">
                                            <option value="">Seçiniz</option>
                                            <option value="Salon">Salon</option>
                                            <option value="Bahçe">Bahçe</option>
                                            <option value="VIP">VIP</option>
                                            <option value="Teras">Teras</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-gray-900 mb-2">Tür</label>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                        @foreach(['Masa', 'Loca', 'Bar', 'Toplantı'] as $type)
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="type" value="{{ $type }}" class="peer sr-only" {{ old('type') == $type || $loop->first ? 'checked' : '' }}>
                                            <div class="p-4 rounded-xl border-2 border-gray-100 bg-white text-center hover:border-gray-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all">
                                                <span class="block text-sm font-bold text-gray-700 peer-checked:text-indigo-900">{{ $type }}</span>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        {{-- Actions --}}
                        <div class="bg-gray-50 p-6 sm:p-8 flex items-center justify-end gap-4">
                            <a href="{{ route('vendor.resources.index') }}" class="px-6 py-3.5 rounded-xl font-bold text-gray-600 hover:bg-gray-100 transition-colors">
                                İptal
                            </a>
                            <button type="submit" class="px-8 py-3.5 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:shadow-indigo-300 hover:-translate-y-0.5 active:translate-y-0 transition-all">
                                Oluştur
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
