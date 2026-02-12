@extends('layouts.app')

@section('title', 'Kişisel Verilerin Korunması ve Gizlilik Politikası - RezerVist')

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <!-- Unified Hero Section -->
    <div class="bg-gradient-to-r from-violet-900 to-primary text-white pt-24 pb-32">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <h1 class="text-3xl lg:text-5xl font-black tracking-tight mb-4">Gizlilik Politikası ve Aydınlatma Metni</h1>
            <p class="text-lg text-violet-100 max-w-2xl mx-auto font-medium">
                6698 Sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") Kapsamında Detaylı Bilgilendirme
            </p>
            <div class="mt-8 inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                <span class="text-xs font-bold text-white uppercase tracking-widest">Son Güncelleme: {{ date('d.m.Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Content Container -->
    <div class="max-w-5xl mx-auto px-6 lg:px-8 -mt-20 relative z-10">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 p-8 lg:p-16 prose prose-lg prose-slate max-w-none text-justify">
            
            <p class="lead font-medium text-gray-700">
                RezerVist Teknoloji A.Ş. ("Şirket") olarak, çevrimiçi rezervasyon platformumuz (<a href="https://rezervist.com">www.rezervist.com</a>) ve mobil uygulamalarımız üzerinden hizmet verirken kişisel verilerinizin güvenliğine ve mahremiyetine en üst düzeyde önem veriyoruz. İşbu aydınlatma metni, verilerinizin hangi amaçlarla toplandığı, nasıl işlendiği, kimlerle paylaşıldığı ve haklarınız konusunda sizi en şeffaf şekilde bilgilendirmek amacıyla hazırlanmıştır.
            </p>

            <h3 class="text-primary mt-12 mb-6 border-b border-gray-100 pb-2">1. Veri Sorumlusu Kimliği</h3>
            <p>
                6698 sayılı KVKK ve ilgili mevzuat uyarınca, kişisel verileriniz; veri sorumlusu sıfatıyla RezerVist Teknoloji A.Ş. tarafından aşağıda açıklanan kapsamda toplanmakta ve işlenmektedir.
            </p>
            <div class="not-prose bg-gray-50 rounded-2xl border border-gray-200 overflow-hidden my-6 text-sm">
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-gray-200">
                    <div class="p-4 bg-gray-100 font-bold text-gray-700">Ticari Unvan</div>
                    <div class="p-4 md:col-span-2 text-gray-900">{{ $globalSettings['company_name'] ?? 'RezerVist Teknoloji Anonim Şirketi' }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-gray-200">
                    <div class="p-4 bg-gray-100 font-bold text-gray-700">Adres</div>
                    <div class="p-4 md:col-span-2 text-gray-900">{{ $globalSettings['contact_address'] ?? 'Maslak Mah. Büyükdere Cad. No:123 Sarıyer/İstanbul' }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-gray-200">
                    <div class="p-4 bg-gray-100 font-bold text-gray-700">MERSİS No</div>
                    <div class="p-4 md:col-span-2 text-gray-900">0123456789000016</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3">
                    <div class="p-4 bg-gray-100 font-bold text-gray-700">KEP Adresi</div>
                    <div class="p-4 md:col-span-2 text-gray-900">rezervist@hs01.kep.tr</div>
                </div>
            </div>

            <h3 class="text-primary mt-12 mb-6 border-b border-gray-100 pb-2">2. İşlenen Kişisel Verileriniz</h3>
            <p>
                Platformumuzu kullanımınız sırasında, hizmetlerimizin doğası gereği aşağıdaki veri kategorilerinde yer alan kişisel verileriniz işlenmektedir:
            </p>
            <ul class="marker:text-primary space-y-2">
                <li><strong>Kimlik Bilgileri:</strong> Ad, soyad, T.C. Kimlik Numarası (yasal zorunluluk gerektiren işlemlerde), doğum tarihi, cinsiyet.</li>
                <li><strong>İletişim Bilgileri:</strong> E-posta adresi, cep telefonu numarası, adres, konum bilgisi (bölgesel öneriler için).</li>
                <li><strong>Müşteri İşlem Bilgileri:</strong> Rezervasyon geçmişi, talep ve şikayet bilgileri, iptal/iade kayıtları, platform içi mesajlaşmalar, favori mekanlar.</li>
                <li><strong>Finansal Bilgiler:</strong> Ödeme bilgileri (kredi kartının son 4 hanesi ve maskelenmiş token bilgisi; <u>tam kart numarası tarafımızca asla saklanmaz</u>), fatura bilgileri, IBAN numarası (iade işlemleri için).</li>
                <li><strong>İşlem Güvenliği Bilgileri:</strong> IP adresi, internet sitesi giriş-çıkış saatleri, kullanıcı adı, şifre (hashlenmiş), cihaz bilgisi (ID, model, işletim sistemi), tarayıcı bilgileri.</li>
                <li><strong>Pazarlama Bilgileri:</strong> Alışveriş alışkanlıkları, çerez kayıtları, kampanya kullanım detayları, anket cevapları.</li>
                <li><strong>Görsel ve İşitsel Kayıtlar:</strong> Çağrı merkezi kayıtları, profil fotoğrafı (yüklenmesi halinde).</li>
            </ul>

            <h3 class="text-primary mt-12 mb-6 border-b border-gray-100 pb-2">3. Kişisel Verilerin İşlenme Amaçları ve Hukuki Sebepleri</h3>
            <p>Kişisel verileriniz, KVKK'nın 5. ve 6. maddelerinde belirtilen kişisel veri işleme şartları ve amaçları dahilinde işlenmektedir. Detaylı tablo aşağıdadır:</p>

            <div class="not-prose overflow-x-auto rounded-2xl border border-gray-200 shadow-sm my-8">
                <table class="w-full text-left border-collapse bg-white">
                    <thead class="bg-violet-50 text-violet-900">
                        <tr>
                            <th class="p-4 text-xs font-black uppercase tracking-wider w-1/4">Veri Kategorisi</th>
                            <th class="p-4 text-xs font-black uppercase tracking-wider">İşleme Amacı</th>
                            <th class="p-4 text-xs font-black uppercase tracking-wider w-1/4">Hukuki Sebep (KVKK m.5)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        <tr>
                            <td class="p-4 font-bold text-gray-900 align-top">Kimlik & İletişim</td>
                            <td class="p-4 text-gray-600 align-top">
                                Üyelik süreçlerinin yürütülmesi, rezervasyonların oluşturulması ve teyidi, fatura kesilmesi, sizlerle iletişime geçilmesi.
                            </td>
                            <td class="p-4 align-top"><span class="px-2 py-1 bg-blue-50 text-blue-700 rounded font-bold text-xs inline-block">Sözleşmenin Kurulması/İfası</span></td>
                        </tr>
                        <tr>
                            <td class="p-4 font-bold text-gray-900 align-top">Finansal Bilgiler</td>
                            <td class="p-4 text-gray-600 align-top">
                                Ödeme işlemlerinin gerçekleştirilmesi, iade/iptal süreçleri, muhasebe ve finans takibi.
                            </td>
                            <td class="p-4 align-top"><span class="px-2 py-1 bg-blue-50 text-blue-700 rounded font-bold text-xs inline-block">Sözleşmenin İfası</span></td>
                        </tr>
                        <tr>
                            <td class="p-4 font-bold text-gray-900 align-top">İşlem Güvenliği</td>
                            <td class="p-4 text-gray-600 align-top">
                                5651 sayılı kanun gereği trafik kayıtlarının tutulması, sistem güvenliğinin sağlanması, yetkisiz işlemlerin önlenmesi.
                            </td>
                            <td class="p-4 align-top"><span class="px-2 py-1 bg-amber-50 text-amber-700 rounded font-bold text-xs inline-block">Kanunlarda Öngörülme & Hukuki Yükümlülük</span></td>
                        </tr>
                        <tr>
                            <td class="p-4 font-bold text-gray-900 align-top">Pazarlama</td>
                            <td class="p-4 text-gray-600 align-top">
                                Kişiye özel kampanyaların oluşturulması, çapraz satış, müşteri memuniyeti analizleri.
                            </td>
                            <td class="p-4 align-top"><span class="px-2 py-1 bg-emerald-50 text-emerald-700 rounded font-bold text-xs inline-block">Açık Rıza</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="text-primary mt-12 mb-6 border-b border-gray-100 pb-2">4. Kişisel Verilerin Aktarılması</h3>
            <p>
                Kişisel verileriniz, yukarıda belirtilen amaçların gerçekleştirilmesi doğrultusunda ve KVKK'nın 8. ve 9. maddelerine uygun olarak üçüncü kişilere aktarılabilir:
            </p>
            <ul class="marker:text-primary space-y-2">
                <li><strong>Hizmet Sağlayıcılar (İşletmeler):</strong> Rezervasyon yaptığınız restoran, kafe, kuaför vb. işletmelerle; randevunun oluşturulması ve teyidi için Ad, Soyad, Telefon ve rezervasyon detayları paylaşılır.</li>
                <li><strong>Tedarikçiler ve İş Ortakları:</strong> Sunucu barındırma, veri tabanı, SMS/E-posta gönderimi, çağrı merkezi hizmeti aldığımız firmalar ve ödeme altyapısı sağlayıcıları (Iyzico, Stripe vb.) ile paylaşılır. Bu firmalar verilerinizi sadece hizmetin ifası için kullanabilir.</li>
                <li><strong>Kanunen Yetkili Kamu Kurumları:</strong> Emniyet Genel Müdürlüğü, Savcılıklar, Mahkemeler, Gelir İdaresi Başkanlığı gibi kurumların hukuki talepleri doğrultusunda verileriniz paylaşılabilir.</li>
            </ul>

            <h3 class="text-primary mt-12 mb-6 border-b border-gray-100 pb-2">5. Veri Güvenliği Tedbirleri</h3>
            <p>Şirketimiz, kişisel verilerinizin güvenliğini sağlamak amacıyla aşağıdaki teknik ve idari tedbirleri almaktadır:</p>
            <ul class="marker:text-primary space-y-2">
                <li>Tüm veri iletişimi 256-bit SSL (Secure Socket Layer) sertifikası ile şifrelenmektedir.</li>
                <li>Veri tabanlarına erişimler yetki matrisleri ile sınırlandırılmıştır.</li>
                <li>Siber saldırılara karşı güvenlik duvarları (Firewall) ve saldırı tespit sistemleri (WAF) kullanılmaktadır.</li>
                <li>Personelimiz, veri güvenliği konusunda düzenli eğitimlerden geçirilmektedir.</li>
                <li>Periyodik sızma testleri yapılarak sistem güvenliği denetlenmektedir.</li>
            </ul>

            <h3 class="text-primary mt-12 mb-6 border-b border-gray-100 pb-2">6. İlgili Kişinin Hakları (KVKK Madde 11)</h3>
            <p>Veri sahibi olarak Şirketimize başvurarak aşağıdaki haklarınızı kullanabilirsiniz:</p>
            <ol class="marker:text-primary space-y-2 list-decimal pl-5">
                <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme,</li>
                <li>İşlenmişse buna ilişkin bilgi talep etme,</li>
                <li>İşlenme amacını ve amacına uygun kullanılıp kullanılmadığını öğrenme,</li>
                <li>Yurt içinde veya yurt dışında aktarıldığı 3. kişileri bilme,</li>
                <li>Eksik veya yanlış işlenmişse düzeltilmesini isteme,</li>
                <li>KVKK'da öngörülen şartlar çerçevesinde silinmesini/yok edilmesini isteme,</li>
                <li>Düzeltme/silme işlemlerinin, verilerin aktarıldığı 3. kişilere bildirilmesini isteme,</li>
                <li>Otomatik sistemlerle analiz edilmesi sonucu aleyhinize bir sonucun çıkmasına itiraz etme,</li>
                <li>Kanuna aykırı işleme nedeniyle zarara uğramanız halinde zararın giderilmesini talep etme.</li>
            </ol>
            <div class="bg-violet-50 p-6 rounded-xl border border-violet-100 mt-6">
                <p class="mb-0 text-violet-800 text-sm font-medium">
                    <strong class="block mb-1 text-violet-900">Başvuru Yöntemi:</strong>
                    Taleplerinizi, yazılı olarak kimlik teyidi yapılarak şirket adresimize bizzat, noter kanalıyla veya güvenli elektronik imza ile <a href="mailto:kvkk@rezervist.com" class="text-violet-700 underline">kvkk@rezervist.com</a> adresine iletebilirsiniz. Başvurularınız en geç 30 gün içinde ücretsiz olarak sonuçlandırılacaktır.
                </p>
            </div>

        </div>
    </div>
</div>
@endsection
