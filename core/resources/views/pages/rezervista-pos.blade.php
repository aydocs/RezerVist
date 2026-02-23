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
        <section class="relative pt-28 pb-24 dot-pattern overflow-hidden flex flex-col items-center">
            <div class="absolute inset-x-0 bottom-0 h-64 bg-gradient-to-t from-white via-white/80 to-transparent"></div>

            <!-- Terminal Container -->
            <div class="w-full max-w-[1400px] px-6 relative z-20" x-show="booted" x-transition:enter="reveal-up" x-transition:enter-start="opacity-0 translate-y-20 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" style="transition-delay: 200ms;">
                <div class="pos-window rounded-[2.5rem] p-3 shadow-pos-large animate-subtle-float">
                    <!-- Main App Window -->
                    <div class="bg-white rounded-[2.2rem] h-[600px] lg:h-[700px] overflow-hidden flex flex-col border border-slate-100 shadow-inner-subtle relative">
                        
                        <!-- Window Title Bar -->
                        <div class="h-10 bg-slate-50/80 border-b border-slate-100 px-6 flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                <div class="flex gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-slate-300"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-slate-300"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-slate-200"></div>
                                </div>
                                <div class="h-4 w-px bg-slate-200"></div>
                                <div class="flex items-center gap-2 text-slate-400">
                                    <i class="fas fa-terminal text-[8px]"></i>
                                    <span class="text-[9px] font-black uppercase tracking-[0.3em]">RezerVistA Terminal v4.5.12</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2 px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-lg">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_6px_#10b981]"></div>
                                    <span class="text-[8px] font-black text-emerald-600 tracking-widest uppercase">ONLINE</span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-400">
                                    <i class="fas fa-satellite-dish text-[9px]"></i>
                                    <span class="text-[9px] font-bold">16:42:01</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex-1 flex overflow-hidden">
                            <!-- Sidebar -->
                            <div class="w-14 bg-white border-r border-slate-100 py-6 flex flex-col items-center gap-5">
                                <div class="w-8 h-8 bg-brand-500 text-white rounded-xl flex items-center justify-center text-sm shadow-lg shadow-brand-500/30 relative">
                                    <i class="fas fa-desktop"></i>
                                    <div class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-rose-500 border border-white rounded-full"></div>
                                </div>
                                <div class="w-8 h-8 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:text-brand-500 transition-all">
                                    <i class="fas fa-utensils text-xs"></i>
                                </div>
                                <div class="w-8 h-8 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:text-brand-500 transition-all">
                                    <i class="fas fa-file-invoice-dollar text-xs"></i>
                                </div>
                                <div class="w-8 h-8 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:text-brand-500 transition-all">
                                    <i class="fas fa-chart-pie text-xs"></i>
                                </div>
                                <div class="w-8 h-8 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:text-brand-500 transition-all">
                                    <i class="fas fa-box text-xs"></i>
                                </div>
                                <div class="mt-auto">
                                    <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                        <i class="fas fa-user-tie text-xs"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Main Workspace: Massive Table Grid -->
                            <div class="flex-1 flex flex-col bg-white">
                                <!-- Top Bar -->
                                <div class="px-5 py-3 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
                                    <div class="flex gap-1.5">
                                        <button class="px-3 py-1.5 bg-slate-950 text-white rounded-lg text-[9px] font-black">ANA SALON</button>
                                        <button class="px-3 py-1.5 bg-white text-slate-400 border border-slate-100 rounded-lg text-[9px] font-black hover:bg-slate-50 transition-colors">VIP ROOM</button>
                                        <button class="px-3 py-1.5 bg-white text-slate-400 border border-slate-100 rounded-lg text-[9px] font-black hover:bg-slate-50 transition-colors">DIŞ ALAN</button>
                                        <button class="px-3 py-1.5 bg-white text-slate-400 border border-slate-100 rounded-lg text-[9px] font-black hover:bg-slate-50 transition-colors">TERAS</button>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="relative">
                                            <input type="text" placeholder="Masa ara..." class="bg-white border border-slate-200 rounded-lg px-7 py-1.5 text-[9px] w-40 focus:border-brand-500 focus:outline-none transition-colors">
                                            <i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[8px]"></i>
                                        </div>
                                        <div class="w-7 h-7 bg-white border border-slate-100 rounded-lg flex items-center justify-center text-slate-400 cursor-pointer">
                                            <i class="fas fa-sync-alt text-[8px]"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Table Grid -->
                                <div class="flex-1 p-4 overflow-y-auto custom-scrollbar bg-slate-50/10">
                                    <div class="grid grid-cols-6 gap-2.5">
                                        <!-- Active Tables with Real Data -->
                                        <template x-for="i in 24">
                                            <div class="aspect-square bg-white border rounded-2xl p-3 flex flex-col justify-between cursor-pointer transition-all duration-300 shadow-sm"
                                                 :class="[1, 4, 7, 12, 18].includes(i) ? 'border-rose-100 bg-rose-50/20' : (i == 2 ? 'bg-brand-500 border-brand-500 shadow-lg shadow-brand-500/30 scale-[1.05] z-10' : 'border-slate-100 hover:border-brand-500/30')">
                                                
                                                <div class="flex justify-between items-start">
                                                    <span class="text-[7px] font-black uppercase tracking-widest" :class="i == 2 ? 'text-white/60' : ([1,4,7,12,18].includes(i) ? 'text-rose-400' : 'text-emerald-500')" x-text="i == 2 ? 'SEL' : ([1,4,7,12,18].includes(i) ? 'OCC' : 'RDY')"></span>
                                                    <span class="text-[7px] font-bold" :class="i == 2 ? 'text-white/40' : 'text-slate-300'" x-text="[1,4,7,12,18].includes(i) ? (i*3+'m') : ''"></span>
                                                </div>
                                                
                                                <div class="text-center">
                                                    <p class="text-[7px] font-black uppercase tracking-widest mb-0.5" :class="i == 2 ? 'text-white/30' : 'text-slate-300'">T</p>
                                                    <p class="text-xl font-black font-display leading-none" :class="i == 2 ? 'text-white' : 'text-slate-950'" x-text="i < 10 ? '0'+i : i"></p>
                                                </div>
                                                
                                                <div class="text-center">
                                                    <span class="text-[8px] font-black tracking-tighter" :class="i == 2 ? 'text-white' : ([1,4,7,12,18].includes(i) ? 'text-slate-700' : 'text-transparent')" x-text="[1,4,7,12,18].includes(i) ? '₺'+(i*240) : '-'"></span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Receipt Sidebar -->
                            <div class="w-64 bg-white border-l border-slate-100 flex flex-col">
                                <div class="px-5 py-4 border-b border-slate-50">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="text-[9px] font-black text-slate-950 uppercase tracking-[0.15em]">CURRENT ORDER</h4>
                                        <span class="px-2 py-0.5 bg-brand-500 text-white rounded text-[7px] font-black">T-02</span>
                                    </div>
                                    <p class="text-[8px] font-bold text-slate-400">4 PERSONS • MEHMET CAN</p>
                                </div>
                                
                                <div class="flex-1 px-5 py-3 space-y-4 overflow-y-auto custom-scrollbar">
                                    <div class="flex justify-between items-center">
                                        <div class="flex gap-2 items-center">
                                            <span class="text-[8px] font-black text-slate-400 w-5">x2</span>
                                            <div>
                                                <p class="text-[9px] font-black text-slate-950">Ribeye Steak</p>
                                                <p class="text-[7px] text-brand-500 font-bold">Med. Rare</p>
                                            </div>
                                        </div>
                                        <p class="text-[9px] font-black text-slate-950">₺1,840</p>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div class="flex gap-2 items-center">
                                            <span class="text-[8px] font-black text-slate-400 w-5">x1</span>
                                            <div>
                                                <p class="text-[9px] font-black text-slate-950">Caesar Salad</p>
                                                <p class="text-[7px] text-slate-400 italic">No Anchovies</p>
                                            </div>
                                        </div>
                                        <p class="text-[9px] font-black text-slate-950">₺220</p>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div class="flex gap-2 items-center">
                                            <span class="text-[8px] font-black text-slate-400 w-5">x3</span>
                                            <div>
                                                <p class="text-[9px] font-black text-slate-950">Chardonnay</p>
                                            </div>
                                        </div>
                                        <p class="text-[9px] font-black text-slate-950">₺3,450</p>
                                    </div>
                                </div>
                                
                                <!-- Checkout -->
                                <div class="px-5 py-4 bg-slate-50/50 border-t border-slate-100">
                                    <div class="space-y-2 mb-4">
                                        <div class="flex justify-between text-[8px] font-bold text-slate-400">
                                            <span>Subtotal</span><span>₺5,510</span>
                                        </div>
                                        <div class="flex justify-between text-[8px] font-bold text-slate-400">
                                            <span>Tax 18%</span><span>₺991</span>
                                        </div>
                                        <div class="pt-2 border-t border-slate-200 flex justify-between items-baseline">
                                            <span class="text-[8px] font-black text-slate-950 uppercase tracking-wide">Total</span>
                                            <span class="text-lg font-black text-slate-950 tracking-tighter">₺6,501<span class="text-brand-500 text-xs font-normal">.80</span></span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button class="py-2.5 bg-white border border-slate-200 text-slate-950 rounded-xl text-[8px] font-black uppercase hover:bg-slate-50 transition-all">Split</button>
                                        <button class="py-2.5 bg-brand-500 text-white rounded-xl text-[8px] font-black uppercase shadow-lg shadow-brand-500/30 hover:bg-brand-600 transition-all flex items-center justify-center gap-1.5">
                                            <i class="fas fa-credit-card text-[8px]"></i> Pay
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
