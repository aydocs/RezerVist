<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RezerVistA POS | Yetenekli, Hızlı, Keskin.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#F5F3FF', 100: '#EDE9FE', 200: '#DDD6FE',
                            300: '#C4B5FD', 400: '#A78BFA',
                            500: '#6200EE', 600: '#5000D3', 700: '#4200AF',
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * { -webkit-font-smoothing: antialiased; }
        body { background: #fff; overflow-x: hidden; }

        /* ─── Intro Overlay ─── */
        #intro-overlay {
            position: fixed; inset: 0; z-index: 9999;
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            transition: opacity 0.8s ease, visibility 0.8s ease;
        }
        #intro-overlay.hidden-overlay { opacity: 0; visibility: hidden; pointer-events: none; }

        /* ─── Progress Bar ─── */
        #intro-progress {
            position: absolute; top: 0; left: 0; height: 3px;
            background: #6200EE;
            transition: width 0.15s linear;
        }

        /* ─── Skip Button ─── */
        #skip-btn {
            position: absolute; top: 24px; right: 28px;
            padding: 10px 22px; border-radius: 50px;
            border: 1.5px solid #6200EE; color: #6200EE;
            font-size: 11px; font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase;
            cursor: pointer; background: transparent;
            transition: background 0.2s, color 0.2s;
            font-family: 'Outfit', sans-serif;
        }
        #skip-btn:hover { background: #6200EE; color: #fff; }

        /* ─── Scene Base ─── */
        .scene { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; flex-direction: column; padding: 40px; }

        /* ─── Keyframes ─── */
        @keyframes fadeSlideUp   { from { opacity:0; transform:translateY(40px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeSlideDown { from { opacity:0; transform:translateY(-30px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeIn        { from { opacity:0; } to { opacity:1; } }
        @keyframes scaleIn       { from { opacity:0; transform:scale(0.7); } to { opacity:1; transform:scale(1); } }
        @keyframes scaleInBig   { from { opacity:0; transform:scale(0.3); } to { opacity:1; transform:scale(1); } }
        @keyframes slideInLeft  { from { opacity:0; transform:translateX(-60px); } to { opacity:1; transform:translateX(0); } }
        @keyframes slideInRight { from { opacity:0; transform:translateX(60px); } to { opacity:1; transform:translateX(0); } }
        @keyframes pulse-brand { 0%,100% { box-shadow:0 0 0 0 rgba(98,0,238,0.3); } 50% { box-shadow:0 0 0 18px rgba(98,0,238,0); } }
        @keyframes barGrow { from { height:0; } to { height:var(--h); } }
        @keyframes drawLine { from { stroke-dashoffset: 300; } to { stroke-dashoffset: 0; } }
        @keyframes countUp   { from { opacity:0; transform:scale(0.5); } to { opacity:1; transform:scale(1); } }

        .anim-fadeSlideUp   { animation: fadeSlideUp   0.7s cubic-bezier(0.19,1,0.22,1) both; }
        .anim-fadeSlideDown { animation: fadeSlideDown 0.7s cubic-bezier(0.19,1,0.22,1) both; }
        .anim-fadeIn        { animation: fadeIn        0.6s ease both; }
        .anim-scaleIn       { animation: scaleIn       0.7s cubic-bezier(0.19,1,0.22,1) both; }
        .anim-scaleInBig    { animation: scaleInBig    0.9s cubic-bezier(0.19,1,0.22,1) both; }
        .anim-slideInLeft   { animation: slideInLeft   0.7s cubic-bezier(0.19,1,0.22,1) both; }
        .anim-slideInRight  { animation: slideInRight  0.7s cubic-bezier(0.19,1,0.22,1) both; }

        .delay-100 { animation-delay:0.1s; }
        .delay-200 { animation-delay:0.2s; }
        .delay-300 { animation-delay:0.3s; }
        .delay-500 { animation-delay:0.5s; }
        .delay-700 { animation-delay:0.7s; }

        /* ─── Typewriter ─── */
        .typewriter { overflow:hidden; border-right:3px solid #6200EE; white-space:nowrap; animation: typing 1.8s steps(16,end) both, blink 0.6s step-end infinite alternate; }
        @keyframes typing { from { max-width:0; } to { max-width:100%; } }
        @keyframes blink  { 50% { border-color:transparent; } }

        /* ─── Bar chart ─── */
        .bar { width:28px; border-radius:6px 6px 0 0; background:#6200EE; animation: barGrow 0.8s cubic-bezier(0.19,1,0.22,1) both; align-self:flex-end; }

        /* ─── Network line ─── */
        .net-line { stroke-dasharray:300; animation: drawLine 1.5s ease both; }

        /* ─── Main page reveal ─── */
        #main-page { opacity:0; transition: opacity 0.8s ease; }
        #main-page.visible { opacity:1; }

        /* ─── Dot pattern ─── */
        .dot-bg { background-image:radial-gradient(#E2E8F0 1px, transparent 1px); background-size:28px 28px; }

        /* ─── Floating terminal ─── */
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        .floating { animation: float 6s ease-in-out infinite; }

        /* ─── Feature card hover ─── */
        .feature-card { transition:transform 0.3s,box-shadow 0.3s; }
        .feature-card:hover { transform:translateY(-6px); box-shadow:0 24px 48px -12px rgba(98,0,238,0.12); }
    </style>
</head>
<body x-data="posIntro()" x-init="start()">

<!-- ════════════════════════════════════
     INTRO OVERLAY
════════════════════════════════════ -->
<div id="intro-overlay" :class="{ 'hidden-overlay': done }">

    <!-- Progress bar -->
    <div id="intro-progress" :style="'width:' + progress + '%'"></div>

    <!-- Skip button -->
    <button id="skip-btn" @click="skip()">Tanıtımı Geç &rarr;</button>

    <!-- ── Scene 0: Logo Reveal ── -->
    <div class="scene" x-show="scene === 0" x-cloak>
        <div class="anim-scaleInBig flex flex-col items-center gap-6">
            <div class="w-32 h-32 bg-brand-500 rounded-[2rem] flex items-center justify-center shadow-2xl shadow-brand-500/30" style="animation: pulse-brand 2s infinite 0.9s;">
                <span class="text-white font-black text-6xl font-display">R</span>
            </div>
            <p class="text-slate-400 text-sm font-semibold tracking-[0.4em] uppercase anim-fadeIn delay-500">Rezervist Systems</p>
        </div>
    </div>

    <!-- ── Scene 1: Name Typewriter ── -->
    <div class="scene" x-show="scene === 1" x-cloak>
        <div class="text-center">
            <div class="flex items-center justify-center gap-3 mb-4 anim-fadeSlideDown">
                <div class="w-10 h-10 bg-brand-500 rounded-xl flex items-center justify-center">
                    <span class="text-white font-black text-xl font-display">R</span>
                </div>
            </div>
            <h1 class="typewriter text-5xl lg:text-7xl font-black text-slate-950 font-display tracking-tighter">RezerVistA POS</h1>
            <p class="text-slate-400 text-base font-medium mt-6 anim-fadeIn delay-700 tracking-widest uppercase">Satış Noktası Çözümü</p>
            <div class="flex items-center justify-center gap-2 mt-8 anim-fadeIn delay-700">
                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                <span class="text-xs font-bold text-slate-400 tracking-widest uppercase">v4.5 — Hazır</span>
            </div>
        </div>
    </div>

    <!-- ── Scene 2: Speed ── -->
    <div class="scene" x-show="scene === 2" x-cloak>
        <div class="max-w-2xl w-full flex flex-col lg:flex-row items-center gap-16">
            <div class="anim-slideInLeft flex-1">
                <div class="w-20 h-20 bg-brand-50 rounded-3xl flex items-center justify-center mb-6 border border-brand-100">
                    <i class="fas fa-bolt text-brand-500 text-3xl"></i>
                </div>
                <h2 class="text-5xl lg:text-7xl font-black text-slate-950 font-display tracking-tighter leading-none mb-4">15<span class="text-brand-500">ms</span></h2>
                <p class="text-slate-500 text-lg font-medium leading-relaxed">Maksimum yanıt süresi. İnternet olmadan bile yerel ağda tam hızda çalışır.</p>
            </div>
            <div class="anim-slideInRight flex-shrink-0">
                <div class="w-48 h-48 rounded-full border-8 border-brand-100 flex items-center justify-center relative" style="animation: pulse-brand 2s infinite;">
                    <div class="text-center">
                        <div class="text-4xl font-black text-brand-500 font-display">~15</div>
                        <div class="text-xs font-black text-slate-400 uppercase tracking-widest">ms avg</div>
                    </div>
                    <svg class="absolute inset-0" viewBox="0 0 192 192">
                        <circle cx="96" cy="96" r="86" fill="none" stroke="#EDE9FE" stroke-width="8"/>
                        <circle cx="96" cy="96" r="86" fill="none" stroke="#6200EE" stroke-width="8"
                            stroke-dasharray="540" stroke-dashoffset="54"
                            stroke-linecap="round" transform="rotate(-90 96 96)"
                            class="net-line"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Scene 3: Table Management ── -->
    <div class="scene" x-show="scene === 3" x-cloak>
        <div class="max-w-2xl w-full">
            <div class="anim-fadeSlideDown text-center mb-10">
                <div class="inline-flex items-center gap-3 px-4 py-2 bg-brand-50 border border-brand-100 rounded-xl mb-4">
                    <i class="fas fa-th text-brand-500 text-sm"></i>
                    <span class="text-xs font-black text-brand-500 uppercase tracking-widest">Masa Yönetimi</span>
                </div>
                <h2 class="text-4xl lg:text-6xl font-black text-slate-950 font-display tracking-tighter">Tüm Masaları <span class="text-brand-500">Tek Ekranda</span></h2>
            </div>
            <div class="grid grid-cols-6 gap-2 anim-scaleIn delay-300">
                <template x-for="n in 18">
                    <div class="aspect-square rounded-xl flex flex-col items-center justify-center text-xs font-black border"
                         :class="[2,5,9,14].includes(n) ? 'bg-brand-500 text-white border-brand-500 shadow-lg shadow-brand-500/30 scale-105' : (n % 3 === 0 ? 'bg-rose-50 text-rose-500 border-rose-100' : 'bg-white text-slate-400 border-slate-100')">
                        <span class="text-[8px] uppercase tracking-widest" x-text="[2,5,9,14].includes(n) ? 'Dolu' : (n%3===0 ? 'Meşgul' : 'Boş')"></span>
                        <span class="text-base font-black font-display" :class="[2,5,9,14].includes(n) ? 'text-white' : ''" x-text="n < 10 ? '0'+n : n"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- ── Scene 4: Reporting ── -->
    <div class="scene" x-show="scene === 4" x-cloak>
        <div class="max-w-2xl w-full flex flex-col lg:flex-row items-center gap-16">
            <div class="anim-slideInLeft flex-1">
                <div class="w-20 h-20 bg-brand-50 rounded-3xl flex items-center justify-center mb-6 border border-brand-100">
                    <i class="fas fa-chart-bar text-brand-500 text-3xl"></i>
                </div>
                <h2 class="text-4xl lg:text-6xl font-black text-slate-950 font-display tracking-tighter leading-none mb-4">Anlık<br><span class="text-brand-500">Raporlama</span></h2>
                <p class="text-slate-500 text-lg font-medium leading-relaxed">Günlük, haftalık, aylık satış grafikleri. Personel performansı. PDF ihracat.</p>
                <div class="flex gap-4 mt-6">
                    <div class="px-4 py-2 bg-brand-50 rounded-xl border border-brand-100">
                        <div class="text-xl font-black text-brand-500 font-display">₺124K</div>
                        <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Bu Ay</div>
                    </div>
                    <div class="px-4 py-2 bg-emerald-50 rounded-xl border border-emerald-100">
                        <div class="text-xl font-black text-emerald-500 font-display">+32%</div>
                        <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Büyüme</div>
                    </div>
                </div>
            </div>
            <!-- Bar chart -->
            <div class="anim-slideInRight flex-shrink-0 flex items-end gap-3 h-40">
                <div class="bar delay-100" style="--h:50%;  height:50%;  animation-delay:0.1s"></div>
                <div class="bar delay-200" style="--h:75%;  height:75%;  animation-delay:0.2s"></div>
                <div class="bar delay-300" style="--h:40%;  height:40%;  animation-delay:0.3s"></div>
                <div class="bar" style="--h:90%;  height:90%;  animation-delay:0.4s; background:#a78bfa;"></div>
                <div class="bar" style="--h:60%;  height:60%;  animation-delay:0.5s"></div>
                <div class="bar" style="--h:100%; height:100%; animation-delay:0.6s"></div>
                <div class="bar" style="--h:70%;  height:70%;  animation-delay:0.7s; background:#a78bfa;"></div>
            </div>
        </div>
    </div>

    <!-- ── Scene 5: Cloud + Local Network ── -->
    <div class="scene" x-show="scene === 5" x-cloak>
        <div class="max-w-2xl w-full text-center">
            <div class="anim-fadeSlideDown mb-8">
                <div class="inline-flex items-center gap-3 px-4 py-2 bg-brand-50 border border-brand-100 rounded-xl mb-4">
                    <i class="fas fa-cloud text-brand-500 text-sm"></i>
                    <span class="text-xs font-black text-brand-500 uppercase tracking-widest">Hibrit Altyapı</span>
                </div>
                <h2 class="text-4xl lg:text-6xl font-black text-slate-950 font-display tracking-tighter">Bulut <span class="text-brand-500">&amp;</span> Yerel Ağ</h2>
                <p class="text-slate-500 text-lg font-medium mt-4">Internet kesilse de veriler kaybolmaz. Tüm terminaller birbirini görür.</p>
            </div>
            <!-- Network diagram -->
            <div class="anim-scaleIn delay-300 relative flex items-center justify-center gap-0">
                <svg width="520" height="140" viewBox="0 0 520 140" fill="none" class="max-w-full">
                    <!-- Cloud node -->
                    <circle cx="80" cy="70" r="40" fill="#EDE9FE" stroke="#6200EE" stroke-width="2"/>
                    <text x="80" y="67" text-anchor="middle" fill="#6200EE" font-size="18" font-family="Font Awesome 6 Free" font-weight="900">&#xf0c2;</text>
                    <text x="80" y="86" text-anchor="middle" fill="#6200EE" font-size="9" font-family="Plus Jakarta Sans" font-weight="800" letter-spacing="1">CLOUD</text>
                    <!-- Line -->
                    <line x1="120" y1="70" x2="400" y2="70" stroke="#6200EE" stroke-width="2.5" stroke-dasharray="8 6" class="net-line"/>
                    <!-- Pulse dot -->
                    <circle cx="260" cy="70" r="7" fill="#6200EE" opacity="0.3" style="animation:pulse-brand 1.5s infinite"/>
                    <circle cx="260" cy="70" r="4" fill="#6200EE"/>
                    <!-- Terminal node -->
                    <circle cx="440" cy="70" r="40" fill="#F5F3FF" stroke="#6200EE" stroke-width="2"/>
                    <text x="440" y="67" text-anchor="middle" fill="#6200EE" font-size="18" font-family="Font Awesome 6 Free" font-weight="900">&#xf108;</text>
                    <text x="440" y="86" text-anchor="middle" fill="#6200EE" font-size="9" font-family="Plus Jakarta Sans" font-weight="800" letter-spacing="1">LOCAL</text>
                </svg>
            </div>
            <div class="flex justify-center gap-6 mt-6 anim-fadeIn delay-700">
                <div class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-emerald-500"></div><span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Senkron: %100</span></div>
                <div class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-brand-500"></div><span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Bağlı</span></div>
            </div>
        </div>
    </div>

    <!-- ── Scene 6: Feature Burst ── -->
    <div class="scene" x-show="scene === 6" x-cloak>
        <div class="text-center mb-10 anim-fadeSlideDown">
            <h2 class="text-4xl lg:text-6xl font-black text-slate-950 font-display tracking-tighter">Her Şey <span class="text-brand-500">Hazır.</span></h2>
            <p class="text-slate-400 text-lg font-medium mt-3">Saniyeler içinde kurulum. Yıllarca kararlı çalışma.</p>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 max-w-3xl w-full">
            <div class="anim-scaleIn delay-100 bg-brand-50 border border-brand-100 rounded-2xl p-5 text-center">
                <i class="fas fa-bolt text-brand-500 text-2xl mb-3"></i>
                <div class="text-sm font-black text-slate-950">Ultra Hız</div>
                <div class="text-xs text-slate-400 mt-1">15ms latency</div>
            </div>
            <div class="anim-scaleIn delay-200 bg-brand-50 border border-brand-100 rounded-2xl p-5 text-center">
                <i class="fas fa-th text-brand-500 text-2xl mb-3"></i>
                <div class="text-sm font-black text-slate-950">Masa Grid</div>
                <div class="text-xs text-slate-400 mt-1">Sürükle & bırak</div>
            </div>
            <div class="anim-scaleIn delay-300 bg-brand-50 border border-brand-100 rounded-2xl p-5 text-center">
                <i class="fas fa-chart-bar text-brand-500 text-2xl mb-3"></i>
                <div class="text-sm font-black text-slate-950">Raporlama</div>
                <div class="text-xs text-slate-400 mt-1">PDF & Excel</div>
            </div>
            <div class="anim-scaleIn" style="animation-delay:0.4s" class="bg-brand-50 border border-brand-100 rounded-2xl p-5 text-center">
                <i class="fas fa-cloud text-brand-500 text-2xl mb-3"></i>
                <div class="text-sm font-black text-slate-950">Hibrit Ağ</div>
                <div class="text-xs text-slate-400 mt-1">%99.9 uptime</div>
            </div>
        </div>
        <div class="anim-fadeIn delay-700 mt-10">
            <button @click="skip()" class="px-10 py-4 bg-brand-500 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-brand-600 transition-all shadow-2xl shadow-brand-500/30">
                POS Sistemini İncele &rarr;
            </button>
        </div>
    </div>

</div><!-- /intro-overlay -->


<!-- ════════════════════════════════════
     MAIN PAGE
════════════════════════════════════ -->
<div id="main-page" :class="{ 'visible': done }">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 border-b border-slate-100 bg-white/95 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-6 h-18 flex items-center justify-between h-16">
            <a href="/" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-brand-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-brand-500/30">
                    <i class="fas fa-bolt text-sm"></i>
                </div>
                <span class="text-lg font-black text-slate-900 tracking-tighter font-display">RezerVist<span class="text-brand-500">A</span></span>
            </a>
            <div class="flex items-center gap-8">
                <a href="{{ route('pages.pos.versions') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-brand-500 transition-colors hidden md:block">İndir</a>
                <a href="/business-partner" class="text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-brand-500 transition-colors hidden md:block">Demo</a>
                <a href="/login" class="px-6 py-2.5 bg-slate-950 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-brand-500 transition-all shadow-lg">Giriş Yap</a>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero -->
        <section class="relative pt-32 pb-24 dot-bg overflow-hidden">
            <div class="absolute inset-x-0 bottom-0 h-64 bg-gradient-to-t from-white to-transparent"></div>
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16 relative z-10">
                    <div class="inline-flex items-center gap-3 px-4 py-2 bg-brand-50 border border-brand-100 rounded-xl text-[10px] font-black tracking-[0.3em] text-brand-500 uppercase mb-6">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                        </span>
                        Profesyonel POS Çözümü
                    </div>
                    <h1 class="text-5xl lg:text-8xl font-black text-slate-950 tracking-tighter leading-[0.88] mb-6 font-display">
                        Restoran Yönetiminde<br><span class="text-brand-500">Yeni Standart.</span>
                    </h1>
                    <p class="text-lg text-slate-500 font-medium max-w-xl mx-auto leading-relaxed mb-10">
                        Hızlı, kararlı ve modern. Tek ekrandan masaları, siparişleri, raporları ve personeli yönetin.
                    </p>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="{{ route('pages.pos.versions') }}" class="px-10 py-4 bg-slate-950 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-brand-500 transition-all shadow-2xl shadow-slate-950/20 flex items-center gap-3">
                            <i class="fab fa-windows"></i> Terminali İndir
                        </a>
                        <a href="/business-partner" class="px-10 py-4 bg-white border border-slate-200 text-slate-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">
                            Demo Talep Et
                        </a>
                    </div>
                </div>

                <!-- Terminal Mockup -->
                <div class="relative z-10 max-w-5xl mx-auto floating">
                    <div class="bg-white border-8 border-slate-100 rounded-[2.5rem] shadow-[0_40px_100px_-20px_rgba(0,0,0,0.15)] overflow-hidden">
                        <!-- Title bar -->
                        <div class="h-10 bg-slate-50 border-b border-slate-100 px-5 flex items-center justify-between">
                            <div class="flex items-center gap-5">
                                <div class="flex gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-slate-200"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-slate-200"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-slate-200"></div>
                                </div>
                                <div class="flex items-center gap-2 text-slate-400">
                                    <i class="fas fa-terminal text-[8px]"></i>
                                    <span class="text-[9px] font-black uppercase tracking-widest">RezerVistA POS v4.5</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-lg">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                <span class="text-[8px] font-black text-emerald-600 uppercase tracking-widest">Canlı</span>
                            </div>
                        </div>
                        <!-- App body -->
                        <div class="flex h-[480px] lg:h-[560px]">
                            <!-- Sidebar -->
                            <div class="w-14 bg-white border-r border-slate-100 py-5 flex flex-col items-center gap-4">
                                <div class="w-8 h-8 bg-brand-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/30 relative">
                                    <i class="fas fa-desktop text-xs"></i>
                                    <div class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-rose-500 border border-white rounded-full"></div>
                                </div>
                                <div class="w-8 h-8 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:text-brand-500 transition-all cursor-pointer"><i class="fas fa-utensils text-xs"></i></div>
                                <div class="w-8 h-8 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:text-brand-500 transition-all cursor-pointer"><i class="fas fa-chart-bar text-xs"></i></div>
                                <div class="w-8 h-8 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:text-brand-500 transition-all cursor-pointer"><i class="fas fa-file-invoice text-xs"></i></div>
                                <div class="w-8 h-8 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:text-brand-500 transition-all cursor-pointer"><i class="fas fa-box text-xs"></i></div>
                                <div class="mt-auto w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-400"><i class="fas fa-user-tie text-xs"></i></div>
                            </div>
                            <!-- Main area -->
                            <div class="flex-1 flex flex-col bg-white">
                                <!-- Tabs -->
                                <div class="px-5 py-3 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                                    <div class="flex gap-1.5">
                                        <button class="px-3 py-1.5 bg-slate-950 text-white rounded-lg text-[9px] font-black">ANA SALON</button>
                                        <button class="px-3 py-1.5 bg-white text-slate-400 border border-slate-100 rounded-lg text-[9px] font-black">VIP ROOM</button>
                                        <button class="px-3 py-1.5 bg-white text-slate-400 border border-slate-100 rounded-lg text-[9px] font-black">TERAS</button>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="relative">
                                            <input type="text" placeholder="Masa ara..." class="bg-white border border-slate-200 rounded-lg px-7 py-1.5 text-[9px] w-36 focus:outline-none focus:border-brand-500 transition-colors">
                                            <i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[8px]"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- Table grid -->
                                <div class="flex-1 p-4 overflow-hidden bg-slate-50/10">
                                    <div class="grid grid-cols-6 gap-2 h-full">
                                        <template x-for="n in 24">
                                            <div class="aspect-square bg-white border rounded-xl p-2 flex flex-col justify-between cursor-pointer transition-all"
                                                 :class="[2,7,11,16].includes(n) ? 'border-rose-100 bg-rose-50/30' : (n===3 ? 'bg-brand-500 border-brand-500 shadow-md shadow-brand-500/30 scale-105' : 'border-slate-100 hover:border-brand-200')">
                                                <span class="text-[7px] font-black uppercase tracking-widest leading-none"
                                                      :class="n===3 ? 'text-white/60' : ([2,7,11,16].includes(n) ? 'text-rose-400' : 'text-emerald-500')"
                                                      x-text="n===3 ? 'SEL' : ([2,7,11,16].includes(n) ? 'OCC' : 'RDY')"></span>
                                                <div class="text-center">
                                                    <span class="text-lg font-black font-display leading-none"
                                                          :class="n===3 ? 'text-white' : 'text-slate-900'"
                                                          x-text="n<10?'0'+n:n"></span>
                                                </div>
                                                <span class="text-[7px] font-black text-center block"
                                                      :class="n===3?'text-white':([2,7,11,16].includes(n)?'text-slate-600':'text-transparent')"
                                                      x-text="[2,7,11,16].includes(n) ? '₺'+(n*180) : '-'"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <!-- Receipt panel -->
                            <div class="w-56 border-l border-slate-100 flex flex-col bg-white">
                                <div class="px-4 py-3 border-b border-slate-50">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[9px] font-black text-slate-900 uppercase tracking-wider">Sipariş</span>
                                        <span class="px-2 py-0.5 bg-brand-500 text-white rounded text-[7px] font-black">M-03</span>
                                    </div>
                                    <p class="text-[8px] text-slate-400 font-semibold">4 Kişi • Ahmet B.</p>
                                </div>
                                <div class="flex-1 px-4 py-3 space-y-3 overflow-hidden">
                                    <div class="flex justify-between items-center">
                                        <div><p class="text-[9px] font-black text-slate-900">Ribeye Steak x2</p><p class="text-[7px] text-brand-500 font-bold">Med. Rare</p></div>
                                        <span class="text-[9px] font-black text-slate-900">₺1,840</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div><p class="text-[9px] font-black text-slate-900">Caesar Salad x1</p><p class="text-[7px] text-slate-400 italic">No Anchovy</p></div>
                                        <span class="text-[9px] font-black text-slate-900">₺220</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div><p class="text-[9px] font-black text-slate-900">Chardonnay x3</p></div>
                                        <span class="text-[9px] font-black text-slate-900">₺3,450</span>
                                    </div>
                                </div>
                                <div class="px-4 py-3 bg-slate-50/60 border-t border-slate-100">
                                    <div class="flex justify-between text-[8px] font-bold text-slate-400 mb-1"><span>Ara Toplam</span><span>₺5,510</span></div>
                                    <div class="flex justify-between text-[8px] font-bold text-slate-400 mb-3"><span>KDV %18</span><span>₺991</span></div>
                                    <div class="flex justify-between items-baseline mb-3">
                                        <span class="text-[9px] font-black text-slate-900 uppercase">Toplam</span>
                                        <span class="text-base font-black text-slate-900 font-display">₺6,501<span class="text-brand-500 text-xs">.80</span></span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-1.5">
                                        <button class="py-2 bg-white border border-slate-200 text-slate-900 rounded-lg text-[8px] font-black hover:bg-slate-50 transition-all">Böl</button>
                                        <button class="py-2 bg-brand-500 text-white rounded-lg text-[8px] font-black hover:bg-brand-600 transition-all flex items-center justify-center gap-1">
                                            <i class="fas fa-credit-card text-[7px]"></i> Öde
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Status bar -->
                        <div class="h-7 bg-slate-950 px-5 flex items-center justify-between">
                            <div class="flex items-center gap-5">
                                <div class="flex items-center gap-1.5"><div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div><span class="text-[8px] text-white/40 font-black uppercase tracking-widest">Local: OK</span></div>
                                <div class="flex items-center gap-1.5"><div class="w-1.5 h-1.5 rounded-full bg-brand-400"></div><span class="text-[8px] text-white/40 font-black uppercase tracking-widest">Cloud: OK</span></div>
                            </div>
                            <span class="text-[8px] text-white/20 font-black uppercase tracking-widest">AES-256 Şifreli</span>
                        </div>
                    </div>
                    <!-- Floating stat bubble -->
                    <div class="absolute -bottom-6 -left-8 bg-white border border-slate-100 rounded-2xl px-6 py-4 shadow-xl flex items-center gap-4">
                        <div class="w-10 h-10 bg-brand-50 rounded-xl flex items-center justify-center"><i class="fas fa-chart-line text-brand-500"></i></div>
                        <div><p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Günlük Ciro</p><p class="text-xl font-black text-slate-900 font-display">₺24,840<span class="text-emerald-500 text-xs ml-2 font-bold">+18%</span></p></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="py-32 bg-white border-t border-slate-50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl lg:text-6xl font-black text-slate-950 tracking-tighter font-display">Neden <span class="text-brand-500">RezerVistA?</span></h2>
                    <p class="text-slate-400 text-lg mt-4 font-medium">İşletmenizin ihtiyaç duyduğu her şey tek bir sistemde.</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="feature-card bg-white border border-slate-100 rounded-3xl p-8 shadow-sm">
                        <div class="w-14 h-14 bg-brand-50 border border-brand-100 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-bolt text-brand-500 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-950 mb-3">Ultra Hızlı</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">15ms maksimum yanıt süresi. İnternet bağlantısı kesilse bile yerel ağda tam hızda çalışmaya devam eder.</p>
                        <div class="mt-6 px-4 py-2 bg-brand-50 rounded-xl inline-block">
                            <span class="text-2xl font-black text-brand-500 font-display">15ms</span>
                            <span class="text-xs text-slate-400 font-bold ml-2 uppercase tracking-widest">Latency</span>
                        </div>
                    </div>
                    <div class="feature-card bg-white border border-slate-100 rounded-3xl p-8 shadow-sm">
                        <div class="w-14 h-14 bg-brand-50 border border-brand-100 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-th text-brand-500 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-950 mb-3">Masa Yönetimi</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">Tüm masaları tek ekrandan görün. Masa birleştirme, transfer, bekleme listesi ve oturma planı — hepsi dahil.</p>
                        <div class="grid grid-cols-4 gap-1.5 mt-6">
                            <template x-for="n in 8">
                                <div class="aspect-square rounded-lg border text-center flex items-center justify-center text-[9px] font-black"
                                     :class="[2,5].includes(n) ? 'bg-brand-500 text-white border-brand-500' : 'bg-slate-50 text-slate-400 border-slate-100'"
                                     x-text="n < 10 ? '0'+n : n"></div>
                            </template>
                        </div>
                    </div>
                    <div class="feature-card bg-white border border-slate-100 rounded-3xl p-8 shadow-sm">
                        <div class="w-14 h-14 bg-brand-50 border border-brand-100 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-chart-bar text-brand-500 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-950 mb-3">Anlık Raporlama</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">Günlük, haftalık ve aylık satış raporları. Personel bazlı performans, en çok satan ürünler, PDF/Excel çıktısı.</p>
                        <div class="flex items-end gap-1.5 mt-6 h-12">
                            <div class="flex-1 bg-brand-100 rounded-sm" style="height:40%"></div>
                            <div class="flex-1 bg-brand-200 rounded-sm" style="height:65%"></div>
                            <div class="flex-1 bg-brand-300 rounded-sm" style="height:50%"></div>
                            <div class="flex-1 bg-brand-500 rounded-sm" style="height:90%"></div>
                            <div class="flex-1 bg-brand-400 rounded-sm" style="height:75%"></div>
                            <div class="flex-1 bg-brand-500 rounded-sm" style="height:100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats row -->
        <section class="py-20 bg-brand-500">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-black text-white font-display">99.9%</div>
                    <div class="text-brand-200 text-sm font-bold mt-1 uppercase tracking-widest">Uptime SLA</div>
                </div>
                <div>
                    <div class="text-4xl font-black text-white font-display">15ms</div>
                    <div class="text-brand-200 text-sm font-bold mt-1 uppercase tracking-widest">Maks. Gecikme</div>
                </div>
                <div>
                    <div class="text-4xl font-black text-white font-display">500+</div>
                    <div class="text-brand-200 text-sm font-bold mt-1 uppercase tracking-widest">İşletme</div>
                </div>
                <div>
                    <div class="text-4xl font-black text-white font-display">7/24</div>
                    <div class="text-brand-200 text-sm font-bold mt-1 uppercase tracking-widest">Destek</div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-40 bg-white text-center border-t border-slate-50">
            <div class="max-w-4xl mx-auto px-6">
                <h2 class="text-5xl lg:text-8xl font-black text-slate-950 tracking-tighter font-display mb-8">Hemen <span class="text-brand-500">Başlayın.</span></h2>
                <p class="text-xl text-slate-400 font-medium mb-12">Dakikalar içinde kurulum. İlk ay ücretsiz deneyin.</p>
                <div class="flex flex-wrap gap-5 justify-center">
                    <a href="{{ route('pages.pos.versions') }}" class="px-14 py-5 bg-brand-500 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-brand-600 transition-all shadow-2xl shadow-brand-500/30 flex items-center gap-4">
                        <i class="fab fa-windows text-lg"></i> Terminali İndir
                    </a>
                    <a href="/register" class="px-14 py-5 bg-white border border-slate-200 text-slate-900 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-slate-50 transition-all">
                        Ücretsiz Hesap Aç
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-100 py-12 bg-white">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-brand-500 rounded-lg flex items-center justify-center text-white"><i class="fas fa-bolt text-xs"></i></div>
                <span class="font-black text-slate-900 tracking-tighter font-display">RezerVistA POS</span>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">© {{ date('Y') }} RezerVist. Tüm Hakları Saklıdır.</p>
        </div>
    </footer>
</div>


<script>
function posIntro() {
    return {
        scene: 0,
        done: false,
        progress: 0,
        totalScenes: 7,
        timer: null,
        progressTimer: null,

        // Scene durations in ms
        durations: [2000, 2200, 2500, 2800, 2800, 2800, 3500],

        start() {
            this.runScene(0);
        },

        runScene(index) {
            if (index >= this.totalScenes) { this.finish(); return; }
            this.scene = index;
            const duration = this.durations[index];
            const startProgress = (index / this.totalScenes) * 100;
            const endProgress = ((index + 1) / this.totalScenes) * 100;

            // Animate progress bar smoothly
            clearInterval(this.progressTimer);
            const steps = 60;
            const stepTime = duration / steps;
            const stepSize = (endProgress - startProgress) / steps;
            let step = 0;
            this.progress = startProgress;
            this.progressTimer = setInterval(() => {
                step++;
                this.progress = startProgress + (stepSize * step);
                if (step >= steps) clearInterval(this.progressTimer);
            }, stepTime);

            // Advance to next scene
            clearTimeout(this.timer);
            this.timer = setTimeout(() => this.runScene(index + 1), duration);
        },

        skip() {
            clearTimeout(this.timer);
            clearInterval(this.progressTimer);
            this.progress = 100;
            setTimeout(() => this.finish(), 300);
        },

        finish() {
            clearTimeout(this.timer);
            clearInterval(this.progressTimer);
            this.done = true;
            document.body.style.overflow = '';
            window.scrollTo(0,0);
        }
    }
}
</script>
</body>
</html>
