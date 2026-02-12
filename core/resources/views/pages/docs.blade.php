@extends('layouts.app')

@section('title', 'Dokümantasyon | Rezervist')

@section('content')
<div class="bg-white min-h-screen font-sans selection:bg-primary/10 selection:text-primary" x-data="{ currentTab: 'intro', sidebarOpen: true }">
    <!-- Sub-Navbar for Search/Title -->
    <div class="border-b border-gray-100 sticky top-20 bg-white/80 backdrop-blur-xl z-40 hidden md:block">
        <div class="max-w-8xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary">
                    <i class="fas fa-book-open text-sm"></i>
                </div>
                <h1 class="font-black text-slate-800 uppercase tracking-widest text-xs italic">Yardım Merkezi & Dokümantasyon</h1>
            </div>
            <div class="flex items-center gap-6">
                <div class="relative group">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors text-sm"></i>
                    <input type="text" placeholder="Yardım dökümanlarında ara..." class="pl-11 pr-6 py-2 bg-gray-50 border-none rounded-xl text-sm w-80 focus:ring-2 focus:ring-primary/10 transition-all font-medium">
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-8xl mx-auto px-6 py-12">
        <div class="flex flex-col lg:flex-row gap-16 items-start">
            <!-- Sidebar Navigation -->
            <aside class="w-full lg:w-72 flex-shrink-0 sticky lg:top-48 z-30">
                <nav class="space-y-10">
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] px-4">Genel Bakış</h4>
                        <div class="space-y-1">
                            <button @click="currentTab = 'intro'" :class="currentTab === 'intro' ? 'bg-primary/5 text-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'" class="w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 font-bold text-sm transition-all group">
                                <i class="fas fa-flag-checkered w-5 text-center group-hover:scale-110 transition-transform"></i>
                                Giriş
                            </button>
                            <button @click="currentTab = 'quickstart'" :class="currentTab === 'quickstart' ? 'bg-primary/5 text-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'" class="w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 font-bold text-sm transition-all group">
                                <i class="fas fa-bolt w-5 text-center group-hover:scale-110 transition-transform"></i>
                                Hızlı Başlangıç
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] px-4">POS İşletimi</h4>
                        <div class="space-y-1">
                            <button @click="currentTab = 'terminal'" :class="currentTab === 'terminal' ? 'bg-primary/5 text-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'" class="w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 font-bold text-sm transition-all group">
                                <i class="fas fa-desktop w-5 text-center"></i>
                                Terminal Kurulumu
                            </button>
                            <button @click="currentTab = 'orders'" :class="currentTab === 'orders' ? 'bg-primary/5 text-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'" class="w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 font-bold text-sm transition-all group">
                                <i class="fas fa-utensils w-5 text-center"></i>
                                Adisyon Yönetimi
                            </button>
                            <button @click="currentTab = 'billing'" :class="currentTab === 'billing' ? 'bg-primary/5 text-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'" class="w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 font-bold text-sm transition-all group">
                                <i class="fas fa-receipt w-5 text-center"></i>
                                Tahsilat ve Faturalar
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] px-4">Yönetim Paneli</h4>
                        <div class="space-y-1">
                            <button @click="currentTab = 'menu_management'" :class="currentTab === 'menu_management' ? 'bg-primary/5 text-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'" class="w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 font-bold text-sm transition-all group">
                                <i class="fas fa-list-ul w-5 text-center"></i>
                                Menü & Fiyatlandırma
                            </button>
                            <button @click="currentTab = 'reports'" :class="currentTab === 'reports' ? 'bg-primary/5 text-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'" class="w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 font-bold text-sm transition-all group">
                                <i class="fas fa-chart-line w-5 text-center"></i>
                                Finansal Analiz
                            </button>
                        </div>
                    </div>
                </nav>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 max-w-4xl animate-in fade-in slide-in-from-bottom-4 duration-700">
                
                <!-- Introduction Content -->
                <div x-show="currentTab === 'intro'" class="space-y-10">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/5 border border-primary/10 rounded-full text-primary text-[10px] font-black tracking-widest uppercase mb-6">
                            <span class="flex h-1.5 w-1.5 rounded-full bg-primary animate-pulse"></span>
                            Versiyon 1.0.0 Pro
                        </div>
                        <h2 class="text-6xl font-black text-slate-900 tracking-tighter leading-none mb-8 italic uppercase">Geleceğİn <br> İşletme Yönetİmİ.</h2>
                        <p class="text-xl text-slate-500 font-medium leading-relaxed italic">
                            Rezervist, restoranınızın veya kafenizin tüm operasyonlarını tek bir merkezden yönetmenizi sağlayan premium bir ekosistemdir.
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-8 pt-6">
                        <div class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100 group hover:shadow-2xl transition-all duration-500">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-primary shadow-sm mb-6 group-hover:scale-110 transition-transform">
                                <i class="fas fa-check-circle text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-slate-900 mb-4 tracking-tight uppercase">Tam Senkronizasyon</h3>
                            <p class="text-slate-500 text-sm font-semibold leading-relaxed">
                                Terminal, Mobil Uygulama ve Web Paneli arasındaki veri aktarımı 150ms'nin altında gerçekleşir.
                            </p>
                        </div>
                        <div class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100 group hover:shadow-2xl transition-all duration-500">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-emerald-500 shadow-sm mb-6 group-hover:scale-110 transition-transform">
                                <i class="fas fa-shield-alt text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-slate-900 mb-4 tracking-tight uppercase">Kritik Güvenlik</h3>
                            <p class="text-slate-500 text-sm font-semibold leading-relaxed">
                                Tüm finansal verileriniz AES-256 bit şifreleme ve SSL sertifikalı altyapımızla korunur.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Terminal Setup Content -->
                <div x-show="currentTab === 'terminal'" class="space-y-10" x-cloak>
                    <div>
                        <h2 class="text-5xl font-black text-slate-900 tracking-tighter mb-6 uppercase italic">Terminal Kurulumu.</h2>
                        <p class="text-lg text-slate-500 font-medium leading-relaxed italic">
                            Windows cihazınıza RezerVistA POS Terminali'ni kurmak sadece 60 saniye sürer.
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div class="flex gap-6 items-start">
                            <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black flex-shrink-0">1</div>
                            <div>
                                <h4 class="text-lg font-black text-slate-900 uppercase tracking-tight mb-2">Yazılımı İndirin</h4>
                                <p class="text-slate-500 text-sm font-semibold mb-4 leading-relaxed">
                                    Kontrol Panelinizden veya <a href="#" class="text-primary hover:underline">indirme sayfasından</a> güncel terminal dosyasını indirin.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-6 items-start">
                            <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black flex-shrink-0">2</div>
                            <div>
                                <h4 class="text-lg font-black text-slate-900 uppercase tracking-tight mb-2">Lisans Anahtarı ile Eşleştirin</h4>
                                <p class="text-slate-500 text-sm font-semibold mb-4 leading-relaxed">
                                    İşletme Panelinizdeki "POS Cihazları" sekmesinden üreteceğiniz 64 karakterli anahtarı terminale yapıştırın.
                                </p>
                                <div class="bg-gray-900 p-4 rounded-xl text-emerald-400 font-mono text-xs overflow-x-auto border-l-4 border-emerald-500">
                                    fI1pZyjx6kHKmmGwphxqZcHESlWxmAsJSdgghc0UTTGw...
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-6 items-start">
                            <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black flex-shrink-0">3</div>
                            <div>
                                <h4 class="text-lg font-black text-slate-900 uppercase tracking-tight mb-2">Kullanmaya Başlayın</h4>
                                <p class="text-slate-500 text-sm font-semibold mb-4 leading-relaxed">
                                    Verileriniz otomatik olarak senkronize edilecek. Menünüz ve masalarınız terminalde hazır görünecektir.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-orange-50 border border-orange-100 p-8 rounded-[2rem] flex gap-6 items-start">
                        <div class="p-3 bg-orange-100 text-orange-600 rounded-xl">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h5 class="text-orange-900 font-black uppercase text-sm mb-2 italic">Önemli Hatırlatma</h5>
                            <p class="text-orange-800/70 text-xs font-bold leading-relaxed">
                                Terminal kurulumu için .NET Framework 4.8 veya üzeri yüklü olması gerekmektedir. Windows 10 ve 11 cihazlarda bu paket genellikle yüklü gelir.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Order Management Content -->
                <div x-show="currentTab === 'orders'" class="space-y-10" x-cloak>
                    <div>
                        <h2 class="text-5xl font-black text-slate-900 tracking-tighter mb-6 uppercase italic">Adisyon Yönetimi.</h2>
                        <p class="text-lg text-slate-500 font-medium leading-relaxed italic">
                            Verimlilik odaklı tasarlanan arayüzümüzle hatayı sıfıra indirin.
                        </p>
                    </div>

                    <div class="grid gap-6">
                        <div class="p-8 border border-gray-100 rounded-[2rem] bg-white shadow-sm hover:shadow-md transition-shadow">
                            <h4 class="text-primary font-black uppercase tracking-widest text-[10px] mb-4">Senaryo A</h4>
                            <h3 class="text-xl font-black text-slate-900 mb-4 tracking-tight">Masa Bazlı Sipariş Alımı</h3>
                            <ul class="space-y-3">
                                <li class="flex items-center gap-3 text-sm font-semibold text-slate-600">
                                    <i class="fas fa-circle text-[6px] text-primary"></i>
                                    Ekrandan kat veya alan seçimi yapın (Örn: Bahçe).
                                </li>
                                <li class="flex items-center gap-3 text-sm font-semibold text-slate-600">
                                    <i class="fas fa-circle text-[6px] text-primary"></i>
                                    Açık masaya tıklayıp kategorize edilmiş menüden ürünleri ekleyin.
                                </li>
                                <li class="flex items-center gap-3 text-sm font-semibold text-slate-600">
                                    <i class="fas fa-circle text-[6px] text-primary"></i>
                                    "Kaydet" butonuna bastığınızda mutfak fişi otomatik olarak yazdırılır.
                                </li>
                            </ul>
                        </div>

                        <div class="p-8 border border-gray-100 rounded-[2rem] bg-white shadow-sm hover:shadow-md transition-shadow">
                            <h4 class="text-primary font-black uppercase tracking-widest text-[10px] mb-4">Senaryo B</h4>
                            <h3 class="text-xl font-black text-slate-900 mb-4 tracking-tight">Hızlı Satış & Gel-Al</h3>
                            <p class="text-slate-500 text-sm font-semibold leading-relaxed">
                                Masa açmadan doğrudan satış yapmak için Dashboards üzerindeki "Hızlı Satış" butonunu kullanın. Ödeme yöntemi seçildiği an adisyon tamamlanır.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Billing Content -->
                <div x-show="currentTab === 'billing'" class="space-y-10" x-cloak>
                    <div>
                        <h2 class="text-5xl font-black text-slate-900 tracking-tighter mb-6 uppercase italic">Tahsilat & Faturalar.</h2>
                        <p class="text-lg text-slate-500 font-medium leading-relaxed italic">
                            Ödemelerinizi şeffaf bir şekilde yönetin ve raporlayın.
                        </p>
                    </div>

                    <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl overflow-hidden relative group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/20 blur-[60px] rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
                        <div class="relative z-10">
                            <h3 class="text-2xl font-black mb-6 italic uppercase tracking-tight">Ödeme Yöntemleri</h3>
                            <div class="grid sm:grid-cols-2 gap-6">
                                <div class="p-4 bg-white/5 border border-white/10 rounded-2xl flex items-center gap-4">
                                    <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center text-white">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">Nakit</p>
                                        <p class="text-[10px] font-bold text-slate-500">Hızlı kapanış sağlar.</p>
                                    </div>
                                </div>
                                <div class="p-4 bg-white/5 border border-white/10 rounded-2xl flex items-center gap-4">
                                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">Kredi Kartı</p>
                                        <p class="text-[10px] font-bold text-slate-500">Banka dökümleriyle uyumludur.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-xl font-black text-slate-900 tracking-tight uppercase">Günlük İcmal (Z Raporu)</h3>
                        <p class="text-slate-500 text-sm font-semibold leading-relaxed">
                            Vardiya sonunda Terminal üzerindeki "İşlemi Kapat" veya "Rapor Al" butonuna basarak o güne ait tüm nakit ve kart akışının detaylı özetini alabilir, dijital kopyasını PDF olarak saklayabilirsiniz.
                        </p>
                    </div>
                </div>

                <!-- Fallback content for unfinished tabs -->
                <div x-show="!['intro', 'terminal', 'orders', 'billing'].includes(currentTab)" class="py-32 flex flex-col items-center justify-center text-center opacity-40 italic" x-cloak>
                    <i class="fas fa-tools text-4xl mb-6"></i>
                    <h3 class="font-black text-xl uppercase tracking-tighter">İçerik Hazırlanıyor</h3>
                    <p class="text-xs font-bold uppercase tracking-widest mt-2">Bu dökümantasyon sayfası üzerinde geliştirme devam ediyor.</p>
                </div>

            </main>
        </div>
    </div>

    <!-- Footer CTA -->
    <div class="bg-gray-50 border-t border-gray-100 py-32 mt-32 overflow-hidden relative">
        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter mb-8 italic uppercase">Sorularınız <br> Cevapsız Kalmasın.</h2>
            <p class="text-slate-500 font-bold mb-12 uppercase tracking-widest text-xs italic">Daha fazla yardım mı gerekiyor? Canlı destek ekibimizle iletişime geçin.</p>
            <div class="flex justify-center gap-6">
                <a href="/live-support" class="px-10 py-5 bg-primary text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] shadow-2xl shadow-primary/30 hover:bg-slate-900 transition-all active:scale-95">Destek Talebİ Oluştur</a>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    
    /* Layout adjustments for Navbar conflict */
    main { min-height: 80vh; }
    
    /* Smooth Scroll */
    html { scroll-behavior: smooth; }

    /* Custom scrollbar for sidebar */
    aside::-webkit-scrollbar { width: 4px; }
    aside::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
@endsection
