<!DOCTYPE html>
<html lang="<?php echo str_replace('_', '-', app()->getLocale()); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    
    <title>RezerVistA POS | Yetenekli, Hızlı, Keskin.</title>
    
    <!-- Premium Global Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#F5F3FF',
                            100: '#EDE9FE',
                            500: '#6200EE',
                            600: '#5000D3',
                            700: '#4c00b0',
                        },
                        slate: {
                            50: '#F8FAFC',
                            100: '#F1F5F9',
                            200: '#E2E8F0',
                            300: '#CBD5E1',
                            400: '#94A3B8',
                            500: '#64748B',
                            600: '#475569',
                            700: '#334155',
                            800: '#1E293B',
                            900: '#0F172A',
                            950: '#020617',
                        }
                    },
                    boxShadow: {
                        'premium': '0 20px 50px -12px rgba(0, 0, 0, 0.08)',
                        'inner-subtle': 'inset 0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                        'card': '0 10px 30px -5px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02)',
                        'pos': '0 40px 100px -20px rgba(0, 0, 0, 0.15), 0 20px 40px -15px rgba(0, 0, 0, 0.1)',
                    }
                }
            }
        }
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { 
            background-color: #FFFFFF;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .dot-pattern {
            background-image: radial-gradient(#CBD5E1 0.7px, transparent 0.7px);
            background-size: 32px 32px;
        }
        .pos-window {
            background: #FFFFFF;
            border: 8px solid #F1F5F9;
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.15);
        }
        .text-gradient {
            background: linear-gradient(135deg, #020617 0%, #475569 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        @keyframes subtle-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .animate-subtle-float {
            animation: subtle-float 6s ease-in-out infinite;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    </style>
</head>
<body class="text-slate-600 font-sans selection:bg-brand-500/10 selection:text-brand-600">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 border-b border-slate-100 bg-white/90 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-4 group">
                <div class="w-10 h-10 bg-brand-500 rounded-xl flex items-center justify-center text-white shadow-xl shadow-brand-500/30">
                    <i class="fas fa-bolt"></i>
                </div>
                <span class="text-xl font-black text-slate-900 tracking-tighter">RezerVist<span class="text-brand-500">A</span></span>
            </a>
            <div class="flex items-center gap-10">
                <a href="/" class="text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-brand-500 transition-colors">Ana Sayfa</a>
                <a href="/login" class="px-8 py-3 bg-slate-950 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-brand-500 transition-all shadow-lg shadow-slate-950/20">Giriş Yap</a>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section: High-Fidelity Detailing -->
        <section class="relative pt-40 pb-32 lg:pt-56 lg:pb-72 dot-pattern overflow-hidden">
            <div class="absolute inset-x-0 bottom-0 h-96 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
            
            <div class="max-w-7xl mx-auto px-6 relative z-10 grid lg:grid-cols-12 gap-16 items-center">
                
                <!-- Hero Content -->
                <div class="lg:col-span-5 text-center lg:text-left">
                    <div class="inline-flex items-center gap-3 px-4 py-2 bg-brand-50 border border-brand-100 rounded-xl text-[10px] font-black tracking-[0.2em] text-brand-500 uppercase mb-10">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                        </span>
                        Enterprise Grade Console
                    </div>
                    
                    <h1 class="text-6xl lg:text-8xl font-black text-slate-950 tracking-tighter leading-[0.9] mb-10 font-display">
                        <span class="text-gradient">Sahayı Siz</span> <br>
                        <span class="text-brand-500 italic">Yönetin.</span>
                    </h1>
                    
                    <p class="text-lg text-slate-500 font-medium leading-relaxed mb-12 max-w-xl mx-auto lg:mx-0">
                        Hantallıktan arındırılmış, milisaniyelik hızlarla çalışan dünyanın en gelişmiş bulut tabanlı POS ekosistemine hoş geldiniz.
                    </p>
                    
                    <div class="flex flex-wrap gap-5 justify-center lg:justify-start">
                        <a href="<?php echo route('pages.pos.versions'); ?>" class="px-12 py-5 bg-slate-950 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-brand-500 transition-all shadow-2xl shadow-slate-950/20 flex items-center gap-4 group">
                            <i class="fab fa-windows text-lg"></i> VERSİYONLARI İNDİR
                        </a>
                        <a href="/business-partner" class="px-12 py-5 bg-white border border-slate-200 text-slate-900 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-50 transition-all flex items-center gap-4">
                            DEMO İZLE
                        </a>
                    </div>
                </div>

                <!-- Ultra-Detailed POS Window Mockup -->
                <div class="lg:col-span-7 relative">
                    <div class="pos-window rounded-[3rem] p-3 animate-subtle-float">
                        <div class="bg-white rounded-[2.5rem] h-[580px] overflow-hidden flex flex-col border border-slate-100 shadow-inner-subtle relative">
                            
                            <!-- Window Title Bar -->
                            <div class="h-14 bg-slate-50/50 border-b border-slate-100 px-8 flex items-center justify-between">
                                <div class="flex items-center gap-8">
                                    <div class="flex gap-2">
                                        <div class="w-2.5 h-2.5 rounded-full bg-slate-300"></div>
                                        <div class="w-2.5 h-2.5 rounded-full bg-slate-300"></div>
                                        <div class="w-2.5 h-2.5 rounded-full bg-slate-200"></div>
                                    </div>
                                    <div class="h-4 w-px bg-slate-200"></div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">RezerVistA Terminal v4.2.0</span>
                                </div>
                                <div class="flex items-center gap-5">
                                    <div class="flex items-center gap-2 px-3 py-1 bg-white border border-slate-200 rounded-lg">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                        <span class="text-[9px] font-black text-slate-500">CLOUD SYNC</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400">14:22:05</span>
                                </div>
                            </div>
                            
                            <div class="flex-1 flex overflow-hidden">
                                <!-- POS Sidebar: Professional Sidebar -->
                                <div class="w-20 bg-white border-r border-slate-100 py-8 flex flex-col items-center gap-8">
                                    <div class="w-11 h-11 bg-brand-500 text-white rounded-2xl flex items-center justify-center text-xl shadow-lg shadow-brand-500/20">
                                        <i class="fas fa-th-large"></i>
                                    </div>
                                    <div class="w-11 h-11 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center text-lg hover:text-brand-500 transition-colors">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <div class="w-11 h-11 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center text-lg hover:text-brand-500 transition-colors">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <div class="w-11 h-11 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center text-lg hover:text-brand-500 transition-colors">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <div class="mt-auto mb-2">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 border border-slate-200">
                                            <i class="fas fa-user text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- POS Main View: Detailed Floor Plan -->
                                <div class="flex-1 flex flex-col bg-white">
                                    <!-- Floor Selector & Search -->
                                    <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                                        <div class="flex gap-1 bg-slate-100 p-1 rounded-xl">
                                            <button class="px-4 py-2 bg-white text-slate-900 rounded-lg text-[10px] font-black shadow-sm">ZEMİN KAT</button>
                                            <button class="px-4 py-2 text-slate-500 rounded-lg text-[10px] font-black">TERAS</button>
                                            <button class="px-4 py-2 text-slate-500 rounded-lg text-[10px] font-black">BAHÇE</button>
                                        </div>
                                        <div class="relative">
                                            <input type="text" placeholder="Masa ara..." class="bg-slate-50 border border-slate-200 rounded-xl px-10 py-2.5 text-[10px] w-40 focus:outline-none">
                                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Table Grid -->
                                    <div class="flex-1 p-6 overflow-y-auto custom-scrollbar">
                                        <div class="grid grid-cols-4 gap-4">
                                            <!-- Masa 1: Active -->
                                            <div class="aspect-square bg-slate-50 border border-rose-100 rounded-2xl p-4 flex flex-col justify-between group cursor-pointer hover:scale-[1.02] transition-transform">
                                                <div class="flex justify-between items-start">
                                                    <span class="text-[9px] font-black text-rose-500 uppercase">Dolu</span>
                                                    <span class="text-[8px] font-bold text-slate-400 italic">42 dk</span>
                                                </div>
                                                <div class="text-center">
                                                    <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Masa</p>
                                                    <p class="text-2xl font-black text-slate-900">01</p>
                                                </div>
                                                <div class="text-center font-black text-slate-800 text-xs">₺420</div>
                                            </div>

                                            <!-- Masa 2: Selected -->
                                            <div class="aspect-square bg-brand-500 border border-brand-500 rounded-2xl p-4 flex flex-col justify-between shadow-xl shadow-brand-500/20 transform scale-[1.05]">
                                                <div class="flex justify-between items-start">
                                                    <span class="text-[9px] font-black text-white/60 uppercase">Aktif</span>
                                                    <i class="fas fa-hand-pointer text-[10px] text-white/50"></i>
                                                </div>
                                                <div class="text-center text-white">
                                                    <p class="text-[10px] font-black uppercase opacity-60 mb-1">Masa</p>
                                                    <p class="text-2xl font-black">02</p>
                                                </div>
                                                <div class="text-center font-black text-white text-xs">₺810</div>
                                            </div>

                                            <template x-for="i in [3,4,5,6,7,8,9,10,11,12]">
                                                <div class="aspect-square border border-slate-100 rounded-2xl p-4 flex flex-col justify-between hover:bg-slate-50 transition-colors">
                                                    <span class="text-[9px] font-black" :class="i == 5 ? 'text-amber-500' : 'text-emerald-500'" x-text="i == 5 ? 'ÖDEME' : 'BOŞ'"></span>
                                                    <div class="text-center">
                                                        <p class="text-[10px] font-black text-slate-300 uppercase mb-1">Masa</p>
                                                        <p class="text-2xl font-black text-slate-900" x-text="i < 10 ? '0'+i : i"></p>
                                                    </div>
                                                    <div class="text-center font-black text-[10px]" :class="i == 5 ? 'text-slate-800' : 'text-transparent'" x-text="i == 5 ? '₺1,240' : '₺0'"></div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- POS Order: Ultra-Detailed Receipt -->
                                <div class="w-72 bg-white border-l border-slate-100 flex flex-col p-6">
                                    <div class="mb-8 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Masa 02 Sipariş</span>
                                        </div>
                                        <div class="text-[9px] font-bold text-slate-400">Garson: Ahmet Arda</div>
                                    </div>
                                    
                                    <div class="flex-1 space-y-5 overflow-y-auto custom-scrollbar">
                                        <div class="flex justify-between items-start">
                                            <div class="flex gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 text-[10px] font-black">x1</div>
                                                <div>
                                                    <p class="text-[11px] font-black text-slate-900">Antrikot Izgara</p>
                                                    <p class="text-[9px] text-brand-500 font-bold">Orta Pişmiş</p>
                                                </div>
                                            </div>
                                            <p class="text-[11px] font-black text-slate-900">₺450</p>
                                        </div>
                                        <div class="flex justify-between items-start">
                                            <div class="flex gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 text-[10px] font-black">x1</div>
                                                <div>
                                                    <p class="text-[11px] font-black text-slate-900">Sezar Salata</p>
                                                </div>
                                            </div>
                                            <p class="text-[11px] font-black text-slate-900">₺220</p>
                                        </div>
                                        <div class="flex justify-between items-start">
                                            <div class="flex gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 text-[10px] font-black">x2</div>
                                                <div>
                                                    <p class="text-[11px] font-black text-slate-900">Coca Cola</p>
                                                </div>
                                            </div>
                                            <p class="text-[11px] font-black text-slate-900">₺140</p>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-6 border-t border-slate-100 mt-6">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ara Toplam</span>
                                            <span class="text-xs font-bold text-slate-900">₺810,00</span>
                                        </div>
                                        <div class="flex justify-between items-center mb-6">
                                            <span class="text-xs font-black text-slate-900 uppercase">Genel Toplam</span>
                                            <span class="text-2xl font-black text-slate-950">₺810,00</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <button class="py-4 bg-slate-100 text-slate-900 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">Kalan Borç</button>
                                            <button class="py-4 bg-brand-500 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl shadow-brand-500/20 hover:bg-brand-600 transition-colors">ÖDEME AL</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- POS Status Bar -->
                            <div class="h-8 bg-slate-950 px-8 flex items-center justify-between text-[8px] font-black tracking-widest text-white/40">
                                <div class="flex items-center gap-6">
                                    <span class="flex items-center gap-2"><i class="fas fa-print text-emerald-500"></i> MUTFAK YAZICI: HAZIR</span>
                                    <span class="flex items-center gap-2"><i class="fas fa-database text-brand-500"></i> DB SYNC: OK</span>
                                </div>
                                <span class="uppercase">SESSION: POS_WK_2408</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating High-Contrast Revenue Badge -->
                    <div class="absolute -bottom-12 -left-12 bg-white p-8 rounded-[2.5rem] shadow-premium border border-slate-100 flex items-center gap-8 z-30 group hover:-translate-y-2 transition-transform duration-500">
                        <div class="w-16 h-16 bg-slate-950 text-brand-500 rounded-3xl flex items-center justify-center text-3xl shadow-2xl transition-transform group-hover:rotate-12">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">REAL-TIME REVENUE</p>
                            <div class="flex items-baseline gap-3">
                                <p class="text-3xl font-black text-slate-950">₺74,820</p>
                                <span class="text-xs font-black text-emerald-500">+24.5%</span>
                            </div>
                            <div class="mt-3 w-32 h-1 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-brand-500 w-[85%]"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Detailing Feature Grid: Technical Luxe -->
        <section class="py-32 bg-white border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid lg:grid-cols-3 gap-10">
                    <!-- Feature Detail 1: Speed -->
                    <div class="p-12 rounded-[3.5rem] bg-slate-50 border border-slate-100 flex flex-col group hover:bg-white hover:border-brand-500/20 transition-all duration-500">
                        <div class="w-20 h-20 bg-white border border-slate-200 rounded-3xl flex items-center justify-center text-3xl mb-12 shadow-inner-subtle text-brand-500 group-hover:bg-brand-500 group-hover:text-white transition-all">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-950 uppercase tracking-tighter mb-4 italic">Milisaniyelik Tepki</h3>
                        <p class="text-slate-500 font-medium leading-relaxed mb-10 flex-1">Arayüz geçişleri ve veri işleme süreçleri 15ms'nin altında gerçekleşir. Beklemek işletmenizin doğasında yok.</p>
                        <!-- Tech Visual: Mini chart -->
                        <div class="flex items-end gap-1 h-12">
                            <div class="w-full bg-brand-500/20 rounded-t-sm h-[40%]"></div>
                            <div class="w-full bg-brand-500/20 rounded-t-sm h-[60%]"></div>
                            <div class="w-full bg-brand-500/20 rounded-t-sm h-[30%]"></div>
                            <div class="w-full bg-brand-500 rounded-t-sm h-[90%]"></div>
                            <div class="w-full bg-brand-500/20 rounded-t-sm h-[50%]"></div>
                        </div>
                    </div>

                    <!-- Feature Detail 2: Hybrid -->
                    <div class="p-12 rounded-[3.5rem] bg-slate-50 border border-slate-100 flex flex-col group hover:bg-white hover:border-emerald-500/20 transition-all duration-500">
                        <div class="w-20 h-20 bg-white border border-slate-200 rounded-3xl flex items-center justify-center text-3xl mb-12 shadow-inner-subtle text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                            <i class="fas fa-cloud-sun"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-950 uppercase tracking-tighter mb-4 italic">Kuvvetli Hibrit</h3>
                        <p class="text-slate-500 font-medium leading-relaxed mb-10 flex-1">İnternet kesintilerinde bile mutfak ve kasa arasındaki iletişim kesilmez. Yerel ağ üzerinden kusursuz çalışmaya devam edin.</p>
                        <!-- Tech Visual: Toggle mockup -->
                        <div class="flex items-center gap-3 bg-white p-3 rounded-2xl border border-slate-200">
                            <div class="w-8 h-4 bg-emerald-500 rounded-full relative">
                                <div class="absolute right-0.5 top-0.5 w-3 h-3 bg-white rounded-full shadow-sm"></div>
                            </div>
                            <span class="text-[9px] font-black text-slate-400 uppercase">Hybrid Mode: Active</span>
                        </div>
                    </div>

                    <!-- Feature Detail 3: Intelligence -->
                    <div class="p-12 rounded-[3.5rem] bg-slate-50 border border-slate-100 flex flex-col group hover:bg-white hover:border-slate-950/20 transition-all duration-500">
                        <div class="w-20 h-20 bg-white border border-slate-200 rounded-3xl flex items-center justify-center text-3xl mb-12 shadow-inner-subtle text-slate-800 group-hover:bg-slate-950 group-hover:text-white transition-all">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-950 uppercase tracking-tighter mb-4 italic">İleri Analitik</h3>
                        <p class="text-slate-500 font-medium leading-relaxed mb-10 flex-1">Hangi ürün ne kadar satıyor, hangi saatlerde yoğunluk var? Tüm bu soruların cevabı saniyeler içinde önünüzde.</p>
                        <!-- Tech Visual: Percentage indicators -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white p-3 rounded-xl border border-slate-200">
                                <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Stock Flux</p>
                                <p class="text-xs font-black text-slate-900">+12%</p>
                            </div>
                            <div class="bg-white p-3 rounded-xl border border-slate-200">
                                <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Accuracy</p>
                                <p class="text-xs font-black text-slate-900">100%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Enterprise Philosophy Section: Pure Luxe -->
        <section class="py-48 bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 relative">
                <div class="flex flex-col lg:flex-row items-center gap-24 relative z-10">
                    <div class="lg:w-1/2">
                        <h2 class="text-6xl lg:text-8xl font-black text-slate-950 tracking-tighter leading-[0.9] mb-12 uppercase italic">Sınırları <br> <span class="text-brand-500">Kaldırın.</span></h2>
                        <p class="text-xl text-slate-500 font-medium leading-relaxed mb-16">
                            Profesyonel bir işletmenin omurgasını oluşturan en ince teknik detaylara kadar sahip çıkıyoruz. Karmaşıklığı biz yönetiyoruz, siz sadece işinizi büyütüyorsunuz.
                        </p>
                        <div class="grid grid-cols-2 gap-12">
                            <div class="space-y-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">Uptime</p>
                                <p class="text-5xl font-black text-slate-950 tracking-tighter italic">99.9<span class="text-brand-500">%</span></p>
                            </div>
                            <div class="space-y-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">Security</p>
                                <p class="text-5xl font-black text-slate-950 tracking-tighter italic">AES</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="lg:w-1/2 w-full">
                        <!-- Ultra-Premium Data Widget -->
                        <div class="bg-slate-950 rounded-[4rem] p-16 text-white shadow-2xl relative group overflow-hidden">
                            <div class="absolute inset-0 bg-brand-500/5 group-hover:bg-brand-500/10 transition-colors pointer-events-none"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-20">
                                    <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center">
                                        <i class="fas fa-layer-group text-brand-500"></i>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="w-2 h-2 rounded-full bg-brand-500 shadow-[0_0_10px_#6200EE]"></div>
                                        <div class="w-2 h-2 rounded-full bg-white/10"></div>
                                    </div>
                                </div>
                                <h4 class="text-3xl font-black uppercase tracking-tight mb-6 italic italic">Global <br> Senkronİzasyon</h4>
                                <p class="text-slate-400 font-medium leading-relaxed mb-12 max-w-sm">Tüm şubelerinizin verileri anlık olarak buluta aktarılır ve tek bir merkezden eş zamanlı kontrol edilir.</p>
                                
                                <div class="flex items-center gap-10">
                                    <div class="relative w-24 h-24">
                                        <svg class="w-full h-full rotate-[-90deg]">
                                            <circle cx="48" cy="48" r="40" fill="transparent" stroke="rgba(255,255,255,0.05)" stroke-width="8"></circle>
                                            <circle cx="48" cy="48" r="40" fill="transparent" stroke="#6200EE" stroke-width="8" stroke-dasharray="251.2" stroke-dashoffset="30"></circle>
                                        </svg>
                                        <div class="absolute inset-0 flex items-center justify-center font-black text-sm italic">88%</div>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Efficiency Level</p>
                                        <p class="text-xs font-bold text-white uppercase italic">Optimal Performance</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- High-Stakes Closing CTA -->
        <section class="py-48 bg-slate-950 text-center relative overflow-hidden">
            <div class="absolute inset-0 dot-pattern opacity-10"></div>
            <div class="max-w-5xl mx-auto px-6 relative z-10">
                <h2 class="text-7xl lg:text-9xl font-black text-white tracking-tighter uppercase italic leading-[0.85] mb-20">Hazırsan <br> Bİz Buradayız.</h2>
                <div class="flex flex-col sm:flex-row justify-center gap-8">
                    <a href="<?php echo route('pages.pos.versions'); ?>" class="px-14 py-7 bg-brand-500 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] hover:bg-white hover:text-slate-950 hover:-translate-y-2 transition-all duration-500 shadow-2xl shadow-brand-500/30 flex items-center justify-center gap-5">
                        <i class="fab fa-windows text-2xl"></i> TERMİNALİ İNDİR
                    </a>
                    <a href="/register" class="px-14 py-7 bg-white text-slate-950 rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] hover:bg-slate-100 transition-all duration-500 shadow-2xl shadow-slate-950/20 flex items-center justify-center gap-5">
                        YENİ HESAP AÇ
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-white border-t border-slate-100 py-16">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <div class="flex items-center gap-4 text-slate-950">
                <div class="w-12 h-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center font-black text-sm">R</div>
                <div>
                    <span class="font-black text-lg tracking-tighter block leading-none">RezerVist</span>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em]">Engineering</span>
                </div>
            </div>
            <p class="md:text-right text-slate-400 text-[10px] font-black uppercase tracking-[0.6em]">© <?php echo date('Y'); ?> REZERVIST. ALL RIGHTS RESERVED.</p>
        </div>
    </footer>

</body>
</html>
