@extends('layouts.app')

@section('title', 'Abonelik Yönetimi - RezerVist')

@section('content')
<div class="min-h-screen bg-[#FDFDFF] py-16 px-4 sm:px-6 lg:px-8 font-['Outfit'] relative overflow-hidden">
    <!-- Dynamic Mesh Background Blobs -->
    <div class="fixed top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary/5 rounded-full blur-[120px] pointer-events-none animate-pulse"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-500/5 rounded-full blur-[120px] pointer-events-none animate-pulse" style="animation-delay: 2s;"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <!-- Header Section -->
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-16">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-primary/5 rounded-full mb-4 border border-primary/10">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-ping"></span>
                    <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">Finans Kontrol Paneli</span>
                </div>
                <h1 class="text-5xl font-[1000] text-gray-900 tracking-[-0.04em] mb-3">Abonelik <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-600">Yönetimi</span></h1>
                <p class="text-gray-500 font-semibold text-lg max-w-xl leading-relaxed opacity-80">İşletmenizin dijital gücünü ölçeklendirin ve ödeme trafiğinizi anlık takip edin.</p>
            </div>
            
            <div class="flex items-center gap-5 bg-white/60 backdrop-blur-xl p-3 pr-8 rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/80 group transition-all hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:-translate-y-1">
                <div class="w-14 h-14 rounded-full {{ $subscription ? 'bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-emerald-200' : 'bg-gradient-to-br from-rose-400 to-rose-600 shadow-rose-200' }} flex items-center justify-center text-white shadow-lg shrink-0 transition-transform group-hover:scale-110">
                    <i class="fa-solid {{ $subscription ? 'fa-check-double' : 'fa-power-off' }} text-xl"></i>
                </div>
                <div class="space-y-0.5">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Sistem Durumu</span>
                    </div>
                    <span class="text-base font-black text-gray-900 tracking-tight">{{ $subscription ? $subscription->package->name : 'Erişim Kısıtlı' }}</span>
                </div>
            </div>
        </header>

        <!-- Current Subscription & Quick Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
            <div class="lg:col-span-2 relative">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl"></div>
                
                <div class="bg-white border border-gray-100 rounded-[3rem] p-10 shadow-xl shadow-gray-200/40 relative overflow-hidden">
                    <!-- Decor -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 -mr-20 -mt-20 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-10">
                            <div>
                                <h2 class="text-[11px] font-black text-primary uppercase tracking-[0.25em] mb-4">MEVCUT ABONELİK</h2>
                                @if($subscription)
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-6xl font-[900] text-gray-900 tracking-tighter">₺{{ number_format($subscription->package->price_monthly, 0, ',', '.') }}</span>
                                        <span class="text-gray-400 font-bold text-lg">/ aylık</span>
                                    </div>
                                @endif
                            </div>
                            
                            @if($subscription)
                                <div class="px-8 py-3 bg-primary text-white rounded-[1.5rem] font-black text-xs uppercase tracking-widest shadow-lg shadow-primary/20 border border-white/20">
                                    {{ $subscription->package->name }}
                                </div>
                            @endif
                        </div>

                        @if($subscription)
                            @php
                                $totalDays = $subscription->starts_at->diffInDays($subscription->ends_at);
                                $passedDays = $subscription->starts_at->diffInDays(now());
                                $remainingDays = (int) now()->diffInDays($subscription->ends_at, false);
                                $percent = $totalDays > 0 ? min(100, max(0, ($passedDays / $totalDays) * 100)) : 0;
                                $isExpiring = $remainingDays < 7;
                            @endphp

                            @if($subscription->package->slug != 'free')
                                <div class="space-y-6 bg-gray-50/50 p-8 rounded-[2.5rem] border border-gray-100">
                                    <div class="flex justify-between items-end">
                                        <span class="text-sm font-bold text-gray-600">Plan Kullanım Süresi</span>
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-clock {{ $isExpiring ? 'text-rose-500' : 'text-primary' }}"></i>
                                            <span class="text-sm font-black {{ $isExpiring ? 'text-rose-500' : 'text-gray-900' }}">
                                                {{ $remainingDays > 0 ? $remainingDays . ' gün kaldı' : 'Abonelik doldu' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="w-full h-4 bg-gray-200 rounded-full overflow-hidden flex shadow-inner p-1">
                                        <div class="h-full rounded-full {{ $isExpiring ? 'bg-rose-500 shadow-[0_0_15px_rgba(244,63,94,0.4)]' : 'bg-primary shadow-[0_0_15px_rgba(98,0,238,0.3)]' }} transition-all duration-1000 ease-out" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-[11px] font-black text-gray-400 uppercase tracking-widest px-2">
                                        <span class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div> {{ $subscription->starts_at->translatedFormat('d F Y') }}</span>
                                        <span class="flex items-center gap-2">{{ $subscription->ends_at->translatedFormat('d F Y') }} <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div></span>
                                    </div>
                                </div>
                            @else
                                <div class="bg-emerald-50 rounded-[2.5rem] p-8 border border-emerald-100/50 flex flex-col sm:flex-row items-center gap-6">
                                    <div class="w-16 h-16 rounded-2xl bg-emerald-500 flex items-center justify-center text-white shadow-xl shadow-emerald-200">
                                        <i class="fa-solid fa-infinity text-2xl"></i>
                                    </div>
                                    <div class="text-center sm:text-left">
                                        <h4 class="font-[900] text-emerald-900 text-lg mb-1 italic">Süresiz Başlangıç Planı</h4>
                                        <p class="text-emerald-700/80 text-sm font-bold leading-relaxed max-w-sm text-balance">Mevcut planınızın herhangi bir zaman kısıtlaması yoktur. İstediğiniz zaman üst paketlere geçiş yapabilirsiniz.</p>
                                    </div>
                                    <div class="sm:ml-auto">
                                        <div class="px-6 py-2 bg-white text-emerald-600 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-sm">KOMİSYONLU SÜRÜM</div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="py-16 text-center bg-gray-50 rounded-[3rem] border-2 border-dashed border-gray-200">
                                <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                                    <i class="fa-solid fa-box-open text-gray-200 text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-black text-gray-900 mb-2">Aktif Bir Abonelik Yok</h3>
                                <p class="text-gray-400 font-bold max-w-xs mx-auto mb-8">Hizmetlerimize erişmek için lütfen aşağıdan size en uygun paketi seçin.</p>
                                <a href="#pricing-grid" class="inline-flex items-center gap-2 px-8 py-4 bg-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-105 transition-all">
                                    PAKETLERİ GÖR
                                    <i class="fa-solid fa-arrow-down"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Side Card: Help & Support -->
            <div class="bg-primary rounded-[3rem] p-10 text-white shadow-2xl shadow-primary/30 flex flex-col relative overflow-hidden">
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                
                <div class="relative z-10 flex flex-col h-full">
                    <div class="w-16 h-16 bg-white/10 rounded-[1.5rem] flex items-center justify-center text-white mb-8 border border-white/10 shadow-lg backdrop-blur-md">
                        <i class="fa-solid fa-headset text-2xl"></i>
                    </div>
                    
                    <h3 class="text-3xl font-[900] tracking-tight mb-4 leading-tight">Desteğe mi İhtiyacınız Var?</h3>
                    <p class="text-primary-50 text-base font-medium leading-relaxed mb-10 text-balance opacity-80">
                        Ödeme işlemleri, kurumsal entegrasyonlar veya kapasite artırımı hakkında her zaman yanınızdayız.
                    </p>
                    
                    <div class="mt-auto space-y-4">
                        <a href="https://wa.me/{{ $globalSettings['whatsapp_number'] ?? '' }}" target="_blank" class="flex items-center justify-center gap-3 w-full py-5 bg-white text-primary rounded-[1.5rem] font-black text-sm hover:bg-gray-50 transition-all shadow-xl shadow-black/10">
                            <i class="fa-brands fa-whatsapp text-xl"></i>
                            Anlık Destek Alın
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <!-- Pricing Grid -->
        <div id="pricing-grid" class="mb-24" 
             x-data="{ 
                globalSelected: 1,
                options: [
                    { value: 1, label: '1 AY (Standart)' },
                    { value: 3, label: '3 AY (%5 İNDİRİMLİ)' },
                    { value: 6, label: '6 AY (%10 İNDİRİMLİ)' },
                    { value: 12, label: '1 YIL (%20 İNDİRİMLİ)' }
                ],
                formatMoney(amount) {
                    return new Intl.NumberFormat('tr-TR').format(amount);
                },
                getTimeLabel(val) {
                    if (val == 1) return '/ay';
                    if (val == 3) return '/3 ay';
                    if (val == 6) return '/6 ay';
                    if (val == 12) return '/yıl';
                    return '/ay';
                },
                getGlobalLabel(val) {
                    return this.options.find(o => o.value == val).label;
                }
             }">
            <div class="text-center mb-16 relative">
                <div class="absolute left-1/2 -top-12 -translate-x-1/2 w-48 h-48 bg-primary/5 rounded-full blur-3xl"></div>
                <h2 class="text-6xl font-[1000] text-gray-900 mb-6 tracking-[-0.05em] italic">Dünyanın En Güçlü <span class="text-primary italic">Adisyon Ekosistemi</span></h2>
                <p class="text-gray-400 font-bold text-lg max-w-2xl mx-auto mb-10 opacity-80 leading-relaxed">İşletmenizin hacmi ne olursa olsun, sizin için tasarlanmış bir çözümümüz var. Şimdi paketinizi seçin ve geleceği başlatın.</p>
                <div class="inline-flex p-1.5 bg-gray-100/80 backdrop-blur-md rounded-3xl border border-white/50 shadow-inner">
                    <template x-for="option in options" :key="option.value">
                        <button @click="globalSelected = option.value" 
                                class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                                :class="globalSelected == option.value ? 'bg-white shadow text-primary' : 'text-gray-400 hover:text-gray-600'">
                            <span x-text="option.label.split(' ')[0] + ' ' + option.label.split(' ')[1]"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($packages as $package)
                <div class="group relative bg-white border border-gray-100 rounded-[3.5rem] p-10 shadow-[0_10px_40px_rgba(0,0,0,0.03)] hover:shadow-[0_40px_80px_rgba(98,0,238,0.12)] hover:-translate-y-4 transition-all duration-700 flex flex-col {{ $package->slug == 'enterprise' ? 'ring-2 ring-primary shadow-primary/10' : '' }}"
                     x-data="{ 
                        monthlyPrice: {{ $package->price_monthly }},
                        open: false,
                        get calculatedPrice() {
                            let total = this.monthlyPrice * globalSelected;
                            let discount = 0;
                            if (globalSelected == 3) discount = 0.05;
                            if (globalSelected == 6) discount = 0.10;
                            if (globalSelected == 12) discount = 0.20;
                            return Math.floor(total * (1 - discount));
                        }
                     }">
                    
                    @if($package->slug == 'enterprise')
                        <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-gradient-to-r from-primary to-purple-600 text-white px-10 py-2.5 rounded-full text-[10px] font-black uppercase tracking-[0.25em] shadow-[0_10px_30px_rgba(98,0,238,0.4)] animate-bounce hover:scale-110 transition-transform">
                            EN POPÜLER SEÇİM
                        </div>
                    @endif

                    <div class="mb-10 text-center">
                        <h3 class="text-sm font-black text-gray-400 uppercase tracking-[0.2em] mb-4">{{ $package->name }}</h3>
                        <div class="flex items-center justify-center gap-1 min-h-[60px]">
                            @if($package->price_monthly == 0)
                                <span class="text-4xl font-[900] text-gray-900 tracking-tighter italic">Ücretsiz</span>
                            @else
                                <span class="text-gray-400 font-bold text-xl mr-1 italic">₺</span>
                                <span class="text-5xl font-[900] text-gray-900 tracking-tighter" x-text="formatMoney(calculatedPrice)">{{ number_format($package->price_monthly, 0, ',', '.') }}</span>
                                <span class="text-gray-400 font-bold text-sm ml-1 italic" x-text="getTimeLabel(globalSelected)">/ay</span>
                            @endif
                        </div>
                        <p class="text-gray-400 text-xs mt-4 font-bold leading-relaxed px-4">{{ $package->features['description'] ?? '' }}</p>
                    </div>

                    <!-- Features List -->
                    <div class="space-y-4 mb-10 flex-grow border-t border-gray-50 pt-10">
                        @if(isset($package->features['display_features']) && is_array($package->features['display_features']))
                            @foreach($package->features['display_features'] as $feature)
                            <div class="flex items-start gap-4 group/feature hover:translate-x-2 transition-all duration-300">
                                <div class="w-6 h-6 rounded-lg bg-primary/5 text-primary flex items-center justify-center flex-shrink-0 mt-0.5 border border-primary/10 group-hover/feature:bg-primary group-hover/feature:text-white transition-colors">
                                    <i class="fa-solid fa-check text-[10px] sm:text-xs"></i>
                                </div>
                                <span class="text-sm font-bold text-gray-600 leading-tight">{{ $feature }}</span>
                            </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="mt-auto space-y-6 pt-8 border-t border-gray-50/50">
                        <form action="{{ route('vendor.billing.upgrade', $package->id) }}" method="POST" class="space-y-6">
                            @csrf
                            @if($package->price_monthly > 0)
                            <div class="relative">
                                <input type="hidden" name="months" :value="globalSelected">
                                <button type="button" @click="open = !open" 
                                        class="w-full flex items-center justify-between px-6 py-4 bg-gray-50 border-2 border-transparent rounded-[1.5rem] text-xs font-black text-gray-900 hover:border-primary/20 transition-all focus:outline-none focus:ring-4 focus:ring-primary/5 group"
                                        :class="open ? 'border-primary ring-4 ring-primary/5' : ''">
                                    <span x-text="getGlobalLabel(globalSelected)"></span>
                                    <i class="fa-solid fa-chevron-down text-gray-300 group-hover:text-primary transition-all" :class="open ? 'rotate-180 text-primary' : ''"></i>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="absolute z-[60] bottom-full left-0 right-0 mb-4 bg-white border border-gray-100 rounded-[2rem] shadow-2xl overflow-hidden py-3 p-2">
                                    <template x-for="option in options" :key="option.value">
                                        <div @click="globalSelected = option.value; open = false" 
                                             class="px-6 py-3 text-xs font-black cursor-pointer rounded-2xl transition-all"
                                             :class="globalSelected == option.value ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-500 hover:bg-gray-50'">
                                            <span x-text="option.label"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            @else
                                <input type="hidden" name="months" value="120">
                            @endif

                            <button 
                                @if($subscription && $package->price_monthly < $subscription->package->price_monthly)
                                    type="button"
                                    onclick="confirmDowngrade()"
                                @else
                                    type="submit"
                                @endif
                                    class="w-full py-5 rounded-[1.5rem] font-black text-[11px] uppercase tracking-[0.2em] transition-all shadow-xl shadow-gray-200/50 active:scale-95 flex items-center justify-center gap-3
                                    {{ ($subscription && $subscription->package_id == $package->id) ? 'bg-emerald-50 text-emerald-600 cursor-default' : (($subscription && $package->price_monthly < $subscription->package->price_monthly) ? 'bg-gray-100 text-gray-400 hover:bg-gray-200' : 'bg-primary text-white hover:bg-primary-dark hover:shadow-primary/30 hover:scale-[1.02]') }}" 
                                    {{ ($subscription && $subscription->package_id == $package->id) ? 'disabled' : '' }}>
                                @if($subscription && $subscription->package_id == $package->id)
                                    <i class="fa-solid fa-check"></i>
                                    AKTİF PAKETİNİZ
                                @elseif($subscription && $package->price_monthly < $subscription->package->price_monthly)
                                    <i class="fa-solid fa-ban"></i>
                                    PAKET DÜŞÜRME
                                @else
                                    SİSTEMİ BAŞLAT
                                    <i class="fa-solid fa-arrow-right"></i>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <script>
            function confirmDowngrade() {
                Swal.fire({
                    title: 'Paket Değişikliği Bildirimi',
                    text: "Mevcut paketinizden daha düşük bir pakete geçiş yapmak için lütfen müşteri hizmetlerimizle iletişime geçiniz. İade ve iptal işlemleri manuel olarak yetkili ekibimiz tarafından gerçekleştirilmektedir.",
                    icon: 'info',
                    confirmButtonText: 'Tamam',
                    confirmButtonColor: '#6200EE',
                    customClass: {
                        popup: 'rounded-[2rem] p-6',
                        confirmButton: 'rounded-xl px-6 py-3 font-bold'
                    }
                });
            }
        </script>

        <!-- Masterpiece Comparison Matrix -->
        <div class="mb-32">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/5 rounded-full mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                    <span class="text-[10px] font-black text-primary uppercase tracking-widest">Kapsamlı Kıyaslama</span>
                </div>
                <h2 class="text-4xl font-[900] text-gray-900 tracking-tighter mb-4 italic">Detaylı Paket Matrisi</h2>
                <p class="text-gray-400 font-bold text-base max-w-lg mx-auto leading-relaxed">İşletmenizin ihtiyaçlarına en uygun teknolojik altyapıyı tüm detaylarıyla keşfedin.</p>
            </div>

            <div class="bg-white/80 backdrop-blur-3xl border border-white/50 rounded-[4rem] shadow-[0_50px_100px_rgba(0,0,0,0.06)] overflow-hidden relative">
                <!-- Decorative Blur -->
                <div class="absolute -top-32 -right-32 w-96 h-96 bg-primary/5 rounded-full blur-[120px]"></div>
                <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-purple-500/5 rounded-full blur-[120px]"></div>

                <div class="overflow-x-auto relative z-10">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="p-10 text-left min-w-[320px] bg-gray-50/30">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-primary border border-gray-100">
                                            <i class="fa-solid fa-layer-group"></i>
                                        </div>
                                        <span class="text-sm font-[900] text-gray-900 uppercase tracking-widest">Özellikler</span>
                                    </div>
                                </th>
                                <th class="p-10 text-center min-w-[180px]">
                                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Başlangıç</div>
                                    <div class="text-lg font-[900] text-gray-900 italic">Starter</div>
                                </th>
                                <th class="p-10 text-center min-w-[180px] bg-primary/5 border-x border-primary/10">
                                    <div class="inline-block px-3 py-1 bg-primary text-white text-[9px] font-black rounded-full mb-2 tracking-widest">POPÜLER</div>
                                    <div class="text-lg font-[900] text-primary italic">Pro Suite</div>
                                </th>
                                <th class="p-10 text-center min-w-[180px]">
                                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Mobil Odaklı</div>
                                    <div class="text-lg font-[900] text-gray-900 italic">Mobile+</div>
                                </th>
                                <th class="p-10 text-center min-w-[180px] bg-gray-900 text-white rounded-tr-[3rem]">
                                    <div class="text-[10px] font-black text-primary-200 uppercase tracking-widest mb-2">Kurumsal Güç</div>
                                    <div class="text-lg font-[900] text-white italic">Ultimate</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <!-- Group: Core -->
                            <tr>
                                <td colspan="5" class="px-10 py-5 bg-gray-50/50 text-[10px] font-black text-primary uppercase tracking-[0.25em]">01 — Temel Platform Özellikleri</td>
                            </tr>
                            <tr class="group hover:bg-primary/[0.02] transition-colors">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                                            <i class="fa-solid fa-globe text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">RezerVist.com Panel Erişimi</div>
                                            <div class="text-[10px] text-gray-400 font-medium">Global ağ üzerinden rezervasyon alımı</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center"><i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i></td>
                                <td class="px-10 py-8 text-center bg-primary/5 border-x border-primary/10"><i class="fa-solid fa-circle-check text-primary text-xl"></i></td>
                                <td class="px-10 py-8 text-center"><i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i></td>
                                <td class="px-10 py-8 text-center bg-gray-900/95"><i class="fa-solid fa-circle-check text-primary text-xl opacity-70"></i></td>
                            </tr>
                            <tr class="group hover:bg-primary/[0.02] transition-colors">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                                            <i class="fa-solid fa-users text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">Sınırsız Personel Yetkilendirme</div>
                                            <div class="text-[10px] text-gray-400 font-medium">Sınırsız çalışan hesabı ve yetki kontrolü</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center text-gray-400 text-xs font-bold">Max. 5</td>
                                <td class="px-10 py-8 text-center bg-primary/5 border-x border-primary/10"><i class="fa-solid fa-infinity text-primary text-xl"></i></td>
                                <td class="px-10 py-8 text-center"><i class="fa-solid fa-infinity text-emerald-500 text-lg"></i></td>
                                <td class="px-10 py-8 text-center bg-gray-900/95"><i class="fa-solid fa-infinity text-primary text-xl opacity-70"></i></td>
                            </tr>
                            <tr class="group hover:bg-primary/[0.02] transition-colors">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                                            <i class="fa-solid fa-calendar-check text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">Dijital Rezervasyon Defteri</div>
                                            <div class="text-[10px] text-gray-400 font-medium">Bireysel ve toplu rezervasyon yönetimi</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center"><i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i></td>
                                <td class="px-10 py-8 text-center bg-primary/5 border-x border-primary/10"><i class="fa-solid fa-circle-check text-primary text-xl"></i></td>
                                <td class="px-10 py-8 text-center"><i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i></td>
                                <td class="px-10 py-8 text-center bg-gray-900/95"><i class="fa-solid fa-circle-check text-primary text-xl opacity-70"></i></td>
                            </tr>

                            <!-- Group: POS -->
                            <tr>
                                <td colspan="5" class="px-10 py-5 bg-gray-50/50 text-[10px] font-black text-primary uppercase tracking-[0.25em]">02 — Servis & Adisyon Operasyonu</td>
                            </tr>
                            <tr class="group hover:bg-primary/[0.02] transition-colors">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                                            <i class="fa-solid fa-cash-register text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">Gelişmiş Adisyon & POS</div>
                                            <div class="text-[10px] text-gray-400 font-medium">Tam entegre sipariş ve ödeme altyapısı</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center text-gray-300">—</td>
                                <td class="px-10 py-8 text-center bg-primary/5 border-x border-primary/10"><i class="fa-solid fa-circle-check text-primary text-xl shadow-lg"></i></td>
                                <td class="px-10 py-8 text-center"><i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i></td>
                                <td class="px-10 py-8 text-center bg-gray-900/95"><i class="fa-solid fa-circle-check text-primary text-xl opacity-70"></i></td>
                            </tr>
                            <tr class="group hover:bg-primary/[0.02] transition-colors">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                                            <i class="fa-solid fa-chair text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">Kat & Masa Planı Yönetimi</div>
                                            <div class="text-[10px] text-gray-400 font-medium">Görsel masa yerleşimi ve doluluk takibi</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center text-gray-300">—</td>
                                <td class="px-10 py-8 text-center bg-primary/5 border-x border-primary/10"><i class="fa-solid fa-circle-check text-primary text-xl"></i></td>
                                <td class="px-10 py-8 text-center"><i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i></td>
                                <td class="px-10 py-8 text-center bg-gray-900/95"><i class="fa-solid fa-circle-check text-primary text-xl opacity-70"></i></td>
                            </tr>

                            <!-- Group: Mobile -->
                            <tr>
                                <td colspan="5" class="px-10 py-5 bg-gray-50/50 text-[10px] font-black text-primary uppercase tracking-[0.25em]">03 — Mobil & Müşteri Deneyimi</td>
                            </tr>
                            <tr class="group hover:bg-primary/[0.02] transition-colors">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                                            <i class="fa-solid fa-mobile-screen-button text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">Markanıza Özel Müşteri App</div>
                                            <div class="text-[10px] text-gray-400 font-medium">iOS & Android uygulama yayınlama</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center text-gray-300">—</td>
                                <td class="px-10 py-8 text-center bg-primary/5 border-x border-primary/10 text-gray-400">—</td>
                                <td class="px-10 py-8 text-center"><i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i></td>
                                <td class="px-10 py-8 text-center bg-gray-900/95"><i class="fa-solid fa-circle-check text-primary text-xl opacity-70"></i></td>
                            </tr>
                            <tr class="group hover:bg-primary/[0.02] transition-colors">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                                            <i class="fa-solid fa-bolt text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">Anlık Push Bildirimleri</div>
                                            <div class="text-[10px] text-gray-400 font-medium">Sınırsız kampanya ve duyuru iletme</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center text-gray-300">—</td>
                                <td class="px-10 py-8 text-center bg-primary/5 border-x border-primary/10 text-gray-400">—</td>
                                <td class="px-10 py-8 text-center"><i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i></td>
                                <td class="px-10 py-8 text-center bg-gray-900/95"><i class="fa-solid fa-circle-check text-primary text-xl opacity-70"></i></td>
                            </tr>

                            <!-- Group: ERP -->
                            <tr>
                                <td colspan="5" class="px-10 py-5 bg-gray-50/50 text-[10px] font-black text-primary uppercase tracking-[0.25em]">04 — Kurumsal Finans & ERP</td>
                            </tr>
                            <tr class="group hover:bg-primary/[0.02] transition-colors">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                                            <i class="fa-solid fa-file-invoice text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">Finans & Ön Muhasebe</div>
                                            <div class="text-[10px] text-gray-400 font-medium">Cari takip ve gelir-gider analizi</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center text-gray-300">—</td>
                                <td class="px-10 py-8 text-center bg-primary/5 border-x border-primary/10 text-gray-400">—</td>
                                <td class="px-10 py-8 text-center text-gray-300">—</td>
                                <td class="px-10 py-8 text-center bg-gray-900/95"><i class="fa-solid fa-circle-check text-primary text-xl shadow-[0_0_15px_rgba(98,0,238,0.5)]"></i></td>
                            </tr>
                            <tr class="group hover:bg-primary/[0.02] transition-colors">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                                            <i class="fa-solid fa-sitemap text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">Multi-Branch (Çoklu Şube)</div>
                                            <div class="text-[10px] text-gray-400 font-medium">Panel üzerinden franchise yönetimi</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center text-gray-300">—</td>
                                <td class="px-10 py-8 text-center bg-primary/5 border-x border-primary/10 text-gray-400">—</td>
                                <td class="px-10 py-8 text-center text-gray-300">—</td>
                                <td class="px-10 py-8 text-center bg-gray-900/95"><i class="fa-solid fa-circle-check text-primary text-xl opacity-70"></i></td>
                            </tr>
                            <tr class="group hover:bg-primary/[0.02] transition-colors">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                                            <i class="fa-solid fa-fingerprint text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">White Label (Beyaz Etiket)</div>
                                            <div class="text-[10px] text-gray-400 font-medium">RezerVist logosunu gizleme opsiyonu</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center text-gray-300">—</td>
                                <td class="px-10 py-8 text-center bg-primary/5 border-x border-primary/10 text-gray-400">—</td>
                                <td class="px-10 py-8 text-center text-gray-300">—</td>
                                <td class="px-10 py-8 text-center bg-gray-900/95 rounded-br-[3rem]"><i class="fa-solid fa-circle-check text-primary text-xl opacity-70"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Ödeme Geçmişi (Tablo) -->
        <div class="bg-white/70 backdrop-blur-2xl border border-white/60 rounded-[3.5rem] p-12 shadow-[0_20px_50px_rgba(0,0,0,0.03)] overflow-hidden mb-24 relative">
            <div class="flex justify-between items-center mb-12 border-b border-gray-100 pb-10">
                <div>
                    <h2 class="text-3xl font-[1000] text-gray-900 tracking-tighter mb-2">Ödeme <span class="text-primary italic">Geçmişi</span></h2>
                    <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.3em]">Muhasebe Kayıtları — {{ $invoices->count() }} İŞLEM</p>
                </div>
                <div class="w-16 h-16 bg-primary/10 rounded-3xl flex items-center justify-center text-primary shadow-inner">
                    <i class="fa-solid fa-file-invoice-dollar text-2xl"></i>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                            <th class="px-6 py-4">Fatura No</th>
                            <th class="px-6 py-4">Tarih</th>
                            <th class="px-6 py-4">Tutar</th>
                            <th class="px-6 py-4">Yöntem</th>
                            <th class="px-6 py-4">Durum</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($invoices as $invoice)
                        <tr class="group hover:bg-gray-50/50 transition-all duration-300">
                            <td class="px-6 py-6 font-black text-gray-900 text-sm italic">{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-6 text-gray-500 font-bold text-sm">{{ $invoice->created_at->translatedFormat('d.m.Y H:i') }}</td>
                            <td class="px-6 py-6 font-[900] text-gray-900 text-sm tracking-tighter italic">₺{{ number_format($invoice->amount, 2, ',', '.') }}</td>
                            <td class="px-6 py-6">
                                <span class="px-4 py-1.5 bg-gray-100 text-gray-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-gray-200">
                                    {{ strtoupper($invoice->payment_method) }}
                                </span>
                            </td>
                            <td class="px-6 py-6">
                                <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                                    ÖDENDİ
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="bg-gray-50/50 rounded-[2.5rem] p-12 border-2 border-dashed border-gray-100">
                                    <i class="fa-solid fa-magnifying-glass text-gray-100 text-5xl mb-6"></i>
                                    <p class="text-gray-400 font-bold italic">Henüz bir ödeme kaydı bulunamadı.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    /* Elite UI Kit Improvements */
    .bg-primary-dark { background-color: #4B00B5; }
    [x-cloak] { display: none !important; }
    button:focus { outline: none !important; }
    .transition-all { transition-duration: 500ms !important; }

    /* Custom Scrollbar for matrix */
    .overflow-x-auto::-webkit-scrollbar { height: 6px; }
    .overflow-x-auto::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #6200EE33; border-radius: 10px; }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #6200EE; }

    /* Floating Animation */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    .animate-float { animation: float 6s ease-in-out infinite; }

    /* Glossy Effect */
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
</style>
@endsection
