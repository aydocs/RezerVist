@extends('layouts.app')

@section('title', 'Abonelik Yönetimi - RezerVist')

@section('content')
<div class="min-h-screen bg-[#FDFDFF] py-20 px-4 sm:px-6 lg:px-8 font-['Outfit'] relative overflow-hidden">
    <!-- Subtle Background Decorations -->
    <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-primary/5 to-transparent pointer-events-none"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary/10 rounded-full blur-3xl opacity-50"></div>
    <div class="absolute top-1/2 -left-24 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl opacity-50"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <!-- Header Section -->
        <header class="text-center mb-20 space-y-6" data-aos="fade-down">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white rounded-full border border-slate-200 shadow-sm mb-4">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-2">Abonelik Merkezi</span>
            </div>
            <h1 class="text-5xl md:text-6xl font-[1000] text-slate-900 tracking-[-0.04em] leading-tight">
                İşletmenizi <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-600 italic">Ölçeklendirin</span>
            </h1>
            <p class="text-slate-500 font-medium text-lg max-w-2xl mx-auto leading-relaxed">
                İhtiyacınıza en uygun paketi seçerek dijital dönüşümünüzü başlatın ve ödeme trafiğinizi anlık takip edin.
            </p>
        </header>

        <!-- Current Plan Banner -->
        @if($subscription && $subscription->package)
        <div class="max-w-4xl mx-auto mb-20 relative" data-aos="fade-up">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-purple-500/10 rounded-[2.5rem] transform rotate-1 scale-105 opacity-50 blur-lg"></div>
            <div class="relative bg-white border border-slate-200/60 rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-slate-200/50 flex flex-col md:flex-row items-center justify-between gap-8 backdrop-blur-xl">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-gradient-to-br from-primary to-purple-600 flex items-center justify-center shadow-lg shadow-primary/30 flex-shrink-0">
                        <i class="fa-solid fa-crown text-2xl md:text-3xl text-white"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-[10px] md:text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Kullanılan Paket</span>
                            @if($subscription->status === 'trial' || $subscription->package->slug === 'free')
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[9px] font-black tracking-widest border border-amber-100 flex items-center gap-1.5">
                                    <span class="relative flex h-1.5 w-1.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-amber-500"></span>
                                    </span>
                                    DENEME SÜRÜMÜ
                                </span>
                            @else
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-black tracking-widest border border-emerald-100 flex items-center gap-1.5">
                                    <span class="relative flex h-1.5 w-1.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                                    </span>
                                    AKTİF
                                </span>
                            @endif
                        </div>
                        <h2 class="text-3xl md:text-4xl font-[1000] text-slate-900 tracking-tight italic">{{ $subscription->package->name }}</h2>
                    </div>
                </div>
                
                <div class="w-px h-16 bg-slate-200 hidden md:block"></div>
                
                <div class="flex flex-col md:flex-row items-center gap-8 w-full md:w-auto mt-4 md:mt-0 pt-6 md:pt-0 border-t border-slate-100 md:border-0">
                    <div class="text-center md:text-left">
                        <div class="text-[10px] md:text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Bitiş / Yenileme</div>
                        <div class="text-lg font-bold text-slate-700">{{ $subscription->ends_at ? $subscription->ends_at->translatedFormat('d F Y') : 'Sınırsız' }}</div>
                        @if($subscription->ends_at)
                            <div class="text-xs font-semibold text-slate-500 mt-1">
                                Kalan Süre: <span class="text-primary font-black">{{ now()->diffInDays($subscription->ends_at, false) > 0 ? now()->diffInDays($subscription->ends_at) . ' gün' : 'Süresi Doldu' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Dynamic Billing Toggle -->
        <div class="flex justify-center mb-20" x-data="{ globalSelected: 1 }" id="billing-container"
             x-init="$watch('globalSelected', value => window.dispatchEvent(new CustomEvent('billing-changed', { detail: value })))">
            <div class="relative p-1.5 bg-slate-100/80 backdrop-blur-md border border-slate-200 rounded-2xl shadow-inner flex items-center">
                <div class="absolute h-[calc(100%-12px)] top-1.5 left-1.5 bg-white shadow-md border border-slate-100 rounded-xl transition-all duration-500 ease-[cubic-bezier(0.23,1,0.32,1)]"
                     :style="globalSelected == 1 ? 'width: 150px; transform: translateX(0)' : 'width: 190px; transform: translateX(150px)'"></div>
                
                <button @click="globalSelected = 1" 
                        class="relative z-10 w-[150px] py-3.5 text-[11px] font-black uppercase tracking-widest transition-colors duration-300"
                        :class="globalSelected == 1 ? 'text-primary' : 'text-slate-500 hover:text-slate-700'">
                    AYLIK ÖDEME
                </button>
                <button @click="globalSelected = 12" 
                        class="relative z-10 w-[190px] py-3.5 text-[11px] font-black uppercase tracking-widest transition-colors duration-300"
                        :class="globalSelected == 12 ? 'text-primary' : 'text-slate-500 hover:text-slate-700'">
                    YILLIK <span class="text-emerald-500 ml-1 opacity-80">(AVANTAJLI)</span>
                </button>
            </div>
        </div>

        <!-- Pricing Grid: 3+2 Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-8 mb-32">
            @php
                $orderedPackages = $packages->sortBy(fn($p) => array_search($p->slug, ['free', 'standard', 'mobile', 'pro', 'enterprise']));
            @endphp
            @foreach($orderedPackages as $package)
            @php
                // Determining the span based on loop index for 3+2 layout
                // 1st, 2nd, 3rd -> col-span-2
                // 4th, 5th -> col-span-3
                $isTopRow = $loop->index < 3;
                $colSpan = $isTopRow ? 'lg:col-span-2 shadow-sm' : 'lg:col-span-3 shadow-md';
            @endphp
            <div class="{{ $colSpan }} group relative flex flex-col p-1 rounded-[3rem] transition-all duration-500 hover:-translate-y-2"
                 x-data="{ 
                    monthlyPrice: {{ $package->price_monthly }},
                    yearlyDiscount: {{ $package->features['yearly_discount'] ?? 0 }},
                    globalSelected: 1,
                    formatMoney(amount) {
                         return new Intl.NumberFormat('tr-TR').format(amount);
                    },
                    get calculatedPrice() {
                        let total = this.monthlyPrice * this.globalSelected;
                        let discount = 0;
                        if (this.globalSelected == 12) discount = this.yearlyDiscount;
                        return Math.floor(total * (1 - discount));
                    }
                 }"
                 @billing-changed.window="globalSelected = $event.detail"
                 data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                
                <!-- Main Content Card -->
                <div class="relative flex-grow bg-white rounded-[2.8rem] p-10 flex flex-col border border-slate-200 shadow-[0_10px_40px_rgba(0,0,0,0.03)] group-hover:shadow-[0_30px_70px_rgba(98,0,238,0.1)] group-hover:border-primary/30 transition-all duration-500 overflow-hidden">
                    
                    @if($package->slug == 'pro' || $package->slug == 'enterprise')
                        <div class="absolute top-0 right-10 {{ $package->slug == 'enterprise' ? 'bg-slate-900' : 'bg-primary' }} text-white px-4 py-2 rounded-b-2xl text-[9px] font-[1000] tracking-widest uppercase">
                            {{ $package->slug == 'enterprise' ? 'KURUMSAL' : 'EN POPÜLER' }}
                        </div>
                    @endif

                    <div class="mb-10">
                        <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 group-hover:text-primary transition-colors">{{ $package->name }}</h3>
                        
                        <div class="flex items-baseline gap-1 min-h-[60px]">
                            @if($package->slug == 'enterprise')
                                <span class="text-3xl font-[1000] text-slate-900 tracking-tight">Teklif Alın</span>
                            @elseif($package->price_monthly == 0)
                                <span class="text-4xl font-[1000] text-slate-900 tracking-tight italic">Ücretsiz</span>
                            @else
                                <span class="text-slate-400 font-bold text-lg mr-0.5">₺</span>
                                <span class="text-5xl font-[1000] text-slate-900 tracking-tighter" x-text="formatMoney(calculatedPrice)">{{ number_format($package->price_monthly, 0, ',', '.') }}</span>
                                <span class="text-slate-400 font-bold text-xs" x-text="globalSelected == 12 ? '/yıl' : '/ay'">/ay</span>
                            @endif
                        </div>
                        
                        <p class="text-slate-500 text-xs mt-6 font-semibold leading-relaxed line-clamp-2">
                            {{ $package->features['description'] ?? '' }}
                        </p>
                    </div>

                    <!-- Features -->
                    <div class="space-y-4 mb-10 flex-grow border-t border-slate-50 pt-8">
                        @if(isset($package->features['display_features']) && is_array($package->features['display_features']))
                            @foreach($package->features['display_features'] as $feature)
                            <div class="flex items-start gap-3 group/item">
                                <i class="fa-solid fa-check-double text-[10px] text-emerald-500 mt-1"></i>
                                <span class="text-[12px] font-bold text-slate-600 group-hover/item:text-slate-900 transition-colors leading-snug">{{ $feature }}</span>
                            </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Action Button -->
                    <div class="mt-auto pt-8 border-t border-slate-100/50">
                        @if($package->slug == 'enterprise')
                            <a href="https://wa.me/{{ $globalSettings['whatsapp_number'] ?? '' }}" target="_blank"
                               class="w-full py-5 rounded-[1.5rem] font-black text-[11px] uppercase tracking-widest transition-all bg-slate-900 text-white hover:bg-black hover:scale-[1.02] active:scale-95 shadow-[0_20px_40px_rgba(0,0,0,0.2)] flex items-center justify-center gap-3">
                                İLETİŞİME GEÇİN
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        @else
                            <form action="{{ route('vendor.billing.upgrade', $package->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="months" :value="globalSelected">
                                <button type="submit" 
                                        class="w-full py-5 rounded-[1.5rem] font-black text-[11px] uppercase tracking-widest transition-all flex items-center justify-center gap-3 hover:scale-[1.02] active:scale-95 shadow-xl group-btn-premium
                                        {{ ($subscription && $subscription->package_id == $package->id) ? 'bg-emerald-50 text-emerald-600 cursor-default border border-emerald-100 shadow-none' : 'bg-primary text-white hover:bg-[#4B00B5] shadow-primary/30' }}"
                                        {{ ($subscription && $subscription->package_id == $package->id) ? 'disabled' : '' }}>
                                    @if($subscription && $subscription->package_id == $package->id)
                                        <i class="fa-solid fa-check-circle"></i>
                                        @if($subscription->status === 'trial' || $package->slug === 'free')
                                            DENEME SÜRÜMÜ AKTİF
                                        @else
                                            AKTİF PAKET
                                        @endif
                                    @else
                                        ŞİMDİ BAŞLAT
                                        <i class="fa-solid fa-chevron-right text-[9px] group-hover:translate-x-1 transition-transform"></i>
                                    @endif
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Detailed Comparison Matrix -->
        <div class="bg-white rounded-[3.5rem] border border-slate-200 shadow-sm overflow-hidden mb-32" data-aos="fade-up">
            <div class="p-12 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Paket Karşılaştırması</h2>
                    <p class="text-slate-500 font-bold text-sm">Tüm özellikleri detaylıca inceleyin.</p>
                </div>
                <div class="px-6 py-2 bg-slate-100 rounded-full text-[10px] font-black text-slate-500 uppercase tracking-widest border border-slate-200">
                    SİSTEM ÖZELLİKLERİ
                </div>
            </div>

            <div class="overflow-x-auto scrollbar-clean">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">
                            <th class="p-8 text-left min-w-[300px]">Özellik Seti</th>
                            <th class="p-8 text-center">Starter</th>
                            <th class="p-8 text-center italic">Standard</th>
                            <th class="p-8 text-center text-primary">Mobile+</th>
                            <th class="p-8 text-center italic">Pro Suite</th>
                            <th class="p-8 text-center">Enterprise</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $matrix = [
                                '01 — TEMEL ALTYAPI & GÜVENLİK' => [
                                    ['Bulut Tabanlı Merkezi Yönetim Paneli', [true, true, true, true, true]],
                                    ['SSL Sertifikalı Güvenli Veri İletişimi', [true, true, true, true, true]],
                                    ['Günlük Otomatik Veritabanı Yedekleme', [true, true, true, true, true]],
                                    ['Personel Yetkilendirme Sistemi', ['3 Roller', 'Sınırsız', 'Sınırsız', 'Sınırsız', 'Sınırsız']],
                                    ['Anlık Cihaz Senkronizasyonu', [true, true, true, true, true]]
                                ],
                                '02 — REZERVASYON & CRM' => [
                                    ['Dijital Rezervasyon Defteri (Sınırsız)', [true, true, true, true, true]],
                                    ['Müşteri Geçmişi & Profil Yönetimi', [true, true, true, true, true]],
                                    ['Gelişmiş Filtreleme & Arama', [false, true, true, true, true]],
                                    ['VIP Müşteri Etiketleme & Notlar', [false, true, true, true, true]],
                                    ['Müşteri Tercih Analizleri', [false, false, true, true, true]]
                                ],
                                '03 — POS & OPERASYON' => [
                                    ['Adisyon Açma, Kapatma & Parçalı Ödeme', [false, true, true, true, true]],
                                    ['Masa Taşıma & Hesap Birleştirme', [false, true, true, true, true]],
                                    ['Hızlı Satış & Barkod Okuyucu Desteği', [false, true, true, true, true]],
                                    ['Z Raporu & Detaylı Günlük Kapanış', [false, true, true, true, true]],
                                    ['Varyasyonlu Ürün Yönetimi (Gelişmiş)', [false, true, true, true, true]],
                                    ['Zayiat & Fire Takibi Modülü', [false, false, false, true, true]]
                                ],
                                '04 — MOBİL & QR EKOSİSTEM' => [
                                    ['Dijital QR Menü (Sınırsız Okutma)', [true, true, true, true, true]],
                                    ['Masaya Özel QR & İnteraktif Adisyon', [false, false, true, true, true]],
                                    ['Masadan Anlık Mobil Ödeme Altyapısı', [false, false, true, true, true]],
                                    ['Müşteri Sadakat Puanı & Cüzdan', [false, false, true, true, true]],
                                    ['Push Bildirim (Kampanya & Duyuru)', [false, false, true, true, true]],
                                    ['Markaya Özel App Store / Play Store Uygulaması', [false, false, false, false, true]]
                                ],
                                '05 — ANALİTİK & ENTEGRASYON' => [
                                    ['Detaylı Ürün Satış Performans Analizi', [false, true, true, true, true]],
                                    ['Kar/Zarar & Maliyet Yönetim Paneli', [false, false, false, true, true]],
                                    ['Otomatik Stok/Enventer Takibi', [false, false, false, true, true]],
                                    ['Dışa Aktarma (Excel, PDF, CSV)', [true, true, true, true, true]],
                                    ['API Servislerine Erişim (Developer Portal)', [false, false, false, true, true]],
                                    ['ERP & Muhasebe Programı Entegrasyonu', [false, false, false, true, true]]
                                ],
                                '06 — DESTEK & KURUMSAL' => [
                                    ['7/24 Teknik Destek (Standart)', [true, true, true, true, true]],
                                    ['Öncelikli Canlı Destek (Premium)', [false, false, true, true, true]],
                                    ['Atanmış Özel Danışman Hizmeti', [false, false, false, false, true]],
                                    ['Multi-Branch (Birleşik Şube Yönetimi)', [false, true, true, true, true]],
                                    ['Yerinde Kurulum & Personel Eğitimi', [false, false, false, false, true]],
                                    ['Beyaz Etiket (Tüm Marka İmzalarının Kaldırılması)', [false, false, false, false, true]]
                                ]
                            ];
                        @endphp

                        @foreach($matrix as $label => $rows)
                        <tr>
                            <td colspan="6" class="px-8 py-5 bg-slate-50/80 text-[10px] font-black text-primary/70 uppercase tracking-[0.2em]">{{ $label }}</td>
                        </tr>
                        @foreach($rows as $row)
                        <tr class="hover:bg-primary/[0.02] transition-colors">
                            <td class="p-8 text-sm font-bold text-slate-700">{{ $row[0] }}</td>
                            @foreach($row[1] as $val)
                            <td class="p-8 text-center">
                                @if(is_bool($val))
                                    @if($val)
                                        <div class="w-8 h-8 rounded-full bg-emerald-50 mx-auto flex items-center justify-center text-emerald-500 border border-emerald-100">
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                        </div>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                @else
                                    <span class="text-xs font-black text-slate-500 italic">{{ $val }}</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary / CTA Card -->
        <div class="relative bg-white rounded-[3.5rem] p-16 border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-12 overflow-hidden mb-32" data-aos="zoom-in">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="space-y-6 relative z-10 text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 rounded-full border border-emerald-100">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Abonelik Güvencesi</span>
                </div>
                <h3 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight italic">Doğru Paketi <span class="text-primary italic">Seçin</span></h3>
                <p class="text-slate-500 font-bold text-lg max-w-md">Yıllık planlarda geçerli indirim oranlarıyla işletme maliyetlerinizi optimize edin.</p>
            </div>

            @if($subscription)
            <div class="bg-slate-50 p-10 rounded-[2.5rem] border border-slate-200 min-w-[340px] text-center shadow-inner">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Mevcut Planınız</p>
                <div class="text-2xl font-black text-primary mb-2 italic">{{ $subscription->package->name }}</div>
                <div class="text-sm font-bold text-slate-400 mb-8 italic">Yenileme Tarihi: {{ $subscription->ends_at->translatedFormat('d F Y') }}</div>
                <a href="#billing-container" class="inline-block px-10 py-5 bg-white text-primary border border-primary/20 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-primary hover:text-white transition-all shadow-sm">
                    PLANI YÖNET
                </a>
            </div>
            @else
            <div class="bg-slate-50 p-10 rounded-[2.5rem] border border-slate-200 border-dashed min-w-[340px] text-center">
                <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center mx-auto mb-6 shadow-sm border border-slate-100">
                    <i class="fa-solid fa-rocket text-slate-300 text-2xl"></i>
                </div>
                <h4 class="text-xl font-black text-slate-400 mb-6 italic">Abonelik Başlatın</h4>
                <a href="#billing-container" class="inline-block px-10 py-5 bg-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">
                    HEMEN BAŞLA
                </a>
            </div>
            @endif
        </div>
        
        <!-- Payment History -->
        <div class="bg-white/50 border border-slate-200 rounded-[3.5rem] p-12 mb-32">
            <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6">
                <h3 class="text-2xl font-black text-slate-900 tracking-tighter italic">İşlem <span class="text-primary">Geçmişi</span></h3>
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kayıtlı {{ $invoices->count() }} Fatura</div>
            </div>
            
            <div class="overflow-x-auto scrollbar-clean">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest opacity-60">
                            <th class="px-6 py-4">Fatura</th>
                            <th class="px-6 py-4">Tarih</th>
                            <th class="px-6 py-4">Tutar</th>
                            <th class="px-6 py-4">Durum</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($invoices as $invoice)
                        <tr class="hover:bg-white transition-colors group">
                            <td class="px-6 py-6 font-bold text-slate-900 text-sm italic">#{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-6 text-slate-500 font-bold text-sm">{{ $invoice->created_at->translatedFormat('d.m.Y') }}</td>
                            <td class="px-6 py-6 font-black text-slate-900 text-sm italic">₺{{ number_format($invoice->amount, 2, ',', '.') }}</td>
                            <td class="px-6 py-6">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black tracking-widest border border-emerald-100">ÖDENDİ</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center opacity-40 italic font-bold">Herhangi bir kayıt bulunamadı.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- AOS library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });
</script>

<style>
    [x-cloak] { display: none !important; }
    button:focus { outline: none !important; }
    
    .scrollbar-clean::-webkit-scrollbar { height: 6px; }
    .scrollbar-clean::-webkit-scrollbar-track { background: #f8fafc; border-radius: 10px; }
    .scrollbar-clean::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .scrollbar-clean::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

    .bg-primary-dark { background-color: #4B00B5; }
</style>
@endsection
