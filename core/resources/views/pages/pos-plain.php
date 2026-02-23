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
                        'inner-subtle': 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.05)',
                        'card': '0 10px 30px -5px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02)',
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
            background-image: radial-gradient(#E2E8F0 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .pos-window {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.15), 0 18px 36px -18px rgba(0, 0, 0, 0.2);
        }
        .receipt-shadow {
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .text-gradient {
            background: linear-gradient(135deg, #1E293B 0%, #64748B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 5s ease-in-out infinite;
        }
    </style>
</head>
<body class="text-slate-600 font-sans selection:bg-brand-100 selection:text-brand-600">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 border-b border-slate-100 bg-white/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-4 group">
                <div class="w-10 h-10 bg-brand-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-brand-500/20">
                    <i class="fas fa-bolt"></i>
                </div>
                <span class="text-xl font-black text-slate-900 tracking-tighter">RezerVist<span class="text-brand-500">A</span></span>
            </a>
            <div class="flex items-center gap-8">
                <a href="/" class="text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-brand-500 transition-colors">Ana Sayfa</a>
                <a href="/login" class="px-6 py-2.5 bg-brand-500 text-white rounded-lg text-xs font-black uppercase tracking-widest hover:bg-brand-600 transition-all shadow-md">Giriş Yap</a>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section: Premium Light -->
        <section class="relative pt-40 pb-32 lg:pt-56 lg:pb-64 dot-pattern overflow-hidden">
            <div class="absolute inset-x-0 bottom-0 h-64 bg-gradient-to-t from-white to-transparent"></div>
            
            <div class="max-w-7xl mx-auto px-6 relative z-10 grid lg:grid-cols-12 gap-20 items-center">
                
                <!-- Hero Content -->
                <div class="lg:col-span-5 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-brand-50 border border-brand-100 rounded-full text-[10px] font-black tracking-widest text-brand-500 uppercase mb-10">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span>
                        Yeni Nesil POS Deneyimi
                    </div>
                    
                    <h1 class="text-5xl lg:text-7xl font-black text-slate-950 tracking-tight leading-[1] mb-10 font-display">
                        <span class="text-gradient">İşletmenizi</span> <br>
                        <span class="text-brand-500 italic">Yeniden <br> Tanımlayın.</span>
                    </h1>
                    
                    <p class="text-lg text-slate-500 font-medium leading-relaxed mb-12 max-w-xl mx-auto lg:mx-0">
                        Karmaşık sistemleri unutun. RezerVistA POS ile hız, güvenlik ve kusursuz bir kullanıcı deneyimi tek bir platformda birleşiyor.
                    </p>
                    
                    <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                        <a href="<?php echo route('pages.pos.versions'); ?>" class="px-10 py-5 bg-slate-950 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-brand-500 transition-all shadow-xl shadow-slate-950/10 flex items-center gap-3">
                            <i class="fab fa-windows"></i> Hemen İndir
                        </a>
                        <a href="/business-partner" class="px-10 py-5 bg-white border border-slate-200 text-slate-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-50 transition-all flex items-center gap-3">
                            ÜCRETSİZ DEMO
                        </a>
                    </div>
                </div>

                <!-- Realistic POS Window Mockup -->
                <div class="lg:col-span-7 relative">
                    <div class="pos-window rounded-[2.5rem] p-3 overflow-hidden animate-float">
                        <div class="bg-slate-50 rounded-[2rem] h-[500px] overflow-hidden flex flex-col border border-slate-200 shadow-inner-subtle">
                            <!-- Window Header -->
                            <div class="h-14 bg-white border-b border-slate-200 px-6 flex items-center justify-between">
                                <div class="flex items-center gap-6">
                                    <div class="flex gap-1.5">
                                        <div class="w-3 h-3 rounded-full bg-slate-200"></div>
                                        <div class="w-3 h-3 rounded-full bg-slate-200"></div>
                                        <div class="w-3 h-3 rounded-full bg-slate-200"></div>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">RezerVistA Terminal v4.0</span>
                                </div>
                                <div class="flex items-center gap-3 text-slate-400">
                                    <i class="fas fa-wifi text-[10px]"></i>
                                    <span class="text-[10px] font-bold">12:45 PM</span>
                                </div>
                            </div>
                            
                            <div class="flex-1 flex overflow-hidden">
                                <!-- POS Sidebar (Categories) -->
                                <div class="w-20 bg-white border-r border-slate-200 py-6 flex flex-col items-center gap-6">
                                    <div class="w-10 h-10 bg-brand-500 text-white rounded-xl flex items-center justify-center text-sm shadow-md">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                    <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center text-sm">
                                        <i class="fas fa-coffee"></i>
                                    </div>
                                    <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center text-sm">
                                        <i class="fas fa-wine-glass"></i>
                                    </div>
                                    <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center text-sm">
                                        <i class="fas fa-ice-cream"></i>
                                    </div>
                                </div>
                                
                                <!-- POS Main View (Tables/Grid) -->
                                <div class="flex-1 p-6 overflow-y-auto">
                                    <div class="flex items-center justify-between mb-8">
                                        <h3 class="text-sm font-black text-slate-900 uppercase">Zemin Kat - Salon</h3>
                                        <div class="flex gap-2">
                                            <div class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-bold">12 Boş</div>
                                            <div class="px-3 py-1 bg-brand-50 text-brand-600 rounded-full text-[9px] font-bold">8 Dolu</div>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-4 gap-4">
                                        <template x-for="i in 12">
                                            <div class="aspect-square rounded-2xl border-2 flex flex-col items-center justify-center gap-2 transition-all cursor-pointer"
                                                 :class="[1, 3, 6, 8, 10].includes(i) ? 'bg-white border-slate-200' : (i == 2 ? 'bg-brand-500 border-brand-500 shadow-lg shadow-brand-500/20' : 'bg-brand-50 border-brand-100')">
                                                <span class="text-[10px] font-black uppercase" :class="i == 2 ? 'text-white' : 'text-slate-400'">Masa</span>
                                                <span class="text-lg font-black" :class="i == 2 ? 'text-white' : 'text-slate-900'" x-text="i"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- POS Order (Receipt) -->
                                <div class="w-64 bg-white border-l border-slate-200 flex flex-col p-5">
                                    <div class="mb-6">
                                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Masa 2</div>
                                        <div class="text-xs font-black text-slate-900">Aktif Sipariş</div>
                                    </div>
                                    
                                    <div class="flex-1 space-y-4 overflow-y-auto">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="text-[11px] font-bold text-slate-800">Antrikot Izgara</div>
                                                <div class="text-[9px] text-slate-400">Orta Pişmiş</div>
                                            </div>
                                            <div class="text-[11px] font-black text-slate-900">₺450</div>
                                        </div>
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="text-[11px] font-bold text-slate-800">Sezar Salata</div>
                                            </div>
                                            <div class="text-[11px] font-black text-slate-900">₺220</div>
                                        </div>
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="text-[11px] font-bold text-slate-800">Coca Cola (x2)</div>
                                            </div>
                                            <div class="text-[11px] font-black text-slate-900">₺140</div>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-5 border-t border-slate-100 mt-5">
                                        <div class="flex justify-between items-center mb-6">
                                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Toplam</span>
                                            <span class="text-xl font-black text-slate-950">₺810,00</span>
                                        </div>
                                        <button class="w-full py-3 bg-brand-500 text-white rounded-xl text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-brand-500/20">ÖDEME AL</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating Sales Card -->
                    <div class="absolute -bottom-10 -left-10 bg-white p-6 rounded-3xl shadow-premium border border-slate-100 flex items-center gap-5 z-20 hover:scale-105 transition-transform">
                        <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Günlük Ciro</p>
                            <p class="text-xl font-black text-slate-900">₺14,820</p>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="text-[9px] font-bold text-emerald-500">+12.5%</span>
                                <span class="text-[9px] text-slate-400 font-medium tracking-tight">düne göre</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Bento: Light Luxe -->
        <section class="py-32 bg-slate-50 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="text-center mb-24">
                    <h2 class="text-4xl lg:text-5xl font-black text-slate-950 tracking-tight leading-tighter mb-6 font-display">Teknolojinin Kalbi Burada.</h2>
                    <p class="text-slate-500 font-medium max-w-2xl mx-auto">Her detayı titizlikle düşünülmüş, işletmenizi bir adım öteye taşıyacak profesyonel araçlar.</p>
                </div>

                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white p-10 rounded-[2.5rem] border border-slate-200 shadow-card flex flex-col group hover:border-brand-500/50 transition-colors">
                        <div class="w-16 h-16 bg-brand-50 text-brand-500 rounded-2xl flex items-center justify-center text-3xl mb-12 shadow-inner-subtle group-hover:bg-brand-500 group-hover:text-white transition-all">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-4 italic">Kusursuz Hız</h3>
                        <p class="text-slate-500 font-medium leading-relaxed mb-8 flex-1">En yoğun saatlerde bile donmayan, kasmayan ve anında yanıt veren bir arayüz ile operasyonunuz aksamasın.</p>
                        <div class="h-1 w-12 bg-brand-500"></div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white p-10 rounded-[2.5rem] border border-slate-200 shadow-card flex flex-col group hover:border-emerald-500/50 transition-colors">
                        <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-3xl mb-12 shadow-inner-subtle group-hover:bg-emerald-500 group-hover:text-white transition-all">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-4 italic">Üstün Güvenlik</h3>
                        <p class="text-slate-500 font-medium leading-relaxed mb-8 flex-1">Verileriniz bizimle güvende. Uçtan uca şifreleme ve gelişmiş kullanıcı yetkilendirme sistemleri ile her şey kontrolünüzde.</p>
                        <div class="h-1 w-12 bg-emerald-500"></div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white p-10 rounded-[2.5rem] border border-slate-200 shadow-card flex flex-col group hover:border-slate-900/50 transition-colors">
                        <div class="w-16 h-16 bg-slate-100 text-slate-800 rounded-2xl flex items-center justify-center text-3xl mb-12 shadow-inner-subtle group-hover:bg-slate-900 group-hover:text-white transition-all">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-4 italic">Hibrit Altyapı</h3>
                        <p class="text-slate-500 font-medium leading-relaxed mb-8 flex-1">İnternet bağlantınız kopsa dahi terminaliniz çalışmaya devam eder. Bağlantı geldiğinde verileriniz otomatik senkronize olur.</p>
                        <div class="h-1 w-12 bg-slate-950"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Product Philosophy: Light/Dark Contrast -->
        <section class="py-40 bg-white">
            <div class="max-w-7xl mx-auto px-6 flex flex-col lg:flex-row items-center gap-24">
                <div class="lg:w-1/2">
                    <h2 class="text-5xl lg:text-7xl font-black text-slate-950 tracking-tighter leading-[0.95] mb-12 uppercase italic">Arayüz Değil, <br> <span class="text-brand-500">Standart.</span></h2>
                    <p class="text-xl text-slate-500 font-medium leading-relaxed mb-12">
                        RezerVistA bir yazılımdan fazlasıdır. İhtiyacınız olan her özelliği, en sade ve en şık haliyle işletmenizin merkezine yerleştirir. Karmaşıklığa yer yok.
                    </p>
                    <div class="grid grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <div class="text-5xl font-black text-slate-950 tracking-tighter">99.9%</div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Hizmet Süresi</div>
                        </div>
                        <div class="space-y-4">
                            <div class="text-5xl font-black text-brand-500 tracking-tighter">&infin;</div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Sınırsız Kapasite</div>
                        </div>
                    </div>
                </div>
                
                <div class="lg:w-1/2 w-full">
                    <!-- Realistic Dashboard Feature Card -->
                    <div class="bg-slate-950 rounded-[3rem] p-10 text-white shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-brand-500/10 blur-[100px]"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-16">
                                <h4 class="text-xl font-black uppercase tracking-tight">Akıllı Raporlama</h4>
                                <div class="px-4 py-1.5 bg-brand-500 text-white rounded-full text-[10px] font-bold uppercase tracking-widest">Canlı</div>
                            </div>
                            
                            <div class="space-y-8 mb-16">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-end">
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Haftalık Satış Hedefi</span>
                                        <span class="text-sm font-black">₺140,000 / ₺200,000</span>
                                    </div>
                                    <div class="h-2 bg-white/5 rounded-full overflow-hidden">
                                        <div class="h-full bg-brand-500 w-[70%]" style="box-shadow: 0 0 15px #6200EE;"></div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-3 gap-6">
                                    <div class="bg-white/5 p-5 rounded-2xl border border-white/5">
                                        <div class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-3">Siparişler</div>
                                        <div class="text-2xl font-black">1.2k</div>
                                    </div>
                                    <div class="bg-white/5 p-5 rounded-2xl border border-white/5">
                                        <div class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-3">Ort. Sepet</div>
                                        <div class="text-2xl font-black">₺340</div>
                                    </div>
                                    <div class="bg-white/5 p-5 rounded-2xl border border-white/5">
                                        <div class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-3">İptaller</div>
                                        <div class="text-2xl font-black text-emerald-400">-4%</div>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-slate-400 font-medium text-sm">
                                Tek bir panel üzerinden tüm şubelerinizin verilerini eş zamanlı takip edin, analiz edin ve işletmenizi büyütün.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final Call to Action -->
        <section class="py-48 bg-slate-950 text-center relative overflow-hidden">
            <div class="absolute inset-0 dot-pattern opacity-10"></div>
            <div class="max-w-4xl mx-auto px-6 relative z-10">
                <h2 class="text-6xl lg:text-8xl font-black text-white tracking-tighter uppercase italic leading-[0.9] mb-16">Deneyİme <br> Hazır mısın?</h2>
                <div class="flex flex-col sm:flex-row justify-center gap-6">
                    <a href="<?php echo route('pages.pos.versions'); ?>" class="px-12 py-6 bg-brand-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-brand-600 transition-all shadow-xl shadow-brand-500/20 flex items-center justify-center gap-4">
                        <i class="fab fa-windows text-xl"></i> TERMİNALİ İNDİR
                    </a>
                    <a href="/register" class="px-12 py-6 bg-white text-slate-950 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-50 transition-all flex items-center justify-center gap-4">
                        HESAP OLUŞTUR
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-white border-t border-slate-100 py-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3 text-slate-900 transition-colors">
                <div class="w-8 h-8 rounded-lg bg-slate-950 text-white flex items-center justify-center font-black text-xs">R</div>
                <span class="font-bold text-sm tracking-widest uppercase">RezerVist</span>
            </div>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.4em]">© <?php echo date('Y'); ?> RezerVist Engineering. Tüm Hakları Saklıdır.</p>
        </div>
    </footer>

</body>
</html>
