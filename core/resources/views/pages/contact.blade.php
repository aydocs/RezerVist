@extends('layouts.app')

@section('title', 'Bize Ulaşın - ' . ($globalSettings['site_name'] ?? config('app.name')))

@section('content')
<div class="bg-gray-50 min-h-screen py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4 font-outfit">Bize Ulaşın</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Her türlü soru, öneri veya şikayetiniz için yanınızdayız. Ekibimiz en kısa sürede size geri dönüş yapacaktır.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Contact Info -->
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 transition-all hover:shadow-xl hover:shadow-primary/5 group">
                    <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">E-posta Gönderin</h3>
                    <p class="text-gray-500 mb-4">Sorularınız için bize e-posta yoluyla ulaşabilirsiniz.</p>
                    <a href="mailto:{{ $globalSettings['contact_email'] ?? '' }}" class="text-primary font-bold hover:underline">{{ $globalSettings['contact_email'] ?? 'destek@rezervist.com' }}</a>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 transition-all hover:shadow-xl hover:shadow-primary/5 group">
                    <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Bizi Arayın</h3>
                    <p class="text-gray-500 mb-4">Müşteri hizmetlerimiz haftanı 7 günü hizmetinizdedir.</p>
                    <a href="tel:{{ str_replace(' ', '', $globalSettings['contact_phone'] ?? '') }}" class="text-gray-900 font-bold hover:underline">{{ $globalSettings['contact_phone'] ?? '0850 555 1234' }}</a>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 transition-all hover:shadow-xl hover:shadow-primary/5 group text-left">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Ofisimiz</h3>
                    <p class="text-gray-500">{!! nl2br(e($globalSettings['contact_address'] ?? 'İstanbul, Türkiye')) !!}</p>
                </div>

                @if(!empty($globalSettings['contact_map_iframe']))
                <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100 overflow-hidden h-64 relative group">
                    <div class="absolute inset-0 bg-gray-100 animate-pulse" x-show="!loaded"></div>
                    <div class="w-full h-full rounded-2xl overflow-hidden grayscale hover:grayscale-0 transition-all duration-700">
                        {!! $globalSettings['contact_map_iframe'] !!}
                    </div>
                </div>
                @endif
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100">
                    <form action="{{ route('pages.contact.submit') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Adınız Soyadınız</label>
                                <input type="text" name="name" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white outline-none transition" placeholder="Örn: Ahmet Yılmaz">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">E-posta Adresiniz</label>
                                <input type="email" name="email" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white outline-none transition" placeholder="Örn: ahmet@mail.com">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Konu</label>
                            <input type="text" name="subject" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white outline-none transition" placeholder="Mesajınızın konusu">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mesajınız</label>
                            <textarea name="message" rows="6" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white outline-none transition resize-none" placeholder="Size nasıl yardımcı olabiliriz?"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white text-lg font-bold py-5 rounded-2xl shadow-lg shadow-primary/30 hover:bg-purple-700 transition-all transform active:scale-[0.98] flex items-center justify-center gap-3">
                            <span>Mesajı Gönder</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

