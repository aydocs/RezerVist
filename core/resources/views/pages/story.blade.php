@extends('layouts.app')

@section('title', 'Hikayemiz - Rezervist')

@section('content')
<div class="bg-white overflow-hidden">
     <!-- Story Hero -->
    <div class="relative py-24 sm:py-32 bg-slate-900 overflow-hidden isolate">
        <!-- Background Effects -->
        <div class="absolute inset-0 -z-10">
             <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1556761175-5973dc0f32e7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1632&q=80')] bg-cover bg-center opacity-20 mix-blend-overlay"></div>
             <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-[800px] h-[800px] bg-primary/30 rounded-full blur-[120px]"></div>
             <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3 w-[600px] h-[600px] bg-purple-600/30 rounded-full blur-[100px]"></div>
        </div>

        <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center relative z-10">
            <h1 class="text-4xl font-black tracking-tight text-white sm:text-6xl mb-6">
                Yolculuğumuz
            </h1>
            <p class="mt-6 text-lg leading-8 text-gray-300 max-w-2xl mx-auto">
                Bir fikirle başladı, bir tutkuya dönüştü. İşte Rezervist'in dünü, bugünü ve yarını.
            </p>
        </div>
    </div>

    <!-- Main Story Content -->
    <div class="relative py-24 sm:py-32 bg-white overflow-hidden">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">
                
                <!-- Left: Typography & Story -->
                <div class="order-2 lg:order-1">
                    <div class="inline-flex items-center rounded-full px-4 py-1.5 text-xs font-bold text-primary ring-1 ring-inset ring-primary/20 bg-primary/5 mb-6 uppercase tracking-widest">
                        Misyonumuz
                    </div>
                    <h2 class="text-3xl font-black tracking-tight text-gray-900 sm:text-4xl lg:text-5xl mb-8 leading-tight">
                        Zamanı <span class="relative inline-block">
                            <span class="relative z-10 text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-600">Değerli</span>
                            <span class="absolute bottom-2 left-0 w-full h-3 bg-primary/20 -rotate-2 -z-10"></span>
                        </span> Kılıyoruz
                    </h2>
                    
                    <div class="space-y-6 text-lg leading-relaxed text-gray-600">
                        <p>
                            Rezervist, modern yaşamın karmaşasında "zaman" kavramını yeniden tanımlamak için yola çıktı. Bizim için rezervasyon, sadece bir masa ayırtmak değil; sevdiklerinizle geçireceğiniz kaliteli zamanın garantisidir.
                        </p>
                        <p>
                            2020 yılında küçük bir girişim olarak başladığımız bu yolda, bugün binlerce işletme ve yüz binlerce kullanıcıyı buluşturan devasa bir aileye dönüştük.
                        </p>
                        <p>
                             Teknolojiyi insan odaklı bir yaklaşımla harmanlayarak, hem işletmelerin operasyonel yükünü hafifletiyor hem de kullanıcılarımıza saniyeler içinde plan yapma özgürlüğü sunuyoruz.
                        </p>
                    </div>

                    <div class="mt-10 flex items-center gap-6">
                         <div class="flex -space-x-4">
                            <img class="w-12 h-12 rounded-full border-4 border-white object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="">
                            <img class="w-12 h-12 rounded-full border-4 border-white object-cover" src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=100&q=80" alt="">
                            <img class="w-12 h-12 rounded-full border-4 border-white object-cover" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=100&q=80" alt="">
                            <div class="w-12 h-12 rounded-full border-4 border-white bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600">+40</div>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-900">Tutkulu Bir Ekip</span>
                            <span class="text-sm text-gray-500">Sizin için çalışıyoruz</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Visual Composition -->
                <div class="order-1 lg:order-2 relative lg:h-[600px] flex items-center justify-center">
                    <!-- Decor Blobs -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-tr from-purple-200 to-indigo-200 rounded-full blur-[100px] -z-10 opacity-50"></div>
                    
                    <div class="relative w-full max-w-[500px] aspect-[4/5] group">
                        <!-- Main Image -->
                        <div class="absolute inset-0 rounded-[2.5rem] overflow-hidden shadow-2xl rotate-3 transition group-hover:rotate-0 duration-700 z-10">
                            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=2940&q=80" alt="Meeting" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            <div class="absolute bottom-8 left-8 text-white">
                                <p class="font-bold text-xl">Toplantıdan Eğlenceye</p>
                                <p class="text-sm opacity-80">Her anınızda yanınızdayız</p>
                            </div>
                        </div>

                        <!-- Secondary Image -->
                         <div class="absolute -bottom-6 -right-6 w-2/3 aspect-video rounded-[2rem] overflow-hidden shadow-2xl -rotate-6 z-20 border-8 border-white hidden sm:block transition group-hover:-rotate-3 duration-500">
                            <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1770&q=80" alt="Office" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Timeline Section (Optional/Added value) -->
     <div class="py-24 bg-gray-50">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center mb-16">
                 <h2 class="text-3xl font-bold tracking-tight text-gray-900">Kilometre Taşlarımız</h2>
            </div>
             <div class="mx-auto grid max-w-2xl grid-cols-1 gap-8 overflow-hidden lg:mx-0 lg:max-w-none lg:grid-cols-4">
                <div>
                    <time datetime="2021-08" class="flex items-center text-sm font-semibold leading-6 text-primary">
                        <svg viewBox="0 0 4 4" class="mr-4 h-1 w-1 flex-none" aria-hidden="true"><circle cx="2" cy="2" r="2" fill="currentColor"/></svg>
                        Aug 2021
                    </time>
                    <p class="mt-6 text-lg font-semibold leading-8 tracking-tight text-gray-900">Kuruluş</p>
                    <p class="mt-1 text-base leading-7 text-gray-600">İstanbul'da küçük bir ofiste ilk kod satırlarımızı yazdık.</p>
                </div>
                <div>
                    <time datetime="2022-12" class="flex items-center text-sm font-semibold leading-6 text-primary">
                        <svg viewBox="0 0 4 4" class="mr-4 h-1 w-1 flex-none" aria-hidden="true"><circle cx="2" cy="2" r="2" fill="currentColor"/></svg>
                        Dec 2022
                    </time>
                    <p class="mt-6 text-lg font-semibold leading-8 tracking-tight text-gray-900">1000. İşletme</p>
                    <p class="mt-1 text-base leading-7 text-gray-600">Türkiye genelinde 1000 partner işletmeye ulaştık.</p>
                </div>
                <div>
                    <time datetime="2024-02" class="flex items-center text-sm font-semibold leading-6 text-primary">
                        <svg viewBox="0 0 4 4" class="mr-4 h-1 w-1 flex-none" aria-hidden="true"><circle cx="2" cy="2" r="2" fill="currentColor"/></svg>
                        Feb 2024
                    </time>
                    <p class="mt-6 text-lg font-semibold leading-8 tracking-tight text-gray-900">Global Açılım</p>
                    <p class="mt-1 text-base leading-7 text-gray-600">Londra ve Berlin ofislerimizi açarak Avrupa'ya adım attık.</p>
                </div>
                <div>
                    <time datetime="2026-01" class="flex items-center text-sm font-semibold leading-6 text-primary">
                        <svg viewBox="0 0 4 4" class="mr-4 h-1 w-1 flex-none" aria-hidden="true"><circle cx="2" cy="2" r="2" fill="currentColor"/></svg>
                        Şimdi
                    </time>
                    <p class="mt-6 text-lg font-semibold leading-8 tracking-tight text-gray-900">Sektör Lideri</p>
                    <p class="mt-1 text-base leading-7 text-gray-600">Rezervasyon teknolojilerinde standartları belirlemeye devam ediyoruz.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
