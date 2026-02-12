@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('vendor.locations.index') }}" class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-primary transition-colors gap-2 mb-4 group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Şube Listesine Dön
            </a>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Yeni Şube Ekle</h1>
            <p class="mt-2 text-sm text-slate-500 font-medium">Yeni bir fiziksel şube/mağaza kaydı oluşturun.</p>
        </div>

        <form action="{{ route('vendor.locations.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 space-y-6">
                <!-- Branch Name -->
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ŞUBE ADI</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           class="w-full h-14 px-6 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary/20 font-bold text-slate-700 placeholder:text-slate-300 transition-all"
                           placeholder="Örn: Kadıköy Şubesi, Nişantaşı Outlet">
                    @error('name') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- City -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ŞEHİR</label>
                        <input type="text" name="city" value="{{ old('city') }}" required 
                               class="w-full h-14 px-6 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary/20 font-bold text-slate-700 placeholder:text-slate-300 transition-all"
                               placeholder="Örn: İstanbul">
                    </div>
                    <!-- District -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">İLÇE</label>
                        <input type="text" name="district" value="{{ old('district') }}" 
                               class="w-full h-14 px-6 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary/20 font-bold text-slate-700 placeholder:text-slate-300 transition-all"
                               placeholder="Örn: Kadıköy">
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">TAM ADRES</label>
                    <textarea name="address" required rows="3"
                              class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary/20 font-bold text-slate-700 placeholder:text-slate-300 transition-all"
                              placeholder="Mahalle, sokak, numara..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Phone -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ŞUBE TELEFONU</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" 
                               class="w-full h-14 px-6 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary/20 font-bold text-slate-700 placeholder:text-slate-300 transition-all"
                               placeholder="0212 XXX XX XX">
                    </div>
                    <!-- Email -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ŞUBE E-POSTASI</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full h-14 px-6 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary/20 font-bold text-slate-700 placeholder:text-slate-300 transition-all"
                               placeholder="sube@isletme.com">
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-primary text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary-dark transition shadow-xl shadow-primary/30 active:scale-95 flex items-center gap-3">
                        Şubeyi Kaydet
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
