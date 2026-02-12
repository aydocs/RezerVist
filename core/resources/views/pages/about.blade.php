@extends('layouts.app')

@section('title', 'Hakkımızda - Rezervist')

@section('content')
<div class="bg-white overflow-hidden">
    <!-- Hero Section -->
    <div class="relative isolate pt-14">
        <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
            <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-primary to-[#ff80b5] opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
        </div>
        
        <div class="min-h-screen flex items-center py-24 sm:py-32 lg:pb-40 relative z-10">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl bg-clip-text text-transparent bg-gradient-to-r from-gray-900 via-primary to-gray-900 animate-gradient-x pb-2">
                        Rezervasyonun Geleceği
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        Rezervist olarak, işletmeler ve müşteriler arasındaki buluşmayı kusursuz, hızlı ve dijital bir deneyime dönüştürüyoruz. Sadece bir masa değil, unutulmaz anlar rezerve ediyoruz.
                    </p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <a href="{{ route('register') }}" class="rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-primary/30 hover:bg-primary-600 hover:scale-105 transition-all duration-300">Hemen Başlayın</a>
                        <a href="{{ route('pages.story') }}" class="text-sm font-semibold leading-6 text-gray-900 flex items-center gap-1 group">
                            Hikayemiz <span aria-hidden="true" class="group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    </div>
                </div>
                
                <!-- Floating Stats Cards Removed as per request -->
            </div>
        </div>
        
        <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
            <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-primary opacity-20 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
        </div>
    </div>

    <!-- Vision & Purpose Section -->
    <div class="py-24 sm:py-32 bg-white relative overflow-hidden">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-16 gap-y-12 items-center">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 sm:text-4xl mb-6">Vizyonumuz & <span class="text-primary">Amacımız</span></h2>
                    <p class="text-lg text-slate-600 leading-relaxed mb-8">
                        Rezervist, sadece bir rezervasyon aracı değil; sosyal etkileşimin ve işletme verimliliğinin merkezinde yer alan bir teknoloji ekosistemidir. Amacımız, dünyadaki her işletmenin dijital çağın hızına yetişmesini sağlamaktır.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary flex-shrink-0 mt-1">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">Kusursuz Deneyim</h4>
                                <p class="text-sm text-slate-500">Müşterileriniz için saniyeler süren, pürüzsüz bir rezervasyon süreci.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0 mt-1">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">Akıllı Yönetim</h4>
                                <p class="text-sm text-slate-500">İşletmeler için veriye dayalı karar alma ve kaynak optimizasyonu.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="aspect-video bg-gradient-to-br from-slate-100 to-slate-200 rounded-[2rem] overflow-hidden shadow-2xl relative group">
                        <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&auto=format&fit=crop&w=2850&q=80" alt="Tech" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-primary/10 mix-blend-overlay"></div>
                    </div>
                    <!-- Absolute Decorative Elements -->
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-primary rounded-3xl -z-10 rotate-12 blur-2xl opacity-30"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Parallax Brand Section -->
    <div class="relative py-32 sm:py-48 bg-slate-900 border-y border-white/5 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-slate-900/60 z-10"></div>
            <div class="w-full h-full bg-fixed bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2850&q=80');"></div>
        </div>
        
        <div class="mx-auto max-w-7xl px-6 lg:px-8 relative z-20 text-center">
            <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold text-primary ring-1 ring-inset ring-primary/40 bg-white/10 backdrop-blur-md mb-8 uppercase tracking-[0.3em]">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                </span>
                Gelecek Bugün Başlıyor
            </div>
            <h2 class="text-4xl font-black tracking-tight text-white sm:text-6xl lg:text-7xl mb-12">
                Hızlı, Modern, <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-400">Kesintisiz</span> Deneyim
            </h2>
            <p class="mx-auto max-w-2xl text-lg leading-8 text-gray-300">
                Rezervist altyapısı, yoğun saatlerde bile pürüzsüz çalışacak şekilde tasarlandı. Siz işinize odaklanın, teknolojiyi bize bırakın.
            </p>
        </div>
    </div>
</div>
@endsection
