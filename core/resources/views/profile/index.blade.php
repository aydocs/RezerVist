@extends('layouts.app')

@section('title', 'Profilim - ' . ($globalSettings['site_name'] ?? config('app.name')))

@section('content')
@php
    $user = auth()->user();
@endphp

<div class="bg-gray-50/50 min-h-screen py-12 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ photoPreview: '{{ $user->profile_photo_url }}', editingPhoto: false }">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3">
                <div class="sticky top-24">
                    @include('profile.partials.sidebar')
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-9">
                <!-- Header -->
                <div class="mb-6 md:mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">Profil Ayarları</h1>
                        <p class="text-sm text-gray-500 mt-1 md:mt-2">Kişisel bilgilerinizi ve hesap ayarlarınızı yönetin.</p>
                    </div>
                </div>

                <!-- Photo Upload Collapse Area -->
                <div x-show="editingPhoto" x-collapse 
                     class="mb-8 bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-purple-500/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                    
                     <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                        @csrf
                        <div class="relative group cursor-pointer">
                            <input type="file" name="photo" class="hidden" id="photoInput" accept="image/*" 
                                   @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                            <label for="photoInput" class="block w-32 h-32 rounded-full border-4 border-white shadow-xl overflow-hidden relative group-hover:ring-4 ring-primary/20 transition-all">
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!photoPreview">
                                   <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                   </div>
                                </template>
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                </div>
                            </label>
                        </div>
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Profil Fotoğrafını Değiştir</h3>
                            <p class="text-gray-500 text-sm mb-6 max-w-sm">Daha iyi bir görünüm için net ve yüzünüzün göründüğü bir fotoğraf tercih edin. (JPG, PNG - Max 2MB)</p>
                             <div class="flex items-center justify-center md:justify-start gap-3">
                                <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-xl font-black border-2 border-white/20 hover:border-white transition shadow-lg shadow-primary/25">Kaydet</button>
                                <button type="button" @click="editingPhoto = false" class="px-6 py-2.5 rounded-xl font-black text-gray-600 hover:bg-gray-50 border-2 border-gray-200 hover:border-gray-400 transition">İptal</button>
                            </div>
                        </div>
                    </form>
                </div>

                @if($errors->any())
                    <div class="mb-8 bg-red-50 border border-red-100 text-red-800 px-6 py-4 rounded-2xl animate-fade-in-down">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="font-bold">Bir hata oluştu!</p>
                        </div>
                        <ul class="list-disc list-inside text-sm opacity-90">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-8 bg-green-50 border border-green-100 text-green-800 px-6 py-4 rounded-2xl flex items-center gap-4 animate-fade-in-down">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold">Başarılı!</p>
                            <p class="text-sm opacity-90">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <form action="/profile" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Personal Info Card -->
                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-gray-100/50 mb-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Kişisel Bilgiler</h2>
                                <p class="text-xs text-gray-500">Temel hesap bilgileriniz</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name Input -->
                            <div class="group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-primary transition-colors">Ad Soyad</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <input type="text" name="name" value="{{ $user->name }}" class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-xl text-gray-900 font-medium focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all placeholder-gray-400" placeholder="Adınız Soyadınız">
                                </div>
                            </div>

                            <!-- Email Input -->
                            <div class="group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">E-posta Adresi</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <input type="email" value="{{ $user->email }}" disabled class="w-full pl-12 pr-4 py-3 bg-gray-50/50 border-none rounded-xl text-gray-500 font-medium cursor-not-allowed">
                                    <input type="hidden" name="email" value="{{ $user->email }}">
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Phone Input -->
                            <div class="group md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-primary transition-colors">Telefon Numarası</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </div>
                                    <input type="tel" name="phone" value="{{ $user->phone }}" class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-xl text-gray-900 font-medium focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all placeholder-gray-400" placeholder="05XX XXX XX XX">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info Card -->
                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-gray-100/50 mb-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Fatura Bilgileri</h2>
                                <p class="text-xs text-gray-500">Faturalandırma ve iletişim için</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Address -->
                            <div class="group md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-purple-600 transition-colors">Adres</label>
                                <div class="relative">
                                     <div class="absolute top-3.5 left-4 flex items-start pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <textarea name="address" rows="3" class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-xl text-gray-900 font-medium focus:ring-2 focus:ring-purple-500/20 focus:bg-white transition-all placeholder-gray-400" placeholder="Açık adresiniz">{{ $user->address }}</textarea>
                                </div>
                            </div>

                            <!-- City -->
                            <div class="group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-purple-600 transition-colors">Şehir</label>
                                <div class="relative">
                                    <input type="text" name="city" value="{{ $user->city }}" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-gray-900 font-medium focus:ring-2 focus:ring-purple-500/20 focus:bg-white transition-all placeholder-gray-400" placeholder="İl">
                                </div>
                            </div>

                             <!-- District -->
                            <div class="group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-purple-600 transition-colors">İlçe</label>
                                <div class="relative">
                                    <input type="text" name="district" value="{{ $user->district }}" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-gray-900 font-medium focus:ring-2 focus:ring-purple-500/20 focus:bg-white transition-all placeholder-gray-400" placeholder="İlçe">
                                </div>
                            </div>

                             <!-- Zip -->
                            <div class="group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-purple-600 transition-colors">Posta Kodu</label>
                                <div class="relative">
                                    <input type="text" name="zip_code" value="{{ $user->zip_code }}" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-gray-900 font-medium focus:ring-2 focus:ring-purple-500/20 focus:bg-white transition-all placeholder-gray-400" placeholder="34XXX">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Wallet / Balance Card -->
                    <a href="{{ route('profile.wallet.index') }}" class="block bg-gradient-to-r from-purple-600 to-indigo-600 rounded-3xl p-8 border border-purple-500 shadow-xl shadow-purple-500/20 mb-8 text-white relative overflow-hidden group hover:scale-[1.01] transition-transform duration-300">
                        <div class="absolute right-0 top-0 h-full w-1/3 bg-white/10 skew-x-12 transform origin-bottom-left transition duration-500 group-hover:skew-x-6"></div>
                        <div class="flex items-center gap-4 mb-6 relative z-10">
                            <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-white">Cüzdan Bakiyem</h2>
                                    <svg class="w-5 h-5 text-white/50 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                                <p class="text-xs text-purple-200">Bakiye yüklemek ve geçmişi görmek için tıklayın</p>
                            </div>
                        </div>
                        
                        <div class="relative z-10">
                            <div class="text-4xl font-bold mb-2 tracking-tight">₺{{ number_format($user->balance, 2) }}</div>
                            <p class="text-sm text-purple-100 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Bu bakiye ile rezervasyonlarınızı anında ödeyebilirsiniz.
                            </p>
                        </div>
                    </a>

                    <!-- Notification Settings Card -->
                    <div id="notifications" class="bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-gray-100/50 mb-8 scroll-mt-24">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Bildirim Ayarları</h2>
                                <p class="text-xs text-gray-500">Tarayıcı bildirimlerini yönetin</p>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row items-center justify-between gap-6 p-6 bg-slate-50 rounded-2xl border border-slate-100"
                             x-data="{ isPushEnabled: window.isPushEnabled || false }"
                             x-on:push-status-changed.window="isPushEnabled = $event.detail">
                            <div>
                                <h3 class="font-bold text-slate-800">Web Push Bildirimleri</h3>
                                <p class="text-sm text-slate-500 max-w-md">Tarayıcınız kapalı olsa bile rezervasyon hatırlatmaları ve önemli güncellemelerden haberdar olun.</p>
                            </div>
                            <button type="button" 
                                    x-on:click="isPushEnabled ? (window.unsubscribePush ? window.unsubscribePush() : showToast('Sistem hazırlanıyor...', 'info')) : (window.subscribePush ? window.subscribePush() : showToast('Sistem hazırlanıyor...', 'info'))"
                                    :class="isPushEnabled ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20'"
                                    class="px-6 py-3 rounded-xl font-bold border transition-all flex items-center gap-2 group whitespace-nowrap">
                                <template x-if="!isPushEnabled">
                                    <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </template>
                                <template x-if="isPushEnabled">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </template>
                                <span x-text="isPushEnabled ? 'Etkinleştirildi' : 'Etkinleştir'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Security Card -->
                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-gray-100/50 mb-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Güvenlik</h2>
                                <p class="text-xs text-gray-500">Şifre güncelleme işlemleri</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Current Password -->
                            <div class="group md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-orange-600 transition-colors">Mevcut Şifre</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-orange-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                    <input type="password" name="current_password" class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-xl text-gray-900 font-medium focus:ring-2 focus:ring-orange-500/20 focus:bg-white transition-all placeholder-gray-400" placeholder="••••••••">
                                </div>
                                @error('current_password')
                                    <div class="mt-2 text-sm text-red-600 flex items-center justify-between bg-red-50 p-3 rounded-lg border border-red-100">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span>{{ $message }}</span>
                                        </div>
                                        <a href="{{ route('password.request') }}" class="font-bold underline hover:text-red-800 whitespace-nowrap ml-4">Şifrenizi mi unuttunuz?</a>
                                    </div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-orange-600 transition-colors">Yeni Şifre</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-orange-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                    </div>
                                    <input type="password" name="password" class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-xl text-gray-900 font-medium focus:ring-2 focus:ring-orange-500/20 focus:bg-white transition-all placeholder-gray-400" placeholder="••••••••">
                                </div>
                            </div>

                             <!-- Password Confirm -->
                            <div class="group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 group-focus-within:text-orange-600 transition-colors">Şifre Tekrar</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-orange-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <input type="password" name="password_confirmation" class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-xl text-gray-900 font-medium focus:ring-2 focus:ring-orange-500/20 focus:bg-white transition-all placeholder-gray-400" placeholder="••••••••">
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <p class="text-xs text-orange-600 bg-orange-50 px-4 py-3 rounded-xl border border-orange-100 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Şifrenizi değiştirmek istemiyorsanız bu alanları boş bırakabilirsiniz.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Bar -->
                    <div class="flex items-center justify-center md:justify-end mt-4">
                        <button type="submit" class="w-full md:w-auto bg-gray-900 text-white px-10 py-4 rounded-2xl font-black text-lg border-2 border-white/10 hover:border-white transition shadow-xl shadow-gray-900/20 hover:scale-[1.02] active:scale-[0.98] transform duration-200 flex items-center justify-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Değişiklikleri Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
