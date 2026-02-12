@extends('layouts.app')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen pb-20 font-inter pt-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Text -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight mb-2">Hızlı Başlangıç Rehberi</h1>
            <p class="text-slate-500 font-medium text-base">İşletmenizi zirveye taşımak için adımları tamamlayın.</p>
        </div>

        <!-- Main Card Container -->
        <div class="relative w-full max-w-6xl mx-auto">
            
            <!-- Dark Card -->
            <div class="relative bg-[#0B0F19] rounded-[32px] overflow-hidden shadow-2xl shadow-indigo-500/10 border border-slate-800/50 p-8 md:p-14">
                
                <!-- Background Gradients/Glows -->
                <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[120px] -mr-40 -mt-40 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-violet-600/5 rounded-full blur-[100px] -ml-20 -mb-20 pointer-events-none"></div>

                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    
                    <!-- Left Side: Content -->
                    <div class="lg:col-span-7">
                        <!-- Badge -->
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 mb-6">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                            <span class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest">KURULUM SİHİRBAZI</span>
                        </div>

                        <!-- Headline -->
                        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight tracking-tight">
                            İşletmenizi <br>
                            <span class="text-indigo-400">Zirveye Taşıyın!</span>
                            <span class="inline-flex items-center justify-center p-1.5 ml-2 rounded-xl bg-indigo-500/20 align-top mt-1 transform rotate-12">
                                <svg class="w-6 h-6 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
                            </span>
                        </h2>

                        <p class="text-slate-400 text-lg leading-relaxed mb-10 max-w-lg">
                            Rezervist ile dijitalleşmek artık çok kolay. Profilinizi tamamlayın, menünüzü ekleyin ve rezervasyonları kabul etmeye hemen başlayın.
                        </p>

                        <!-- Action Cards Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @php
                                $cards = [
                                    ['title' => 'Profil', 'desc' => 'Bilgileri Düzenle', 'icon' => 'user',     'route' => 'vendor.business.edit'],
                                    ['title' => 'Menü',   'desc' => 'Ürün Ekle',         'icon' => 'clipboard', 'route' => 'vendor.menus.index'],
                                    ['title' => 'Saatler','desc' => 'Zamanı Yönet',      'icon' => 'clock',     'route' => 'vendor.business.hours.edit'],
                                    ['title' => 'Ekip',   'desc' => 'Personel Ekle',     'icon' => 'users',     'route' => 'vendor.staff.index'],
                                ];
                            @endphp

                            @foreach($cards as $card)
                            <a href="{{ route($card['route']) }}" class="group relative flex flex-col items-center justify-center bg-[#151B2B] hover:bg-[#1A2133] border border-white/5 hover:border-indigo-500/30 rounded-2xl p-4 transition-all duration-300 hover:-translate-y-1 text-center h-full">
                                <div class="w-10 h-10 rounded-xl bg-[#1E2538] text-indigo-400 flex items-center justify-center mb-3 group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                                    @if($card['icon'] == 'user')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    @elseif($card['icon'] == 'clipboard')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    @elseif($card['icon'] == 'clock')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @elseif($card['icon'] == 'users')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    @endif
                                </div>
                                <h3 class="text-white font-bold text-sm mb-1">{{ $card['title'] }}</h3>
                                <p class="text-slate-500 text-[10px] font-medium">{{ $card['desc'] }}</p>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right Side: Progress -->
                    <div class="lg:col-span-5">
                        <div class="bg-[#111623] rounded-3xl p-8 border border-white/5 relative shadow-xl">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">KURULUM DURUMU</p>
                                    <p class="text-4xl font-black text-white">%{{ $onboarding['percent'] }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                                    <svg class="w-5 h-5 {{ $onboarding['percent'] < 100 ? 'animate-spin-slow' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="h-2 w-full bg-slate-800 rounded-full mb-8 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-indigo-600 to-violet-500 rounded-full shadow-[0_0_15px_rgba(99,102,241,0.5)] transition-all duration-1000" style="width: {{ $onboarding['percent'] }}%"></div>
                            </div>

                            <!-- Checklist -->
                            <div class="space-y-4">
                                <!-- Step 1 -->
                                <div class="flex items-center gap-4">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $onboarding['steps']['profile'] ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-800 text-slate-600' }}">
                                        @if($onboarding['steps']['profile'])
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold {{ $onboarding['steps']['profile'] ? 'text-white' : 'text-slate-500' }}">İşletme Profili Oluşturuldu</span>
                                </div>

                                <!-- Step 2 -->
                                <div class="flex items-center gap-4">
                                     <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $onboarding['steps']['hours'] ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-800 text-slate-600' }}">
                                        @if($onboarding['steps']['hours'])
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold {{ $onboarding['steps']['hours'] ? 'text-white' : 'text-slate-500' }}">Çalışma Saatleri Girildi</span>
                                </div>

                                <!-- Step 3 -->
                                <div class="flex items-center gap-4">
                                     <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $onboarding['steps']['menu'] ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-800 text-slate-600' }}">
                                        @if($onboarding['steps']['menu'])
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold {{ $onboarding['steps']['menu'] ? 'text-white' : 'text-slate-500' }}">Menü Eklendi</span>
                                </div>

                                <!-- Step 4 -->
                                <div class="flex items-center gap-4">
                                     <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $onboarding['steps']['first_sale'] ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-800 text-slate-600' }}">
                                        @if($onboarding['steps']['first_sale'])
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold {{ $onboarding['steps']['first_sale'] ? 'text-white' : 'text-slate-500' }}">İlk Rezervasyon Tamamlandı</span>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Return Link -->
        <div class="text-center mt-12">
            <a href="{{ route('vendor.dashboard') }}" class="inline-flex items-center gap-2 text-slate-500 font-bold text-sm hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Panele Dön
            </a>
        </div>

    </div>
</div>
@endsection
