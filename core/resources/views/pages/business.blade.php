@extends('layouts.app')

@section('title', 'RezerVist: İşletmenizi Dijital Bir Güç Merkezine Dönüştürün')

@section('content')
<div class="bg-[#020617] min-h-screen font-sans selection:bg-primary/30 selection:text-white overflow-x-hidden relative" 
     x-data="{ 
        scrolled: false,
        reveal: { hero: false, map: false, details: false, grid: false, cta: false }
     }" 
     x-init="
        window.addEventListener('scroll', () => scrolled = window.scrollY > 50);
        setTimeout(() => reveal.hero = true, 100);
     "
     @scroll.window="
        if (window.scrollY > 150) reveal.map = true;
        if (window.scrollY > 600) reveal.details = true;
        if (window.scrollY > 1300) reveal.grid = true;
        if (window.scrollY > 2000) reveal.cta = true;
     ">

    <!-- Prism Glass Aura: Advanced Layered Background -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <!-- Main Aurora Blobs -->
        <div class="absolute top-[-10%] left-[-10%] w-[60%] h-[60%] bg-primary/20 rounded-full blur-[120px] animate-blob"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-indigo-500/20 rounded-full blur-[140px] animate-blob animation-delay-2000"></div>
        <div class="absolute top-[30%] right-[10%] w-[40%] h-[40%] bg-purple-600/15 rounded-full blur-[100px] animate-blob animation-delay-4000"></div>
        
        <!-- Center Prism Glow -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80%] h-[80%] bg-primary/5 rounded-full blur-[160px] opacity-30"></div>
        
        <!-- Subtle Grid Overlay -->
        <div class="absolute inset-0 z-[1] bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-20 mix-blend-overlay"></div>
    </div>

    <!-- Hero V6: Elegance + Prism Aura -->
    <section class="relative min-h-[90vh] flex items-center justify-center pt-24 pb-20 overflow-hidden z-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center"
             x-show="reveal.hero"
             x-transition:enter="transition ease-out duration-1000"
             x-transition:enter-start="opacity-0 translateY-10 scale-98"
             x-transition:enter-end="opacity-100 translateY-0 scale-100">
            

            <h1 class="text-5xl lg:text-8xl font-black text-white tracking-tighter mb-8 leading-[0.9] transition-all duration-700">
                Geleceğin İşletmesini <br/>
                <span class="text-transparent bg-clip-text bg-[linear-gradient(110deg,#6366f1,45%,#a855f7,55%,#6366f1)] bg-[length:200%_100%] animate-shimmer">Bugün İnşa Edin.</span>
            </h1>

            <p class="text-lg lg:text-xl text-slate-400 max-w-2xl mx-auto font-medium leading-relaxed mb-16 px-4">
                RezerVist Prism; karmaşık operasyonları sadeleştiren, veriyi kazanca dönüştüren ve işletmenizi <span class="text-white border-b border-primary/40 font-bold">tek bir çatı altında toplayan</span> en gelişmiş ekosistemdir.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-8">
                <a href="{{ route('business.apply') }}" class="group relative px-12 py-5 bg-primary text-white font-black rounded-xl overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-[0_0_30px_rgba(110,68,255,0.3)]">
                    <span class="relative z-10">İşletmenizi Taşıyın</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                </a>
                
            </div>
        </div>
    </section>

    <!-- Ecosystem Architecture: Refined Grid -->
    <section class="py-24 relative z-10" x-show="reveal.map" x-transition:enter="transition ease-out duration-1000 delay-200" x-transition:enter-start="opacity-0 translateY-12" x-transition:enter-end="opacity-100 translateY-0">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $components = [
                        ['title' => 'Ana Panel', 'desc' => 'Merkezi yönetim, finansal zeka ve anlık kontrol.', 'color' => 'from-indigo-500 to-primary', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                        ['title' => 'Akıllı POS', 'desc' => 'Bulut tabanlı adisyon, mutfak ve hızlı ödeme.', 'color' => 'from-purple-500 to-indigo-500', 'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                        ['title' => 'Yönetici Uygulaması', 'desc' => 'İşletmenizin nabzı her an cebinizde.', 'color' => 'from-cyan-500 to-blue-500', 'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
                        ['title' => 'QR Deneyimi', 'desc' => 'Temassız sipariş ve interaktif müşteri yolculuğu.', 'color' => 'from-emerald-500 to-teal-500', 'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z'],
                    ];
                @endphp

                @foreach($components as $comp)
                <div class="group relative p-8 rounded-[2.5rem] bg-white/[0.04] border border-white/10 backdrop-blur-2xl transition-all duration-500 hover:-translate-y-2 hover:bg-white/[0.08] hover:border-white/20">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-tr {{ $comp['color'] }} flex items-center justify-center text-white mb-8 shadow-xl">
                        <svg class="w-6 h-6 font-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $comp['icon'] }}"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-white mb-4 leading-tight">{{ $comp['title'] }}</h3>
                    <p class="text-slate-400 text-xs leading-relaxed mb-8 font-medium group-hover:text-slate-300 transition-colors">{{ $comp['desc'] }}</p>
                    <div class="h-1 w-full bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r {{ $comp['color'] }} w-[60%] group-hover:w-full transition-all duration-500"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Dynamic Tech Core: Performance Breakdown -->
    <section class="py-32 relative z-10" 
             x-data="{ techActive: 1 }" 
             x-show="reveal.details" 
             x-transition:enter="transition ease-out duration-1000">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-24">
                <div class="lg:w-1/2">
                    <div class="inline-flex px-4 py-1.5 rounded-lg bg-primary/10 text-primary text-[9px] font-black uppercase tracking-[0.2em] mb-6 shadow-sm border border-primary/10">Performance Core</div>
                    <h2 class="text-4xl lg:text-6xl font-black text-white mb-10 leading-[0.95] tracking-tighter transition-all duration-500">Teknik Üstünlük, <br/><span class="text-primary italic animate-pulse">Sade Tecrübe</span></h2>
                    
                    <div class="space-y-6">
                        @php
                            $details = [
                                ['id' => 1, 'num' => '01', 'title' => 'Omnichannel Rezervasyon', 'desc' => 'Tüm kanallardan gelen talepler anında merkezi havuzda.'],
                                ['id' => 2, 'num' => '02', 'title' => 'Cloud Integrated POS', 'desc' => 'Donanım sınırlaması olmadan, her yerden tam operasyon.'],
                                ['id' => 3, 'num' => '03', 'title' => 'Prism Analitik', 'desc' => 'Yapay zeka ile personelden kâra her veriyi optimize edin.'],
                            ];
                        @endphp

                        @foreach($details as $detail)
                        <div class="flex gap-6 group cursor-pointer p-6 rounded-2xl transition-all duration-500"
                             :class="techActive === {{ $detail['id'] }} ? 'bg-white/5 border border-white/10 shadow-xl translate-x-3' : 'hover:translate-x-1 grayscale-[0.5] opacity-60 hover:opacity-100 hover:grayscale-0'"
                             @click="techActive = {{ $detail['id'] }}"
                             @mouseenter="techActive = {{ $detail['id'] }}">
                            <span class="text-2xl font-black transition-colors duration-400"
                                  :class="techActive === {{ $detail['id'] }} ? 'text-primary' : 'text-white/10'">{{ $detail['num'] }}</span>
                            <div>
                                <h4 class="text-xl font-bold mb-2 transition-colors duration-400"
                                    :class="techActive === {{ $detail['id'] }} ? 'text-white' : 'text-slate-400'">{{ $detail['title'] }}</h4>
                                <p class="text-sm leading-relaxed font-medium transition-colors duration-400"
                                   :class="techActive === {{ $detail['id'] }} ? 'text-slate-300' : 'text-slate-500'">{{ $detail['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Interactive Dynamic Mockup -->
                <div class="lg:w-1/2 relative group w-full min-h-[500px] flex items-center justify-center">
                    <div class="absolute -inset-10 bg-primary/20 rounded-full blur-[80px] opacity-20 group-hover:opacity-40 transition-opacity duration-1000"></div>
                    
                    <!-- Container: Glass Frame -->
                    <div class="relative bg-white/[0.02] border border-white/10 backdrop-blur-3xl rounded-[3.5rem] p-10 overflow-hidden shadow-2xl w-full border-t-white/20 h-full min-h-[400px]">
                        
                        <!-- Header Stub -->
                        <div class="flex items-center justify-between mb-10 opacity-40">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-400/50"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-400/50"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500/50"></div>
                            </div>
                            <div class="w-32 h-6 bg-white/10 rounded-full"></div>
                        </div>

                        <!-- Content 01: Reservation Engine -->
                        <div x-show="techActive === 1" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="space-y-6">
                            <div class="text-xs font-black text-primary uppercase tracking-widest mb-4">Masa Planlama</div>
                            @foreach([['A14', '19:30', '4 Kisi'], ['B02', '20:15', '2 Kisi'], ['VIP-1', '21:00', '8 Kisi']] as $res)
                            <div class="flex items-center justify-between p-5 bg-white/5 rounded-2xl border border-white/5 hover:border-primary/30 transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-primary/20 flex items-center justify-center text-primary font-bold">{{ $res[0] }}</div>
                                    <div class="text-white text-sm font-bold">{{ $res[2] }}</div>
                                </div>
                                <div class="text-slate-400 text-xs font-black">{{ $res[1] }}</div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Content 02: Cloud POS -->
                        <div x-show="techActive === 2" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="space-y-8">
                            <div class="text-xs font-black text-purple-400 uppercase tracking-widest mb-4 font-black">Adisyon Özet</div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-6 bg-white/5 rounded-3xl border border-white/5 text-center">
                                    <div class="text-[10px] text-slate-500 uppercase font-black mb-2">Acık Masalar</div>
                                    <div class="text-3xl font-black text-white">24</div>
                                </div>
                                <div class="p-6 bg-white/5 rounded-3xl border border-white/5 text-center">
                                    <div class="text-[10px] text-slate-500 uppercase font-black mb-2">Bekleyen</div>
                                    <div class="text-3xl font-black text-white">8</div>
                                </div>
                            </div>
                            <div class="p-6 bg-indigo-500/20 rounded-2xl border border-indigo-500/30 flex justify-between items-center">
                                <div class="text-indigo-200 text-sm font-bold italic font-black uppercase">Toplam Ciro</div>
                                <div class="text-white text-2xl font-black">₺124.500</div>
                            </div>
                        </div>

                        <!-- Content 03: AI Analytics -->
                        <div x-show="techActive === 3" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="space-y-8">
                            <div class="text-xs font-black text-emerald-400 uppercase tracking-widest mb-6 font-black">Performans Analizi</div>
                            <div class="h-40 flex items-end justify-between px-4 pb-2 border-b border-white/10 gap-2">
                                @foreach([40, 70, 45, 95, 60, 80, 55] as $h)
                                <div class="w-full bg-gradient-to-t from-emerald-500 to-teal-400 rounded-t-lg transition-all duration-1000" style="height: {{ $h }}%"></div>
                                @endforeach
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="h-2 bg-white/10 rounded-full overflow-hidden"><div class="h-full bg-emerald-500 w-2/3"></div></div>
                                <div class="h-2 bg-white/10 rounded-full overflow-hidden"><div class="h-full bg-teal-500 w-1/2"></div></div>
                                <div class="h-2 bg-white/10 rounded-full overflow-hidden"><div class="h-full bg-emerald-400 w-4/5"></div></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Balanced Grid: Solution Map -->
    <section class="py-32 relative overflow-hidden bg-[#010413]/50" x-show="reveal.grid" x-transition:enter="transition ease-out duration-1000">
        <div class="absolute inset-0 z-0">
            <svg class="w-full h-full text-primary/5" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs><pattern id="elegance-grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.08"/></pattern></defs>
                <rect width="100" height="100" fill="url(#elegance-grid)"/>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-white text-4xl lg:text-6xl font-black mb-10 leading-none tracking-tighter">Sınırsız Kapasite, <br/><span class="text-primary italic animate-shimmer bg-clip-text text-transparent bg-gradient-to-r from-primary via-purple-400 to-primary bg-[length:200%_auto]">Tek Bir Kontrol.</span></h2>
            <p class="text-slate-500 text-lg font-medium mb-24 max-w-2xl mx-auto">Tüm dijital süreçlerinizi kusursuz bir uyumla yönetmeniz için tasarlandı.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-left">
                @php
                    $features = [
                        ['lbl' => 'Reservation', 'title' => 'Masa Planlama', 'desc' => 'AI destekli masa yerleşimi ve misafir sadakati.'],
                        ['lbl' => 'POS', 'title' => 'Hibrit Adisyon', 'desc' => 'Çevrimdışı mod ve anlık stok entegrasyonu.'],
                        ['lbl' => 'Fleet', 'title' => 'Ekip Yönetimi', 'desc' => 'Garson, kurye ve vale dijital kontrolü.'],
                        ['lbl' => 'QR', 'title' => 'Dijital Deneyim', 'desc' => 'Masadan ödeme ve interaktif menü.'],
                        ['lbl' => 'AI', 'title' => 'Analitik Zeka', 'desc' => 'Gelecek tahminleme ve yoğunluk haritaları.'],
                        ['lbl' => 'Connect', 'title' => 'Entegrasyon', 'desc' => 'API ile kusursuz sistem haberleşmesi.'],
                    ];
                @endphp

                @foreach($features as $feat)
                <div class="group p-10 rounded-[2.5rem] bg-white/[0.03] border border-white/5 backdrop-blur-3xl hover:border-primary/40 hover:bg-white/[0.06] transition-all duration-500">
                    <div class="text-primary text-[9px] font-black uppercase tracking-[0.2em] mb-6 opacity-60 group-hover:opacity-100 transition-opacity">{{ $feat['lbl'] }}</div>
                    <h4 class="text-2xl font-bold text-white mb-4 group-hover:text-primary transition-colors">{{ $feat['title'] }}</h4>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium group-hover:text-slate-300 transition-colors">{{ $feat['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Elegant Footer CTA -->
    <section class="py-48 relative z-10 text-center" x-show="reveal.cta" x-transition:enter="transition ease-out duration-1000">
        <div class="max-w-3xl mx-auto px-6">
            <h2 class="text-white text-5xl lg:text-7xl font-black mb-12 leading-[0.9] tracking-tighter italic">Hazırsanız <br/><span class="text-primary">Kusursuzlaşalım.</span></h2>
            <p class="text-slate-500 text-lg font-medium mb-16 px-8">Ekosisteme bugün dahil olun, işletmenizi geleceğe hazırlayın.</p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-8">
                <a href="{{ route('business.apply') }}" class="w-full sm:w-auto px-12 py-5 bg-primary text-white font-black rounded-xl shadow-2xl hover:scale-105 transition-all shadow-primary/20">
                    Ekosisteme Katılın
                </a>
                <a href="{{ route('pages.contact') }}" class="w-full sm:w-auto px-12 py-5 bg-white/5 backdrop-blur-xl text-white font-black rounded-xl border border-white/10 hover:bg-white/10 transition-all">
                    Sunum İsteyin
                </a>
            </div>
        </div>
    </section>
</div>

<style>
@keyframes shimmer {
    to { background-position: 200% center; }
}
.animate-shimmer {
    animation: shimmer 5s linear infinite;
}
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
    animation: blob 7s infinite;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}
</style>
@endsection
