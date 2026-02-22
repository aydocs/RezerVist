<!DOCTYPE html>
<html lang="<?php echo str_replace('_', '-', app()->getLocale()); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    
    <title>RezerVistA POS | Yetenekli, Hızlı, Keskin.</title>
    <meta name="description" content="Türkiye'nin en gelişmiş restoran POS ve adisyon sistemi. Masa yönetimi, sipariş takibi, stok kontrolü ve bulut senkronizasyon.">
    
    <!-- Premium Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- TailwindCSS & Plugins -->
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
                            900: '#2E008F',
                        },
                        dark: {
                            DEFAULT: '#09090B',
                            soft: '#18181B',
                            card: '#0C0C0E',
                        },
                        ui: {
                            border: '#27272A',
                            surface: '#09090B',
                        }
                    },
                    animation: {
                        'slow-spin': 'spin 12s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'reveal': 'reveal 0.8s cubic-bezier(0.2, 1, 0.2, 1) forwards',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        reveal: {
                            '0%': { opacity: '0', transform: 'translateY(40px) scale(0.98)' },
                            '100%': { opacity: '1', transform: 'translateY(0) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .bg-grid {
            background-size: 50px 50px;
            background-image: linear-gradient(to right, #18181b 1px, transparent 1px),
                              linear-gradient(to bottom, #18181b 1px, transparent 1px);
        }
        .reveal { opacity: 0; }
        .reveal.active { animation: reveal 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .bento-card {
            background: #0C0C0E;
            border: 1px solid #18181B;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .bento-card:hover {
            border-color: #6200EE;
            box-shadow: 0 0 30px rgba(98, 0, 238, 0.1);
            transform: translateY(-4px);
        }
        
        .premium-mask {
            mask-image: linear-gradient(to bottom, black 50%, transparent);
        }
    </style>
</head>
<body class="bg-dark text-slate-300 font-sans selection:bg-brand-500/20 selection:text-brand-500">
    
    <!-- Ultra-Clean Navbar -->
    <nav class="fixed top-0 w-full z-50 border-b border-white/5 bg-dark/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-4 group">
                <div class="w-10 h-10 bg-brand-500 rounded-lg flex items-center justify-center text-white rotate-3 group-hover:rotate-0 transition-all duration-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="text-xl font-black text-white tracking-tighter">RezerVist<span class="text-brand-500 italic">A</span></span>
            </a>
            
            <div class="hidden md:flex items-center gap-10">
                <a href="/" class="text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-white transition-colors">Ana Sayfa</a>
                <a href="/business-partner" class="text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-white transition-colors">İşletme</a>
                <a href="/login" class="px-6 py-2 border border-white/10 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-white hover:text-dark transition-all">Giriş</a>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero: Industrial Minimalist -->
        <section class="relative pt-40 pb-20 lg:pt-64 lg:pb-48 bg-grid overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-dark via-transparent to-dark"></div>
            
            <div class="max-w-7xl mx-auto px-6 relative z-10 text-center lg:text-left grid lg:grid-cols-2 gap-20 items-center">
                <div x-data x-intersect="$el.classList.add('active')" class="reveal">
                    <div class="inline-flex items-center gap-3 px-3 py-1.5 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-500 text-[10px] font-black tracking-[0.2em] uppercase mb-10">
                        <span class="flex h-2 w-2 rounded-full bg-brand-500 shadow-[0_0_10px_#6200EE]"></span>
                        Next-Gen Console
                    </div>
                    
                    <h1 class="text-6xl lg:text-[7.5rem] font-black text-white tracking-[-0.04em] leading-[0.9] mb-8 font-display">
                        Yetenekli. <br>
                        <span class="text-slate-600">Keskin.</span> <br>
                        <span class="text-brand-500">Hızlı.</span>
                    </h1>
                    
                    <p class="text-lg text-slate-400 font-medium leading-relaxed max-w-xl mb-12">
                        RezerVistA, restoran yönetimini dijital bir sanata dönüştüren yüksek performanslı terminal sistemidir. Hantallığı arkada bırakın, geleceğe dokunun.
                    </p>
                    
                    <div class="flex flex-wrap gap-5 justify-center lg:justify-start">
                        <a href="<?php echo route('pages.pos.versions'); ?>" class="flex items-center gap-4 px-10 py-5 bg-white text-dark rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-brand-500 hover:text-white transition-all duration-500 group">
                            <i class="fab fa-windows text-lg"></i>
                            Terminali İndir
                        </a>
                        <a href="/business-partner" class="flex items-center gap-4 px-10 py-5 bg-dark border border-white/10 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:border-brand-500 transition-all duration-500">
                            Demo İzle
                        </a>
                    </div>
                </div>

                <!-- Abstract Terminal UI Mockup -->
                <div class="relative hidden lg:block" x-data x-intersect="$el.classList.add('active')" class="reveal delay-200">
                    <div class="relative bg-dark-card border border-white/5 rounded-3xl p-8 shadow-2xl overflow-hidden aspect-[4/3] flex flex-col group">
                        <!-- Simulated Terminal Header -->
                        <div class="flex items-center justify-between mb-10">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-white/10"></div>
                                <div class="w-3 h-3 rounded-full bg-white/10"></div>
                                <div class="w-3 h-3 rounded-full bg-white/10"></div>
                            </div>
                            <div class="text-[10px] font-bold text-white/20 uppercase tracking-[0.4em]">Floor Alpha-09</div>
                        </div>

                        <div class="flex-1 grid grid-cols-12 gap-6">
                            <!-- Tables Visualization -->
                            <div class="col-span-8 grid grid-cols-4 grid-rows-4 gap-4">
                                <template x-for="i in 16">
                                    <div class="rounded-lg border border-white/5 transition-all duration-700"
                                         :class="[1, 4, 7, 10, 15].includes(i) ? 'bg-brand-500/20 border-brand-500/30' : 'bg-white/[0.02]'"></div>
                                </template>
                            </div>
                            
                            <!-- Sidebar Stats -->
                            <div class="col-span-4 space-y-6">
                                <div class="p-4 bg-white/[0.03] border border-white/5 rounded-2xl">
                                    <div class="h-1 bg-brand-500 w-2/3 mb-3"></div>
                                    <div class="text-xs font-black text-white tracking-widest uppercase">₺14,820</div>
                                </div>
                                <div class="p-4 bg-white/[0.03] border border-white/5 rounded-2xl">
                                    <div class="h-1 bg-slate-700 w-1/2 mb-3"></div>
                                    <div class="text-xs font-black text-slate-500 tracking-widest uppercase">Peak Mode</div>
                                </div>
                                <div class="flex-1 rounded-2xl border-2 border-dashed border-white/5 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white/10 group-hover:text-brand-500 transition-colors duration-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Glow effect -->
                        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-brand-500/5 blur-[100px] pointer-events-none"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bento Showcase: Technical Depth -->
        <section class="py-32 bg-dark">
            <div class="max-w-7xl mx-auto px-6">
                <div class="mb-24 flex flex-col md:flex-row md:items-end justify-between gap-10" x-data x-intersect="$el.classList.add('active')" class="reveal">
                    <div class="max-w-xl">
                        <h2 class="text-4xl lg:text-6xl font-black text-white tracking-tighter mb-6 uppercase italic">Teknolojik <br> Derinlik.</h2>
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">Aradığınız her şey tek bir noktada.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-6 lg:grid-cols-12 gap-6">
                    <!-- Bento 1: Huge Left -->
                    <div class="md:col-span-6 lg:col-span-8 p-10 bento-card rounded-[2.5rem] flex flex-col justify-between reveal" x-data x-intersect="$el.classList.add('active')">
                        <div class="flex justify-between items-start mb-20">
                            <div class="w-16 h-16 bg-brand-500/10 text-brand-500 rounded-2xl flex items-center justify-center text-3xl">
                                <i class="fas fa-microchip"></i>
                            </div>
                            <div class="px-6 py-2 rounded-full border border-white/5 text-[9px] font-black uppercase tracking-widest text-slate-500">LATENCY < 15MS</div>
                        </div>
                        <div>
                            <h3 class="text-3xl font-black text-white tracking-tight mb-4 uppercase">Hibrit Mimari</h3>
                            <p class="text-slate-400 font-medium leading-relaxed max-w-sm">İnternet kopsa da terminal durmaz. Tüm işlemler saniyeler içinde lokalden buluta senkronize olur.</p>
                        </div>
                    </div>

                    <!-- Bento 2: Square Top -->
                    <div class="md:col-span-3 lg:col-span-4 p-10 bento-card rounded-[2.5rem] reveal delay-100" x-data x-intersect="$el.classList.add('active')">
                        <div class="w-16 h-16 bg-slate-900 text-emerald-500 rounded-2xl flex items-center justify-center text-3xl mb-12">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3 class="text-2xl font-black text-white tracking-tight mb-4 uppercase">Hız</h3>
                        <p class="text-slate-500 font-medium">60 FPS Akıcı Deneyim</p>
                    </div>

                    <!-- Bento 3: Square Bottom -->
                    <div class="md:col-span-3 lg:col-span-4 p-10 bento-card rounded-[2.5rem] reveal delay-200" x-data x-intersect="$el.classList.add('active')">
                        <div class="w-16 h-16 bg-slate-900 text-blue-500 rounded-2xl flex items-center justify-center text-3xl mb-12">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="text-2xl font-black text-white tracking-tight mb-4 uppercase">Güvenlik</h3>
                        <p class="text-slate-500 font-medium">AES-256 Şifreleme</p>
                    </div>

                    <!-- Bento 4: Wide Large -->
                    <div class="md:col-span-6 lg:col-span-8 p-10 bento-card rounded-[2.5rem] bg-gradient-to-br from-dark-card to-zinc-900 reveal delay-300 relative overflow-hidden" x-data x-intersect="$el.classList.add('active')">
                        <div class="absolute inset-0 bg-brand-500/5 pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-brand-500 text-white rounded-2xl flex items-center justify-center text-2xl mb-12 shadow-2xl shadow-brand-500/20">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <h3 class="text-3xl font-black text-white tracking-tight mb-4 uppercase">Global Panele <br> Tam Erişim</h3>
                            <p class="text-slate-400 font-medium leading-relaxed max-w-md">Tüm işletme verileri, stok hareketleri ve kâr-zarar raporları anlık olarak dashboard ekranınızda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Product Philosophy -->
        <section class="py-48 bg-white text-dark rounded-t-[4rem] relative z-20">
            <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-24 items-center">
                <div x-data x-intersect="$el.classList.add('active')" class="reveal">
                    <div class="w-20 h-2 bg-brand-500 mb-12"></div>
                    <h2 class="text-5xl lg:text-7xl font-black tracking-tighter leading-[0.9] mb-12 uppercase italic">Arayüz <br> Değil, <br> İşletim <br> Sistemi.</h2>
                    <p class="text-xl text-slate-600 font-medium leading-relaxed">RezerVistA bir POS cihazından fazlasıdır. Personelin hızını kesen karmaşık menüler yok. Her piksel en verimli sonuca ulaşmak için optimize edildi.</p>
                </div>
                
                <div class="grid grid-cols-2 gap-10">
                    <div class="space-y-4">
                        <div class="text-4xl font-black text-brand-500 font-display">60ms</div>
                        <div class="text-xs font-bold uppercase tracking-widest text-slate-400">SYNC DURATION</div>
                    </div>
                    <div class="space-y-4">
                        <div class="text-4xl font-black text-dark font-display">99.9%</div>
                        <div class="text-xs font-bold uppercase tracking-widest text-slate-400">UPTIME GUARANTEE</div>
                    </div>
                    <div class="space-y-4">
                        <div class="text-4xl font-black text-dark font-display">AES</div>
                        <div class="text-xs font-bold uppercase tracking-widest text-slate-400">MILITARY GRADE</div>
                    </div>
                    <div class="space-y-4">
                        <div class="text-4xl font-black text-dark font-display">&infin;</div>
                        <div class="text-xs font-bold uppercase tracking-widest text-slate-400">STORAGE LIMIT</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final Call to Action -->
        <section class="py-48 bg-dark text-white text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-grid opacity-20"></div>
            <div class="max-w-5xl mx-auto px-6 relative z-10" x-data x-intersect="$el.classList.add('active')" class="reveal">
                <h2 class="text-5xl lg:text-8xl font-black tracking-tighter uppercase italic leading-[0.9] mb-16">Deneyİme <br> Hazır mısın?</h2>
                <div class="flex flex-col sm:flex-row justify-center gap-6">
                    <a href="<?php echo route('pages.pos.versions'); ?>" class="px-12 py-6 bg-brand-500 text-white rounded-3xl font-black text-xs uppercase tracking-[0.2em] hover:bg-white hover:text-dark transition-all duration-500 flex items-center justify-center gap-4">
                        <i class="fab fa-windows text-xl"></i>
                        TERMİNALİ İNDİR
                    </a>
                    <a href="/register" class="px-12 py-6 bg-dark border border-white/10 text-white rounded-3xl font-black text-xs uppercase tracking-[0.2em] hover:border-brand-500 transition-all duration-500 flex items-center justify-center">
                        HESAP OLUŞTUR
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-dark border-t border-white/5 py-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center font-black text-white text-xs">R</div>
                <span class="font-bold text-white text-sm tracking-[0.2em] uppercase">RezerVist</span>
            </div>
            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.4em]">© <?php echo date('Y'); ?> RezerVist Engineering. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
