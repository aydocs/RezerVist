@extends('layouts.app')

@section('content')
<div class="relative min-h-screen pt-24 pb-12 bg-gray-50">
    <!-- Subtle Background Pattern (Brand Consistent) -->
    <div class="absolute inset-0 z-0 opacity-40 pointer-events-none" style="background-image: radial-gradient(#6366f1 1px, transparent 1px); background-size: 32px 32px;"></div>
    
    <!-- Top Gradient Blob (Subtle) -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-[400px] bg-indigo-500/10 blur-[100px] rounded-full pointer-events-none -z-10"></div>

    <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Header -->
        <div class="text-center mb-16 space-y-4">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight leading-tight">
                Nasıl Yardımcı Olabiliriz?
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                RezerVist deneyiminizi mükemmelleştirmek için buradayız. Sorularınızı, önerilerinizi veya yaşadığınız sorunları bizimle paylaşın.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Sidebar Info Cards -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Status Card -->
                <div class="p-6 rounded-2xl bg-white shadow-lg border border-gray-100 group hover:border-indigo-100 transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4 group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Hızlı Yanıt</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Destek talepleriniz ortalama <span class="text-indigo-600 font-bold">15 dakika</span> içerisinde uzman ekibimiz tarafından yanıtlanır.
                    </p>
                </div>

                <!-- FAQ Link Card -->
                <a href="{{ route('pages.help') }}" class="block p-6 rounded-2xl bg-white shadow-lg border border-gray-100 group hover:border-indigo-200 hover:shadow-xl transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-4 group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition">Sıkça Sorulan Sorular</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Beklemeden çözüm bulmak ister misiniz? Kapsamlı yardım merkezimize göz atın.
                    </p>
                </a>
            </div>

            <!-- Main Form & History -->
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden relative">
                    <!-- Top Line Gradient -->
                    <div class="h-1.5 w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                    
                    @if(session('success'))
                        <div class="m-8 p-4 rounded-xl bg-green-50 border border-green-100 flex items-center gap-4 animate-fade-in-down">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-green-800 font-bold">Talep Alındı!</h4>
                                <p class="text-green-600 text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="p-8 md:p-10">
                        <form action="{{ route('pages.live-support.submit') }}" method="POST" class="space-y-8" x-data="{ selectedSubject: '' }">
                            @csrf
                            
                            <!-- User Identity Section -->
                            @auth
                                <div class="flex items-center gap-5 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                                    <div class="relative">
                                        @if(auth()->user()->profile_photo_path)
                                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" class="w-14 h-14 rounded-full object-cover border-2 border-white shadow-sm">
                                        @else
                                            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-xl font-bold text-white border-2 border-white shadow-sm">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                                    </div>
                                    <div>
                                        <p class="text-gray-900 font-bold text-lg">{{ auth()->user()->name }}</p>
                                        <div class="flex items-center gap-2 text-gray-500 text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ auth()->user()->email }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2 opacity-50">
                                        <label class="text-sm font-bold text-gray-700 ml-1">Ad Soyad</label>
                                        <div class="h-12 w-full rounded-xl bg-gray-100 border border-gray-200 flex items-center px-4 text-gray-400 text-sm italic cursor-not-allowed">
                                            Giriş yapmanız gerekiyor
                                        </div>
                                    </div>
                                    <div class="space-y-2 opacity-50">
                                        <label class="text-sm font-bold text-gray-700 ml-1">E-Posta</label>
                                        <div class="h-12 w-full rounded-xl bg-gray-100 border border-gray-200 flex items-center px-4 text-gray-400 text-sm italic cursor-not-allowed">
                                            Giriş yapmanız gerekiyor
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span class="text-indigo-800 text-sm font-medium">Taleplerinizi takip etmek için giriş yapmalısınız.</span>
                                    </div>
                                    <a href="{{ route('login') }}" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg transition shadow-lg shadow-indigo-600/20">Giriş Yap</a>
                                </div>
                            @endauth

                            <div class="space-y-6">
                                <div class="space-y-3">
                                    <label class="text-sm font-bold text-gray-700 ml-1">Konu Başlığı</label>
                                    <input type="hidden" name="subject" x-model="selectedSubject" required>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        <!-- Rezervasyon -->
                                        <button type="button" @click="selectedSubject = 'Rezervasyon İşlemleri'" 
                                            :class="selectedSubject == 'Rezervasyon İşlemleri' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-500/10' : 'border-gray-200 bg-white hover:border-indigo-200'"
                                            class="flex flex-col items-center justify-center p-4 rounded-xl border text-center transition-all group">
                                            <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center mb-3 group-hover:bg-indigo-100 transition"
                                                :class="selectedSubject == 'Rezervasyon İşlemleri' ? 'bg-indigo-100 text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600'">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <span class="text-xs font-bold" :class="selectedSubject == 'Rezervasyon İşlemleri' ? 'text-indigo-700' : 'text-gray-600'">Rezervasyon</span>
                                        </button>

                                        <!-- Ödeme -->
                                        <button type="button" @click="selectedSubject = 'Ödeme Sorunları'" 
                                            :class="selectedSubject == 'Ödeme Sorunları' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-500/10' : 'border-gray-200 bg-white hover:border-indigo-200'"
                                            class="flex flex-col items-center justify-center p-4 rounded-xl border text-center transition-all group">
                                            <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center mb-3 group-hover:bg-indigo-100 transition"
                                                :class="selectedSubject == 'Ödeme Sorunları' ? 'bg-indigo-100 text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600'">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                            </div>
                                            <span class="text-xs font-bold" :class="selectedSubject == 'Ödeme Sorunları' ? 'text-indigo-700' : 'text-gray-600'">Ödeme</span>
                                        </button>

                                        <!-- Hesap -->
                                        <button type="button" @click="selectedSubject = 'Hesap Ayarları'" 
                                            :class="selectedSubject == 'Hesap Ayarları' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-500/10' : 'border-gray-200 bg-white hover:border-indigo-200'"
                                            class="flex flex-col items-center justify-center p-4 rounded-xl border text-center transition-all group">
                                            <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center mb-3 group-hover:bg-indigo-100 transition"
                                                :class="selectedSubject == 'Hesap Ayarları' ? 'bg-indigo-100 text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600'">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            </div>
                                            <span class="text-xs font-bold" :class="selectedSubject == 'Hesap Ayarları' ? 'text-indigo-700' : 'text-gray-600'">Hesap</span>
                                        </button>

                                        <!-- Teknik -->
                                        <button type="button" @click="selectedSubject = 'Teknik Destek'" 
                                            :class="selectedSubject == 'Teknik Destek' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-500/10' : 'border-gray-200 bg-white hover:border-indigo-200'"
                                            class="flex flex-col items-center justify-center p-4 rounded-xl border text-center transition-all group">
                                            <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center mb-3 group-hover:bg-indigo-100 transition"
                                                :class="selectedSubject == 'Teknik Destek' ? 'bg-indigo-100 text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600'">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            </div>
                                            <span class="text-xs font-bold" :class="selectedSubject == 'Teknik Destek' ? 'text-indigo-700' : 'text-gray-600'">Teknik</span>
                                        </button>

                                        <!-- Şikayet -->
                                        <button type="button" @click="selectedSubject = 'Şikayet & Öneri'" 
                                            :class="selectedSubject == 'Şikayet & Öneri' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-500/10' : 'border-gray-200 bg-white hover:border-indigo-200'"
                                            class="flex flex-col items-center justify-center p-4 rounded-xl border text-center transition-all group">
                                            <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center mb-3 group-hover:bg-indigo-100 transition"
                                                :class="selectedSubject == 'Şikayet & Öneri' ? 'bg-indigo-100 text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600'">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                            </div>
                                            <span class="text-xs font-bold" :class="selectedSubject == 'Şikayet & Öneri' ? 'text-indigo-700' : 'text-gray-600'">Öneri</span>
                                        </button>
                                        
                                        <!-- Diğer -->
                                        <button type="button" @click="selectedSubject = 'Diğer Konular'" 
                                            :class="selectedSubject == 'Diğer Konular' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-500/10' : 'border-gray-200 bg-white hover:border-indigo-200'"
                                            class="flex flex-col items-center justify-center p-4 rounded-xl border text-center transition-all group">
                                            <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center mb-3 group-hover:bg-indigo-100 transition"
                                                :class="selectedSubject == 'Diğer Konular' ? 'bg-indigo-100 text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600'">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                                            </div>
                                            <span class="text-xs font-bold" :class="selectedSubject == 'Diğer Konular' ? 'text-indigo-700' : 'text-gray-600'">Diğer</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-2 group">
                                    <label for="message" class="text-sm font-bold text-gray-700 ml-1 group-focus-within:text-indigo-600 transition">Mesajınız</label>
                                    <div class="relative">
                                        <textarea name="message" id="message" rows="6" class="w-full p-4 pl-12 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all resize-none leading-relaxed placeholder-gray-400" placeholder="Detaylı bir şekilde açıklar mısınız?" required></textarea>
                                        <svg class="w-6 h-6 text-gray-400 absolute left-4 top-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                    </div>
                                    <p class="text-xs text-gray-400 text-right">0/2000 karakter</p>
                                </div>
                            </div>

                            <button type="submit" class="group w-full h-14 bg-indigo-600 hover:bg-indigo-700 rounded-xl font-bold text-white text-lg shadow-xl shadow-indigo-600/20 hover:shadow-indigo-600/40 hover:scale-[1.01] active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-3 relative overflow-hidden">
                                <span>Destek Talebi Gönder</span>
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- History Link -->
                @auth
                <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                    <p class="text-gray-500 mb-4">Daha önce oluşturduğunuz talepleri ve yanıtlarını görüntülemek mi istiyorsunuz?</p>
                    <a href="{{ route('profile.support') }}" class="inline-flex items-center gap-2 text-indigo-600 font-bold hover:text-indigo-800 hover:underline transition">
                        <span>Talep Geçmişini Görüntüle</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
