@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-row-reverse">
    <!-- Right Side - Image -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 relative overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;80&quot; height=&quot;80&quot; viewBox=&quot;0 0 80 80&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.3&quot;%3E%3Cpath d=&quot;M50 50c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10s-10-4.477-10-10 4.477-10 10-10zM10 10c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10S0 25.523 0 20s4.477-10 10-10zm10 8c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8zm40 40c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8z&quot; /%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); background-size: 80px 80px;"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-center items-center text-center px-12 w-full">
            <div class="mb-8">
                <div class="w-28 h-28 bg-white/20 backdrop-blur-lg rounded-3xl flex items-center justify-center mb-6 mx-auto shadow-2xl transition duration-300">
                    <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
            
            <h1 class="text-5xl font-black text-white mb-6 leading-tight">
                {!! nl2br(__('auth.register.hero_title')) !!}
            </h1>
            
            <p class="text-xl text-white/90 mb-12 max-w-md leading-relaxed">
                {{ __('auth.register.hero_subtitle') }}
            </p>

            <!-- Features List -->
            <div class="space-y-4 text-left max-w-sm">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <span class="text-white text-lg font-medium">{{ __('auth.register.features.discover') }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <span class="text-white text-lg font-medium">{{ __('auth.register.features.reviews') }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002 2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                    </div>
                    <span class="text-white text-lg font-medium">{{ __('auth.register.features.online') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Left Side - Register Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-5 sm:p-8 bg-white" x-data="registrationForm()">
        <div class="max-w-md w-full">
            <div class="text-center mb-6 lg:hidden">
                <div class="w-16 h-16 bg-gradient-to-br from-primary to-purple-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-white font-bold text-3xl">R</span>
                </div>
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">{{ __('auth.register.title') }}</h2>
            <p class="text-gray-600 mb-6 text-sm sm:text-base">{{ __('auth.register.subtitle') }}</p>

            <form action="/register" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('auth.register.name') }}</label>
                        <input 
                            type="text" 
                            name="name" 
                            required 
                            value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition bg-gray-50/50"
                            placeholder="Ahmet Yılmaz">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Telefon <span class="text-gray-400 font-normal">(İsteğe bağlı)</span></label>
                        <input 
                            type="tel" 
                            name="phone" 
                            value="{{ old('phone') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition bg-gray-50/50"
                            placeholder="5XX XXX XX XX">
                        @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('auth.register.email') }}</label>
                    <input 
                        type="email" 
                        name="email" 
                        required 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition bg-gray-50/50"
                        placeholder="ornek@email.com">
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Referans Kodu <span class="text-gray-400 font-normal">(Opsiyonel)</span></label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="referral_code" 
                            value="{{ old('referral_code', request('ref')) }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition bg-white uppercase placeholder:normal-case placeholder:text-gray-400"
                            placeholder="Arkadaşının davet kodu varsa buraya yaz">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa-solid fa-tag"></i>
                        </div>
                    </div>
                    @error('referral_code')
                    <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <div x-data="{ show: false }">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('auth.register.password') }}</label>
                    <div class="relative">
                        <input 
                            :type="show ? 'text' : 'password'" 
                            name="password" 
                            required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition bg-gray-50/50 pr-12"
                            placeholder="En az 8 karakter">
                        <button 
                            type="button"
                            @click="show = !show"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('auth.register.password_confirmation') }}</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition bg-gray-50/50"
                        placeholder="Şifrenizi tekrar girin">
                </div>

                <!-- Premium Terms Agreement Card -->
                <div class="relative mt-6">
                    <input type="checkbox" name="terms_accepted" x-model="terms_accepted" required class="sr-only">
                    
                    <div class="p-4 rounded-2xl border-2 transition-all duration-500 overflow-hidden relative group"
                         :class="terms_accepted ? 'bg-green-50 border-green-200' : 'bg-blue-50/50 border-blue-100 hover:border-primary/30'">
                        
                        <!-- Animated Background for Completed State -->
                        <div x-show="terms_accepted" 
                             x-transition:enter="transition duration-1000"
                             x-transition:enter-start="opacity-0 translate-x-full"
                             x-transition:enter-end="opacity-100 translate-x-0"
                             class="absolute inset-0 bg-gradient-to-r from-green-100 to-emerald-50 z-0"></div>

                        <div class="relative z-10 flex items-center gap-4">
                            <!-- Status Icon -->
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 transition-all duration-500 transform"
                                 @click="openModal('terms')"
                                 :class="terms_accepted ? 'bg-green-500 scale-110 rotate-[360deg]' : 'bg-primary/10 group-hover:bg-primary group-hover:scale-110 cursor-pointer'">
                                <svg x-show="!terms_accepted" class="w-6 h-6 text-primary group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <svg x-show="terms_accepted" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>

                            <div class="flex-1">
                                <h4 class="text-sm font-black text-gray-900 leading-none mb-1" x-text="terms_accepted ? 'Sözleşmeler Onaylandı' : 'Yasal Belgeler & Onay'"></h4>
                                <div class="text-xs text-gray-500 leading-tight">
                                    <template x-if="!terms_accepted">
                                        <p>Devam etmek için <button type="button" @click="openModal('terms')" class="text-primary font-bold hover:underline">Kullanım Koşulları</button> ve <button type="button" @click="openModal('privacy')" class="text-primary font-bold hover:underline">Gizlilik Politikası</button>'nı inceleyin.</p>
                                    </template>
                                    <template x-if="terms_accepted">
                                        <p class="text-green-700 font-bold">Tüm yasal şartları okudunuz ve kabul ettiniz.</p>
                                    </template>
                                </div>
                            </div>

                            <!-- Fancy Button (Hidden if accepted) -->
                            <button x-show="!terms_accepted" 
                                    type="button" 
                                    @click="openModal('terms')"
                                    class="px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-black text-primary hover:bg-primary hover:text-white transition-all shadow-sm">
                                İncele & Onayla
                            </button>
                        </div>
                    </div>
                    
                    @error('terms_accepted')
                    <p class="mt-2 text-sm text-red-600 px-2">{{ $message }}</p>
                    @enderror
                </div>

                <button 
                    type="submit"
                    class="w-full py-4 mt-2 bg-gradient-to-r from-primary to-purple-700 hover:from-purple-700 hover:to-primary text-white font-black text-lg rounded-xl border-2 border-white/20 hover:border-white transition-all duration-300 shadow-xl shadow-primary/30 transform active:scale-[0.98] flex items-center justify-center gap-2">
                    {{ __('auth.register.register_button') }}
                </button>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-400 font-medium">{{ __('auth.login.or') }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('social.redirect', 'google') }}" class="flex items-center justify-center px-4 py-3 border border-gray-200 rounded-xl bg-white hover:bg-gray-50 transition group">
                        <svg class="w-6 h-6 transition-transform group-hover:rotate-12" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    </a>
                    <a href="{{ route('social.redirect', 'apple') }}" class="flex items-center justify-center px-4 py-3 border border-transparent rounded-xl bg-black hover:bg-gray-900 transition text-white group">
                        <i class="fa-brands fa-apple text-2xl transition-transform group-hover:-rotate-12"></i>
                    </a>
                </div>
            </form>

            <p class="mt-8 text-center text-sm text-gray-500">
                {{ __('auth.register.has_account') }}
                <a href="/login" class="font-black text-primary hover:text-purple-700 transition">{{ __('auth.register.login_link') }}</a>
            </p>
        </div>

        <!-- Extreme Premium Modal -->
        <template x-teleport="body">
            <div x-show="showModal" 
                 class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-8"
                 style="display: none;">
                
                <!-- Blur Backdrop -->
                <div class="absolute inset-0 bg-gray-900/80 backdrop-blur-md transition-opacity"
                     x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="scrolledToBottom ? closeModal() : null"></div>

                <!-- Modal Dialog -->
                <div class="relative bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[85vh] border border-white/20"
                     x-show="showModal"
                     x-transition:enter="ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-24 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-24 scale-95">
                    
                    <!-- Premium Header -->
                    <div class="px-10 pt-10 pb-6 flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-3.632A9.963 9.963 0 0014 21h1a9 9 0 009-9v-1a9 9 0 00-9-9H5a2 2 0 00-2 2v10a2 2 0 002 2h3"></path></svg>
                                </div>
                                <h3 class="text-2xl font-black text-gray-900 tracking-tight" x-text="modalTitle"></h3>
                            </div>
                            <p class="text-sm text-gray-400 font-medium">Bu belgeyi onaylamanız kayıt işleminiz için gereklidir.</p>
                        </div>
                        
                        <!-- Close Button Area -->
                        <div x-show="scrolledToBottom">
                            <button @click="closeModal()" class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-900 hover:bg-gray-100 transition-all transform active:scale-90">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Scroll Control Component -->
                    <div class="flex-1 overflow-hidden flex flex-col relative">
                        <!-- Scrollable Content Area -->
                        <div class="flex-1 overflow-y-auto px-10 pb-10 scroll-smooth" 
                             id="modalContentArea"
                             @scroll="handleScroll($event)">
                            
                            <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                                <div x-show="modalType === 'terms'">
                                    @include('pages.terms_content')
                                </div>
                                <div x-show="modalType === 'privacy'">
                                    @include('pages.privacy_content')
                                </div>
                            </div>

                            <!-- Indicator that they reached the end -->
                            <div x-show="scrolledToBottom" x-transition class="mt-8 p-6 bg-green-50 rounded-2xl border border-green-100 flex items-center gap-4">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white shrink-0 shadow-lg shadow-green-500/20">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <h5 class="font-black text-green-900 text-sm">Harika!</h5>
                                    <p class="text-green-700 text-xs font-medium">Metnin tamamını okudunuz. Şimdi onaylayabilirsiniz.</p>
                                </div>
                            </div>
                            
                            <div class="h-6"></div>
                        </div>

                        <!-- Fade Out Effect at Bottom (Shown only if NOT scrolled bottom) -->
                        <div x-show="!scrolledToBottom" 
                             class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-white to-transparent pointer-events-none transition-opacity duration-300"></div>
                    </div>

                    <!-- Premium Footer -->
                    <div class="px-10 py-8 bg-gray-50/80 backdrop-blur-md border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4 group">
                            <!-- Circular Progress Indicator -->
                            <div class="relative w-12 h-12 flex items-center justify-center">
                                <svg class="w-full h-full -rotate-90">
                                    <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="4" fill="transparent" class="text-gray-200" />
                                    <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="4" fill="transparent" :style="`stroke-dasharray: 125.6; stroke-dashoffset: ${125.6 * (1 - scrollPercent/100)}`" class="text-primary transition-all duration-300" />
                                </svg>
                                <span class="absolute text-[10px] font-black text-gray-500" x-text="`${Math.round(scrollPercent)}%`" x-show="!scrolledToBottom"></span>
                                <svg x-show="scrolledToBottom" class="absolute w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h6 class="text-xs font-black text-gray-900 uppercase tracking-widest leading-none mb-1" x-text="scrolledToBottom ? 'Hazır' : 'Okuma Durumu'"></h6>
                                <p class="text-[10px] text-gray-400 font-bold" x-text="scrolledToBottom ? 'Onay bekliyor' : 'Devam etmek için kaydırın'"></p>
                            </div>
                        </div>

                        <button 
                            @click="acceptTerms()"
                            :disabled="!scrolledToBottom"
                            class="w-full sm:w-auto px-12 py-4 bg-primary text-white font-black rounded-2xl shadow-xl shadow-primary/30 transition-all duration-500 disabled:opacity-20 disabled:grayscale disabled:scale-95 transform active:scale-90 flex items-center justify-center gap-2">
                            <span>Okudum, Kabul Ediyorum</span>
                            <svg x-show="scrolledToBottom" class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('registrationForm', () => ({
            terms_accepted: false,
            showModal: false,
            modalType: '',
            modalTitle: '',
            scrolledToBottom: false,
            scrollPercent: 0,
            
            openModal(type) {
                console.log('Opening Modal:', type);
                this.modalType = type;
                this.modalTitle = type === 'terms' ? 'Kullanım Koşulları' : 'Gizlilik Politikası';
                this.scrolledToBottom = false;
                this.scrollPercent = 0;
                this.showModal = true;
                
                // Allow teleport/DOM to catch up
                setTimeout(() => {
                    const el = document.getElementById('modalContentArea');
                    if (el) {
                        el.scrollTop = 0;
                        // Initial check if content is small enough to be already at bottom
                        if (el.scrollHeight <= el.clientHeight) {
                            this.scrolledToBottom = true;
                            this.scrollPercent = 100;
                        }
                    }
                }, 50);
            },
            
            closeModal() {
                this.showModal = false;
            },
            
            handleScroll(e) {
                const element = e.target;
                const total = element.scrollHeight - element.clientHeight;
                const current = element.scrollTop;
                
                if (total > 0) {
                    this.scrollPercent = Math.min((current / total) * 100, 100);
                } else {
                    this.scrollPercent = 100;
                }

                // Threshold check (5px tolerance)
                if (total - current <= 10) {
                    this.scrolledToBottom = true;
                }
            },
            
            acceptTerms() {
                this.terms_accepted = true;
                this.closeModal();
                
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Yasal belgeler başarıyla onaylandı.',
                    showConfirmButton: false,
                    timer: 2500,
                    background: '#ffffff',
                    color: '#6200EE',
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border-l-4 border-primary'
                    }
                });
            }
        }));
    });
</script>

<style>
    /* Custom scrollbar for premium look */
    #modalContentArea::-webkit-scrollbar {
        width: 8px;
    }
    #modalContentArea::-webkit-scrollbar-track {
        background: transparent;
    }
    #modalContentArea::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    #modalContentArea::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>
@endsection

