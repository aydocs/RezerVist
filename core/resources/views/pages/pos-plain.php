<!DOCTYPE html>
<html lang="<?php echo str_replace('_', '-', app()->getLocale()); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    
    <title>RezerVistA POS | Yetenekli, Hızlı, Keskin.</title>
    <meta name="description" content="Türkiye'nin en gelişmiş restoran POS ve adisyon sistemi. Masa yönetimi, sipariş takibi, stok kontrolü ve bulut senkronizasyon.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- TailwindCSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#6200EE',
                        primaryHover: '#5000D3',
                        surface: '#FAFAFA',
                        surfaceDark: '#121212',
                        borderLight: '#E5E7EB',
                        borderDark: '#27272A',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'drift': 'drift 20s linear infinite',
                        'fade-in-up': 'fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        drift: {
                            '0%': { transform: 'translateX(0) translateY(0)' },
                            '100%': { transform: 'translateX(-50px) translateY(-50px)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    },
                    boxShadow: {
                        'premium': '0 20px 40px -10px rgba(0,0,0,0.08)',
                        'premium-hover': '0 30px 60px -15px rgba(98, 0, 238, 0.15)',
                        'solid-dark': '0 25px 50px -12px rgba(0, 0, 0, 0.5)',
                    }
                }
            }
        }
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { 
            -webkit-font-smoothing: antialiased; 
            -moz-osx-font-smoothing: grayscale; 
        }
        .reveal.active { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .reveal { opacity: 0; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        
        .solid-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, #f1f5f9 1px, transparent 1px),
                              linear-gradient(to bottom, #f1f5f9 1px, transparent 1px);
        }
        .dark-solid-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, #1f2937 1px, transparent 1px),
                              linear-gradient(to bottom, #1f2937 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-surface text-slate-800 selection:bg-primary selection:text-white" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
    
    <!-- Navbar -->
    <nav :class="{'bg-white shadow-sm border-b border-borderLight': scrolled, 'bg-transparent': !scrolled}" class="fixed top-0 w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white font-bold text-xl transition-transform group-hover:scale-105">
                        <i class="fas fa-bolt text-lg"></i>
                    </div>
                    <span class="text-xl font-black text-slate-900 tracking-tight">RezerVist<span class="text-primary">A</span></span>
                </a>
                <div class="hidden md:flex items-center gap-6">
                    <a href="/" class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors">Ana Sayfa</a>
                    <a href="/business-partner" class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors">İşletme</a>
                    <a href="/login" class="px-6 py-2.5 bg-slate-900 text-white rounded-lg text-sm font-semibold hover:bg-primary transition-colors shadow-premium">Panele Gir</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-white solid-grid">
        <div class="absolute inset-x-0 top-0 h-96 bg-gradient-to-b from-white to-transparent"></div>
        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 grid lg:grid-cols-2 gap-16 items-center">
            <div class="z-10 text-center lg:text-left" x-data x-intersect="$el.classList.add('active')" class="reveal">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100 text-primary text-xs font-bold tracking-wide uppercase mb-8 border border-slate-200">
                    <i class="fas fa-circle text-[8px] text-emerald-500 animate-pulse"></i>
                    Yeni Nesil Terminal
                </div>
                <h1 class="text-5xl lg:text-[5rem] font-black text-slate-900 tracking-tight leading-[1] mb-8">
                    Siz Yönetin,<br>
                    <span class="text-primary">Teknoloji Çalışsın.</span>
                </h1>
                <p class="text-lg text-slate-600 mb-10 leading-relaxed font-medium max-w-xl mx-auto lg:mx-0">
                    Hantal arayüzlere veda edin. RezerVistA POS, restoran trafiğinizi yönetmek için tasarlanmış, saf güce sahip, keskin ve yüksek performanslı bir işletim sistemidir.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="<?php echo route('pages.pos.versions'); ?>" class="px-8 py-4 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primaryHover transition-colors flex items-center justify-center gap-3 shadow-[0_10px_20px_rgba(98,0,238,0.2)]">
                        <i class="fab fa-windows text-lg"></i>
                        Terminali İndir
                    </a>
                    <a href="/business-partner" class="px-8 py-4 bg-white text-slate-900 border border-slate-200 rounded-xl font-bold text-sm hover:border-slate-300 hover:bg-slate-50 transition-colors flex items-center justify-center gap-3 shadow-sm">
                        Demo Talebi
                    </a>
                </div>
            </div>
            
            <div class="relative z-10 hidden lg:block" x-data x-intersect="$el.classList.add('active')" class="reveal delay-200">
                <div class="relative w-full aspect-square max-w-lg mx-auto">
                    <!-- Solid Product Representation -->
                    <div class="absolute inset-0 bg-slate-900 rounded-[2.5rem] shadow-solid-dark transform rotate-3 flex flex-col overflow-hidden border border-slate-800">
                        <div class="h-12 bg-slate-800 border-b border-slate-700 flex items-center px-6 gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                            <div class="ml-4 px-2 py-0.5 bg-slate-700 rounded text-[10px] text-slate-300 font-mono">localhost:8000</div>
                        </div>
                        <div class="flex-1 p-6 grid grid-cols-12 gap-4">
                            <!-- Sidebar -->
                            <div class="col-span-3 space-y-3">
                                <div class="h-8 bg-slate-800 rounded-md"></div>
                                <div class="h-8 bg-primary rounded-md"></div>
                                <div class="h-8 bg-slate-800 rounded-md"></div>
                                <div class="h-8 bg-slate-800 rounded-md"></div>
                            </div>
                            <!-- Main Content Area -->
                            <div class="col-span-9 space-y-4">
                                <!-- Top Stats -->
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="h-20 bg-slate-800 rounded-xl border border-slate-700 p-3">
                                        <div class="w-6 h-6 rounded bg-emerald-500/20 text-emerald-500 flex items-center justify-center text-xs mb-2"><i class="fas fa-chart-line"></i></div>
                                        <div class="w-1/2 h-3 bg-slate-600 rounded"></div>
                                    </div>
                                    <div class="h-20 bg-slate-800 rounded-xl border border-slate-700 p-3">
                                        <div class="w-6 h-6 rounded bg-blue-500/20 text-blue-500 flex items-center justify-center text-xs mb-2"><i class="fas fa-users"></i></div>
                                        <div class="w-2/3 h-3 bg-slate-600 rounded"></div>
                                    </div>
                                    <div class="h-20 bg-slate-800 rounded-xl border border-slate-700 p-3">
                                        <div class="w-6 h-6 rounded bg-amber-500/20 text-amber-500 flex items-center justify-center text-xs mb-2"><i class="fas fa-clock"></i></div>
                                        <div class="w-1/2 h-3 bg-slate-600 rounded"></div>
                                    </div>
                                </div>
                                <!-- Table Chart/Grid -->
                                <div class="bg-slate-800 rounded-xl border border-slate-700 h-48 p-4">
                                    <div class="w-1/4 h-4 bg-slate-600 rounded mb-4"></div>
                                    <div class="grid grid-cols-4 gap-2 h-32">
                                        <div class="bg-primary/20 border border-primary/30 rounded-lg"></div>
                                        <div class="bg-slate-700 rounded-lg"></div>
                                        <div class="bg-slate-700 rounded-lg"></div>
                                        <div class="bg-primary/20 border border-primary/30 rounded-lg"></div>
                                        <div class="bg-slate-700 rounded-lg"></div>
                                        <div class="bg-red-500/20 border border-red-500/30 rounded-lg"></div>
                                        <div class="bg-slate-700 rounded-lg"></div>
                                        <div class="bg-slate-700 rounded-lg"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Floating Solid Accent -->
                    <div class="absolute -right-8 top-16 bg-white p-5 rounded-2xl shadow-premium border border-slate-100 flex items-center gap-4 animate-float">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">Performans</p>
                            <p class="text-sm font-black text-slate-900">Sıfır Gecikme</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Solid Bento Features -->
    <section class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="mb-16 max-w-2xl" x-data x-intersect="$el.classList.add('active')" class="reveal">
                <h2 class="text-4xl font-black text-slate-900 tracking-tight mb-4">Saf Teknoloji, <br>Kesin Sonuçlar.</h2>
                <p class="text-lg text-slate-600 font-medium">Karmaşıklıktan uzak, tamamen performans ve hız odaklı geliştirilmiş altyapı.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Feature 1 -->
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200 hover:border-primary transition-colors flex flex-col reveal delay-100" x-data x-intersect="$el.classList.add('active')">
                    <div class="w-14 h-14 bg-white rounded-2xl border border-slate-200 flex items-center justify-center text-primary text-2xl mb-8 shadow-sm">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-3">Hibrit Mimari</h3>
                    <p class="text-slate-600 font-medium leading-relaxed mb-6 flex-1">
                        İnternet kesintileri işinizi durduramaz. Tamamen yerel ağda çalışıp, bağlantı geldiğinde bulutla pürüzsüzce senkronize olan dayanıklı yapı.
                    </p>
                    <div class="inline-flex items-center gap-2 text-sm font-bold text-slate-900">
                        <i class="fas fa-check text-emerald-500"></i> Çevrimdışı Çalışma
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="bg-slate-900 rounded-3xl p-8 border border-slate-800 text-white flex flex-col reveal delay-200" x-data x-intersect="$el.classList.add('active')">
                    <div class="w-14 h-14 bg-slate-800 rounded-2xl border border-slate-700 flex items-center justify-center text-amber-400 text-2xl mb-8">
                        <i class="fas fa-fighter-jet"></i>
                    </div>
                    <h3 class="text-xl font-black text-white mb-3">Tavizsiz Hız</h3>
                    <p class="text-slate-400 font-medium leading-relaxed mb-6 flex-1">
                        Yoğun saatlerde saniyelerin bile önemi var. RezerVistA donanımınızı son damlasına kadar kullanarak 60FPS akıcılığında tepki verir.
                    </p>
                    <div class="inline-flex items-center gap-2 text-sm font-bold text-white">
                        <i class="fas fa-check text-emerald-400"></i> Anında İşlem
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200 hover:border-primary transition-colors flex flex-col reveal delay-300" x-data x-intersect="$el.classList.add('active')">
                    <div class="w-14 h-14 bg-white rounded-2xl border border-slate-200 flex items-center justify-center text-blue-600 text-2xl mb-8 shadow-sm">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-3">Kurumsal Güvenlik</h3>
                    <p class="text-slate-600 font-medium leading-relaxed mb-6 flex-1">
                        Tüm sipariş verileriniz uçtan uca şifrelenir. Yetkisiz erişimler ve veri kayıpları tarihe karışır. Güvenilir yedekleme standarttır.
                    </p>
                    <div class="inline-flex items-center gap-2 text-sm font-bold text-slate-900">
                        <i class="fas fa-check text-emerald-500"></i> AES-256 Şifreleme
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- UI Snapshot Section -->
    <section class="py-24 bg-slate-900 text-white dark-solid-grid relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent z-0"></div>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 flex flex-col lg:flex-row items-center gap-16">
            
            <div class="lg:w-1/2 w-full" x-data x-intersect="$el.classList.add('active')" class="reveal">
                <div class="bg-slate-800 border-l-4 border-primary p-8 rounded-r-2xl shadow-2xl relative">
                    <div class="absolute -top-4 -right-4 w-12 h-12 bg-primary rounded-xl flex items-center justify-center text-white rotate-12 shadow-lg">
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <h3 class="text-2xl font-black tracking-tight mb-4">Her Şey Kontrol Altında</h3>
                    <p class="text-slate-400 text-lg leading-relaxed mb-8">
                        Personel performansından ciro raporlarına, stok analizlerinden iptal edilen ürünlere kadar aradığınız her veri saniyeler içinde önünüzde.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-700 pb-2">
                            <span class="text-slate-300 font-semibold text-sm">Masalar (Dolu/Boş)</span>
                            <span class="text-emerald-400 font-bold">%85 Doluluk</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-700 pb-2">
                            <span class="text-slate-300 font-semibold text-sm">Paket Servis Yoğunluğu</span>
                            <span class="text-amber-400 font-bold">Yüksek</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-300 font-semibold text-sm">Günlük Ciro Hedefi</span>
                            <span class="text-primary font-bold">Tamamlandı</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:w-1/2 w-full text-center lg:text-left" x-data x-intersect="$el.classList.add('active')" class="reveal delay-200">
                <h2 class="text-4xl lg:text-5xl font-black tracking-tight mb-6">Detaylı, Ama Bir O Kadar <span class="text-primary">Sade.</span></h2>
                <p class="text-lg text-slate-400 leading-relaxed mb-8">
                    Dashboard ekranınız size işinize dair gerçek zamanlı içgörüler sunar. Göz yormayan, net renk paletleri ve yüksek kontrast oranlarıyla, bilgiye erişmek hiç bu kadar zahmetsiz olmamıştı.
                </p>
                <ul class="space-y-3 text-left max-w-md mx-auto lg:mx-0">
                    <li class="flex items-center gap-3 text-slate-300 font-medium">
                        <div class="w-6 h-6 rounded-full bg-slate-800 text-primary flex items-center justify-center text-xs"><i class="fas fa-check"></i></div>
                        Gelişmiş Filtreleme
                    </li>
                    <li class="flex items-center gap-3 text-slate-300 font-medium">
                        <div class="w-6 h-6 rounded-full bg-slate-800 text-primary flex items-center justify-center text-xs"><i class="fas fa-check"></i></div>
                        Veri İhracı (Excel, PDF)
                    </li>
                    <li class="flex items-center gap-3 text-slate-300 font-medium">
                        <div class="w-6 h-6 rounded-full bg-slate-800 text-primary flex items-center justify-center text-xs"><i class="fas fa-check"></i></div>
                        Personel Yetkilendirme
                    </li>
                </ul>
            </div>
            
        </div>
    </section>

    <!-- Bottom CTA CTA -->
    <section class="py-24 bg-white border-t border-slate-100">
        <div class="max-w-4xl mx-auto px-6 text-center" x-data x-intersect="$el.classList.add('active')" class="reveal">
            <h2 class="text-4xl lg:text-5xl font-black text-slate-900 tracking-tight mb-6">Aksiyon Zamanı.</h2>
            <p class="text-xl text-slate-600 mb-10 max-w-2xl mx-auto">
                Modern bir işletmenin ihtiyaç duyduğu tüm kontrol mekanizmalarına anında erişin. Eski kaba yazılımlara vedanın vakti geldi.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo route('pages.pos.versions'); ?>" class="px-10 py-4 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-black transition-colors shadow-premium hover:shadow-solid-dark flex items-center justify-center gap-2">
                    <i class="fab fa-windows"></i> Hemen İndir
                </a>
                <a href="/register" class="px-10 py-4 bg-white text-slate-900 border border-slate-300 rounded-xl font-bold text-sm hover:bg-slate-50 transition-colors flex items-center justify-center">
                    Yeni İşletme Kaydı
                </a>
            </div>
        </div>
    </section>

    <!-- Solid Footer -->
    <footer class="bg-slate-50 border-t border-slate-200 py-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-slate-900 rounded text-white flex items-center justify-center text-[10px] font-bold">R</div>
                <span class="font-bold text-slate-900 text-sm tracking-tight">RezerVist</span>
            </div>
            <p class="text-slate-500 text-sm font-medium">
                &copy; <?php echo date('Y'); ?> RezerVist Engineering. Tüm hakları saklıdır.
            </p>
        </div>
    </footer>

</body>
</html>
