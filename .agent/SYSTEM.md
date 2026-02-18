# RezerVist — Tam Sistem Dokümantasyonu

> Bu dosya, AI agent'ın projeyi tam olarak anlayabilmesi için hazırlanmıştır.
> Her bölüm, sistemin farklı bir katmanını detaylı şekilde açıklar.

---

## 1. Proje Genel Bakış

**RezerVist**, restoran ve işletmeler için geliştirilmiş bir SaaS platformudur. Rezervasyon yönetimi, dijital menü, QR ödeme, POS sistemi ve müşteri yönetimini tek çatı altında sunar.

**Hedef Kitle:** Restoranlar, kafeler, oteller, eğlence mekanları.

**Teknoloji Stack:**
| Katman | Teknoloji |
|---|---|
| Backend API | Laravel 11 (PHP 8.2+) |
| POS Uygulaması | React + Vite + TypeScript + Electron |
| Mobil Uygulama | Flutter (Dart) |
| Veritabanı | MySQL 8.0 |
| Ödeme Sistemi | Iyzico (Sandbox + Production) |
| E-posta | SMTP via turkticaret.net |
| Push Bildirim | Web Push (VAPID) |
| Depolama | Laravel Storage (public disk) |
| Önbellek | Database cache driver |
| Kuyruk | Database queue driver |

---

## 2. Monorepo Yapısı

```
Rezervist.com/
├── core/           → Laravel Backend API (ana sistem)
├── pos/            → React/Electron POS Uygulaması
├── mobile/         → Flutter Mobil Uygulama
├── admin/          → Admin paneli (ayrı)
├── business/       → İşletme paneli (ayrı)
├── .agent/         → AI Agent konfigürasyon ve kurallar
│   ├── workflows/  → Tekrarlanabilir iş akışları
│   └── SYSTEM.md   → Bu dosya
└── .gitignore
```

---

## 3. Backend — `core/` (Laravel)

### 3.1 Ortam Konfigürasyonu (`.env`)

```
APP_NAME=Rezervist
APP_ENV=local
APP_URL=http://localhost:8000
APP_LOCALE=tr

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=RezerVist
DB_USERNAME=root
DB_PASSWORD=  (boş)

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=smtp.turkticaret.net
MAIL_PORT=587
MAIL_USERNAME=destek@rezervist.com
MAIL_FROM_ADDRESS=destek@rezervist.com

IYZICO_API_KEY=sandbox-SOdBDDbZVdQhyqoxEfUqkQmV6VXFmbA3
IYZICO_SECRET_KEY=sandbox-RQaPpG9N47wUVuSen4lxLxkQuSw6byAY
IYZICO_BASE_URL=https://sandbox-api.iyzipay.com

VAPID_PUBLIC_KEY=BNAfjkwhObNK9qwNY-...
VAPID_PRIVATE_KEY=5KhgavdgW5sEvYBMpr0...
```

> ⚠️ Iyzico şu an **sandbox** modunda. Production'a geçmek için `.env` anahtarlarını değiştir.

### 3.2 Artisan Sunucu

```bash
# Çalışan komut (her zaman aktif):
php artisan serve --host=127.0.0.1 --port=8000
```

### 3.3 Veritabanı

- **Veritabanı adı:** `RezerVist`
- **Bağlantı:** MySQL, localhost:3306, root/boş şifre
- **Migrations:** `core/database/migrations/` (114 dosya)

#### Önemli Tablolar

| Tablo | Açıklama |
|---|---|
| `users` | Tüm kullanıcılar (müşteri + işletme sahibi + admin) |
| `businesses` | İşletme profilleri |
| `resources` | Masa/oda/alan tanımları |
| `reservations` | Rezervasyonlar |
| `orders` | POS siparişleri |
| `order_items` | Sipariş kalemleri |
| `menus` | Menü ürünleri |
| `categories` | Menü kategorileri |
| `payments` | Ödeme kayıtları |
| `subscriptions` | SaaS abonelikler |
| `packages` | Abonelik paketleri |
| `invoices` | Faturalar |
| `wallet_transactions` | Cüzdan hareketleri |
| `withdrawals` | Para çekme talepleri |
| `staff` | Personel (garson, kasiyer) |
| `reviews` | Değerlendirmeler |
| `tags` | İşletme etiketleri |
| `business_tag` | İşletme-etiket pivot |
| `qr_sessions` | QR oturum kayıtları |
| `happy_hours` | Mutlu saat tanımları |
| `coupons` | Kuponlar |
| `campaigns` | Kampanyalar |
| `messages` | Mesajlaşma |
| `notifications` | Bildirimler |
| `activity_logs` | Aktivite logları |
| `audit_logs` | Denetim logları |
| `error_logs` | Hata logları |
| `settings` | Sistem ayarları |
| `locations` | Konum verileri |
| `posts` | Blog yazıları |
| `comments` | Blog yorumları |
| `contact_messages` | İletişim mesajları |
| `waitlists` | Bekleme listesi |
| `user_cards` | Kullanıcı kayıtlı kartları |

#### `orders` Tablosu Alanları
```
id, business_id, resource_id, reservation_id, waiter_id,
status (active|closed|cancelled),
total_amount, paid_amount,
payment_status (unpaid|partial|paid),
payment_method (cash|credit_card|iyzico_app),
opened_at, closed_at, created_at, updated_at
```

#### `order_items` Tablosu Alanları
```
id, order_id, menu_id, name, quantity (decimal:2),
selected_options (JSON), weight_grams (integer),
unit_price, total_price, notes,
status (pending|preparing|ready|completed|cancelled|deleted),
sent_to_kitchen_at, completed_at
```

#### `businesses` Tablosu Önemli Alanlar
```
id, owner_id (→ users.id), name, slug, category_id,
description, phone, email, address, city,
commission_rate (default: 10),
iyzico_submerchant_key, iyzico_iban,
master_pin (POS ana PIN),
device_fingerprint (POS cihaz bağlama),
menu_theme (decommissioned), menu_color (decommissioned),
pricing_type (fixed|per_person),
occupancy_rate, last_occupancy_update
```

#### `users` Tablosu Önemli Alanlar
```
id, name, email, password, role (user|vendor|admin),
phone, phone_verified_at,
balance (cüzdan bakiyesi),
points (puan),
referral_code, referred_by,
profile_photo_path,
google_id, apple_id
```

#### `staff` Tablosu
```
id, business_id, name, role, pin_code,
permissions (JSON: can_discount, can_delete_items, etc.)
```

### 3.4 Kullanıcı Rolleri

| Rol | Açıklama |
|---|---|
| `user` | Normal müşteri |
| `vendor` | İşletme sahibi (Vendor Panel erişimi) |
| `admin` | Süper admin (Admin Panel erişimi) |

### 3.5 Abonelik Sistemi (SaaS)

**Paketler:**
- **Free** — Temel özellikler
- **Standard (POS)** — 750 TL/ay — POS sistemi
- **Mobile** — 100 TL/ay — Mobil uygulama erişimi
- **Pro** — 2500 TL/ay — Tüm özellikler
- **Enterprise** — Özel fiyat

**Feature Keys (Middleware kontrolü):**
- `pos` — POS sistemi
- `mobile_access` — Mobil/QR erişimi
- `finance` — Finans paneli

**Middleware:** `CheckSubscription` → `subscribed:feature_key`

---

## 4. Backend — Controller Haritası

### 4.1 Web Controllers (`routes/web.php`)

| Controller | Prefix/Route | Açıklama |
|---|---|---|
| `AuthController` | `/login`, `/register` | Kimlik doğrulama |
| `WebAuthController` | `/auth/*` | Web auth (sosyal login dahil) |
| `SocialAuthController` | `/auth/google`, `/auth/apple` | Google/Apple OAuth |
| `PageController` | `/`, `/about`, `/contact` | Statik sayfalar |
| `BusinessController` | `/b/{slug}` | İşletme profil sayfası |
| `SearchController` | `/search` | Arama |
| `BookController` | `/book/{business}` | Rezervasyon akışı |
| `ReservationController` | `/reservations/*` | Rezervasyon yönetimi |
| `VendorController` | `/vendor/*` | Vendor Panel (tüm işletme yönetimi) |
| `VendorMenuController` | `/vendor/menu/*` | Menü yönetimi |
| `VendorResourceController` | `/vendor/resources/*` | Masa/alan yönetimi |
| `VendorCouponController` | `/vendor/coupons/*` | Kupon yönetimi |
| `VendorLocationController` | `/vendor/locations/*` | Konum yönetimi |
| `AdminController` | `/admin/*` | Admin Panel |
| `CustomerQrController` | `/q/{payload}`, `/m/{payload}`, `/bill/{payload}` | QR müşteri akışı |
| `PaymentController` | `/payment/*` | Ödeme akışı (rezervasyon) |
| `BillingController` | `/billing/*` | SaaS abonelik |
| `WalletController` | `/wallet/*` | Cüzdan |
| `ProfileController` | `/profile/*` | Kullanıcı profili |
| `MessageController` | `/messages/*` | Mesajlaşma |
| `ReviewController` | `/reviews/*` | Değerlendirmeler |
| `BlogController` | `/blog/*` | Blog |
| `SitemapController` | `/sitemap.xml` | SEO sitemap |
| `ThemeDemoController` | `/qr-demo/*` | QR tema önizleme |

### 4.2 API Controllers (`routes/api.php`)

| Controller | Prefix | Açıklama |
|---|---|---|
| `PosApiController` | `/api/pos/*` | POS tüm işlemleri |
| `QrTableController` | `/api/qr/*` | QR masa/sipariş API |
| `SplitBillController` | `/api/split-bill/*` | Hesap bölme |
| `ProfileApiController` | `/api/profile/*` | Profil API |
| `WalletApiController` | `/api/wallet/*` | Cüzdan API |

### 4.3 PosApiController Endpoint'leri (Kritik)

```
GET  /api/pos/tables          → Tüm masaları ve aktif siparişleri getir
GET  /api/pos/order/{tableId} → Belirli masa siparişini getir
POST /api/pos/order/submit    → Yeni sipariş/ürün ekle
POST /api/pos/payment         → Ödeme işle
DELETE /api/pos/order/{orderId}/items/{itemId} → Ürün sil
PATCH /api/pos/order/{orderId}/items/{itemId}  → Ürün güncelle
POST /api/pos/tables/transfer → Masa taşı/birleştir
POST /api/pos/update-occupancy → Doluluk oranı güncelle
GET  /api/pos/menu            → Menü getir
GET  /api/pos/orders          → Sipariş geçmişi (faturalar)
GET  /api/pos/summary         → Dashboard özeti
POST /api/pos/staff/verify    → Personel PIN doğrula
```

**POS Auth:** Laravel Sanctum token-based. Token `localStorage`'da saklanır.

---

## 5. Backend — Service Sınıfları

| Service | Açıklama |
|---|---|
| `IyzicoPaymentService` | Iyzico checkout form başlatma, marketplace desteği |
| `IyzicoMarketplaceService` | Sub-merchant kayıt, IBAN doğrulama |
| `RefundService` | İade işlemleri |
| `SubscriptionService` | Abonelik kontrol ve yönetim |
| `SmsService` | SMS gönderimi |
| `TwilioService` | Twilio SMS entegrasyonu |
| `FileUploadService` | Dosya yükleme ve yönetim |
| `ImageOptimizationService` | Görsel optimizasyon |
| `OGImageService` | Open Graph görsel üretimi |
| `SettingService` | Sistem ayarları cache |

---

## 6. Backend — Middleware

| Middleware | Açıklama |
|---|---|
| `CheckRole` | Kullanıcı rolü kontrolü (`role:admin`, `role:vendor`) |
| `CheckSubscription` | Abonelik/feature kontrolü (`subscribed:pos`) |
| `CheckSystemMaintenance` | Bakım modu kontrolü |
| `PosRateLimitMiddleware` | POS API rate limiting |

---

## 7. QR Ödeme Sistemi

### Akış

```
1. İşletme QR kodu oluşturur (payload = encrypted businessId|resourceId)
2. Müşteri QR tarar → /q/{hex_payload}
3. CustomerQrController::index() → QR landing sayfası
4. Müşteri menüyü görür → /m/{payload}
5. Müşteri hesabı görür → /bill/{payload}
6. Ödeme başlatılır → Iyzico checkout form
7. Iyzico callback → CustomerQrController::paymentCallback()
8. Sipariş güncellenir:
   - OrderItem.status = 'completed'
   - Order.payment_status = 'paid'
   - Order.payment_method = 'iyzico_app'
   - Order.status = 'active' (masa açık kalır, garson kapatır)
9. POS Dashboard → masa yeşile döner, "ÖDENDİ" rozeti
```

### QR Payload Formatı
```php
// Şifreleme:
$payload = bin2hex(Crypt::encryptString("{$businessId}|{$resourceId}"));

// Çözme:
$decrypted = Crypt::decryptString(hex2bin($payload));
[$businessId, $resourceId] = explode('|', $decrypted);
```

---

## 8. Iyzico Entegrasyonu

### Sandbox Bilgileri
```
API Key:    sandbox-SOdBDDbZVdQhyqoxEfUqkQmV6VXFmbA3
Secret Key: sandbox-RQaPpG9N47wUVuSen4lxLxkQuSw6byAY
Base URL:   https://sandbox-api.iyzipay.com
```

### Marketplace (Sub-merchant)
- İşletmeler Vendor Panel → Finance → "Iyzico Pazaryeri Kaydı" ile sub-merchant olabilir
- `businesses.iyzico_submerchant_key` alanına kaydedilir
- Ödeme sırasında `subMerchantKey` basket item'a eklenir
- Platform komisyonu: `businesses.commission_rate` (default: %10)
- Sub-merchant tipler: `PERSONAL`, `PRIVATE_COMPANY`, `LIMITED_OR_JOINT_STOCK_COMPANY`

### Ödeme Yöntemleri (payment_method değerleri)
```
cash         → Nakit
credit_card  → Kredi kartı (POS fiziksel)
iyzico_app   → QR uygulama ödemesi
```

---

## 9. POS Uygulaması — `pos/`

### Teknoloji
- **Framework:** React 18 + TypeScript + Vite
- **Styling:** Tailwind CSS
- **Routing:** React Router v6
- **HTTP:** Axios (custom `api` instance)
- **Desktop:** Electron (`.exe` build)
- **Icons:** Lucide React

### Çalıştırma
```bash
cd pos
npm run dev    # Web geliştirme (localhost:5173)
npm run build  # Production build
```

### Sayfa Yapısı (`pos/src/pages/`)

| Sayfa | Route | Açıklama |
|---|---|---|
| `Login.tsx` | `/login` | PIN veya şifre ile giriş |
| `SetupPin.tsx` | `/setup-pin` | İlk kurulum PIN ayarı |
| `Dashboard.tsx` | `/dashboard` | Masa yönetimi ana ekranı |
| `OrderTerminal.tsx` | `/order/:tableId` | Sipariş terminali |
| `KitchenDisplay.tsx` | `/kitchen` | Mutfak ekranı (KDS) |
| `Invoices.tsx` | `/invoices` | Faturalar ve raporlar |
| `Orders.tsx` | `/orders` | Sipariş geçmişi |
| `Menu.tsx` | `/menu` | Menü görüntüleme |
| `Reports.tsx` | `/reports` | Satış raporları |
| `Settings.tsx` | `/settings` | POS ayarları |

### Bileşenler (`pos/src/components/`)

| Bileşen | Açıklama |
|---|---|
| `DashboardLayout.tsx` | Ana layout (sidebar + header) |
| `Sidebar.tsx` | Sol navigasyon menüsü |
| `PaymentModal.tsx` | Ödeme alma modalı (nakit/kart/bölme) |
| `MenuEditor.tsx` | Menü düzenleme arayüzü |
| `StaffLock.tsx` | Personel PIN kilidi |
| `UpdateNotifier.tsx` | Güncelleme bildirimi |

### Lib (`pos/src/lib/`)
- `api.ts` — Axios instance, Sanctum token yönetimi
- `api-client.ts` — `API_BASE_ROOT` sabiti (backend URL)
- `cache.ts` — `CacheManager` — localStorage tabanlı önbellek

### Önbellek Stratejisi
```typescript
CacheManager.set('tables', data)        // 15 dakika TTL
CacheManager.set('menu', data)          // 30 dakika TTL
CacheManager.set(`order_detail_${id}`, data) // Anlık
CacheManager.invalidate('tables')       // Cache temizle
```

### Dashboard Masa Durumları
```
empty    → Boş masa (beyaz kart)
occupied → Dolu masa (kırmızı kart)
reserved → Rezerveli masa (turuncu kart)
occupied + payment_status=paid + payment_method=iyzico_app
         → QR ödemeli masa (YEŞİL kart + "ÖDENDİ" rozeti)
```

---

## 10. Vendor Panel

**URL:** `/vendor/*`
**Middleware:** `auth`, `role:vendor`

### Ana Bölümler

| Bölüm | Route | Açıklama |
|---|---|---|
| Dashboard | `/vendor/dashboard` | Genel bakış |
| İşletme Ayarları | `/vendor/business/edit` | Profil düzenleme |
| Menü | `/vendor/menu` | Ürün yönetimi |
| Masalar/Alanlar | `/vendor/resources` | Masa/alan tanımları |
| Rezervasyonlar | `/vendor/reservations` | Rezervasyon yönetimi |
| Personel | `/vendor/staff` | Garson/kasiyer yönetimi |
| Finans | `/vendor/finance` | Cüzdan, çekimler, Iyzico |
| Kuponlar | `/vendor/coupons` | Kupon yönetimi |
| Yorumlar | `/vendor/reviews` | Müşteri değerlendirmeleri |
| Mesajlar | `/vendor/messages` | Müşteri mesajları |
| QR Kod | `/vendor/qr` | QR kod oluşturma/yazdırma |
| Abonelik | `/billing` | Paket yönetimi |

---

## 11. Admin Panel

**URL:** `/admin/*`
**Middleware:** `auth`, `role:admin`

### Yönetilen Alanlar
- İşletme başvuruları onaylama
- Tüm kullanıcı yönetimi
- Abonelik ve paket yönetimi
- Sistem ayarları
- Para çekme talepleri
- Kampanya yönetimi
- Blog yönetimi
- Destek mesajları
- Aktivite ve hata logları

---

## 12. Mobil Uygulama — `mobile/`

**Framework:** Flutter (Dart)
**Durum:** Geliştirme aşamasında

```bash
cd mobile
flutter run    # Emülatör veya cihazda çalıştır
```

---

## 13. Git & Deployment

### Repository
```
Remote: https://github.com/aydocs/RezerVist.git
Branch: main
```

### Push Workflow (`.agent/workflows/push.md`)
```bash
git add -A
git commit -m "feat: açıklama"
git push origin main
```

> ⚠️ PowerShell'de `&&` çalışmaz! Komutları ayrı ayrı çalıştır.

---

## 14. Önemli Kurallar & Kısıtlamalar

### Geliştirme Kuralları

1. **Dil:** Tüm kullanıcı arayüzleri Türkçe. Kod/değişken isimleri İngilizce.
2. **Veritabanı:** Migration çalıştırmadan önce mevcut veriyi kontrol et.
3. **POS Cache:** Veri değişikliğinde `CacheManager.invalidate()` çağır.
4. **Iyzico:** Şu an sandbox. Production'a geçmek için `.env` değiştir.
5. **Abonelik:** Yeni özellikler için `CheckSubscription` middleware ekle.
6. **Komisyon:** Platform %10 komisyon alır (businesses.commission_rate).
7. **QR Payload:** Her zaman `Crypt::encryptString` + `bin2hex` kullan.
8. **Dosya Yükleme:** `FILESYSTEM_DISK=public` — `storage/app/public/`.
9. **Kuyruk:** `database` driver — `php artisan queue:work` gerekli.
10. **Oturum:** `database` driver — sessions tablosunda saklanır.

### POS Özel Kurallar

1. **Masa Kapatma:** QR ödeme sonrası masa `active` kalır. Garson manuel kapatır.
2. **Ağırlık Bazlı Ürünler:** `quantity` decimal (kg cinsinden). `weight_grams` ayrı alan.
3. **Sipariş Durumları:** `active` → `closed` (ödeme sonrası). `cancelled` iptal.
4. **Item Durumları:** `pending` → `preparing` → `ready` → `completed`.
5. **Personel PIN:** 4 haneli. `master_pin` tüm işlemlere izin verir.
6. **Rate Limit:** POS API'de `PosRateLimitMiddleware` aktif.

### Iyzico Özel Kurallar

1. **IBAN Format:** Boşluksuz, 26 karakter, `TR` ile başlar.
2. **conversationId:** Sipariş ID'si olarak kullanılır.
3. **paidPrice:** Komisyon sonrası tutar. `price` ile aynı olmalı.
4. **Marketplace:** `subMerchantKey` varsa basket item'a eklenir.
5. **Callback:** Iyzico browser'ı callback URL'e yönlendirir (POST değil GET).

---

## 15. Sık Kullanılan Komutlar

```bash
# Backend
php artisan serve --host=127.0.0.1 --port=8000
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed --class=PackageSeeder
php artisan queue:work
php artisan storage:link
php artisan cache:clear
php artisan config:clear
php artisan route:list

# POS
cd pos && npm run dev
cd pos && npm run build

# Mobile
cd mobile && flutter run
cd mobile && flutter pub get

# Git (PowerShell - ayrı komutlar)
git add -A
git commit -m "feat: açıklama"
git push origin main
```

---

## 16. Dosya Yolları Referansı

```
core/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/PosApiController.php      ← POS ana controller
│   │   │   ├── CustomerQrController.php       ← QR ödeme akışı
│   │   │   ├── VendorController.php           ← Vendor panel
│   │   │   ├── PaymentController.php          ← Rezervasyon ödemesi
│   │   │   └── AdminController.php            ← Admin panel
│   │   └── Middleware/
│   │       ├── CheckRole.php
│   │       └── CheckSubscription.php
│   ├── Models/
│   │   ├── Business.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── User.php
│   │   ├── Resource.php
│   │   ├── Menu.php
│   │   └── Staff.php
│   └── Services/
│       ├── IyzicoPaymentService.php
│       └── IyzicoMarketplaceService.php
├── resources/views/
│   ├── vendor/                ← Vendor panel Blade şablonları
│   ├── admin/                 ← Admin panel Blade şablonları
│   ├── qr/                    ← QR müşteri arayüzü
│   │   ├── layout.blade.php
│   │   ├── index.blade.php    ← QR landing
│   │   ├── menu.blade.php     ← Dijital menü
│   │   ├── bill.blade.php     ← Hesap/ödeme
│   │   └── checkout.blade.php ← Iyzico checkout
│   └── auth/                  ← Kimlik doğrulama sayfaları
├── routes/
│   ├── web.php                ← Web rotaları
│   └── api.php                ← API rotaları
└── database/
    ├── migrations/            ← 114 migration dosyası
    └── seeders/

pos/src/
├── pages/
│   ├── Dashboard.tsx          ← Masa yönetimi
│   ├── OrderTerminal.tsx      ← Sipariş terminali
│   ├── Invoices.tsx           ← Faturalar
│   ├── KitchenDisplay.tsx     ← Mutfak ekranı
│   └── Settings.tsx           ← POS ayarları
├── components/
│   ├── PaymentModal.tsx       ← Ödeme modalı
│   └── MenuEditor.tsx         ← Menü editörü
└── lib/
    ├── api.ts                 ← HTTP client
    └── cache.ts               ← Önbellek yönetimi
```

---

## 17. Bilinen Sorunlar & Notlar

1. **Iyzico Sandbox IBAN:** Sandbox'ta IBAN doğrulaması katı. Test için `TR910006200000029900222955` kullan.
2. **PowerShell `&&`:** Çalışmaz. Komutları ayrı satırlarda çalıştır.
3. **QR Ödeme Callback:** Iyzico browser'ı GET ile yönlendirir. Route `GET` olmalı.
4. **POS Cache Invalidation:** Ödeme sonrası `CacheManager.invalidate('tables')` çağrılmalı.
5. **Flutter:** `mobile/` klasörü aktif geliştirmede. Bazı özellikler eksik olabilir.
6. **Queue Worker:** `database` driver kullanıyor. Üretimde `supervisor` ile çalıştır.
7. **`register_submerchant.php`:** Eski test dosyası. Kullanılmıyor, silinebilir.
8. **`verify_colors.php`:** Test dosyası, `php artisan tinker` ile çalışıyor (aktif terminal var).

---

*Son güncelleme: 2026-02-19*
