@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary to-purple-800 px-8 py-12 text-center">
                <h1 class="text-3xl font-bold text-white mb-4">Sözleşmeler ve Yasal Metinler</h1>
                <p class="text-purple-200">Son Güncelleme: {{ date('d.m.Y') }}</p>
            </div>

            <!-- Content -->
            <div class="p-8" x-data="{ active: 0 }">
                <p class="text-gray-600 mb-8 leading-relaxed">
                    RezerVist platformunun kullanımına ilişkin tüm yasal sözleşmeler ve koşullar aşağıda yer almaktadır. Hizmetlerimizi kullanarak bu sözleşmeleri kabul etmiş sayılırsınız.
                </p>

                <div class="space-y-4">
                    @foreach([
                        [
                            'title' => '1. Kullanıcı Sözleşmesi',
                            'content' => '
                                <h4 class="font-bold mb-2">1. Taraflar</h4>
                                <p class="mb-2">İşbu sözleşme, RezerVist (bundan böyle "PLATFORM" olarak anılacaktır) ile platforma üye olan kullanıcı ("KULLANICI") arasında akdedilmiştir.</p>
                                
                                <h4 class="font-bold mb-2">2. Konu</h4>
                                <p class="mb-2">İşbu sözleşmenin konusu, kullanıcının platform üzerinden sunulan rezervasyon hizmetlerinden faydalanma şartlarının belirlenmesidir.</p>
                                
                                <h4 class="font-bold mb-2">3. Kullanım Şartları</h4>
                                <ul class="list-disc pl-5 mb-2">
                                    <li>Kullanıcı, sisteme girerken verdiği bilgilerin doğruluğunu taahhüt eder.</li>
                                    <li>Kullanıcı, oluşturduğu rezervasyonlara riayet etmeyi veya süresi içinde iptal etmeyi kabul eder.</li>
                                    <li>Platform, aracılık hizmeti vermekte olup, hizmetin asıl sağlayıcısı ilgili İŞLETME\'dir.</li>
                                </ul>
                            '
                        ],
                        [
                            'title' => '2. Mesafeli Satış Sözleşmesi',
                            'content' => '
                                <p class="mb-2">İşbu sözleşme 6502 sayılı Tüketicinin Korunması Hakkında Kanun ve Mesafeli Sözleşmeler Yönetmeliği\'ne uygun olarak düzenlenmiştir.</p>
                                <h4 class="font-bold mb-2">Hizmetin Niteliği</h4>
                                <p class="mb-2">Elektronik ortamda anında ifa edilen rezervasyon ve masa ayırtma hizmetidir.</p>
                                <h4 class="font-bold mb-2">Cayma Hakkı İstisnası</h4>
                                <p>Mesafeli Sözleşmeler Yönetmeliği Madde 15 uyarınca, belirli bir tarihte veya dönemde yapılması gereken konaklama, eğlence veya dinlenme amacıyla yapılan rezervasyonlarda cayma hakkı kullanılamaz. Ancak platform, kullanıcı memnuniyeti adına işletme politikalarına bağlı olarak iptal seçeneği sunabilir.</p>
                            '
                        ],
                        [
                            'title' => '3. İptal ve İade Koşulları',
                            'content' => '
                                <h4 class="font-bold mb-2">Rezervasyon İptali</h4>
                                <p class="mb-2">Kullanıcılar, rezervasyon saatinden en geç 1 saat öncesine kadar platform üzerinden iptal işlemi gerçekleştirebilirler.</p>
                                
                                <h4 class="font-bold mb-2">Ön Ödemeli Rezervasyonlar</h4>
                                <p class="mb-2">Ön ödeme veya kaparo alınan rezervasyonlarda:</p>
                                <ul class="list-disc pl-5 mb-2">
                                    <li>Rezervasyon saatine 24 saat kala yapılan iptallerde %100 iade yapılır.</li>
                                    <li>Son 24 saat içinde yapılan iptallerde işletme politikasına göre kesinti uygulanabilir.</li>
                                </ul>
                                <p>İadeler, ödemenin yapıldığı karta 3-7 iş günü içinde yansıtılır.</p>
                            '
                        ],
                        [
                            'title' => '4. Açık Rıza Metni',
                            'content' => '
                                <p class="mb-2">Kişisel verilerimin 6698 sayılı KVKK kapsamında;</p>
                                <ul class="list-disc pl-5">
                                    <li>Rezervasyon işlemlerinin yürütülmesi,</li>
                                    <li>İletişim faaliyetlerinin yürütülmesi,</li>
                                    <li>Kampanya ve fırsatlardan haberdar edilmesi (Ticari Elektronik İleti onayı varsa)</li>
                                </ul>
                                <p class="mt-2">amaçlarıyla işlenmesine, saklanmasına ve hizmet aldığım ilgili işletmeye aktarılmasına özgür irademle rıza gösteriyorum.</p>
                            '
                        ],
                    ] as $index => $item)
                        <div class="border border-gray-200 rounded-xl overflow-hidden transition-all duration-300" :class="active === {{ $index }} ? 'ring-2 ring-primary ring-offset-2' : 'hover:border-gray-300'">
                            <button @click="active === {{ $index }} ? active = null : active = {{ $index }}" class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-white transition text-left">
                                <span class="font-bold text-gray-900">{{ $item['title'] }}</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="active === {{ $index }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="active === {{ $index }}" x-collapse class="border-t border-gray-200 bg-white">
                                <div class="p-4 text-gray-600 prose prose-sm max-w-none">
                                    {!! $item['content'] !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-8 border-t border-gray-200 text-center">
                    <p class="text-sm text-gray-500">
                        Sorularınız ve yasal talepleriniz için 
                        <a href="mailto:hukuk@rezervist.com" class="text-primary font-bold hover:underline">hukuk@rezervist.com</a> 
                        adresinden bize ulaşabilirsiniz.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
