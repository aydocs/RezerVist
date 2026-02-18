<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Starter Package (Purely Reservation)
        Package::updateOrCreate(['slug' => 'free'], [
            'name' => 'Starter (Rezervasyon)',
            'price_monthly' => 0.00,
            'price_yearly' => 0.00,
            'features' => [
                'reservation_access' => true,
                'pos_access' => false,
                'mobile_access' => false,
                'finance_access' => false,
                'yearly_discount' => 0,
                'display_features' => [
                    'RezerVist Portalı Üzerinden Sınırsız Rezervasyon',
                    'Dijital Rezervasyon Defteri & Ajanda Yönetimi',
                    'Temel Müşteri Kayıt Sistemi (Giriş Seviyesi CRM)',
                    'Mobil Uygulama Erişimi (Anlık İzleme & Bildirim)',
                    'Otomatize Rezervasyon Onay E-postaları',
                    'Basit Günlük Doluluk Raporları',
                    '7/24 Teknik Destek (Bilet Sistemi)',
                ],
                'description' => 'Sadece RezerVist üzerinden gelen rezervasyonları yönetmek isteyenler için.',
            ],
            'is_active' => true,
        ]);

        // 2. Standard Package (Full POS)
        Package::updateOrCreate(['slug' => 'standard'], [
            'name' => 'Standard (POS)',
            'price_monthly' => 750.00,
            'price_yearly' => 7920.00, // 750 * 12 * 0.88 (12% discount)
            'features' => [
                'reservation_access' => true,
                'pos_access' => true,
                'mobile_access' => false,
                'finance_access' => false,
                'yearly_discount' => 0.12,
                'display_features' => [
                    'Tam Kapsamlı Bulut POS & Adisyon Sistemi',
                    'Dinamik Masa Yönetimi (Taşıma, Birleştirme, Rezervasyon)',
                    'Kategorize Edilmiş Sınırsız Ürün & Menü Girişi',
                    'Hızlı Satış Ekranı & Parçalı Ödeme Alma',
                    'Personel Takibi & Vardiya Yönetimi Paneli',
                    'Dijital QR Menü (Sadece Görüntüleme Focus)',
                    'Günlük/Haftalık Ciro & Ürün Satış Raporları',
                    'Yıllık Alımda %12 Net İndirim Avantajı',
                ],
                'description' => 'İşletmesini dijital adisyon sistemiyle yönetmek isteyenler için.',
            ],
            'is_active' => true,
        ]);

        // 3. Mobile Plus (Mobile Payment & Loyalty)
        Package::updateOrCreate(['slug' => 'mobile'], [
            'name' => 'Mobile+ (Ödeme)',
            'price_monthly' => 1000.00,
            'price_yearly' => 11040.00, // 1000 * 12 * 0.92 (8% discount)
            'features' => [
                'reservation_access' => true,
                'pos_access' => true,
                'mobile_access' => true,
                'finance_access' => false,
                'yearly_discount' => 0.08,
                'display_features' => [
                    'Masadan Anlık Mobil Ödeme (Kredi Kartı/Cüzdan)',
                    'Masaya Özel QR & İnteraktif Dijital Adisyon',
                    'Gelişmiş Müşteri Sadakat (Puan & Hediye) Sistemi',
                    'Müşteri Push Bildirimleri & Pazarlama Araçları',
                    'Dijital Cüzdan Altyapısı & Bakiye Yönetimi',
                    'Kampanya & Promosyon Yönetimi Modülü',
                    'Müşteri Geri Bildirim & Değerlendirme Sistemi',
                    'Yıllık Alımda %8 Net İndirim Avantajı',
                ],
                'description' => 'Müşterilerine mobil ödeme ve sadakat deneyimi sunmak isteyenler için.',
            ],
            'is_active' => true,
        ]);

        // 4. Pro Suite (Finance & API)
        Package::updateOrCreate(['slug' => 'pro'], [
            'name' => 'Pro Suite (Finans)',
            'price_monthly' => 2500.00,
            'price_yearly' => 28500.00, // 2500 * 12 * 0.95 (5% discount)
            'features' => [
                'reservation_access' => true,
                'pos_access' => true,
                'mobile_access' => true,
                'finance_access' => true,
                'yearly_discount' => 0.05,
                'display_features' => [
                    'Muhasebe & Finans API Entegrasyon Altyapısı',
                    'Otomatize Stok Takibi & Kritik Seviye Uyarıları',
                    'Detaylı Gider Yönetimi & Kar/Zarar Analizi',
                    'Gelişmiş ERP Bağlantıları & Veri Entegrasyonu',
                    'Multi-Branch (Çoklu Şube) Merkezi Yönetim',
                    'Bulut Tabanlı Sınırsız Veri Saklama & Arşiv',
                    'Excel/PDF/JSON Formatlı Gelişmiş Dışa Aktarma',
                    'Yıllık Alımda %5 Net İndirim Avantajı',
                ],
                'description' => 'Finansal verilerini profesyonel yazılımlar ile konuşturmak isteyenler için.',
            ],
            'is_active' => true,
        ]);

        // 5. Enterprise (Custom Plan)
        Package::updateOrCreate(['slug' => 'enterprise'], [
            'name' => 'Enterprise (Özel)',
            'price_monthly' => 0.00, 
            'price_yearly' => 0.00,
            'features' => [
                'reservation_access' => true,
                'pos_access' => true,
                'mobile_access' => true,
                'finance_access' => true,
                'all_features' => true,
                'yearly_discount' => 0,
                'display_features' => [
                    'Markaya Özel Beyaz Etiket (White Label) Çözümler',
                    'İşletmeye Özel Modül Geliştirme & Yazılım Desteği',
                    'Kurumsal ERP (SAP, Logo vb.) Derin Entegrasyon',
                    'Markaya Özel Müşteri Mobil Uygulaması (App Store/Play Store)',
                    '7/24 Öncelikli Özel Danışman & Teknik Destek',
                    'Yüksek Hacimli Veri İşleme & Özel Veritabanı',
                    'Yerinde Kurulum & Personel Eğitim Hizmeti',
                    'Tamamen İhtiyaca Özel Fiyatlandırma Modeli',
                ],
                'description' => 'İhtiyaçlarınıza göre şekillenen tamamen size özel teknolojik altyapı.',
            ],
            'is_active' => true,
        ]);

        // Remove old 'starter' if it exists
        Package::where('slug', 'starter')->delete();
    }
}
