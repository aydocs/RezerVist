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
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Yeni Personel</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Yeni Personel Ekle</h1>
                <p class="mt-1 text-sm text-gray-500 font-medium">Personel bilgilerini eksiksiz doldurunuz.</p>
            </div>

            <form action="{{ route('vendor.staff.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">AD SOYAD</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all placeholder:text-slate-300" placeholder="Örn: Ahmet Yılmaz">
                        @error('name') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Location Selection -->
                    <div class="md:col-span-2">
                        <label for="location_id" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ÇALIŞTIĞI ŞUBE</label>
                        <select name="location_id" id="location_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all appearance-none">
                            <option value="">Merkez / Tüm Şubeler</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }} ({{ $location->city }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Position -->
                    <div class="md:col-span-2">
                        <label for="position" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">POZİSYON / GÖREV</label>
                        <input type="text" name="position" id="position" value="{{ old('position') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all placeholder:text-slate-300" placeholder="Örn: Kıdemli Berber, Garson, Şef">
                        @error('position') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">E-POSTA ADRESİ</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all placeholder:text-slate-300" placeholder="personel@eposta.com">
                        @error('email') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">TELEFON NUMARASI</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-bold text-slate-900 transition-all placeholder:text-slate-300" placeholder="05XX XXX XX XX">
                        @error('phone') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Photo -->
                    <div class="md:col-span-2">
                        <label for="photo" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">PROFİL FOTOĞRAFI</label>
                        <div class="relative group cursor-pointer">
                            <input type="file" name="photo" id="photo" class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer">
                            <div class="w-full px-6 py-10 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2rem] flex flex-col items-center justify-center group-hover:bg-slate-100 group-hover:border-primary/30 transition-all">
                                <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-slate-400 mb-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-sm font-black text-slate-900">Dosya Seçin veya Sürükleyin</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">JPG, PNG veya WebP (Max 2MB)</p>
                            </div>
                        </div>
                        @error('photo') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                    <a href="{{ route('vendor.staff.index') }}" class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">Vazgeç</a>
                    <button type="submit" class="px-10 py-4 bg-primary text-white rounded-[2rem] font-black text-sm shadow-xl shadow-purple-200 hover:bg-purple-700 transition-all hover:scale-105 active:scale-95">
                        Kaydet ve Yayınla
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
