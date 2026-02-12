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
                        <a href="{{ route('vendor.staff.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary md:ml-2">Personel Yönetimi</a>
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
            <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Personel Düzenle</h1>
                    <p class="mt-1 text-sm text-gray-500 font-medium">{{ $staff->name }} bilgilerini güncelleyin.</p>
                </div>
                <div class="w-16 h-16 rounded-2xl bg-slate-50 overflow-hidden border border-slate-100 shadow-sm">
                    @if($staff->photo_path)
                        <img src="{{ asset('storage/' . $staff->photo_path) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-primary font-black text-xl">
                            {{ substr($staff->name, 0, 1) }}
                        </div>
                    @endif
                </div>
            </div>

            <form action="{{ route('vendor.staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Status Toggle -->
                    <div class="md:col-span-2">
                        <div class="p-4 bg-slate-50 rounded-2xl flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ÇALIŞMA DURUMU</p>
                                <p class="text-sm font-bold text-slate-700 mt-1">Bu personel rezervasyon alabilir mi?</p>
                            </div>
                            <select name="is_active" class="px-4 py-2 border-none bg-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-sm focus:ring-2 focus:ring-primary/20 pointer-events-auto">
                                <option value="1" {{ $staff->is_active ? 'selected' : '' }}>Aktif Çalışıyor</option>
                                <option value="0" {{ !$staff->is_active ? 'selected' : '' }}>Şu An Pasif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">AD SOYAD</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $staff->name) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all placeholder:text-slate-300">
                        @error('name') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Location Selection -->
                    <div class="md:col-span-2">
                        <label for="location_id" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ÇALIŞTIĞI ŞUBE</label>
                        <select name="location_id" id="location_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all appearance-none">
                            <option value="">Merkez / Tüm Şubeler</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ (old('location_id') ?? $staff->location_id) == $location->id ? 'selected' : '' }}>{{ $location->name }} ({{ $location->city }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Position -->
                    <div class="md:col-span-2">
                        <label for="position" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">POZİSYON / GÖREV</label>
                        <input type="text" name="position" id="position" value="{{ old('position', $staff->position) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all placeholder:text-slate-300">
                        @error('position') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">E-POSTA ADRESİ</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $staff->email) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all placeholder:text-slate-300">
                        @error('email') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">TELEFON NUMARASI</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $staff->phone) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all placeholder:text-slate-300">
                        @error('phone') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Photo -->
                    <div class="md:col-span-2">
                        <label for="photo" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">FOTOĞRAFI GÜNCELLE</label>
                        <div class="relative group cursor-pointer">
                            <input type="file" name="photo" id="photo" class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer">
                            <div class="w-full px-6 py-10 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2rem] flex flex-col items-center justify-center group-hover:bg-slate-100 group-hover:border-primary/30 transition-all">
                                <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-slate-400 mb-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-sm font-black text-slate-900">Dosya Seçin veya Sürükleyin</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Dosya seçmezseniz mevcut fotoğraf korunur.</p>
                            </div>
                        </div>
                        @error('photo') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                    <a href="{{ route('vendor.staff.index') }}" class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">Vazgeç</a>
                    <button type="submit" class="px-10 py-4 bg-slate-900 text-white rounded-[2rem] font-black text-sm shadow-xl shadow-slate-200 hover:bg-slate-800 transition-all hover:scale-105 active:scale-95">
                        Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
