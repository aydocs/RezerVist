@extends('layouts.app')

@section('title', 'İnsan Kaynakları ve Kariyer - RezerVist')

@section('content')
<div class="bg-white overflow-hidden">
    <!-- Hero Section (Matching About Us Quality) -->
    <div class="relative isolate pt-14">
        <!-- Background Blur Blobs -->
        <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
            <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-primary to-[#ff80b5] opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
        </div>
        
        <div class="min-h-[80vh] flex items-center justify-center py-24 sm:py-32 lg:pb-40 relative z-10">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-black tracking-tight text-slate-900 sm:text-6xl lg:text-7xl mb-8">
                    Geleceğin Teknolojisine <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-purple-600 to-primary animate-gradient-x">Yön Verin</span>
                </h1>
                <p class="mt-6 text-xl leading-relaxed text-slate-600 max-w-3xl mx-auto font-medium">
                    RezerVist Teknoloji A.Ş. olarak, yetenekli profesyonellerle birlikte büyüyoruz. Kurumsal yapımızda yerinizi alın, başarı hikayemizin bir parçası olun.
                </p>
                <div class="mt-12 flex items-center justify-center gap-x-6">
                    <a href="#positions" class="rounded-2xl bg-slate-900 px-8 py-4 text-base font-bold text-white shadow-xl hover:bg-slate-800 hover:scale-105 transition-all duration-300 ring-1 ring-white/10">
                        Açık Pozisyonları İncele
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Bottom Blur -->
        <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
            <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-primary opacity-20 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
        </div>
    </div>

    <!-- Corporate Values Section -->
    <div class="py-24 sm:py-32 bg-slate-50 relative overflow-hidden">
        <!-- Decor line -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-px h-24 bg-gradient-to-b from-transparent to-slate-300"></div>

        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center mb-20">
                <h2 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Kurumsal Değerlerimiz</h2>
                <p class="mt-4 text-lg text-slate-600">Başarımızın temelinde yatan prensiplerimiz.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Value Card 1 -->
                <div class="group relative bg-white p-10 rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-[100px] -mr-16 -mt-16 transition-all group-hover:scale-150 group-hover:bg-blue-100 duration-700"></div>
                    
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white text-2xl mb-8 shadow-lg shadow-blue-200 group-hover:-translate-y-2 transition-transform duration-500">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4 group-hover:text-blue-700 transition-colors">Tutku ve Ustalık</h3>
                        <p class="text-slate-600 leading-relaxed font-medium">
                            Sadece işimizi yapmıyoruz, ona imzamızı atıyoruz. Yazdığımız her satır kodda ve aldığımız her kararda kaliteyi en önde tutuyoruz.
                        </p>
                    </div>
                </div>

                <!-- Value Card 2 -->
                <div class="group relative bg-white p-10 rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 overflow-hidden transform md:-translate-y-8">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-bl-[100px] -mr-16 -mt-16 transition-all group-hover:scale-150 group-hover:bg-primary/20 duration-700"></div>
                    
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center text-white text-2xl mb-8 shadow-lg shadow-primary/30 group-hover:-translate-y-2 transition-transform duration-500">
                            <i class="fa-solid fa-users-viewfinder"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4 group-hover:text-primary transition-colors">Birlikte Büyüyoruz</h3>
                        <p class="text-slate-600 leading-relaxed font-medium">
                            "Biz bir aileyiz" mottosu değil, gerçek bir takım ruhu. Birbirimizi yukarı taşıyor, başarıyı ve kazancı adilce paylaşıyoruz.
                        </p>
                    </div>
                </div>

                <!-- Value Card 3 -->
                <div class="group relative bg-white p-10 rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-bl-[100px] -mr-16 -mt-16 transition-all group-hover:scale-150 group-hover:bg-emerald-100 duration-700"></div>
                    
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-emerald-600 rounded-2xl flex items-center justify-center text-white text-2xl mb-8 shadow-lg shadow-emerald-200 group-hover:-translate-y-2 transition-transform duration-500">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4 group-hover:text-emerald-700 transition-colors">Geleceği Şekillendirmek</h3>
                        <p class="text-slate-600 leading-relaxed font-medium">
                            Günü kurtaran çözümler değil, yıllara meydan okuyan teknolojiler üretiyoruz. Hedefimiz geçici trendler değil, kalıcı izler bırakmak.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Positions Section With Parallax Feel -->
    <div id="positions" class="relative py-24 sm:py-32 bg-white">
        <div class="mx-auto max-w-5xl px-6 lg:px-8 relative z-10">
             <div class="text-center mb-16">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">Başvuru Süreci</span>
                <h2 class="mt-2 text-3xl font-black text-slate-900 sm:text-5xl">Açık Pozisyonlar</h2>
                <p class="mt-4 text-slate-500 max-w-2xl mx-auto">Niteliklerinize uygun pozisyonlar için aşağıdan detayları inceleyebilir ve başvurunuzu iletebilirsiniz.</p>
            </div>

            <div class="space-y-6" x-data="{ selected: null }">
                <!-- Position 1 -->
                <div class="group border border-slate-200 rounded-3xl overflow-hidden hover:border-primary/50 transition-colors duration-300">
                    <button @click="selected === 1 ? selected = null : selected = 1" class="w-full flex items-center justify-between p-8 text-left bg-white hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                                <i class="fa-solid fa-code"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Kıdemli Yazılım Mühendisi (Backend)</h3>
                                <div class="flex items-center gap-4 mt-1 text-sm font-medium text-slate-500">
                                    <span class="flex items-center gap-1"><i class="fa-solid fa-location-dot"></i> Genel Merkez</span>
                                    <span class="flex items-center gap-1"><i class="fa-solid fa-clock"></i> Tam Zamanlı</span>
                                </div>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center transition-transform duration-300" :class="{'rotate-180 bg-primary border-primary text-white': selected === 1}">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="px-8 pb-8 pt-2 bg-slate-50/50 border-t border-slate-100" x-show="selected === 1" x-collapse>
                        <div class="prose prose-slate max-w-none pt-4">
                            <p class="font-bold text-slate-900">Genel Nitelikler:</p>
                            <ul class="marker:text-primary">
                                <li>Üniversitelerin Bilgisayar Mühendisliği veya ilgili bölümlerinden mezun,</li>
                                <li>PHP ve Laravel framework konusunda en az 5 yıl profesyonel tecrübeli,</li>
                                <li>Yüksek trafikli sistemlerde veritabanı mimarisi ve optimizasyon süreçlerine hakim,</li>
                                <li>Mikroservis mimarileri ve Docker konteynerizasyon konularında bilgi sahibi.</li>
                            </ul>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <a href="mailto:ik@rezervist.com?subject=REF-BE-2024 Başvuru" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-black text-white px-8 py-3 rounded-xl font-bold transition-all hover:shadow-lg">
                                Hemen Başvur <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Position 2 -->
                <div class="group border border-slate-200 rounded-3xl overflow-hidden hover:border-primary/50 transition-colors duration-300">
                    <button @click="selected === 2 ? selected = null : selected = 2" class="w-full flex items-center justify-between p-8 text-left bg-white hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                                <i class="fa-solid fa-laptop-code"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Arayüz Geliştirme Uzmanı (Frontend)</h3>
                                <div class="flex items-center gap-4 mt-1 text-sm font-medium text-slate-500">
                                    <span class="flex items-center gap-1"><i class="fa-solid fa-location-dot"></i> Genel Merkez</span>
                                    <span class="flex items-center gap-1"><i class="fa-solid fa-clock"></i> Tam Zamanlı</span>
                                </div>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center transition-transform duration-300" :class="{'rotate-180 bg-primary border-primary text-white': selected === 2}">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="px-8 pb-8 pt-2 bg-slate-50/50 border-t border-slate-100" x-show="selected === 2" x-collapse>
                        <div class="prose prose-slate max-w-none pt-4">
                            <p class="font-bold text-slate-900">Genel Nitelikler:</p>
                            <ul class="marker:text-primary">
                                <li>Modern JavaScript kütüphaneleri (Vue.js, React vb.) konusunda ileri düzey deneyimli,</li>
                                <li>HTML5, CSS3 ve Tailwind teknolojilerine hakim, pixel-perfect kod yazabilen,</li>
                                <li>Kullanıcı deneyimi (UX) standartlarına ve erişilebilirlik (WCAG) kurallarına önem veren.</li>
                            </ul>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <a href="mailto:ik@rezervist.com?subject=REF-FE-2024 Başvuru" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-black text-white px-8 py-3 rounded-xl font-bold transition-all hover:shadow-lg">
                                Hemen Başvur <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Position 3 -->
                <div class="group border border-slate-200 rounded-3xl overflow-hidden hover:border-primary/50 transition-colors duration-300">
                    <button @click="selected === 3 ? selected = null : selected = 3" class="w-full flex items-center justify-between p-8 text-left bg-white hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                                <i class="fa-solid fa-paper-plane"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Genel Başvuru</h3>
                                <div class="flex items-center gap-4 mt-1 text-sm font-medium text-slate-500">
                                    <span class="flex items-center gap-1"><i class="fa-solid fa-building"></i> Tüm Departmanlar</span>
                                </div>
                            </div>
                        </div>
                         <div class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center transition-transform duration-300" :class="{'rotate-180 bg-primary border-primary text-white': selected === 3}">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="px-8 pb-8 pt-2 bg-slate-50/50 border-t border-slate-100" x-show="selected === 3" x-collapse>
                        <div class="prose prose-slate max-w-none pt-4">
                            <p>İleride açılabilecek pozisyonlarda değerlendirilmek üzere özgeçmişinizi veri tabanımıza kaydedebilirsiniz. Yetkinliklerinize uygun bir pozisyon açıldığında sizinle iletişime geçeceğiz.</p>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <a href="mailto:ik@rezervist.com?subject=Genel Başvuru" class="inline-flex items-center gap-2 bg-white text-slate-900 border border-slate-200 px-8 py-3 rounded-xl font-bold transition-all hover:bg-slate-50 hover:border-slate-300">
                                CV Gönder <i class="fa-solid fa-file-export"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
