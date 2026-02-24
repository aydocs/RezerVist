<?php
/**
 * REZERVIST STORAGE FIXER
 * 
 * Bu betik, üretim sunucusunda (production) yüklenen resimlerin 
 * görünmeme sorununu (storage link/permission) çözmek için hazırlanmıştır.
 * 
 * Kullanım:
 * 1. Bu dosyayı sunucunuzun "public" klasörüne veya ana dizinine yükleyin.
 * 2. Tarayıcıdan bu dosyayı çalıştırın (örn: rezervist.com/fix_storage.php)
 */

// Show errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('LARAVEL_START', microtime(true));

// Autoload & App Initialization
$autoloadPath = __DIR__.'/../vendor/autoload.php';
$appPath = __DIR__.'/../bootstrap/app.php';

if (!file_exists($autoloadPath)) {
    die("Hata: vendor/autoload.php bulunamadı! Yol: $autoloadPath");
}

require $autoloadPath;
$app = require_once $appPath;
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<div style='font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; border: 1px solid #eee; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);'>";
echo "<h1 style='color: #6366f1;'>Rezervist Storage Fixer <span style='font-size: 14px; color: #94a3b8; font-weight: normal;'>(v2.1)</span></h1>";
echo "<hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>";

// 1. Check APP_URL
$configUrl = config('app.url', 'Bilinmiyor');
echo "<strong>APP_URL (Remote):</strong> <code style='background: #f1f5f9; padding: 2px 6px; border-radius: 4px;'>$configUrl</code><br><br>";

// 2. Try to create storage link
echo "<strong>İşlem 1: Storage Link Oluşturuluyor...</strong><br>";
try {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo "<span style='color: #10b981;'>✅ Artisan storage:link komutu başarıyla çalıştırıldı.</span><br><br>";
} catch (\Exception $e) {
    echo "<span style='color: #f43f5e;'>❌ Hata (Artisan): " . $e->getMessage() . "</span><br><br>";
}

// 3. Verify Symlink
$publicStorage = public_path('storage');
echo "<strong>İşlem 2: Symlink Kontrolü...</strong><br>";
if (file_exists($publicStorage)) {
    if (is_link($publicStorage)) {
        echo "<span style='color: #10b981;'>✅ /public/storage klasörü geçerli bir 'sembolik link' olarak mevcut.</span><br>";
        echo "<span style='font-size: 12px; color: #64748b;'>Hedef: " . readlink($publicStorage) . "</span><br><br>";
    } else {
        echo "<span style='color: #f59e0b;'>⚠️ /public/storage bir klasör olarak mevcut ama link değil!</span><br>";
        echo "<span style='font-size: 12px; color: #64748b;'>Çözüm: Bu klasörü silip link olarak tekrar oluşturmanız gerekebilir.</span><br><br>";
    }
} else {
    echo "<span style='color: #f43f5e;'>❌ /public/storage bulunamadı! Link oluşturulamamış olabilir.</span><br><br>";
}

// 4. Check Permissions and File Existence
$uploadPaths = [
    'Menu Images' => storage_path('app/public/menu_images'),
    'Business Images' => storage_path('app/public/business_images'),
    'Public Root' => storage_path('app/public'),
];

echo "<strong>İşlem 3: Klasör ve Dosya Kontrolü...</strong><br>";
foreach ($uploadPaths as $name => $path) {
    if (!file_exists($path)) {
        @mkdir($path, 0775, true);
    }
    
    $writable = is_writable($path);
    $shortPath = str_replace(base_path(), '', $path);
    
    echo "• $name ($shortPath): ";
    if ($writable) {
        echo "<span style='color: #10b981;'>Yazılabilir ✅</span>";
    } else {
        echo "<span style='color: #f43f5e;'>Yazılamaz ❌</span>";
    }
    
    // List some files
    if (file_exists($path) && is_dir($path)) {
        $files = array_diff(scandir($path), ['.', '..']);
        echo " - (Dosya Sayısı: " . count($files) . ")<br>";
        if (count($files) > 0) {
            echo "<ul style='font-size: 11px; color: #64748b; margin: 5px 0 15px 20px;'>";
            $i = 0;
            foreach ($files as $file) {
                if ($i++ > 3) { echo "<li>...</li>"; break; }
                echo "<li>$file</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "<br>";
    }
}

// 5. URL Test
echo "<strong>İşlem 4: Örnek URL Testi...</strong><br>";
$testFile = 'storage_test.txt';
file_put_contents(storage_path('app/public/'.$testFile), 'storage link test content');
$testUrl = asset('storage/'.$testFile);
echo "Şu linke tıklayın, 'storage link test content' yazısını görüyorsanız link çalışıyordur:<br>";
echo "<a href='$testUrl' target='_blank' style='color: #6366f1; text-decoration: underline;'>$testUrl</a><br><br>";

echo "<div style='margin-top: 30px; padding: 15px; background: #f8fafc; border-radius: 12px; font-size: 14px; color: #475569;'>";
echo "<strong>Sonuç:</strong> Eğer yukarıda kırmızı renkli bir hata görmüyorsanız ve resimler hala açılmıyorsa, tarayıcı önbelleğinizi (Cache) temizleyip tekrar deneyin.<br>";
echo "Sorun devam ederse, SSH üzerinden şu komutu çalıştırın: <br>";
echo "<code style='display: block; background: #000; color: #fff; padding: 10px; border-radius: 8px; margin-top: 10px;'>chmod -R 775 storage/app/public bootstrap/cache</code>";
echo "</div>";

echo "</div>";
