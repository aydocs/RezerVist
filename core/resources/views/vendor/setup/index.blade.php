@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen pb-32 font-outfit pt-40 selection:bg-indigo-100 selection:text-indigo-900">
    <div class="max-w-4xl mx-auto px-6">
        
        <!-- Premium Header Section -->
        <div class="text-center mb-24 relative">
            <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl -z-10"></div>
            
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-50 border border-slate-100 mb-8 animate-fade-in">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                <span class="text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase leading-none">Kurulum Süreci</span>
            </div>

            <h1 class="text-5xl md:text-6xl font-black text-slate-900 tracking-tight mb-6 animate-slide-up">
                Hoş Geldiniz, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-600">{{ $business->name }}</span>
            </h1>
            <p class="text-slate-500 font-medium text-xl max-w-2xl mx-auto leading-relaxed animate-slide-up [animation-delay:200ms]">
                İşletmenizi dijital dünyada parlatmak için ihtiyacınız olan her şey burada. Hadi, birlikte başlayalım.
            </p>

            <!-- Minimalist Global Progress -->
            <div class="mt-12 flex flex-col items-center animate-slide-up [animation-delay:400ms]">
                <div class="w-full max-w-sm h-1.5 bg-slate-100 rounded-full overflow-hidden mb-4 p-0.5">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-full transition-all duration-1000 ease-out" style="width: {{ $onboarding['percent'] }}%"></div>
                </div>
                <div class="flex items-center gap-3 text-sm font-bold text-slate-400">
                    <span class="text-indigo-600 font-black">%{{ $onboarding['percent'] }}</span>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    <span>{{ $onboarding['completed_count'] }} / {{ $onboarding['total_count'] }} Görev</span>
                </div>
            </div>
        </div>

        <!-- Mission Journey -->
        <div class="space-y-4">
            @php
                $missions = [
                    [
                        'id' => 'profile',
                        'title' => 'İşletme Profili',
                        'desc' => 'Markanızın ilk izlenimi: Logo ve temel bilgiler.',
                        'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        'route' => 'vendor.business.edit',
                        'completed' => $onboarding['steps']['profile']
                    ],
                    [
                        'id' => 'hours',
                        'title' => 'Çalışma Saatleri',
                        'desc' => 'Hizmet verdiğiniz zaman dilimlerini netleştirin.',
                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'route' => 'vendor.business.hours.edit',
                        'completed' => $onboarding['steps']['hours']
                    ],
                    [
                        'id' => 'reservation_settings',
                        'title' => 'Rezervasyon Kuralları',
                        'desc' => 'Kapasite ve zamanlama limitlerini kontrol edin.',
                        'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                        'route' => 'vendor.business.edit',
                        'anchor' => 'reservation-settings',
                        'completed' => $onboarding['steps']['reservation_settings']
                    ],
                    [
                        'id' => 'resources',
                        'title' => 'Masa ve Alan Planı',
                        'desc' => 'Tesis yerleşimini ve masa kapasitelerini tanımlayın.',
                        'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                        'route' => 'vendor.resources.index',
                        'completed' => $onboarding['steps']['resources']
                    ],
                    [
                        'id' => 'menu',
                        'title' => 'Dijital Menü',
                        'desc' => 'Ürünlerinizi sergileyin ve fiyatlandırın.',
                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                        'route' => 'vendor.menus.index',
                        'completed' => $onboarding['steps']['menu']
                    ],
                    [
                        'id' => 'payments',
                        'title' => 'Ödeme Altyapısı',
                        'desc' => 'Iyzico entegrasyonu ile tahsilatları başlatın.',
                        'icon' => 'M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z M12 2a10 10 0 100 20 10 10 0 000-20z M12 18V6',
                        'route' => 'vendor.finance.index', 
                        'params' => ['open_iyzico' => '1'],
                        'completed' => $onboarding['steps']['payments']
                    ],
                    [
                        'id' => 'staff',
                        'title' => 'Ekip Yönetimi',
                        'desc' => 'Personel hesaplarını oluşturun ve yetkilendirin.',
                        'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                        'route' => 'vendor.staff.index',
                        'completed' => $onboarding['steps']['staff']
                    ],
                ];
            @endphp

            @foreach($missions as $index => $mission)
            <div class="animate-slide-up group" style="animation-delay: {{ 500 + ($index * 100) }}ms">
                <div class="relative bg-white rounded-3xl p-6 border border-slate-100 transition-all duration-500 hover:border-indigo-200 hover:shadow-[0_20px_50px_rgba(79,70,229,0.06)] flex items-center gap-6 overflow-hidden">
                    
                    @if($mission['completed'])
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl -mr-16 -mt-16"></div>
                    @endif

                    <!-- Visual Indicator -->
                    <div class="relative flex-shrink-0">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-500 {{ $mission['completed'] ? 'bg-emerald-500 text-white border border-emerald-400 rotate-12 scale-110' : 'bg-slate-50 text-slate-400 border border-slate-100 group-hover:bg-indigo-50 group-hover:text-indigo-600 group-hover:rotate-0' }}">
                            @if($mission['completed'])
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $mission['icon'] }}"></path></svg>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="flex-grow">
                        <h3 class="text-lg font-black {{ $mission['completed'] ? 'text-slate-900' : 'text-slate-800' }} group-hover:text-indigo-600 transition-colors">
                            {{ $mission['title'] }}
                        </h3>
                        <p class="text-slate-400 font-medium text-sm mt-0.5 tracking-tight leading-relaxed">
                            {{ $mission['desc'] }}
                        </p>
                    </div>

                    <!-- Action -->
                    <div class="flex-shrink-0">
                        @if($mission['completed'])
                            <div class="flex items-center gap-2 text-emerald-500 font-black text-[10px] tracking-widest leading-none bg-emerald-50 py-2 px-3 rounded-full border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
                                OK
                            </div>
                        @else
                            <a href="{{ route($mission['route'], $mission['params'] ?? []) }}{{ isset($mission['anchor']) ? '#' . $mission['anchor'] : '' }}" class="w-10 h-10 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-indigo-600 hover:text-white hover:border-indigo-500 transition-all active:scale-90 group/btn">
                                <svg class="w-5 h-5 group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Floating Support Section -->
        <div class="mt-32 text-center animate-slide-up [animation-delay:1500ms]">
            <div class="inline-flex flex-col md:flex-row items-center gap-8 bg-slate-50 border border-slate-100 p-8 rounded-[40px] relative overflow-hidden">
                <p class="text-slate-500 font-bold text-sm tracking-tight">
                    Bir sorun mu var? <a href="mailto:destek@rezervist.com" class="text-indigo-600 hover:underline">Sizinle konuşmaya hazırız.</a>
                </p>
                <div class="flex items-center gap-3">
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-white grayscale" src="https://ui-avatars.com/api/?name=Support+1&background=random" alt="Support">
                        <img class="w-10 h-10 rounded-full border-2 border-white" src="https://ui-avatars.com/api/?name=Support+2&background=random" alt="Support">
                    </div>
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Destek Ekibi Çevrimiçi</span>
                </div>
            </div>
        </div>

        <!-- Back to Dashboard -->
        <div class="mt-16 text-center animate-slide-up [animation-delay:1700ms]">
             <a href="{{ route('vendor.dashboard') }}" class="text-slate-400 font-bold text-xs hover:text-indigo-600 transition-all uppercase tracking-[0.2em] leading-none">
                 &larr; Yönetim Paneline Dön
             </a>
        </div>

    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap');
    
    .font-outfit {
        font-family: 'Outfit', sans-serif;
    }

    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slide-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fade-in 1s ease-out forwards;
    }

    .animate-slide-up {
        opacity: 0;
        animation: slide-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>
@endsection


