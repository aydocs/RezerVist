@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('vendor.menus.index') }}" class="p-2 bg-white rounded-full border border-gray-200 text-gray-500 hover:text-gray-900 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Menü Düzenle: {{ $menu->name }}</h1>
            </div>
        </div>

        <form action="{{ route('vendor.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ price: '{{ $menu->price }}' }">
            @csrf
            @method('PUT')
            
            <div class="p-8 space-y-6">
                <!-- Image Upload -->
                <div class="w-full">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Fotoğraf</label>
                    <div class="relative w-full h-48 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 hover:border-primary transition flex items-center justify-center cursor-pointer overflow-hidden group">
                        <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10" onchange="previewImage(this)">
                        <div class="text-center pointer-events-none {{ $menu->image ? 'hidden' : '' }}" id="upload-placeholder">
                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm text-gray-500">Değiştirmek için tıklayın</span>
                        </div>
                        <img id="image-preview" src="{{ $menu->image ? Storage::url($menu->image) : '' }}" class="absolute inset-0 w-full h-full object-cover {{ $menu->image ? '' : 'hidden' }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">İsim</label>
                        <input type="text" name="name" value="{{ old('name', $menu->name) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" required>
                    </div>
                    <div x-data="{ isCustom: !['Başlangıç', 'Ana Yemek', 'Tatlı', 'İçecek', 'Kahve', 'Hizmet'].includes('{{ $menu->category }}') }">
                        <label class="block text-sm font-bold text-gray-900 mb-2">Kategori</label>
                        <div class="relative">
                            <select x-show="!isCustom" name="category" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition appearance-none bg-white" @change="if($event.target.value === 'custom') { isCustom = true; $event.target.value = ''; }">
                                @foreach(['Başlangıç', 'Ana Yemek', 'Tatlı', 'İçecek', 'Kahve', 'Hizmet'] as $cat)
                                    <option value="{{ $cat }}" {{ $menu->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                                <option value="custom" class="font-bold text-primary">+ Yeni Kategori Ekle</option>
                            </select>
                            
                            <div x-show="isCustom" class="flex gap-2" x-cloak>
                                <input type="text" name="category" value="{{ $menu->category }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" placeholder="Yeni Kategori Adı" :disabled="!isCustom">
                                <button type="button" @click="isCustom = false" class="px-3 bg-gray-100 rounded-xl hover:bg-gray-200 text-gray-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Açıklama</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition">{{ old('description', $menu->description) }}</textarea>
                </div>

                <!-- Price Calculator -->
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Sizin Belirlediğiniz Fiyat (TL)</label>
                    <div class="flex items-center gap-6">
                        <div class="relative flex-1">
                            <input type="number" step="0.01" name="price" x-model="price" class="w-full px-4 py-3 pl-10 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition font-bold text-lg" required>
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">₺</span>
                        </div>
                        

                        <div class="flex-1 bg-white p-3 rounded-xl border border-primary/30 flex justify-between items-center shadow-sm">
                            <span class="text-sm font-medium text-gray-600">Müşterinin Göreceği:</span>
                            <span class="text-xl font-bold text-primary" x-text="price ? parseFloat(price).toFixed(2) + ' ₺' : '0.00 ₺'">0.00 ₺</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-purple-700 transition shadow-lg shadow-primary/30">
                    Güncelle
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
                document.getElementById('upload-placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
