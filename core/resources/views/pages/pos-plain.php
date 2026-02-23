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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
                            500: '#6200EE',
                            600: '#5000D3',
                        },
                        dark: {
                            900: '#050505',
                            800: '#0A0A0A',
                            700: '#111111',
                            600: '#1A1A1A',
                        }
                    },
                    animation: {
                        'slow-float': 'float 10s ease-in-out infinite',
                        'subtle-pulse': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-15px)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { background-color: #050505; }
        .hero-grid {
            background-image: 
                radial-gradient(circle at 2px 2px, #1a1a1a 1px, transparent 0);
            background-size: 40px 40px;
        }
        .bento-card {
            background: #0A0A0A;
            border: 1px solid #161616;
            transition: all 0.3s ease;
        }
        .bento-card:hover {
            border-color: #6200EE;
            background: #0D0D0D;
        }
        .text-gradient {
            background: linear-gradient(135deg, #fff 0%, #666 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .purple-glow {
            box-shadow: 0 0 50px -10px rgba(98, 0, 238, 0.4);
        }
    </style>
</head>
<body class="text-slate-400 font-sans selection:bg-brand-500/30 selection:text-white">

    <!-- Minimalist Navbar -->
    <nav class="fixed top-0 w-full z-50 border-b border-white/[0.03] bg-dark-900/90 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-4 group">
                <div class="w-9 h-9 bg-brand-500 rounded flex items-center justify-center text-white">
                    <i class="fas fa-terminal text-sm"></i>
                </div>
                <span class="text-lg font-black text-white tracking-tighter">RezerVist<span class="text-brand-500 italic">A</span></span>
            </a>
            <div class="flex items-center gap-8">
                <a href="/" class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500 hover:text-white transition-colors">Ana Sayfa</a>
                <a href="/login" class="px-5 py-2 bg-white text-black rounded text-[10px] font-black uppercase tracking-widest hover:bg-brand-500 hover:text-white transition-all">GİRİŞ</a>
            </div>
        </div>
    </nav>

    <main>
        <!-- Dynamic Hero -->
        <section class="relative pt-40 pb-32 lg:pt-56 lg:pb-48 hero-grid">
            <div class="absolute inset-0 bg-gradient-to-b from-dark-900 via-transparent to-dark-900 pointer-events-none"></div>
            
            <div class="max-w-7xl mx-auto px-6 relative z-10 grid lg:grid-cols-2 gap-24 items-center">
                
                <!-- Hero Content: Always Visible -->
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-3 px-3 py-1 bg-white/5 border border-white/10 rounded text-[9px] font-black tracking-widest text-brand-500 uppercase mb-8">
                        <span class="w-1 h-1 rounded-full bg-brand-500 animate-ping"></span>
                        Enterprise POS Solution
                    </div>
                    
                    <h1 class="text-5xl lg:text-8xl font-black text-white tracking-tighter leading-[0.9] mb-10 font-display">
                        <span class="text-gradient">Siz Yönetin,</span> <br>
                        <span class="text-brand-500 italic">Teknoloji <br> Çalışsın.</span>
                    </h1>
                    
                    <p class="text-base lg:text-lg text-slate-500 font-medium leading-relaxed max-w-lg mb-12 mx-auto lg:mx-0">
                        Hantallıktan arındırılmış, saf performans için optimize edilmiş bir ekosistem. RezerVistA ile işletmenizi saniyeler içinde dijital geleceğe entegre edin.
                    </p>
                    
                    <div class="flex flex-wrap gap-5 justify-center lg:justify-start">
                        <a href="<?php echo route('pages.pos.versions'); ?>" class="px-9 py-5 bg-brand-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-brand-600 transition-all purple-glow flex items-center gap-3">
                            <i class="fab fa-windows"></i> İndir
                        </a>
                        <a href="/business-partner" class="px-9 py-5 bg-dark-700 border border-white/5 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-dark-600 transition-all flex items-center gap-3">
                            Demo İzle
                        </a>
                    </div>
                    
                    <div class="mt-20 flex gap-8 justify-center lg:justify-start">
                        <div class="space-y-1">
                            <p class="text-[9px] font-black text-slate-600 tracking-widest uppercase italic">Latency</p>
                            <p class="text-xl font-black text-white italic">&lt; 15ms</p>
                        </div>
                        <div class="w-px h-10 bg-white/5"></div>
                        <div class="space-y-1">
                            <p class="text-[9px] font-black text-slate-600 tracking-widest uppercase italic">Security</p>
                            <p class="text-xl font-black text-white italic">AES-256</p>
                        </div>
                    </div>
                </div>

                <!-- Abstract Visual Mockup -->
                <div class="relative group lg:block hidden">
                    <div class="relative bg-dark-800 border-2 border-white/[0.02] rounded-[2.5rem] p-3 shadow-2xl animate-slow-float">
                        <div class="bg-dark-900 rounded-[2rem] p-10 overflow-hidden relative">
                            <!-- Terminal UI Sim -->
                            <div class="flex items-center justify-between mb-12">
                                <div class="flex gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-white/10"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-white/10"></div>
                                </div>
                                <div class="text-[9px] font-black text-slate-700 uppercase tracking-[0.4em]">Floor_Master.09</div>
                            </div>
                            
                            <div class="grid grid-cols-4 gap-4 mb-20">
                                <template x-for="i in 12">
                                    <div class="h-8 rounded bg-white/[0.02] border border-white/[0.03]"
                                         :class="[1, 5, 8, 12].includes(i) ? 'bg-brand-500/20 border-brand-500/30' : ''"></div>
                                </template>
                            </div>
                            
                            <div class="flex items-end justify-between">
                                <div class="space-y-4 w-1/2">
                                    <div class="h-1 bg-white/5 w-full"></div>
                                    <div class="h-1 bg-white/5 w-2/3"></div>
                                    <div class="h-1 bg-brand-500 w-1/2"></div>
                                </div>
                                <div class="text-3xl font-black text-white italic tracking-tighter">₺12.4K</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating Solid Badges -->
                    <div class="absolute -top-6 -right-6 bg-dark-700 border border-brand-500/30 px-6 py-4 rounded-2xl shadow-2xl">
                        <p class="text-[9px] font-black text-brand-500 uppercase tracking-widest mb-1">Peak Mode</p>
                        <p class="text-xs font-black text-white">Aktif & Stabil</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bento Grid: Value Prop -->
        <section class="py-32 bg-dark-900 relative">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid lg:grid-cols-12 gap-6">
                    
                    <!-- Bento Main -->
                    <div class="lg:col-span-8 bento-card rounded-[3rem] p-12 flex flex-col justify-between">
                        <div class="w-16 h-16 bg-brand-500 text-white rounded-2xl flex items-center justify-center text-3xl mb-24 rotate-3">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl lg:text-5xl font-black text-white tracking-tighter mb-4 italic uppercase leading-none">Saf Teknoloji, <br> Kesin Sonuçlar.</h2>
                            <p class="text-slate-500 font-medium max-w-md">Karmaşıklıktan uzak, tamamen performans ve hız odaklı geliştirilmiş altyapı her an yanınızda.</p>
                        </div>
                    </div>

                    <!-- Bento Small 1 -->
                    <div class="lg:col-span-4 bento-card rounded-[3rem] p-12">
                        <div class="text-indigo-500 text-4xl mb-12">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3 class="text-xl font-black text-white uppercase mb-4 tracking-tight">Hız Odaklı</h3>
                        <p class="text-slate-500 font-medium text-sm leading-relaxed">Saniyelerin kritik olduğu anlarda asla bekletmez.</p>
                    </div>

                    <!-- Bento Small 2 -->
                    <div class="lg:col-span-4 bento-card rounded-[3rem] p-12">
                        <div class="text-emerald-500 text-4xl mb-12">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <h3 class="text-xl font-black text-white uppercase mb-4 tracking-tight">Hibrit Mimari</h3>
                        <p class="text-slate-500 font-medium text-sm leading-relaxed">Lokal hız, bulut güvenliği. Kesintisiz işleyiş.</p>
                    </div>

                    <!-- Bento Wide -->
                    <div class="lg:col-span-8 bento-card rounded-[3rem] p-12 flex flex-col lg:flex-row items-center gap-12">
                        <div class="lg:flex-1">
                            <h3 class="text-2xl font-black text-white uppercase mb-4 tracking-tight italic">Eşsiz Veri Kontrolü</h3>
                            <p class="text-slate-500 font-medium text-sm leading-relaxed">Raporlamadan stok yönetimine, her şey tek bir merkezden en profesyonel şekilde yönetilir.</p>
                        </div>
                        <div class="lg:w-1/3 w-full bg-white/5 border border-white/10 rounded-2xl p-6">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-[10px] font-bold text-slate-500">Peak Load</span>
                                <span class="w-3 h-3 rounded-full bg-brand-500 shadow-[0_0_8px_#6200EE]"></span>
                            </div>
                            <div class="space-y-3">
                                <div class="h-1 bg-white/10 rounded-full w-full"></div>
                                <div class="h-1 bg-white/10 rounded-full w-3/4"></div>
                                <div class="h-1 bg-brand-500 rounded-full w-1/2"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Technical Excellence Section -->
        <section class="py-40 bg-white border-t border-white/5">
            <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-24 items-center">
                <div class="order-2 lg:order-1">
                    <div class="grid grid-cols-2 gap-8">
                        <div class="p-8 bg-slate-50 rounded-3xl border border-slate-100">
                            <p class="text-4xl font-black text-black mb-2 tracking-tighter">60ms</p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Sync Duration</p>
                        </div>
                        <div class="p-8 bg-slate-50 rounded-3xl border border-slate-100">
                            <p class="text-4xl font-black text-black mb-2 tracking-tighter">99.9%</p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Uptime</p>
                        </div>
                        <div class="p-8 bg-slate-50 rounded-3xl border border-slate-100">
                            <p class="text-4xl font-black text-black mb-2 tracking-tighter">AES</p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Military Grade</p>
                        </div>
                        <div class="p-8 bg-slate-50 rounded-3xl border border-slate-100">
                            <p class="text-4xl font-black text-black mb-2 tracking-tighter">&infin;</p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Scale</p>
                        </div>
                    </div>
                </div>
                
                <div class="order-1 lg:order-2">
                    <h2 class="text-5xl lg:text-7xl font-black text-black tracking-tighter leading-[0.95] mb-10 italic uppercase">Cihaz Değil, <br> Deneyim.</h2>
                    <p class="text-lg text-slate-500 font-medium leading-relaxed mb-12">
                        Personelinizin öğrenmesi gereken karmaşık sayfalar yok. Her piksel, hata payını sıfıra indirmek ve işlem hızını en tepeye taşımak için özenle yerleştirildi.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-4 text-black font-bold uppercase tracking-tight text-sm">
                            <div class="w-6 h-6 bg-brand-500 rounded text-white flex items-center justify-center text-xs"><i class="fas fa-check"></i></div>
                            Fiziksel / Dijital Tam Entegrasyon
                        </li>
                        <li class="flex items-center gap-4 text-black font-bold uppercase tracking-tight text-sm">
                            <div class="w-6 h-6 bg-brand-500 rounded text-white flex items-center justify-center text-xs"><i class="fas fa-check"></i></div>
                            Bulut Tabanlı Raporlama
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Final Call to Action -->
        <section class="py-48 bg-dark-900 text-center relative overflow-hidden">
            <div class="absolute inset-0 hero-grid opacity-10"></div>
            <div class="max-w-4xl mx-auto px-6 relative z-10">
                <h2 class="text-5xl lg:text-8xl font-black text-white tracking-tighter uppercase italic leading-[0.9] mb-16">Hazırsan <br> Başlayalım.</h2>
                <div class="flex flex-col sm:flex-row justify-center gap-6">
                    <a href="<?php echo route('pages.pos.versions'); ?>" class="px-12 py-6 bg-brand-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-brand-600 transition-all purple-glow flex items-center justify-center gap-4">
                        <i class="fab fa-windows text-xl"></i> Terminali İndir
                    </a>
                    <a href="/register" class="px-12 py-6 bg-dark-700 border border-white/5 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-dark-600 transition-all flex items-center justify-center gap-4">
                        Hesap Oluştur
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-dark-900 border-t border-white/[0.03] py-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 bg-white/5 rounded flex items-center justify-center font-black text-white text-[10px]">R</div>
                <span class="font-bold text-white text-xs tracking-widest uppercase">RezerVist</span>
            </div>
            <p class="text-slate-600 text-[10px] font-bold uppercase tracking-[0.4em]">© <?php echo date('Y'); ?> RezerVist Engineering. Tüm Hakları Saklıdır.</p>
        </div>
    </footer>

</body>
</html>
