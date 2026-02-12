<!DOCTYPE html>
<html lang="<?php echo str_replace('_', '-', app()->getLocale()); ?>" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    
    <title>RezerVistA POS | Türkiye'nin En İyi Adisyon ve POS Sistemi | Restoran Yazılımı 2024</title>
    <meta name="description" content="Türkiye'nin en gelişmiş restoran POS ve adisyon sistemi. Masa yönetimi, sipariş takibi, stok kontrolü ve bulut senkronizasyon.">
    <meta name="keywords" content="pos sistemi, adisyon sistemi, restoran yazılımı, kafe yazılımı, restoran pos, adisyon programı">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- TailwindCSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#6200EE',
                    },
                    animation: {
                        'pulse-slow': 'pulse 15s ease-in-out infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0) rotate(0)' },
                            '50%': { transform: 'translateY(-15px) rotate(1deg)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .perspective-2000 { perspective: 2000px; }
        .rotate-x-2 { transform: rotateX(4deg); }
        .bg-noise { background-image: url('https://grainy-gradients.vercel.app/noise.svg'); }
        
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "RezerVistA POS",
        "applicationCategory": "BusinessApplication",
        "applicationSubCategory": "Point of Sale Software",
        "operatingSystem": "Windows, Web",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "TRY",
            "priceValidUntil": "2026-12-31",
            "availability": "https://schema.org/InStock"
        },
        "description": "Türkiye'nin en gelişmiş restoran, kafe ve bar POS sistemi.",
        "softwareVersion": "1.0.0"
    }
    </script>
</head>
<body class="antialiased font-sans bg-white selection:bg-primary selection:text-white overflow-x-hidden">
    
    <!-- Simple Navbar -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-lg border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-indigo-700 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-primary/30 transition hover:scale-105">
                        R
                    </div>
                    <span class="text-xl font-black text-slate-900 tracking-tighter">Rezervist</span>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="/" class="text-sm font-bold text-slate-500 hover:text-primary transition">Ana Sayfa</a>
                    <a href="/business-partner" class="text-sm font-bold text-slate-500 hover:text-primary transition">İşletme</a>
                    <a href="/login" class="px-6 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-primary transition shadow-lg shadow-slate-900/10">Giriş Yap</a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <div class="relative">
            <!-- Hero Background Effects -->
            <div class="absolute top-0 left-0 w-full h-[1500px] pointer-events-none -z-10 overflow-hidden text-slate-600">
                <div class="absolute top-[-5%] left-[-10%] w-[70%] h-[70%] bg-primary/5 blur-[120px] rounded-full animate-pulse-slow"></div>
                <div class="absolute top-[15%] right-[-10%] w-[60%] h-[60%] bg-indigo-500/5 blur-[120px] rounded-full animate-pulse-slow" style="animation-delay: 4s;"></div>
                <div class="absolute inset-0 bg-noise opacity-[0.03] mix-blend-multiply"></div>
            </div>

            <!-- Hero Section -->
            <div class="relative pt-32 pb-24 lg:pt-56 lg:pb-52">
                <div class="mx-auto max-w-7xl px-6 lg:px-8 grid lg:grid-cols-2 gap-24 items-center">
                    <div class="relative z-10 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-primary/5 border border-primary/10 text-primary text-[9px] font-black tracking-[0.3em] uppercase mb-12">
                            <span class="flex h-1.5 w-1.5 rounded-full bg-primary shadow-[0_0_8px_rgba(79,70,229,0.4)]"></span>
                            Terminal v1.0.0 Pro
                        </div>
                        <h1 class="text-5xl lg:text-[5.5rem] font-black text-slate-900 tracking-[-0.06em] leading-[0.88] mb-10">
                            Siz Yönetin, <br>
                            <span class="text-slate-400">Teknoloji <br> Çalışsın.</span>
                        </h1>
                        <p class="text-base text-slate-500 mb-14 leading-relaxed max-w-lg font-medium mx-auto lg:mx-0">
                            Hantal arayüzlere veda edin. RezerVistA POS, en yoğun restoran trafiğini yönetmek için optimize edilmiş, minimalist ve yüksek performanslı bir ekosistemdir.
                        </p>
                        
                        <div class="flex flex-wrap gap-5 items-center justify-center lg:justify-start">
                            <a href="<?php echo route('pages.pos.versions'); ?>" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-sm hover:bg-primary transition-all flex items-center gap-4 shadow-[0_20px_40px_-10px_rgba(15,23,42,0.3)] group active:scale-[0.98]">
                                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-colors">
                                    <i class="fab fa-windows text-lg"></i>
                                </div>
                                <span class="tracking-tight italic uppercase">V.1.0.0 İndİr</span>
                            </a>
                            <a href="/business-partner" class="px-8 py-4 bg-white text-slate-900 rounded-2xl font-black text-sm border border-slate-200 hover:bg-slate-50 transition-all flex items-center gap-4 group shadow-sm active:scale-[0.98]">
                                <span class="tracking-tight italic uppercase">Demo Talebİ</span>
                                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all">
                                    <i class="fas fa-chevron-right text-[10px]"></i>
                                </div>
                            </a>
                        </div>

                        <div class="mt-20 flex items-center justify-center lg:justify-start gap-12 border-t border-slate-100 pt-12">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 italic">Latent Sync</p>
                                <p class="text-lg font-black text-slate-900 tracking-tight leading-none italic">&lt; 150MS</p>
                            </div>
                            <div class="w-px h-10 bg-slate-100"></div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 italic">HIZLI KURULUM</p>
                                <p class="text-lg font-black text-slate-900 tracking-tight leading-none italic">60 SANİYE</p>
                            </div>
                        </div>
                    </div>

                    <!-- 3D Card Visual -->
                    <div class="relative group lg:block hidden">
                        <div class="relative z-10 w-full perspective-2000">
                            <div class="relative bg-white rounded-[2.5rem] p-3 shadow-[0_80px_120px_-30px_rgba(15,23,42,0.15)] border border-slate-100 overflow-hidden transform group-hover:rotate-x-2 transition-all duration-1000">
                                <img src="https://images.unsplash.com/photo-1556742044-3c52d6e88c62?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=100" 
                                     alt="Refined POS Interface" 
                                     class="rounded-[2rem] w-full h-auto object-cover opacity-95 group-hover:opacity-100 transition duration-1000">
                                
                                <div class="absolute top-10 right-10">
                                    <div class="bg-white/70 backdrop-blur-xl border border-white rounded-2xl p-5 shadow-2xl animate-float">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div>
                                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Status</p>
                                                <p class="text-xs font-black text-slate-900 uppercase">Live & Encrypted</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="py-32 bg-white relative">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mb-24 flex flex-col lg:flex-row lg:items-end justify-between gap-10">
                        <div class="max-w-xl">
                            <h2 class="text-4xl lg:text-5xl font-black text-slate-900 mb-6 tracking-tight uppercase italic leading-none">Teknİk <br> Mükemmelİyet.</h2>
                            <p class="text-sm text-slate-400 font-black uppercase tracking-[0.3em] italic">Daha hızlı, daha akıllı, daha keskin.</p>
                        </div>
                        <div class="h-px bg-slate-100 flex-1 lg:mx-15 hidden lg:block"></div>
                        <div class="text-[10px] font-black text-slate-900 tracking-[0.6em] uppercase italic">The New Standard</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="lg:col-span-2 p-1 rounded-[2.5rem] bg-slate-50 border border-slate-100 group hover:bg-gradient-to-br hover:from-primary hover:to-indigo-600 transition-all duration-700">
                             <div class="bg-white p-10 rounded-[2.3rem] h-full flex flex-col items-start transition-all">
                                <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary mb-10 group-hover:bg-white transition-all shadow-sm">
                                    <i class="fas fa-microchip text-xl"></i>
                                </div>
                                <h3 class="text-2xl font-black text-slate-900 mb-6 tracking-tight italic uppercase group-hover:text-primary transition-all">Hibrit Mİmarİ</h3>
                                <p class="text-slate-500 text-sm font-semibold leading-relaxed mb-10">
                                    İnternet koptuğunda kasanız asla durmaz. Tüm veriler lokalde işlenir ve saniyeler içinde bulutla senkronize olur.
                                </p>
                                <ul class="space-y-3 mt-auto w-full">
                                    <li class="flex items-center justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Offline Sync <span class="text-emerald-500 italic">Enabled</span>
                                    </li>
                                    <div class="h-px bg-slate-100 w-full opacity-50"></div>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="p-1 rounded-[2.5rem] bg-slate-50 border border-slate-100 group hover:shadow-2xl transition-all duration-700">
                            <div class="bg-white p-10 rounded-[2.3rem] h-full transition-all">
                                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-10 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-bolt text-xl"></i>
                                </div>
                                <h3 class="text-2xl font-black text-slate-900 mb-6 tracking-tight italic uppercase">HIZLI</h3>
                                <p class="text-sm text-slate-500">60 FPS Akıcı Deneyim</p>
                            </div>
                        </div>

                        <div class="p-1 rounded-[2.5rem] bg-slate-50 border border-slate-100 group hover:shadow-2xl transition-all duration-700">
                            <div class="bg-white p-10 rounded-[2.3rem] h-full transition-all">
                                 <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-10 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-shield-alt text-xl"></i>
                                </div>
                                <h3 class="text-2xl font-black text-slate-900 mb-6 tracking-tight italic uppercase">GÜVENLİ</h3>
                                <p class="text-sm text-slate-500">AES-256 Şifreleme</p>
                            </div>
                        </div>

                        <div class="lg:col-span-4 p-1 rounded-[3rem] bg-slate-900 border border-slate-800 shadow-2xl relative overflow-hidden group mt-6">
                             <div class="absolute inset-0 bg-primary/10 blur-[80px] rounded-full -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition duration-1000"></div>
                             <div class="flex flex-col lg:flex-row items-center gap-15 p-12 h-full relative z-10 text-center lg:text-left">
                                 <div class="lg:flex-1">
                                     <h4 class="text-3xl lg:text-4xl font-black text-white mb-6 tracking-tighter italic uppercase leading-none">Global <br> Dashboard.</h4>
                                     <p class="text-slate-400 text-sm font-bold leading-relaxed mb-10 italic uppercase tracking-widest opacity-80">Raporlama Artık Bir Sanat.</p>
                                     <a href="/login" class="inline-flex items-center gap-3 px-10 py-4 bg-white text-slate-900 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary hover:text-white transition-all active:scale-[0.98] shadow-lg">PANELE GİRİŞ <i class="fas fa-chevron-right text-[8px] opacity-50"></i></a>
                                 </div>
                                 <div class="lg:w-1/2 space-y-6">
                                     <div class="bg-white/5 border border-white/10 rounded-2xl p-8 backdrop-blur-xl">
                                         <div class="flex justify-between items-center mb-8">
                                             <span class="text-[9px] font-black text-slate-500 tracking-[0.4em] uppercase">Branch Performance</span>
                                             <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
                                         </div>
                                         <div class="h-2 bg-white/5 rounded-full overflow-hidden mb-4"><div class="h-full bg-primary w-[75%]"></div></div>
                                         <div class="h-2 bg-white/5 rounded-full overflow-hidden mb-4"><div class="h-full bg-slate-400 w-[45%]"></div></div>
                                         <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div class="h-full bg-primary w-[90%]"></div></div>
                                     </div>
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Preview -->
            <div class="py-48 relative overflow-hidden bg-slate-50/50">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="grid lg:grid-cols-2 gap-24 items-center">
                        <div class="relative order-2 lg:order-1">
                            <div class="relative bg-white rounded-3xl p-2 shadow-[0_40px_80px_-20px_rgba(15,23,42,0.1)] border border-slate-200/60 overflow-hidden">
                                <div class="bg-slate-900 rounded-2xl p-6 lg:p-10 relative overflow-hidden">
                                    <div class="flex items-center justify-between mb-12">
                                        <div class="flex gap-1.5">
                                            <div class="w-2.5 h-2.5 rounded-full bg-red-500/20 border border-red-500/30"></div>
                                            <div class="w-2.5 h-2.5 rounded-full bg-amber-500/20 border border-amber-500/30"></div>
                                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500/20 border border-emerald-500/30"></div>
                                        </div>
                                        <div class="px-4 py-1 rounded-full bg-white/5 border border-white/10 text-[9px] font-black text-slate-500 uppercase tracking-widest italic">Terminal Interface v.08</div>
                                    </div>
                                    
                                    <div class="grid grid-cols-12 gap-4 h-[350px]">
                                        <div class="col-span-8 bg-white/5 rounded-xl border border-white/5 p-6 relative group/inner">
                                            <div class="flex justify-between items-start mb-10">
                                                <div class="w-12 h-12 rounded-xl bg-primary/20 flex items-center justify-center text-primary group-hover/inner:bg-primary group-hover/inner:text-white transition-all shadow-lg shadow-primary/10"><i class="fas fa-th-large"></i></div>
                                                <div class="text-right">
                                                    <p class="text-[10px] font-black text-white italic uppercase tracking-widest leading-none mb-1">Masa Düzenİ</p>
                                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Floor Plan v2</p>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-4 gap-3">
                                                <!-- Active Tables -->
                                                <div class="h-6 rounded-md bg-white/5 border border-white/5 bg-primary/40 border-primary/40 shadow-[0_0_15px_rgba(79,70,229,0.3)]"></div>
                                                <div class="h-6 rounded-md bg-white/5 border border-white/5 bg-primary/40 border-primary/40 shadow-[0_0_15px_rgba(79,70,229,0.3)]"></div>
                                                <div class="h-6 rounded-md bg-white/5 border border-white/5 bg-primary/40 border-primary/40 shadow-[0_0_15px_rgba(79,70,229,0.3)]"></div>
                                                <div class="h-6 rounded-md bg-white/5 border border-white/5 bg-primary/40 border-primary/40 shadow-[0_0_15px_rgba(79,70,229,0.3)]"></div>
                                                <!-- Inactive Tables -->
                                                <div class="h-6 rounded-md bg-white/5 border border-white/5"></div>
                                                <div class="h-6 rounded-md bg-white/5 border border-white/5"></div>
                                                <div class="h-6 rounded-md bg-white/5 border border-white/5"></div>
                                                <div class="h-6 rounded-md bg-white/5 border border-white/5"></div>
                                                <div class="h-6 rounded-md bg-white/5 border border-white/5"></div>
                                                <div class="h-6 rounded-md bg-white/5 border border-white/5"></div>
                                                <div class="h-6 rounded-md bg-white/5 border border-white/5"></div>
                                                <div class="h-6 rounded-md bg-white/5 border border-white/5"></div>
                                            </div>
                                        </div>
                                        <div class="col-span-4 space-y-4">
                                            <div class="h-1/2 bg-white/5 rounded-xl border border-white/5 p-6 flex flex-col items-center justify-center text-center group cursor-pointer hover:bg-white/10 transition">
                                                <i class="fas fa-print text-2xl text-slate-500 mb-4 group-hover:text-emerald-500 transition"></i>
                                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none">Receipt</p>
                                            </div>
                                            <div class="h-1/2 bg-white/5 rounded-xl border border-white/5 p-6 flex flex-col justify-center gap-3">
                                                <div class="h-1 bg-white/10 rounded-full w-full"></div>
                                                <div class="h-1 bg-white/10 rounded-full w-2/3"></div>
                                                <div class="mt-2 text-primary font-black text-lg tracking-tighter leading-none italic">₺842.50</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="absolute -bottom-8 -right-8 w-48 h-48 bg-primary/20 blur-[60px] rounded-full pointer-events-none"></div>
                            </div>
                        </div>

                        <div class="order-1 lg:order-2">
                            <div class="w-16 h-1 bg-primary mb-10"></div>
                            <h2 class="text-5xl lg:text-7xl font-black text-slate-900 mb-10 tracking-tighter leading-[0.95] italic uppercase">Her Pİkselde <br> Rafİne Dokunuş.</h2>
                            <p class="text-base text-slate-500 font-semibold leading-relaxed mb-12">Karmaşık butonlar ve devasa kartlar yok. Personelin hızını kesmeyecek kadar ince, gücünü hissettirecek kadar keskin bir tasarım dili.</p>
                            
                            <div class="grid sm:grid-cols-2 gap-10">
                                <div class="space-y-3">
                                    <p class="text-[11px] font-black text-primary uppercase tracking-[0.3em] mb-4">Core Principles</p>
                                    <h4 class="text-lg font-black text-slate-900 uppercase italic leading-none">Mİnİmal Görsel</h4>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest leading-relaxed">Bilişsel yükü azaltan, odaklanmış arayüz.</p>
                                </div>
                                <div class="space-y-3">
                                    <p class="text-[11px] font-black text-primary uppercase tracking-[0.3em] mb-4">Architecture</p>
                                    <h4 class="text-lg font-black text-slate-900 uppercase italic leading-none">Swift Logic</h4>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest leading-relaxed">Her ekran boyutuna tam uyumlu yapı.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- CTA Section -->
            <div class="py-48 relative border-t border-slate-100 bg-white shadow-2xl overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_120%,rgba(79,70,229,0.05),transparent_70%)] pointer-events-none"></div>
                <div class="mx-auto max-w-4xl px-6 text-center relative z-10">
                    <h2 class="text-5xl lg:text-8xl font-black text-slate-900 mb-12 tracking-tighter italic uppercase leading-none">Şİmdİ <br> Tanışın.</h2>
                    <p class="text-base text-slate-400 mb-16 font-extrabold uppercase tracking-[0.4em] italic opacity-60">Sınırları ortadan kaldırın</p>
                    
                    <div class="flex flex-col sm:flex-row justify-center gap-6">
                        <a href="<?php echo route('pages.pos.versions'); ?>" class="px-12 py-5 bg-slate-900 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] hover:bg-primary transition-all flex items-center justify-center gap-4 shadow-2xl shadow-primary/20 group active:scale-[0.98]">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-colors">
                                <i class="fab fa-windows text-lg"></i>
                            </div>
                            <span>TERMİNALİ İNDİR</span>
                        </a>
                        <a href="/register" class="px-12 py-5 bg-white text-slate-900 border border-slate-200 rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] hover:bg-slate-50 transition-all flex items-center justify-center active:scale-[0.98] shadow-sm">
                            <span>HESAP OLUŞTUR</span>
                        </a>
                    </div>
                    
                    <p class="mt-24 text-[10px] font-black text-slate-300 uppercase tracking-[0.8em] italic">RezerVistA Engineering</p>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-100 py-12">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-slate-400 text-sm font-medium">
                &copy; <?php echo date('Y'); ?> Rezervist. Tüm hakları saklıdır.
            </p>
        </div>
    </footer>
</body>
</html>
