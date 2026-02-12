@extends('layouts.app')

@section('title', 'RezerVist POS - Akıllı İşletme Terminali')

@section('content')
<div class="bg-white overflow-hidden selection:bg-primary/10 selection:text-primary">
    <!-- Section 1: Cinematic Product Hero -->
    <section class="relative pt-32 pb-48 lg:pt-48 lg:pb-64 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-[radial-gradient(circle_at_50%_0%,rgba(98,0,238,0.08),transparent_50%)]"></div>
            <!-- Decorative Grid -->
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(#6200EE 1.5px, transparent 1.5px), linear-gradient(90deg, #6200EE 1.5px, transparent 1.5px); background-size: 60px 60px;"></div>
        </div>

        <div class="container mx-auto px-6 lg:px-16 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-20">
                <div class="flex-1 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/5 border border-primary/10 text-primary text-xs font-black uppercase tracking-widest mb-8">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                        </span>
                        Yeni Nesil Donanım
                    </div>
                    <h1 class="text-5xl md:text-7xl lg:text-8xl font-black tracking-tighter leading-[0.9] text-slate-900 mb-8">
                        Hızın Yeni <br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-400">Tanımı.</span>
                    </h1>
                    <p class="text-xl text-slate-500 font-medium max-w-xl mx-auto lg:mx-0 leading-relaxed mb-12">
                        İşletmenizin kalbi olacak şekilde tasarlandı. RezerVist POS, sadece bir ödeme terminali değil; rezervasyon, stok ve mutfak yönetimini tek bir noktada buluşturan akıllı bir ekosistemdir.
                    </p>
                    <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                        <a href="#features" class="px-10 py-5 bg-primary text-white font-black rounded-2xl hover:bg-slate-900 transition-all shadow-2xl shadow-primary/20 hover:shadow-slate-900/20 active:scale-95">Özellikleri Keşfet</a>
                        <a href="{{ route('pages.contact') }}" class="px-10 py-5 bg-white border-2 border-slate-100 text-slate-900 font-black rounded-2xl hover:bg-slate-50 transition-all active:scale-95">Teklif Al</a>
                    </div>
                </div>

                <!-- Product Visualization -->
                <div class="flex-1 relative w-full max-w-2xl">
                    <div class="relative group">
                        <!-- Custom CSS POS Terminal Mockup -->
                        <div class="relative z-10 w-full aspect-[4/3] bg-slate-900 rounded-[3rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.3)] p-4 transform rotate-3 hover:rotate-0 transition-transform duration-1000">
                             <div class="w-full h-full bg-slate-800 rounded-[2.2rem] overflow-hidden relative">
                                <!-- Screen Header -->
                                <div class="h-10 bg-slate-900 flex items-center justify-between px-6">
                                    <div class="flex gap-1.5">
                                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                        <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                    </div>
                                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">RezerVist OS v4.2</span>
                                </div>
                                <!-- Dashboard Mockup -->
                                <div class="p-8">
                                    <div class="flex items-center justify-between mb-8">
                                        <div class="h-8 w-32 bg-slate-700/50 rounded-lg"></div>
                                        <div class="h-8 w-8 bg-primary rounded-lg"></div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 mb-8">
                                        <div class="h-32 bg-slate-700/30 rounded-2xl"></div>
                                        <div class="h-32 bg-primary/10 rounded-2xl border border-primary/20"></div>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="h-12 bg-slate-700/20 rounded-xl w-full"></div>
                                        <div class="h-12 bg-slate-700/20 rounded-xl w-5/6"></div>
                                        <div class="h-12 bg-slate-700/20 rounded-xl w-4/6"></div>
                                    </div>
                                </div>
                                <!-- Bottom Glow -->
                                <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-primary/10 to-transparent"></div>
                             </div>
                             <!-- Hardware Buttons -->
                             <div class="absolute -right-4 top-1/2 -translate-y-1/2 flex flex-col gap-4">
                                <div class="w-1.5 h-12 bg-slate-700 rounded-full"></div>
                                <div class="w-1.5 h-8 bg-slate-700 rounded-full"></div>
                             </div>
                        </div>
                        <!-- Ambient Shadows -->
                        <div class="absolute -inset-10 bg-primary/20 blur-[100px] rounded-full -z-10 group-hover:bg-primary/30 transition-colors"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2: Technical Power (Detail Overload) -->
    <section id="features" class="py-32 bg-slate-50 relative overflow-hidden">
        <div class="container mx-auto px-6 lg:px-16 relative z-10">
            <div class="text-center mb-24">
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-6">Mikro Detaylar, <span class="text-primary">Makro Performans.</span></h2>
                <p class="text-lg text-slate-500 font-medium max-w-2xl mx-auto">Donanım ve yazılımın mükemmel uyumu ile işletmenizi zirveye taşıyın.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Feature 1 -->
                <div class="p-10 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 hover:-translate-y-2 transition-all duration-500">
                    <div class="w-14 h-14 bg-primary rounded-2xl flex items-center justify-center text-white text-2xl mb-8 shadow-xl shadow-primary/20">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">Anlık Senkronizasyon</h3>
                    <p class="text-slate-500 font-medium leading-relaxed mb-6">Mutfak, kasa ve garson terminalleri arasında 0.2ms gecikme ile veri aktarımı. Artık siparişler asla kaybolmaz.</p>
                    <ul class="space-y-3 text-sm font-bold text-slate-400">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-primary"></i> Real-time DB Sync</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-primary"></i> Cloud + Local Backup</li>
                    </ul>
                </div>

                <!-- Feature 2 -->
                <div class="p-10 bg-slate-900 rounded-[2.5rem] shadow-2xl text-white hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/20 rounded-full blur-3xl"></div>
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-white text-2xl mb-8 border border-white/10">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="text-2xl font-black mb-4">Askeri Seviye Güvenlik</h3>
                    <p class="text-slate-400 font-medium leading-relaxed mb-6">Tüm işlemler 256-bit AES şifreleme ile korunur. PCI-DSS uyumlu ödeme altyapısı ile güvendesiniz.</p>
                    <ul class="space-y-3 text-sm font-bold text-slate-700">
                        <li class="flex items-center gap-2 text-slate-500"><i class="fa-solid fa-check text-white"></i> E2EE Encryption</li>
                        <li class="flex items-center gap-2 text-slate-500"><i class="fa-solid fa-check text-white"></i> Bio-Metric Login</li>
                    </ul>
                </div>

                <!-- Feature 3 -->
                <div class="p-10 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 hover:-translate-y-2 transition-all duration-500">
                    <div class="w-14 h-14 bg-primary/5 rounded-2xl flex items-center justify-center text-primary text-2xl mb-8 border border-primary/10">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">Gelişmiş Analitik</h3>
                    <p class="text-slate-500 font-medium leading-relaxed mb-6">Satış trendlerini, en çok tercih edilen masaları ve personel performansını anlık olarak izleyin.</p>
                    <ul class="space-y-3 text-sm font-bold text-slate-400">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-primary"></i> AI Predictive Sales</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-primary"></i> Heatmap Reports</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Hardware Comparison Table (Professional Detail) -->
    <section class="py-32 bg-white">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-black text-slate-900 mb-12 text-center underline decoration-primary decoration-4 underline-offset-8">Donanım Paketleri</h2>
                
                <div class="overflow-x-auto rounded-[2rem] border border-slate-100 shadow-2xl shadow-slate-200/50">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="p-8 text-xs font-black text-slate-400 uppercase tracking-widest">Özellikler</th>
                                <th class="p-8 text-xl font-black text-slate-900">Lite</th>
                                <th class="p-8 text-xl font-black text-primary">Pro Max</th>
                                <th class="p-8 text-xl font-black text-slate-900">Elite</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-600 font-bold">
                            <tr class="border-b border-slate-50">
                                <td class="p-8">İşlemci</td>
                                <td class="p-8">4-Core ARM</td>
                                <td class="p-8 text-primary">8-Core RezervX</td>
                                <td class="p-8">16-Core Ultra</td>
                            </tr>
                            <tr class="border-b border-slate-50">
                                <td class="p-8">Bellek</td>
                                <td class="p-8">4GB LPDDR4</td>
                                <td class="p-8 text-primary">8GB LPDDR5</td>
                                <td class="p-8">16GB Unified</td>
                            </tr>
                            <tr class="border-b border-slate-50">
                                <td class="p-8">Ekran</td>
                                <td class="p-8">10.1" HD</td>
                                <td class="p-8 text-primary">13.3" 4K OLED</td>
                                <td class="p-8">15" 5K Retina</td>
                            </tr>
                            <tr class="border-b border-slate-50">
                                <td class="p-8">Bağlantı</td>
                                <td class="p-8">Wi-Fi 5</td>
                                <td class="p-8 text-primary">Wi-Fi 6 + 5G</td>
                                <td class="p-8">Ultra Wideband</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 4: Integration Ecosystem (Visual Web) -->
    <section class="py-32 bg-slate-50 relative overflow-hidden">
        <div class="container mx-auto px-6 lg:px-16 text-center">
            <h2 class="text-3xl font-black text-slate-900 mb-16 uppercase tracking-[0.2em]">Sınırsız Entegrasyon</h2>
            
            <div class="flex flex-wrap justify-center gap-8 mb-20">
                @php
                    $integrations = [
                        ['name' => 'Fatura', 'icon' => 'fa-file-invoice-dollar'],
                        ['name' => 'Kurye', 'icon' => 'fa-motorcycle'],
                        ['name' => 'Mutfak', 'icon' => 'fa-utensils'],
                        ['name' => 'Qr Menü', 'icon' => 'fa-qrcode'],
                        ['name' => 'NFC', 'icon' => 'fa-wifi'],
                        ['name' => 'Sanal Pos', 'icon' => 'fa-credit-card'],
                    ];
                @endphp

                @foreach($integrations as $item)
                <div class="flex flex-col items-center gap-4 group">
                    <div class="w-20 h-20 rounded-full bg-white shadow-xl flex items-center justify-center text-slate-400 text-2xl group-hover:bg-primary group-hover:text-white transition-all duration-300 border border-slate-100">
                        <i class="fa-solid {{ $item['icon'] }}"></i>
                    </div>
                    <span class="text-xs font-black text-slate-700 uppercase tracking-widest">{{ $item['name'] }}</span>
                </div>
                @endforeach
            </div>

            <div class="p-12 bg-white rounded-[3rem] border border-slate-100 shadow-xl max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between gap-10">
                <div class="text-left">
                    <h4 class="text-2xl font-black text-slate-900 mb-2">API Desteği ile Özgürleşin</h4>
                    <p class="text-slate-500 font-medium">Kendi yazılımınızı RezerVist donanımına saniyeler içinde bağlayın.</p>
                </div>
                <a href="/docs" class="px-8 py-4 bg-slate-900 text-white font-black rounded-xl hover:bg-slate-800 transition-colors">Dökümanları İncele</a>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-40 bg-primary relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://images.unsplash.com/photo-1556761175-5973dc0f32e7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1632&q=80')] bg-cover bg-center"></div>
        <div class="container mx-auto px-6 text-center relative z-10">
            <h2 class="text-4xl md:text-6xl font-black text-white mb-10 leading-tight tracking-tighter">İşletmenizi Bugün <br/>Dijitalleştirin.</h2>
            <div class="flex justify-center flex-col sm:flex-row gap-6">
                <button class="px-12 py-5 bg-white text-primary font-black rounded-2xl hover:scale-105 transition-all shadow-2xl shadow-black/20">Ücretsiz Başlat</button>
                <button class="px-12 py-5 bg-primary-dark border-2 border-white/20 text-white font-black rounded-2xl hover:bg-white/10 transition-all">Demo Talep Et</button>
            </div>
        </div>
    </section>
</div>
@endsection
