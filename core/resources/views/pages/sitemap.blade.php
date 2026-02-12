@extends('layouts.app')

@section('title', 'Site Haritası - RezerVist')

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <!-- Unified Hero Section -->
    <div class="bg-gradient-to-r from-violet-900 to-primary text-white pt-24 pb-32">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <h1 class="text-3xl lg:text-5xl font-black tracking-tight mb-4">Site Haritası</h1>
            <p class="text-lg text-violet-100 max-w-2xl mx-auto font-medium">
                Aradığınız sayfaya hızlıca ulaşın. RezerVist platformundaki tüm erişilebilir bağlantılar aşağıda listelenmiştir.
            </p>
        </div>
    </div>

    <!-- Content Container -->
    <div class="max-w-6xl mx-auto px-6 lg:px-8 -mt-20 relative z-10">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 p-8 lg:p-16">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16 lg:gap-24">
                
                <!-- SECTION: Public Pages -->
                <div>
                    <h2 class="flex items-center gap-3 text-gray-900 mb-8 text-xl font-black tracking-tight">
                        <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm"><i class="fas fa-globe text-xl"></i></span>
                        Genel
                    </h2>
                    <ul class="space-y-4">
                        @foreach($publicLinks as $link)
                            <li>
                                <a href="{{ $link['url'] }}" class="flex items-center text-gray-600 hover:text-primary transition-all group font-medium text-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-200 group-hover:bg-primary mr-3 transition-colors"></span>
                                    <span class="group-hover:translate-x-1 transition-transform duration-200">{{ $link['title'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- SECTION: Active Businesses -->
                @if($activeBusinesses->count() > 0)
                <div>
                    <h2 class="flex items-center gap-3 text-gray-900 mb-8 text-xl font-black tracking-tight">
                        <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm"><i class="fas fa-search-location text-xl"></i></span>
                        İşletmeler
                    </h2>
                    <ul class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($activeBusinesses as $business)
                            <li>
                                <a href="{{ route('business.show', $business->slug) }}" class="flex items-center text-gray-600 hover:text-primary transition-all group font-medium text-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-200 group-hover:bg-primary mr-3 transition-colors"></span>
                                    <span class="group-hover:translate-x-1 transition-transform duration-200">{{ $business->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- SECTION: Member Pages -->
                @if(!empty($memberLinks))
                <div>
                    <h2 class="flex items-center gap-3 text-gray-900 mb-8 text-xl font-black tracking-tight">
                        <span class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shadow-sm"><i class="fas fa-user text-xl"></i></span>
                        Hesabım
                    </h2>
                    <ul class="space-y-4">
                        @foreach($memberLinks as $link)
                            <li>
                                <a href="{{ $link['url'] }}" class="flex items-center text-gray-600 hover:text-primary transition-all group font-medium text-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-200 group-hover:bg-primary mr-3 transition-colors"></span>
                                    <span class="group-hover:translate-x-1 transition-transform duration-200">{{ $link['title'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- SECTION: Business Pages -->
                @if(!empty($businessLinks))
                <div>
                    <h2 class="flex items-center gap-3 text-gray-900 mb-8 text-xl font-black tracking-tight">
                        <span class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shadow-sm"><i class="fas fa-store text-xl"></i></span>
                        İşletme
                    </h2>
                    <ul class="space-y-4">
                        @foreach($businessLinks as $link)
                            <li>
                                <a href="{{ $link['url'] }}" class="flex items-center text-gray-600 hover:text-primary transition-all group font-medium text-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-200 group-hover:bg-primary mr-3 transition-colors"></span>
                                    <span class="group-hover:translate-x-1 transition-transform duration-200">{{ $link['title'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- SECTION: Admin Pages -->
                @if(!empty($adminLinks))
                <div class="lg:col-span-3">
                    <hr class="my-10 border-gray-100">
                    <h2 class="flex items-center gap-3 text-gray-900 mb-8 text-xl font-black tracking-tight">
                        <span class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shadow-sm"><i class="fas fa-shield-alt text-xl"></i></span>
                        Sistem ve Yönetim
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                         @foreach($adminLinks as $link)
                            <a href="{{ $link['url'] }}" class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-center text-gray-600 hover:text-primary hover:bg-white hover:shadow-lg transition-all group font-bold text-xs uppercase tracking-wider">
                                <span class="group-hover:translate-x-1 transition-transform duration-200">{{ $link['title'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

        </div>

    </div>
</div>
@endsection
