@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('vendor.locations.index') }}" class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-primary transition-colors gap-2 mb-4 group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Şube Listesine Dön
            </a>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Şubeyi Düzenle</h1>
            <p class="mt-2 text-sm text-slate-500 font-medium">{{ $location->name }} şubesinin bilgilerini güncelleyin.</p>
        </div>

        <form action="{{ route('vendor.locations.update', $location->id) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 space-y-6">
                <!-- Status Toggle -->
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 mb-6">
                    <div>
                        <p class="text-sm font-black text-slate-900 uppercase tracking-widest">Şube Durumu</p>
                        <p class="text-xs text-slate-400 font-medium">Şube rezervasyonlara açık mı?</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $location->is_active ? 'checked' : '' }}>
                        <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500"></div>
                    </label>
                </div>

                <!-- Branch Name -->
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ŞUBE ADI</label>
                    <input type="text" name="name" value="{{ old('name', $location->name) }}" required 
                           class="w-full h-14 px-6 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary/20 font-bold text-slate-700 placeholder:text-slate-300 transition-all"
                           placeholder="Örn: Kadıköy Şubesi">
                    @error('name') <p class="mt-1 text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- City -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ŞEHİR</label>
                        <input type="text" name="city" value="{{ old('city', $location->city) }}" required 
                               class="w-full h-14 px-6 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary/20 font-bold text-slate-700 placeholder:text-slate-300 transition-all"
                               placeholder="Örn: İstanbul">
                    </div>
                    <!-- District -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">İLÇE</label>
                        <input type="text" name="district" value="{{ old('district', $location->district) }}" 
                               class="w-full h-14 px-6 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary/20 font-bold text-slate-700 placeholder:text-slate-300 transition-all"
                               placeholder="Örn: Kadıköy">
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">TAM ADRES</label>
                    <textarea name="address" required rows="3"
                              class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary/20 font-bold text-slate-700 placeholder:text-slate-300 transition-all"
                              placeholder="Mahalle, sokak, numara...">{{ old('address', $location->address) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Phone -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ŞUBE TELEFONU</label>
                        <input type="tel" name="phone" value="{{ old('phone', $location->phone) }}" 
                               class="w-full h-14 px-6 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary/20 font-bold text-slate-700 placeholder:text-slate-300 transition-all"
                               placeholder="0212 XXX XX XX">
                    </div>
                    <!-- Email -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ŞUBE E-POSTASI</label>
                        <input type="email" name="email" value="{{ old('email', $location->email) }}" 
                               class="w-full h-14 px-6 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary/20 font-bold text-slate-700 placeholder:text-slate-300 transition-all"
                               placeholder="sube@isletme.com">
                    </div>
                </div>

                <div class="pt-4 flex justify-between">
                    <button type="button" onclick="confirmDelete()" class="px-6 py-4 rounded-2xl text-rose-500 font-bold hover:bg-rose-50 transition-colors">Şubeyi Sil</button>
                    <button type="submit" class="bg-primary text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary-dark transition shadow-xl shadow-primary/30 active:scale-95 flex items-center gap-3">
                        Değişiklikleri Kaydet
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </button>
                </div>
            </div>
        </form>

        <form id="deleteForm" action="{{ route('vendor.locations.destroy', $location->id) }}" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    </div>
</div>

<script>
    function confirmDelete() {
        if(confirm('Şubeyi silmek istediğinize emin misiniz? Bu işlem geri alınamaz.')) {
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endsection
