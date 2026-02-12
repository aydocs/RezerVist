# 🧪 Rezervist - Sistem Test Planı

## ✅ Test Edilmesi Gerekenler

### 1. 🔐 Authentication & Authorization
- [ ] Kullanıcı kaydı (email/şifre)
- [ ] Kullanıcı girişi (email/şifre)
- [ ] Sosyal medya girişi (Google, Apple)
- [ ] Telefon doğrulama (SMS)
- [ ] Şifre sıfırlama
- [ ] Logout
- [ ] Role-based access control (customer, business, admin)
- [ ] Session yönetimi

### 2. 🏢 Business Management
- [ ] İşletme başvurusu
- [ ] İşletme onayı (admin)
- [ ] İşletme profili düzenleme (vendor)
- [ ] İşletme fotoğrafları yükleme/silme
- [ ] İşletme çalışma saatleri yönetimi
- [ ] İşletme listeleme (public)
- [ ] İşletme detay sayfası

### 3. 📋 Menu/Service Management
- [ ] Menü/hizmet ekleme (vendor)
- [ ] Menü/hizmet düzenleme (vendor)
- [ ] Menü/hizmet silme (vendor)
- [ ] Menü listeleme (public)
- [ ] Fiyat hesaplama (komisyon dahil)

### 4. 📅 Reservation System
- [ ] Rezervasyon oluşturma (customer)
- [ ] Rezervasyon listeleme (customer)
- [ ] Rezervasyon iptal etme (customer)
- [ ] Rezervasyon erteleme (customer)
- [ ] Rezervasyon onaylama/reddetme (vendor)
- [ ] Rezervasyon çakışma kontrolü
- [ ] Rezervasyon bildirimleri (email/SMS)

### 5. 💳 Payment System
- [ ] Ödeme sayfası
- [ ] Iyzico entegrasyonu
- [ ] Ödeme callback işleme
- [ ] İade işlemi (refund)
- [ ] Ödeme geçmişi

### 6. ⭐ Reviews & Favorites
- [ ] Yorum ekleme (customer)
- [ ] Yorum listeleme (public)
- [ ] Favorilere ekleme/çıkarma
- [ ] Favori listeleme

### 7. 🔍 Search & Discovery
- [ ] İşletme arama (isim, konum)
- [ ] Filtreleme (kategori, rating, fiyat)
- [ ] Sıralama (önerilen, en yakın, en yüksek rating)
- [ ] Autocomplete

### 8. 👤 Profile Management
- [ ] Profil görüntüleme
- [ ] Profil düzenleme
- [ ] Fotoğraf yükleme
- [ ] İletişim bilgileri (Telefon, adres)
- [ ] Rezervasyon geçmişi
- [ ] Favori işletmeler

### 9. 👨‍💼 Admin Panel
- [ ] Dashboard (istatistikler)
- [ ] Kullanıcı yönetimi
- [ ] İşletme başvuruları yönetimi
- [ ] İşletme onaylama/reddetme

### 10. 🏪 Vendor Dashboard
- [ ] Dashboard (istatistikler)
- [ ] Rezervasyon yönetimi
- [ ] Menü yönetimi
- [ ] İşletme profili yönetimi

### 11. 📱 API Endpoints
- [ ] Public API (işletme listesi, detay)
- [ ] Authenticated API (rezervasyon, favori)
- [ ] Vendor API (menü, rezervasyon yönetimi)
- [ ] Admin API

### 12. 🖼️ Media & Storage
- [ ] Fotoğraf yükleme
- [ ] Fotoğraf optimizasyonu
- [ ] Storage link kontrolü
- [ ] Dosya boyutu limitleri

### 13. 📧 Notifications
- [ ] Email bildirimleri
- [ ] SMS bildirimleri (Twilio)
- [ ] Rezervasyon onay/iptal bildirimleri

### 14. 🗄️ Database
- [ ] Migration'lar çalışıyor mu?
- [ ] Seeder'lar çalışıyor mu?
- [ ] İlişkiler doğru mu?
- [ ] Index'ler var mı?

### 15. 🔒 Security
- [ ] CSRF koruması
- [ ] XSS koruması
- [ ] SQL injection koruması
- [ ] Rate limiting
- [ ] Input validation

### 16. 🎨 Frontend
- [ ] Responsive tasarım
- [ ] JavaScript çalışıyor mu?
- [ ] Alpine.js çalışıyor mu?
- [ ] Form validasyonları
- [ ] Toast mesajları
- [ ] Loading states

---

## 🐛 Bilinen Sorunlar

1. **Storage Link**: `php artisan storage:link` çalıştırılmalı
2. **Environment**: `.env` dosyası oluşturulmalı ve key generate edilmeli
3. **Database**: Migration'lar çalıştırılmalı
4. **Frontend**: `npm run build` çalıştırılmalı

---

## 🚀 Test Senaryoları

### Senaryo 1: Yeni Kullanıcı Kaydı ve Rezervasyon
1. Ana sayfaya git
2. Kayıt ol (email/şifre)
3. İşletme ara
4. İşletme detayına git
5. Rezervasyon yap
6. Ödeme yap
7. Rezervasyonu görüntüle

### Senaryo 2: İşletme Sahibi İşlemleri
1. Business role ile giriş yap
2. Dashboard'a git
3. İşletme profili düzenle
4. Menü ekle
5. Rezervasyonları görüntüle
6. Rezervasyon onayla

### Senaryo 3: Admin İşlemleri
1. Admin role ile giriş yap
2. Admin panel'e git
3. İşletme başvurularını görüntüle
4. Başvuruyu onayla
5. Kullanıcıları yönet

---

## 📝 Test Sonuçları

Test sonuçlarını buraya ekleyin:

```
[ ] Test 1: ...
[ ] Test 2: ...
```

