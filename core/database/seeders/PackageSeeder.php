<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Free Package (Commission Based)
        // 1. Starter Package (Purely Reservation)
        Package::updateOrCreate(['slug' => 'free'], [
            'name' => 'Starter (Rezervasyon)',
            'price_monthly' => 0.00,
            'price_yearly' => 0.00,
            'features' => [
                'reservation_access' => true,
                'pos_access' => false,
                'mobile_access' => false,
                'business_limit' => 1,
                'commission_rate' => 0,
                'staff_limit' => 5,
                'display_features' => [
                    'RezerVist.com Rezervasyon Paneli',
                    'Dijital Rezervasyon Defteri',
                    'Temel Müşteri Yönetimi (CRM)',
                    'Sınırsız Rezervasyon Alımı',
                    '%10 İşlem Komisyonu (KALDIRILDI)',
                    'Sıfır Komisyon Politikası',
                ],
                'description' => 'Sadece RezerVist.com üzerinden rezervasyon yönetimi.',
            ],
            'is_active' => true,
        ]);

        // 2. Pro Package (Starter + POS)
        Package::updateOrCreate(['slug' => 'pro'], [
            'name' => 'Pro (Rezervasyon + POS)',
            'price_monthly' => 1000.00,
            'price_yearly' => 10000.00,
            'features' => [
                'reservation_access' => true,
                'pos_access' => true,
                'mobile_access' => false,
                'business_limit' => -1, // Unlimited
                'commission_rate' => 0,
                'staff_limit' => -1,
                'display_features' => [
                    'Starter Paketindeki Tüm Özellikler',
                    'Gelişmiş Adisyon & POS Sistemi',
                    'Masa ve Kat Planı Yönetimi',
                    'Anlık Stok & Envanter Takibi',
                    'Komisyonsuz Rezervasyon Yönetimi',
                    'Sınırsız Personel Yetkilendirme',
                ],
                'description' => 'Starter pakete ek olarak tam kapsamlı POS/Adisyon çözümü.',
            ],
            'is_active' => true,
        ]);

        // 3. Mobile Package (Pro + Mobile)
        Package::updateOrCreate(['slug' => 'mobile'], [
            'name' => 'Mobile (Plus Sürüm)',
            'price_monthly' => 2500.00,
            'price_yearly' => 25000.00,
            'features' => [
                'reservation_access' => true,
                'pos_access' => true,
                'mobile_access' => true,
                'business_limit' => -1, // Unlimited
                'commission_rate' => 0,
                'staff_limit' => -1,
                'display_features' => [
                    'Pro Paketindeki Tüm Özellikler',
                    'Markanıza Özel Müşteri Mobil Uygulaması',
                    'Dijital Müşteri Kartı & Sadakat Sistemi',
                    'Temassız QR Menü, Sipariş & Ödeme',
                    'Müşterilere Özel "Push" Bildirimleri',
                    'Gelişmiş CRM & Kampanya Yönetimi',
                ],
                'description' => 'Pro özelliklere ek olarak markanıza özel mobil uygulama ve sadakat çözümleri.',
            ],
            'is_active' => true,
        ]);

        // 4. Enterprise Package (All Features + Finance/Accounting)
        Package::updateOrCreate(['slug' => 'enterprise'], [
            'name' => 'Enterprise (Ultimate)',
            'price_monthly' => 5000.00,
            'price_yearly' => 50000.00,
            'features' => [
                'reservation_access' => true,
                'pos_access' => true,
                'mobile_access' => true,
                'business_limit' => -1, // Unlimited
                'commission_rate' => 0,
                'all_features' => true,
                'staff_limit' => -1,
                'display_features' => [
                    'Tüm Paketlerdeki Tüm Özellikler',
                    'Kurumsal Finans & Ön Muhasebe Modülü',
                    'Nakit Akış Takibi & Gider Yönetimi',
                    'Çoklu Şube (Multi-Branch) Yönetimi',
                    'Beyaz Etiket (White Label) Desteği',
                    'Kurumsal API & ERP Entegrasyonları',
                    '7/24 VIP Destek & Atanmış Müşteri Temsilcisi',
                ],
                'description' => 'Tüm dijital ekosistemimize ve kurumsal finans/muhasebe modüllerine tam erişim.',
            ],
            'is_active' => true,
        ]);

        // Remove old 'starter' if it exists
        Package::where('slug', 'starter')->delete();
    }
}
