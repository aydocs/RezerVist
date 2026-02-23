<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
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
                        'pos-large': '0 60px 150px -30px rgba(0, 0, 0, 0.2), 0 30px 60px -20px rgba(0, 0, 0, 0.15)',
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
        }
        .text-gradient {
            background: linear-gradient(135deg, #020617 0%, #475569 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        @keyframes subtle-float {
            0%, 100% { transform: translateY(0) rotateX(0deg); }
            50% { transform: translateY(-12px) rotateX(1deg); }
        }
        .animate-subtle-float {
            animation: subtle-float 8s ease-in-out infinite;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
        
        /* Boot Animation */
        [x-cloak] { display: none !important; }
        .reveal-up {
            animation: revealUp 1.2s cubic-bezier(0.19, 1, 0.22, 1) forwards;
        }
        @keyframes revealUp {
            from { opacity: 0; transform: translateY(40px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>
</head>
<body class="text-slate-600 font-sans selection:bg-brand-500/10 selection:text-brand-600" x-data="{ booted: false }" x-init="setTimeout(() => booted = true, 500)">

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
        <!-- Immersive Hero Section -->
        <section class="relative min-h-screen pt-40 pb-32 dot-pattern overflow-hidden flex flex-col items-center">
            <div class="absolute inset-x-0 bottom-0 h-96 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
            
            <div class="max-w-7xl mx-auto px-6 relative z-10 w-full text-center mb-16" x-show="booted" x-transition:enter="reveal-up">
                <div class="inline-flex items-center gap-3 px-4 py-2 bg-brand-50 border border-brand-100 rounded-xl text-[10px] font-black tracking-[0.3em] text-brand-500 uppercase mb-8">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                    </span>
                    Professional POS Environment
                </div>
                
                <h1 class="text-6xl lg:text-[10rem] font-black text-slate-950 tracking-tighter leading-[0.85] mb-8 font-display">
                    <span class="text-gradient">Teknolojiyle</span> <br>
                    <span class="text-brand-500 italic">Hükmedİn.</span>
                </h1>
                
                <p class="text-xl text-slate-500 font-medium leading-relaxed mb-12 max-w-2xl mx-auto">
                    Dünyanın en hızlı ve en kararlı bulut tabanlı POS arayüzü. Karmaşıklığı geride bırakın, işletmenizi milisaniyelik hızlarla yönetin.
                </p>

                <div class="flex flex-wrap gap-5 justify-center mb-24">
                    <a href="{{ route('pages.pos.versions') }}" class="px-14 py-6 bg-slate-950 text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] hover:bg-brand-500 transition-all shadow-2xl shadow-slate-950/20 flex items-center gap-4">
                        <i class="fab fa-windows text-xl"></i> TERMİNALİ İNDİR
                    </a>
                    <a href="/business-partner" class="px-14 py-6 bg-white border border-slate-200 text-slate-900 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] hover:bg-slate-50 transition-all">
                        DEMOYU BAŞLAT
                    </a>
                </div>
            </div>

            <!-- Ultra-Large Terminal Container -->
            <div class="w-full max-w-[1400px] px-6 relative z-20" x-show="booted" x-transition:enter="reveal-up" x-transition:enter-start="opacity-0 translate-y-20 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" style="transition-delay: 200ms;">
                <div class="pos-window rounded-[4rem] p-4 shadow-pos-large animate-subtle-float">
                    <!-- Main App Window -->
                    <div class="bg-white rounded-[3.5rem] h-[720px] lg:h-[840px] overflow-hidden flex flex-col border border-slate-100 shadow-inner-subtle relative">
                        
                        <!-- Window Title Bar: High Fidelity -->
                        <div class="h-16 bg-slate-50/50 border-b border-slate-100 px-10 flex items-center justify-between">
                            <div class="flex items-center gap-10">
                                <div class="flex gap-3">
                                    <div class="w-3.5 h-3.5 rounded-full bg-slate-300"></div>
                                    <div class="w-3.5 h-3.5 rounded-full bg-slate-300"></div>
                                    <div class="w-3.5 h-3.5 rounded-full bg-slate-200"></div>
                                </div>
                                <div class="h-6 w-px bg-slate-200"></div>
                                <div class="flex items-center gap-4 text-slate-400">
                                    <i class="fas fa-terminal text-[10px]"></i>
                                    <span class="text-[12px] font-black uppercase tracking-[0.4em]">RezerVistA Terminal Enterprise v4.5.12</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-8">
                                <div class="flex items-center gap-3 px-4 py-1.5 bg-emerald-50 border border-emerald-100 rounded-xl">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_#10b981]"></div>
                                    <span class="text-[10px] font-black text-emerald-600 tracking-widest uppercase">SYSCALL: SUCCESS</span>
                                </div>
                                <div class="flex items-center gap-3 text-slate-400">
                                    <i class="fas fa-satellite-dish text-xs"></i>
                                    <span class="text-[12px] font-bold">16:42:01 LOCAL</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex-1 flex overflow-hidden">
                            <!-- Detailed Sidebar -->
                            <div class="w-24 bg-white border-r border-slate-100 py-10 flex flex-col items-center gap-10">
                                <div class="w-14 h-14 bg-brand-500 text-white rounded-3xl flex items-center justify-center text-2xl shadow-2xl shadow-brand-500/40 relative">
                                    <i class="fas fa-desktop"></i>
                                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-3xl flex flex-col items-center justify-center gap-1 hover:text-brand-500 group transition-all">
                                    <i class="fas fa-utensils text-lg"></i>
                                    <span class="text-[7px] font-black uppercase tracking-tighter opacity-0 group-hover:opacity-100 transition-opacity">Floor</span>
                                </div>
                                <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-3xl flex flex-col items-center justify-center gap-1 hover:text-brand-500 group transition-all">
                                    <i class="fas fa-file-invoice-dollar text-lg"></i>
                                    <span class="text-[7px] font-black uppercase tracking-tighter opacity-0 group-hover:opacity-100 transition-opacity">Bills</span>
                                </div>
                                <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-3xl flex flex-col items-center justify-center gap-1 hover:text-brand-500 group transition-all">
                                    <i class="fas fa-chart-pie text-lg"></i>
                                    <span class="text-[7px] font-black uppercase tracking-tighter opacity-0 group-hover:opacity-100 transition-opacity">Stats</span>
                                </div>
                                <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-3xl flex flex-col items-center justify-center gap-1 hover:text-brand-500 group transition-all">
                                    <i class="fas fa-box text-lg"></i>
                                    <span class="text-[7px] font-black uppercase tracking-tighter opacity-0 group-hover:opacity-100 transition-opacity">Stock</span>
                                </div>
                                <div class="mt-auto mb-4">
                                    <div class="w-12 h-12 rounded-full border-2 border-slate-100 p-0.5">
                                        <div class="w-full h-full rounded-full bg-slate-200 flex items-center justify-center text-slate-400">
                                            <i class="fas fa-user-tie text-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Main Workspace: Massive Table Grid -->
                            <div class="flex-1 flex flex-col bg-white">
                                <!-- Top Navigation & Filtering -->
                                <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
                                    <div class="flex gap-2">
                                        <button class="px-6 py-3 bg-slate-950 text-white rounded-2xl text-[11px] font-black shadow-lg">ANA SALON</button>
                                        <button class="px-6 py-3 bg-white text-slate-400 border border-slate-100 rounded-2xl text-[11px] font-black hover:bg-slate-50 transition-colors">VIP ROOM</button>
                                        <button class="px-6 py-3 bg-white text-slate-400 border border-slate-100 rounded-2xl text-[11px] font-black hover:bg-slate-50 transition-colors">DIŞ ALAN</button>
                                        <button class="px-6 py-3 bg-white text-slate-400 border border-slate-100 rounded-2xl text-[11px] font-black hover:bg-slate-50 transition-colors">TERAS</button>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <input type="text" placeholder="Masa veya bekleyen ara..." class="bg-white border border-slate-200 rounded-2xl px-12 py-3.5 text-[11px] w-64 focus:border-brand-500 focus:outline-none transition-colors">
                                            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                        </div>
                                        <div class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 cursor-pointer">
                                            <i class="fas fa-sync-alt text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Extended Table Grid -->
                                <div class="flex-1 p-10 overflow-y-auto custom-scrollbar bg-slate-50/10">
                                    <div class="grid grid-cols-5 gap-6">
                                        <!-- Active Tables with Real Data -->
                                        <template x-for="i in 20">
                                            <div class="aspect-square bg-white border rounded-[2rem] p-6 flex flex-col justify-between group cursor-pointer transition-all duration-300 shadow-sm"
                                                 :class="[1, 4, 7, 12, 18].includes(i) ? 'border-rose-100 bg-rose-50/10 shadow-rose-100/50' : (i == 2 ? 'bg-brand-500 border-brand-500 shadow-2xl shadow-brand-500/40 transform scale-[1.08] z-10' : 'border-slate-100 hover:border-brand-500/30 hover:shadow-xl hover:shadow-brand-500/5')">
                                                
                                                <div class="flex justify-between items-start">
                                                    <span class="text-[10px] font-black uppercase tracking-widest" :class="i == 2 ? 'text-white/60' : ([1,4,7,12,18].includes(i) ? 'text-rose-500' : 'text-emerald-500')" x-text="i == 2 ? 'SELECTED' : ([1,4,7,12,18].includes(i) ? 'OCCUPIED' : 'READY')"></span>
                                                    <span class="text-[9px] font-bold italic" :class="i == 2 ? 'text-white/40' : 'text-slate-300'" x-text="[1,4,7,12,18].includes(i) ? (i*3 + 'min') : ''"></span>
                                                </div>
                                                
                                                <div class="text-center">
                                                    <p class="text-[11px] font-black uppercase tracking-widest mb-1" :class="i == 2 ? 'text-white/40' : 'text-slate-300'">TABLE</p>
                                                    <p class="text-4xl font-black font-display" :class="i == 2 ? 'text-white' : 'text-slate-950'" x-text="i < 10 ? '0'+i : i"></p>
                                                </div>
                                                
                                                <div class="text-center">
                                                    <div class="px-3 py-1 bg-black/5 rounded-full inline-block" :class="i == 2 ? 'bg-white/10' : ''">
                                                        <span class="text-xs font-black tracking-tighter" :class="i == 2 ? 'text-white' : ([1,4,7,12,18].includes(i) ? 'text-slate-800' : 'text-transparent')">
                                                            <span x-text="[1,4,7,12,18].includes(i) ? '₺' + (i * 240) : '—'"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Immersive Receipt Sidebar -->
                            <div class="w-96 bg-white border-l border-slate-100 flex flex-col shadow-inner-subtle">
                                <div class="p-10 border-b border-slate-50 bg-slate-50/20">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-xs font-black text-slate-950 uppercase tracking-[0.2em]">CURRENT ORDER</h4>
                                        <span class="px-3 py-1 bg-brand-500 text-white rounded-lg text-[9px] font-black">TABLE 02</span>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400">GUEST: 4 PERSONS • SERVER: MEHMET CAN</p>
                                </div>
                                
                                <div class="flex-1 p-10 space-y-8 overflow-y-auto custom-scrollbar">
                                    <!-- Order Items -->
                                    <div class="group flex justify-between items-start animate-pulse">
                                        <div class="flex gap-5">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 text-[11px] font-black border border-slate-100">x2</div>
                                            <div>
                                                <p class="text-[13px] font-black text-slate-950 leading-tight mb-0.5">Premium Ribeye Steak</p>
                                                <p class="text-[10px] text-brand-500 font-bold uppercase tracking-widest">Medium Rare • Buttered</p>
                                            </div>
                                        </div>
                                        <p class="text-[13px] font-black text-slate-950 tracking-tighter">₺1,840</p>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <div class="flex gap-5">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 text-[11px] font-black border border-slate-100">x1</div>
                                            <div>
                                                <p class="text-[13px] font-black text-slate-950 leading-tight mb-0.5">Classic Caesar Salad</p>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">No Anchovies</p>
                                            </div>
                                        </div>
                                        <p class="text-[13px] font-black text-slate-950 tracking-tighter">₺220</p>
                                    </div>
                                    <div class="flex justify-between items-start">
                                        <div class="flex gap-5">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 text-[11px] font-black border border-slate-100">x3</div>
                                            <div>
                                                <p class="text-[13px] font-black text-slate-950 leading-tight mb-0.5">Chardonnay (Bottle)</p>
                                            </div>
                                        </div>
                                        <p class="text-[13px] font-black text-slate-950 tracking-tighter">₺3,450</p>
                                    </div>
                                </div>
                                
                                <!-- Multi-tier Checkout -->
                                <div class="p-10 bg-slate-50/50 border-t border-slate-100">
                                    <div class="space-y-4 mb-10">
                                        <div class="flex justify-between text-xs font-bold text-slate-400">
                                            <span class="uppercase tracking-widest">Subtotal</span>
                                            <span>₺5,510.00</span>
                                        </div>
                                        <div class="flex justify-between text-xs font-bold text-slate-400">
                                            <span class="uppercase tracking-widest">Tax (18%)</span>
                                            <span>₺991.80</span>
                                        </div>
                                        <div class="pt-4 border-t border-slate-200/50 flex justify-between items-baseline">
                                            <span class="text-sm font-black text-slate-950 uppercase tracking-widest">Grand Total</span>
                                            <span class="text-4xl font-black text-slate-950 tracking-tighter italic">₺6,501<span class="text-brand-500 font-normal">.80</span></span>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <button class="py-5 bg-white border border-slate-200 text-slate-950 rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] shadow-sm hover:bg-slate-50 transition-all">Split Bill</button>
                                        <button class="py-5 bg-brand-500 text-white rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl shadow-brand-500/30 hover:bg-brand-600 transition-all flex items-center justify-center gap-3">
                                            <i class="fas fa-credit-card"></i> PROCEED
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Console Bottom Bar -->
                        <div class="h-10 bg-slate-950 px-10 flex items-center justify-between">
                            <div class="flex items-center gap-10">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                    <span class="text-[9px] font-black text-white/40 tracking-[0.3em] uppercase italic">Local Infrastructure: Ready</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-brand-500 shadow-[0_0_5px_#6200EE]"></div>
                                    <span class="text-[9px] font-black text-white/40 tracking-[0.3em] uppercase italic">Cloud Gateway: Connected</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <span class="text-[9px] font-black text-white/20 uppercase tracking-[0.2em]">AES-256-GCM SECURE</span>
                                <i class="fas fa-lock text-white/20 text-[10px]"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Immense Floating Stat Badges -->
                <div class="absolute -bottom-16 -left-16 bg-white p-10 rounded-[3.5rem] shadow-premium border border-slate-100 flex items-center gap-10 z-30 transform hover:-translate-y-4 transition-transform duration-700 cursor-default group">
                    <div class="w-20 h-20 bg-slate-950 text-brand-500 rounded-[2rem] flex items-center justify-center text-4xl shadow-2xl transition-all group-hover:rotate-12 group-hover:scale-110">
                        <i class="fas fa-vault"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] mb-2">LIVE NET REVENUE</p>
                        <div class="flex items-baseline gap-4">
                            <p class="text-4xl font-black text-slate-950 tracking-tighter">₺124,842</p>
                            <span class="text-sm font-black text-emerald-500">+32.4%</span>
                        </div>
                        <div class="mt-4 w-48 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-brand-500 w-[92%]" style="box-shadow: 0 0 10px #6200EE;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Product Philosophy: Scaled Details -->
        <section class="py-48 bg-white border-t border-slate-50">
            <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-32 items-center">
                <div class="space-y-16">
                    <div>
                        <h2 class="text-7xl lg:text-9xl font-black text-slate-950 tracking-tighter leading-[0.85] uppercase italic mb-10">Mimarİ Değİl, <br> <span class="text-brand-500 underline decoration-slate-100 underline-offset-8">Standart.</span></h2>
                        <p class="text-xl text-slate-500 font-medium leading-relaxed">
                            Yalnızca bir arayüz tasarlamadık; en yoğun restoran operasyonlarının yükünü saniyeler içinde eritecek teknik bir ekosistem inşa ettik.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-16">
                        <div class="space-y-3">
                            <div class="text-6xl font-black text-slate-950 tracking-tighter">15ms</div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] leading-loose">Max Latency</div>
                        </div>
                        <div class="space-y-3">
                            <div class="text-6xl font-black text-brand-500 tracking-tighter italic">99<span class="text-slate-950 font-normal">.9%</span></div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.5em] leading-loose">SLA Uptime</div>
                        </div>
                    </div>
                </div>

                <!-- High-End Data Logic Module -->
                <div class="bg-slate-950 rounded-[4rem] p-20 text-white shadow-2xl relative overflow-hidden group">
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-brand-500 to-transparent opacity-50"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-white/5 border border-white/10 rounded-3xl mb-16 flex items-center justify-center text-3xl text-brand-500">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <h3 class="text-4xl font-black uppercase tracking-tight italic mb-8">Hİbrİt Ağ <br> Kararlılığı.</h3>
                        <p class="text-slate-400 font-medium leading-relaxed mb-16 max-w-sm text-lg">
                            İnternet kopsa da, elektrik gitse de veriniz asla kaybolmaz. Yerel ağınızdaki her terminal, buluta çıkamasa da birbirini tanır.
                        </p>
                        
                        <!-- Realistic Technical Visualization -->
                        <div class="flex items-center gap-12">
                            <div class="relative w-32 h-32">
                                <svg class="w-full h-full rotate-[-90deg]">
                                    <circle cx="64" cy="64" r="58" fill="transparent" stroke="rgba(255,255,255,0.05)" stroke-width="12"></circle>
                                    <circle cx="64" cy="64" r="58" fill="transparent" stroke="#6200EE" stroke-width="12" stroke-dasharray="364.2" stroke-dashoffset="40" class="transition-all duration-1000 group-hover:stroke-dashoffset-20"></circle>
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-2xl font-black italic">92%</span>
                                    <span class="text-[7px] font-black text-slate-500 uppercase tracking-[0.3em]">LOAD</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Node Sync: 100%</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-brand-500"></div>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Buffer: Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final Immersive Call to Action -->
        <section class="py-64 bg-slate-950 text-center relative overflow-hidden">
            <div class="absolute inset-0 dot-pattern opacity-10"></div>
            <div class="max-w-6xl mx-auto px-6 relative z-10">
                <h2 class="text-7xl lg:text-[12rem] font-black text-white tracking-tighter uppercase italic leading-[0.8] mb-24">Sahne <br> <span class="text-brand-500">Sİzİn.</span></h2>
                <p class="text-2xl text-white/40 font-medium mb-24 max-w-2xl mx-auto">Saniyeler içinde kurulum yapın, yıllarca kararlı kalın. Profesyonel dünyanın merkezine hoş geldiniz.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-10">
                    <a href="{{ route('pages.pos.versions') }}" class="px-16 py-8 bg-brand-500 text-white rounded-[2.5rem] font-black text-sm uppercase tracking-[0.3em] hover:bg-white hover:text-slate-950 hover:-translate-y-4 transition-all duration-700 shadow-2xl shadow-brand-500/40 flex items-center justify-center gap-6 group">
                        <i class="fab fa-windows text-3xl transition-transform group-hover:scale-125"></i> TERMİNALİ İNDİR
                    </a>
                    <a href="/register" class="px-16 py-8 bg-white text-slate-950 rounded-[2.5rem] font-black text-sm uppercase tracking-[0.3em] hover:bg-slate-100 transition-all duration-500 shadow-2xl shadow-slate-950/20 flex items-center justify-center gap-6">
                        YENİ HESAP AÇ
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-white border-t border-slate-100 py-20">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
            <div class="flex items-center gap-5 text-slate-950">
                <div class="w-16 h-16 rounded-[1.5rem] bg-slate-950 text-white flex items-center justify-center font-black text-xl shadow-2xl">R</div>
                <div>
                    <span class="font-black text-2xl tracking-tighter block leading-none">RezerVist</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.5em]">SYSTEMS ENGINEERING</span>
                </div>
            </div>
            <p class="md:text-right text-slate-400 text-[11px] font-black uppercase tracking-[0.8em]">© {{ date('Y') }} REZERVIST. SECURED. DISTRIBUTED. RELIABLE.</p>
        </div>
    </footer>

</body>
</html>
