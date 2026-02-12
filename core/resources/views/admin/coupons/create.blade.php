@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary transition-colors">
                        Yönetim Paneli
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="{{ route('admin.coupons.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary md:ml-2 transition-colors">Kuponlar</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Yeni Oluştur</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <h1 class="text-2xl font-black text-gray-900">Yeni Kupon Oluştur</h1>
                <p class="mt-1 text-sm text-gray-500">Platform genelinde geçerli yeni bir indirim kuponu tanımlayın.</p>
            </div>

            <form action="{{ route('admin.coupons.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Coupon Code -->
                    <div class="md:col-span-2">
                        <label for="code" class="block text-sm font-bold text-gray-700 mb-2">Kupon Kodu</label>
                        <div class="relative">
                            <input type="text" name="code" id="code" value="{{ old('code', strtoupper(Str::random(8))) }}" 
                                   class="block w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-mono font-bold tracking-widest text-lg uppercase"
                                   required>
                            <button type="button" onclick="document.getElementById('code').value = Math.random().toString(36).substring(2, 10).toUpperCase()" 
                                    class="absolute right-3 top-2.5 p-1 text-gray-400 hover:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </button>
                        </div>
                        @error('code') <p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-bold text-gray-700 mb-2">İndirim Tipi</label>
                        <select name="type" id="type" class="block w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Sabit Tutar (TL)</option>
                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Yüzde (%)</option>
                        </select>
                        @error('type') <p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Value -->
                    <div>
                        <label for="value" class="block text-sm font-bold text-gray-700 mb-2">İndirim Değeri</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="value" id="value" value="{{ old('value') }}" 
                                   class="block w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-bold"
                                   required placeholder="0.00">
                        </div>
                        @error('value') <p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Min Amount -->
                    <div>
                        <label for="min_amount" class="block text-sm font-bold text-gray-700 mb-2">Min. Harcama Tutarı</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400 font-bold">₺</span>
                            <input type="number" step="0.01" name="min_amount" id="min_amount" value="{{ old('min_amount', '0.00') }}" 
                                   class="block w-full pl-8 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-bold"
                                   required>
                        </div>
                        @error('min_amount') <p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Max Uses -->
                    <div>
                        <label for="max_uses" class="block text-sm font-bold text-gray-700 mb-2">Kullanım Limiti (Opsiyonel)</label>
                        <input type="number" name="max_uses" id="max_uses" value="{{ old('max_uses') }}" 
                               class="block w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-bold"
                               placeholder="Sınırsız için boş bırakın">
                        @error('max_uses') <p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Expires At -->
                    <div>
                        <label for="expires_at" class="block text-sm font-bold text-gray-700 mb-2">Son Kullanma Tarihi</label>
                        <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at') }}" 
                               class="block w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-bold">
                        @error('expires_at') <p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status -->
                    <div class="flex items-center space-x-3 mt-4">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary/20">
                        <label for="is_active" class="text-sm font-bold text-gray-700">Kuponu Hemen Aktifleştir</label>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.coupons.index') }}" class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition">İptal</a>
                    <button type="submit" class="px-10 py-3 bg-gradient-to-r from-primary to-purple-700 text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:from-primary/90 hover:to-purple-800 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                        Kaydet ve Yayınla
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
