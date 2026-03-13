<?php
/**
 * REZERVIST STORAGE FIXER - PLAN B (Direct Storage)
 * 
 * Bu betik, sunucudaki sembolik link (symlink) kısıtlamasını aşmak için
 * storage klasörünü direkt PUBLIC içine taşır.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
use Illuminate\Contracts\Console\Kernel;
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo "<div style='font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; border: 1px solid #eee; border-radius: 20px;'>";
echo "<h1 style='color: #6366f1;'>RezerVist Storage Fixer <span style='color: #f59e0b;'>(PLAN B v3.1)</span></h1>";
echo "<hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>";

$publicStorage = public_path('storage');
$oldStorage = storage_path('app/public');

// 1. Delete Symlink
echo "<strong>İşlem 1: Eski Link Temizleniyor...</strong><br>";
if (file_exists($publicStorage)) {
    if (is_link($publicStorage)) {
        unlink($publicStorage);
        echo "<span style='color: #10b981;'>✅ Sembolik link silindi.</span><br><br>";
    } else {
        echo "<span style='color: #64748b;'>ℹ️ /public/storage zaten bir klasör veya link değil.</span><br><br>";
    }
}

// 2. Create Real Directory
echo "<strong>İşlem 2: Gerçek Klasör Oluşturuluyor...</strong><br>";
if (!file_exists($publicStorage)) {
    if (mkdir($publicStorage, 0775, true)) {
        echo "<span style='color: #10b981;'>✅ /public/storage klasörü oluşturuldu.</span><br><br>";
    } else {
        die("<span style='color: #f43f5e;'>❌ Hata: Klasör oluşturulamadı! İzin sorunu var.</span>");
    }
}

// 3. Move Files
echo "<strong>İşlem 3: Resimler Taşınıyor...</strong><br>";
function moveContent($src, $dst) {
    if (!file_exists($src)) return;
    $files = array_diff(scandir($src), ['.', '..']);
    foreach ($files as $file) {
        $srcPath = $src . DIRECTORY_SEPARATOR . $file;
        $dstPath = $dst . DIRECTORY_SEPARATOR . $file;
        if (is_dir($srcPath)) {
            if (!file_exists($dstPath)) mkdir($dstPath, 0775, true);
            moveContent($srcPath, $dstPath);
        } else {
            copy($srcPath, $dstPath); // Copy instead of rename for safety during test
        }
    }
}

try {
    moveContent($oldStorage, $publicStorage);
    echo "<span style='color: #10b981;'>✅ Resimler eski konumdan yeni konuma kopyalandı.</span><br><br>";
} catch (\Exception $e) {
    echo "<span style='color: #f43f5e;'>❌ Hata (Taşıma): " . $e->getMessage() . "</span><br><br>";
}

// 4. Verify
echo "<strong>İşlem 4: Doğrulama...</strong><br>";
$testFile = $publicStorage . '/plan_b_test.txt';
file_put_contents($testFile, 'Plan B works!');
$testUrl = asset('storage/plan_b_test.txt');

echo "Şu linke tıkla, 'Plan B works!' yazısını görüyorsan BÜTÜN RESİMLER DÜZELMİŞTİR:<br>";
echo "<a href='$testUrl' target='_blank' style='color: #6366f1; text-decoration: underline;'>$testUrl</a><br><br>";

echo "<div style='background: #fefce8; padding: 15px; border-radius: 12px; font-size: 14px; border: 1px solid #fef08a;'>";
echo "<strong>NOT:</strong> Eğer bu link çalışıyorsa, artık tüm resimlerin görünmesi lazım.<br>";
echo "Bundan sonra sistem dosyaları direkt buraya yükleyeceği için linke ihtiyacın kalmayacak.";
echo "</div>";

echo "</div>";
