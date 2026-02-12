@extends('layouts.app')

@section('title', 'Çerez (Cookie) Politikası - RezerVist')

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <!-- Unified Hero Section -->
    <div class="bg-gradient-to-r from-violet-900 to-primary text-white pt-24 pb-32">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <h1 class="text-3xl lg:text-5xl font-black tracking-tight mb-4">Çerez Politikası</h1>
            <p class="text-lg text-violet-100 max-w-2xl mx-auto font-medium">
                Teknik Tanımlama Bilgileri ve İzleme Teknolojileri Hakkında Detaylı Rehber
            </p>
            <div class="mt-8 inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                <span class="w-2 h-2 bg-amber-400 rounded-full"></span>
                <span class="text-xs font-bold text-white uppercase tracking-widest">Son Güncelleme: {{ date('d.m.Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Content Container -->
    <div class="max-w-5xl mx-auto px-6 lg:px-8 -mt-20 relative z-10">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 p-8 lg:p-16 prose prose-lg prose-slate max-w-none">
            
            <h3 class="text-primary mt-0 mb-6 border-b border-gray-100 pb-2">1. Çerez Nedir?</h3>
            <p>
                RezerVist olarak, internet sitemizden en verimli şekilde faydalanabilmeniz için çerezler kullanıyoruz. Çerezler, tarayıcınız aracılığıyla cihazınıza kaydedilen küçük metin dosyalarıdır. Çerezler, cihazınızın ana belleğinde depolanan verilerinizi taramaz veya zarar vermez.
            </p>

            <h3 class="text-primary mt-12 mb-6 border-b border-gray-100 pb-2">2. Çerez Sınıflandırması</h3>
            <div class="not-prose grid grid-cols-1 md:grid-cols-2 gap-6 my-8">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    <h4 class="text-gray-900 font-bold mb-3 flex items-center gap-2">
                        <i class="fas fa-hourglass-half text-blue-500"></i> Sürelerine Göre
                    </h4>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li><strong>Oturum Çerezleri:</strong> Tarayıcıyı kapatınca silinir.</li>
                        <li><strong>Kalıcı Çerezler:</strong> Belirli süre diskte saklanır.</li>
                    </ul>
                </div>
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    <h4 class="text-gray-900 font-bold mb-3 flex items-center gap-2">
                        <i class="fas fa-globe text-emerald-500"></i> Kaynağına Göre
                    </h4>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li><strong>Birinci Taraf:</strong> Doğrudan bizim tarafımızdan eklenir.</li>
                        <li><strong>Üçüncü Taraf:</strong> Google, Facebook vb. servislerce eklenir.</li>
                    </ul>
                </div>
            </div>

            <h3 class="text-primary mt-12 mb-6 border-b border-gray-100 pb-2">3. Kullanılan Spesifik Çerezler</h3>
            <div class="not-prose overflow-x-auto rounded-2xl border border-gray-200 shadow-sm my-8">
                <table class="w-full text-left border-collapse bg-white">
                    <thead class="bg-violet-50 text-violet-900 border-b border-violet-100">
                        <tr>
                            <th class="p-4 text-xs font-black uppercase tracking-wider">Çerez Adı</th>
                            <th class="p-4 text-xs font-black uppercase tracking-wider">Amaç</th>
                            <th class="p-4 text-xs font-black uppercase tracking-wider">Tip</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4 font-mono font-bold text-gray-800 italic">XSRF-TOKEN</td>
                            <td class="p-4 text-gray-600">Site güvenliğini sağlamak ve saldırıları önlemek.</td>
                            <td class="p-4"><span class="bg-red-50 text-red-600 px-2 py-1 rounded text-[10px] font-bold uppercase">Zorunlu</span></td>
                        </tr>
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4 font-mono font-bold text-gray-800 italic">laravel_session</td>
                            <td class="p-4 text-gray-600">Oturum tercihlerinizi korumak.</td>
                            <td class="p-4"><span class="bg-red-50 text-red-600 px-2 py-1 rounded text-[10px] font-bold uppercase">Zorunlu</span></td>
                        </tr>
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4 font-mono font-bold text-gray-800 italic">_ga, _gid</td>
                            <td class="p-4 text-gray-600">Trafik analizi ve deneyim iyileştirme.</td>
                            <td class="p-4"><span class="bg-amber-50 text-amber-600 px-2 py-1 rounded text-[10px] font-bold uppercase">Analitik</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="text-primary mt-12 mb-6 border-b border-gray-100 pb-2">4. Çerez Tercihlerini Yönetme</h3>
            <p>
                Tarayıcı ayarlarınızı değiştirerek çerezleri reddedebilirsiniz. Chrome, Safari, Firefox ve Edge üzerinden ayarlarınızı kolayca güncelleyebilirsiniz. Zorunlu çerezleri kapatmak site fonksiyonlarını bozabilir.
            </p>

             <hr class="my-10 border-gray-100">
            <p class="text-xs text-gray-400 font-medium italic text-center">
                RezerVist Teknoloji A.Ş. • {{ date('Y') }}
            </p>

        </div>
    </div>
</div>
@endsection
