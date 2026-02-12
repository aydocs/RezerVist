@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <!-- Unified Hero Section -->
    <div class="bg-gradient-to-r from-violet-900 to-primary text-white pt-24 pb-32">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <h1 class="text-3xl lg:text-5xl font-black tracking-tight mb-4">İşletmenizi <span class="text-violet-200">RezerVist</span> ile Büyütün</h1>
            <p class="text-lg text-violet-100 max-w-2xl mx-auto font-medium">
                Müşterilerinize daha kolay ulaşın, rezervasyonlarınızı dijitalleştirin ve gelirinizi artırın. Üyelik ücreti yok, gizli maliyet yok.
            </p>
            <div class="mt-8 flex items-center justify-center gap-4">
                 <a href="{{ route('business.apply') }}" class="px-8 py-4 bg-white text-primary font-bold rounded-xl shadow-xl hover:bg-gray-100 transition-all transform hover:-translate-y-1">
                    Hemen Başvur
                </a>
                <a href="#nasil-calisir" class="px-6 py-4 text-white font-bold hover:text-violet-200 transition-colors">
                    Nasıl Çalışır? <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <!-- Mini Stats -->
            <div class="mt-16 grid grid-cols-1 gap-4 sm:grid-cols-3 max-w-4xl mx-auto">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <dt class="text-violet-200 text-sm font-bold uppercase">Aktif Kullanıcı</dt>
                    <dd class="text-3xl font-black text-white mt-1">10K+</dd>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <dt class="text-violet-200 text-sm font-bold uppercase">Aylık Rezervasyon</dt>
                    <dd class="text-3xl font-black text-white mt-1">50K+</dd>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <dt class="text-violet-200 text-sm font-bold uppercase">Mutlu İşletme</dt>
                    <dd class="text-3xl font-black text-white mt-1">500+</dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Container -->
    <div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-10 relative z-10 space-y-12">
        
        <!-- FEATURE CARDS -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 lg:p-12">
            <div class="text-center mb-12">
                <h2 class="text-base font-bold text-primary uppercase tracking-wider">Neden Rezervist?</h2>
                <p class="mt-2 text-3xl font-black text-gray-900">İşletmenizi Yönetmek Hiç Bu Kadar Kolay Olmamıştı</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">7/24 Rezervasyon</h3>
                    <p class="text-sm text-gray-600">Siz uykudayken bile müşterileriniz online rezervasyon yapsın.</p>
                </div>
                 <!-- Feature 2 -->
                <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Mobil Yönetim</h3>
                    <p class="text-sm text-gray-600">Her yerden rezervasyonları görüntüleyin ve onaylayın.</p>
                </div>
                 <!-- Feature 3 -->
                <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Gelişmiş Analitik</h3>
                    <p class="text-sm text-gray-600">Verilerle işletmenizi büyütün, müşteri alışkanlıklarını öğrenin.</p>
                </div>
                 <!-- Feature 4 -->
                <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Ücretsiz Üyelik</h3>
                    <p class="text-sm text-gray-600">Başlangıç maliyeti yok. Sadece kazandıkça ödeyin.</p>
                </div>
            </div>
        </div>

        <!-- HOW IT WORKS -->
        <div id="nasil-calisir" class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-center relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16 group-hover:bg-primary/10 transition-colors"></div>
                <div class="w-16 h-16 bg-primary text-white rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-6 shadow-lg shadow-primary/30 relative z-10">1</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 relative z-10">Başvuru Yapın</h3>
                <p class="text-gray-600 text-sm relative z-10">İşletme bilgilerinizi girerek formumuzu doldurun.</p>
            </div>
            <!-- Step 2 -->
             <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-center relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16 group-hover:bg-primary/10 transition-colors"></div>
                <div class="w-16 h-16 bg-primary text-white rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-6 shadow-lg shadow-primary/30 relative z-10">2</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 relative z-10">Profil Oluşturun</h3>
                <p class="text-gray-600 text-sm relative z-10">Menü, fotoğraf ve çalışma saatlerinizi ekleyin.</p>
            </div>
            <!-- Step 3 -->
             <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-center relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16 group-hover:bg-primary/10 transition-colors"></div>
                <div class="w-16 h-16 bg-primary text-white rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-6 shadow-lg shadow-primary/30 relative z-10">3</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 relative z-10">Kazanmaya Başlayın</h3>
                <p class="text-gray-600 text-sm relative z-10">Binlerce kullanıcıya anında ulaşın.</p>
            </div>
        </div>

        <!-- CTA Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pb-12">
            <div class="bg-primary rounded-3xl p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <h3 class="text-2xl font-black mb-4 relative z-10">Hemen Başlayın</h3>
                <p class="text-violet-100 mb-8 relative z-10">İlk ay komisyon yok! Fırsatı kaçırmayın.</p>
                <a href="{{ route('business.apply') }}" class="inline-block px-6 py-3 bg-white text-primary font-bold rounded-xl shadow hover:bg-gray-50 transition relative z-10">Şimdi Başvur</a>
            </div>
             <div class="bg-gray-900 rounded-3xl p-8 text-white relative overflow-hidden">
                <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 bg-primary/30 rounded-full blur-2xl"></div>
                <h3 class="text-2xl font-black mb-4 relative z-10">Sorularınız mı Var?</h3>
                <p class="text-gray-400 mb-8 relative z-10">Ekibimiz size yardımcı olmaktan mutluluk duyar.</p>
                <a href="/contact" class="inline-block px-6 py-3 bg-white/10 text-white border border-white/20 font-bold rounded-xl hover:bg-white/20 transition relative z-10">Bize Ulaşın</a>
            </div>
        </div>

    </div>
</div>
@endsection
