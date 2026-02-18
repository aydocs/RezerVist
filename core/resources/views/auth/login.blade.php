@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Side - Image -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary via-purple-700 to-indigo-900 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); background-size: 60px 60px;"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-center items-center text-center px-12 w-full">
            <div class="mb-8">
                <div class="w-24 h-24 bg-white/20 backdrop-blur-lg rounded-3xl flex items-center justify-center mb-6 mx-auto shadow-2xl">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </div>
            </div>
            
            <h1 class="text-5xl font-black text-white mb-6 leading-tight">
                {!! nl2br(__('auth.login.hero_title')) !!}
            </h1>
            
            <p class="text-xl text-white/90 mb-12 max-w-md leading-relaxed">
                {{ __('auth.login.hero_subtitle') }}
            </p>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-8 w-full max-w-lg">
                <div class="text-center">
                    <div class="text-4xl font-black text-white mb-2">1000+</div>
                    <div class="text-sm text-white/80">{{ __('auth.login.stats.places') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-black text-white mb-2">50K+</div>
                    <div class="text-sm text-white/80">{{ __('auth.login.stats.members') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-black text-white mb-2">4.9</div>
                    <div class="text-sm text-white/80">{{ __('auth.login.stats.rating') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-5 sm:p-8 bg-gray-50" x-data="loginForm()">
        <div class="max-w-md w-full">
            <!-- Logo for mobile -->
            <div class="text-center mb-6 lg:hidden">
                <div class="w-16 h-16 bg-gradient-to-br from-primary to-purple-700 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl">
                    <span class="text-white font-bold text-3xl">R</span>
                </div>
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">{{ __('auth.login.title') }}</h2>
            <p class="text-gray-600 mb-6 sm:mb-8 text-sm sm:text-base">{{ __('auth.login.subtitle') }}</p>

            {{-- Flash Messages --}}
            @if(session('info'))
                <div class="mb-6 bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-center gap-3 shadow-sm border-l-4 border-l-blue-500 animate-fade-in-up">
                    <i class="fa-solid fa-circle-info text-blue-500"></i>
                    <p class="text-sm font-bold text-blue-700">{{ session('info') }}</p>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-center gap-3 shadow-sm border-l-4 border-l-emerald-500 animate-fade-in-up">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <p class="text-sm font-bold text-emerald-700">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-rose-50 border border-rose-100 rounded-xl p-4 flex items-center gap-3 shadow-sm border-l-4 border-l-rose-500 animate-fade-in-up">
                    <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                    <p class="text-sm font-bold text-rose-700">{{ session('error') }}</p>
                </div>
            @endif

            <form action="/login" method="POST" class="space-y-5" @submit="handleLoginSubmit()">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('auth.login.email') }}</label>
                    <input 
                        type="email" 
                        name="email" 
                        x-model="email"
                        required 
                        class="w-full px-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        placeholder="ornek@email.com">
                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('auth.login.password') }}</label>
                    <div class="relative">
                        <input 
                            :type="showPassword ? 'text' : 'password'" 
                            name="password" 
                            required
                            class="w-full px-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition pr-12"
                            placeholder="••••••••">
                        <button 
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                    @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" name="remember" x-model="remember" class="sr-only">
                            <div class="w-10 h-6 bg-gray-200 rounded-full shadow-inner transition group-hover:bg-gray-300" :class="remember ? 'bg-primary' : ''"></div>
                            <div class="absolute inset-y-1 left-1 w-4 h-4 bg-white rounded-full shadow transition-transform" :class="remember ? 'translate-x-4' : ''"></div>
                        </div>
                        <span class="ml-3 text-sm text-gray-600 font-medium select-none">{{ __('auth.login.remember_me') }}</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary hover:text-purple-700 transition">{{ __('auth.login.forgot_password') }}</a>
                </div>

                <button 
                    type="submit"
                    class="w-full py-4 px-4 bg-gradient-to-r from-primary to-purple-700 hover:from-purple-700 hover:to-primary text-white font-black text-lg rounded-xl border-2 border-white/20 hover:border-white transition-all duration-300 shadow-xl shadow-primary/30 transform active:scale-[0.98] flex items-center justify-center gap-2">
                    {{ __('auth.login.login_button') }}
                </button>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-gray-50 text-gray-500">{{ __('auth.login.or') }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('social.redirect', 'google') }}" class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-xl bg-white hover:bg-gray-50 transition group">
                        <svg class="w-6 h-6 transition-transform group-hover:scale-110" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    </a>
                    <a href="{{ route('social.redirect', 'apple') }}" class="flex items-center justify-center px-4 py-3 border border-transparent rounded-xl bg-black hover:bg-gray-900 transition text-white group">
                        <i class="fa-brands fa-apple text-2xl transition-transform group-hover:scale-110"></i>
                    </a>
                </div>
            </form>

            <p class="mt-8 text-center text-sm text-gray-600">
                {{ __('auth.login.no_account') }}
                <a href="/register" class="font-bold text-primary hover:text-purple-700 transition">{{ __('auth.login.register_link') }}</a>
            </p>
        </div>
    </div>
</div>

<script>
    function loginForm() {
        return {
            email: '{{ old('email') }}',
            showPassword: false,
            remember: false,
            
            init() {
                const savedEmail = localStorage.getItem('rezervist_remember_email');
                if (savedEmail) {
                    this.email = savedEmail;
                    this.remember = true;
                }
            },
            
            handleLoginSubmit() {
                if (this.remember) {
                    localStorage.setItem('rezervist_remember_email', this.email);
                } else {
                    localStorage.removeItem('rezervist_remember_email');
                }
            }
        }
    }
</script>
@endsection
