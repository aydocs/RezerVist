<?php
/**
 * REZERVIST - MAGIC SUBSCRIPTION FIXER
 * This script forces ALL owned businesses for 'owner1@test.com' to the PRO package
 * and clears every possible cache to ensure the UI updates.
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Business;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

$email = 'owner1@test.com';
$user = User::where('email', $email)->first();

if (!$user) {
    die("HATA: Kullanıcı bulunamadı ($email)\n");
}

$proPackage = Package::where('name', 'LIKE', '%Pro%')->first();
if (!$proPackage) {
    die("HATA: Pro paketi veritabanında bulunamadı!\n");
}

echo "BAŞLIYORUZ: {$user->name} için tüm işletmeler PRO yapılacak.\n";
echo "PRO Paket ID: {$proPackage->id} ({$proPackage->name})\n\n";

$businesses = Business::where('owner_id', $user->id)->get();

foreach ($businesses as $biz) {
    echo "İşlem yapılıyor: {$biz->name} (ID: {$biz->id})...\n";
    
    // 1. Mevcut tüm aktif aboneliklerini iptal et (temizlik)
    Subscription::where('business_id', $biz->id)
        ->where('status', 'active')
        ->update(['status' => 'cancelled']);
    
    // 2. Taze bir PRO aboneliği oluştur (1 Yıllık)
    $endsAt = now()->addYear();
    Subscription::create([
        'business_id' => $biz->id,
        'package_id' => $proPackage->id,
        'starts_at' => now(),
        'ends_at' => $endsAt,
        'status' => 'active',
        'payment_method' => 'manual'
    ]);
    
    // 3. Business tablosundaki cache kolonlarını güncelle
    $biz->update([
        'subscription_status' => 'active',
        'subscription_ends_at' => $endsAt
    ]);
    
    // 4. Model bazlı cache'i zorla temizle (Trait varsa)
    if (method_exists($biz, 'clearModelCache')) {
        $biz->clearModelCache();
    }
}

// 5. GLOBAL CACHE TEMİZLİĞİ
echo "\nSistem cache'leri temizleniyor...\n";
\Illuminate\Support\Facades\Artisan::call('cache:clear');
\Illuminate\Support\Facades\Artisan::call('config:clear');
\Illuminate\Support\Facades\Artisan::call('view:clear');
\Illuminate\Support\Facades\Artisan::call('route:clear');

// 6. SESSION TEMİZLİĞİ (Veritabanı session kullanılıyorsa)
if (config('session.driver') === 'database') {
    DB::table('sessions')->where('user_id', $user->id)->delete();
    echo "Sessions temizlendi.\n";
}

echo "\nBİTTİ! Kanka şimdi tarayıcıdan çıkış yap, tekrar gir. Her şey PRO olmalı.\n";
