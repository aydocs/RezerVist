@extends('layouts.app')

@section('content')
<div class="bg-[#F1F5F9] min-h-screen pb-20 font-inter pt-32">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Welcome Section -->
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight mb-3">Hoş Geldiniz, {{ $business->name }}!</h1>
                <p class="text-slate-500 font-medium text-lg">İşletmenizi tam kapasiteyle kullanmaya başlamak için aşağıdaki görevleri tamamlayın.</p>
            </div>
            <div class="bg-white px-6 py-4 rounded-3xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="relative w-16 h-16">
                    <svg class="w-full h-full transform -rotate-90">
                        <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="6" fill="transparent" class="text-slate-100" />
                        <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="6" fill="transparent" class="text-indigo-600" 
                                stroke-dasharray="175.9" 
                                stroke-dashoffset="{{ 175.9 - (175.9 * $onboarding['percent'] / 100) }}" 
                                stroke-linecap="round" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center text-sm font-bold text-slate-800">
                        %{{ $onboarding['percent'] }}
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-0.5">TOPLAM İLERLEME</p>
                    <p class="text-sm font-bold text-slate-700">{{ $onboarding['completed_count'] }}/{{ $onboarding['total_count'] }} Görev Tamamlandı</p>
                </div>
            </div>
        </div>

        <!-- Mission List -->
        <div class="space-y-6">
            @php
                $missions = [
                    [
                        'id' => 'profile',
                        'title' => 'İşletme Profilini Tamamla',
                        'desc' => 'Logo, açıklama ve adres bilgilerini girerek müşterilerine kendini tanıt.',
                        'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        'route' => 'vendor.business.edit',
                        'completed' => $onboarding['steps']['profile']
                    ],
                    [
                        'id' => 'hours',
                        'title' => 'Çalışma Saatlerini Belirle',
                        'desc' => 'Hangi günlerde ve hangi saatlerde açık olduğunu sisteme tanımla.',
                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'route' => 'vendor.business.hours.edit',
                        'completed' => $onboarding['steps']['hours']
                    ],
                    [
                        'id' => 'reservation_settings',
                        'title' => 'Rezervasyon Kurallarını Koy',
                        'desc' => 'Rezervasyon başlangıç/bitiş saatlerini ve masa periyotlarını ayarla.',
                        'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                        'route' => 'vendor.business.edit',
                        'anchor' => 'reservation-settings',
                        'completed' => $onboarding['steps']['reservation_settings']
                    ],
                    [
                        'id' => 'resources',
                        'title' => 'Masalarını ve Alanlarını Oluştur',
                        'desc' => 'Teras, İç Salon gibi alanlarını ve masalarını ekleyerek rezervasyon altyapını kur.',
                        'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                        'route' => 'vendor.resources.index',
                        'completed' => $onboarding['steps']['resources']
                    ],
                    [
                        'id' => 'menu',
                        'title' => 'Menü ve Ürünlerini Ekle',
                        'desc' => 'Dijital menünü oluştur, ürün fotoğraflarını yükle ve fiyatlarını belirle.',
                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                        'route' => 'vendor.menus.index',
                        'completed' => $onboarding['steps']['menu']
                    ],
                    [
                        'id' => 'payments',
                        'title' => 'Ödeme Almaya Başla (Iyzico)',
                        'desc' => 'Pazaryeri kaydını tamamla ve iyzico ile anında ödeme almaya başla.',
                        'icon' => 'M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z M12 2a10 10 0 100 20 10 10 0 000-20z M12 18V6',
                        'route' => 'vendor.finance.index', 
                        'params' => ['open_iyzico' => '1'],
                        'completed' => $onboarding['steps']['payments']
                    ],
                    [
                        'id' => 'staff',
                        'title' => 'Ekibini Dahil Et',
                        'desc' => 'Personeline hesap aç ve POS sistemini kullanmaya başlasınlar.',
                        'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                        'route' => 'vendor.staff.index',
                        'completed' => $onboarding['steps']['staff']
                    ],
                    [
                        'id' => 'first_sale',
                        'title' => 'İlk Rezervasyonunu Onayla!',
                        'desc' => 'Tebrikler! Sistemin hazır, şimdi ilk müşterini ağırlama vakti.',
                        'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
                        'route' => 'vendor.dashboard',
                        'completed' => $onboarding['steps']['first_sale']
                    ],
                ];
            @endphp

            @foreach($missions as $index => $mission)
            <div class="group relative bg-white rounded-[32px] p-1 shadow-sm border border-slate-200 overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-indigo-500/5 hover:border-indigo-200">
                <div class="flex items-center gap-6 p-6 md:p-8">
                    <!-- Achievement Badge / Index -->
                    <div class="flex-shrink-0 relative">
                        <div class="w-16 h-16 rounded-3xl flex items-center justify-center transition-all duration-500 {{ $mission['completed'] ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600' }}">
                            @if($mission['completed'])
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            @else
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $mission['icon'] }}"></path></svg>
                            @endif
                        </div>
                        @if(!$mission['completed'])
                        <div class="absolute -top-2 -right-2 w-7 h-7 bg-white rounded-full border border-slate-200 flex items-center justify-center text-[10px] font-black text-slate-400">
                            {{ $index + 1 }}
                        </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-grow">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-bold {{ $mission['completed'] ? 'text-slate-900' : 'text-slate-800 group-hover:text-indigo-600 transition-colors' }}">
                                    {{ $mission['title'] }}
                                </h3>
                                <p class="text-slate-500 font-medium text-sm mt-1 max-w-xl">
                                    {{ $mission['desc'] }}
                                </p>
                            </div>
                            
                            @if($mission['completed'])
                                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 font-bold text-xs">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    TAMAMLANDI
                                </div>
                            @else
                                <a href="{{ route($mission['route'], $mission['params'] ?? []) }}{{ isset($mission['anchor']) ? '#' . $mission['anchor'] : '' }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition-all shadow-lg shadow-indigo-200 active:scale-95">
                                    Görevi Başlat
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Subtle background status line -->
                <div class="absolute bottom-0 left-0 h-1 transition-all duration-1000 {{ $mission['completed'] ? 'bg-emerald-500 w-full' : 'bg-slate-100 w-0 group-hover:w-full group-hover:bg-indigo-100' }}"></div>
            </div>
            @endforeach
        </div>

        <!-- Support Section -->
        <div class="mt-16 bg-gradient-to-br from-slate-900 to-indigo-950 rounded-[40px] p-10 md:p-14 relative overflow-hidden text-center md:text-left">
             <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-indigo-500/20 rounded-full blur-[100px] -mr-40 -mt-20"></div>
             
             <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12">
                 <div class="max-w-xl">
                     <h2 class="text-3xl font-bold text-white mb-4">Yardıma mı ihtiyacınız var?</h2>
                     <p class="text-indigo-200/70 text-lg leading-relaxed mb-0">
                         Kurulum sürecinde herhangi bir adımda takılırsanız, uzman destek ekibimiz size rehberlik etmek için hazır. Hemen bize ulaşın.
                     </p>
                 </div>
                 <div class="flex-shrink-0 flex flex-col sm:flex-row gap-4">
                     <a href="mailto:destek@rezervist.com" class="px-8 py-4 rounded-3xl bg-white/10 hover:bg-white/20 text-white font-bold border border-white/10 transition-all text-center">
                        E-posta Gönder
                     </a>
                     <a href="#" class="px-8 py-4 rounded-3xl bg-indigo-500 hover:bg-indigo-400 text-white font-bold transition-all shadow-xl shadow-indigo-500/20 text-center">
                        Canlı Destek
                     </a>
                 </div>
             </div>
        </div>

        <!-- Footer Footer -->
        <div class="mt-12 text-center">
             <a href="{{ route('vendor.dashboard') }}" class="inline-flex items-center gap-2 text-slate-500 font-bold hover:text-indigo-600 transition-colors">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                 Yönetim Paneline Dön
             </a>
        </div>

    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
    
    .font-inter {
        font-family: 'Inter', sans-serif;
    }

    [stroke-dasharray] {
        transition: stroke-dashoffset 1s ease-out;
    }
</style>
@endsection

