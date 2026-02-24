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

// --- CORRECT BOOTSTRAP FOR STANDALONE SCRIPT ---
use Illuminate\Contracts\Console\Kernel;
$kernel = $app->make(Kernel::class);
$kernel->bootstrap(); 
// ----------------------------------------------

echo "<div style='font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; border: 1px solid #eee; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);'>";
echo "<h1 style='color: #6366f1;'>Rezervist Storage Fixer <span style='font-size: 14px; color: #94a3b8; font-weight: normal;'>(v2.6)</span></h1>";
echo "<hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>";

// 0. Server Info
echo "<strong>Sunucu Bilgisi:</strong><br>";
echo "PHP Kullanıcısı: " . (function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : get_current_user()) . "<br>";
echo "Public Dizini: " . public_path() . " (İzin: " . decoct(fileperms(public_path()) & 0777) . ")<br><br>";

// 0.1 Parent Directory Permissions (CRITICAL)
echo "<strong>İşlem 0: Üst Dizini İzinleri (Erişilebilirlik):</strong><br>";
$parents = [
    'core' => base_path(),
    'core/storage' => storage_path(),
    'core/storage/app' => storage_path('app'),
    'core/storage/app/public' => storage_path('app/public'),
];

foreach ($parents as $name => $path) {
    if (file_exists($path)) {
        $perms = decoct(fileperms($path) & 0777);
        $isExecutable = is_executable($path);
        echo "• $name ($perms): " . ($isExecutable ? "<span style='color: #10b981;'>Erişilebilir ✅</span>" : "<span style='color: #f43f5e;'>Erişilemez (403 Sebebi!) ❌</span>") . "<br>";
    } else {
        echo "• $name: <span style='color: #f43f5e;'>Bulunamadı ❌</span><br>";
    }
}
echo "<br>";

// 1. Check APP_URL
$configUrl = config('app.url', 'Bilinmiyor');
echo "<strong>APP_URL (Remote):</strong> <code style='background: #f1f5f9; padding: 2px 6px; border-radius: 4px;'>$configUrl</code><br><br>";

// 2. Try to create storage link (FORCING RELATIVE)
echo "<strong>İşlem 1: Storage Link Oluşturuluyor (Gelişmiş)...</strong><br>";
try {
    $link = public_path('storage');
    $target = '../storage/app/public';
    
    if (file_exists($link)) {
        if (is_link($link)) {
            unlink($link);
        } else {
            // It's a directory! Backup it?
            rename($link, $link.'_backup_'.time());
        }
    }
    
    // Create relative symlink
    if (symlink($target, $link)) {
        echo "<span style='color: #10b981;'>✅ Sembolik link (GÖRECELİ) başarıyla oluşturuldu.</span><br><br>";
    } else {
        echo "<span style='color: #f43f5e;'>❌ Sembolik link oluşturulamadı! (İzin sorunu olabilir)</span><br><br>";
    }
} catch (\Exception $e) {
    echo "<span style='color: #f43f5e;'>❌ Hata (Link): " . $e->getMessage() . "</span><br><br>";
}

// 3. Verify Symlink
$publicStorage = public_path('storage');
echo "<strong>İşlem 2: Symlink Durumu...</strong><br>";
if (file_exists($publicStorage)) {
    if (is_link($publicStorage)) {
        $realTarget = readlink($publicStorage);
        echo "<span style='color: #10b981;'>✅ /public/storage şu an bir link.</span><br>";
        echo "<span style='font-size: 12px; color: #64748b;'>Hedef: $realTarget</span><br><br>";
    } else {
        echo "<span style='color: #f59e0b;'>⚠️ /public/storage bir klasör (Link değil!).</span><br><br>";
    }
} else {
    echo "<span style='color: #f43f5e;'>❌ /public/storage bulunamadı!</span><br><br>";
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
    $perms = decoct(fileperms($path) & 0777);
    $shortPath = str_replace(base_path(), '', $path);
    
    echo "• $name ($shortPath): ";
    if ($writable) {
        echo "<span style='color: #10b981;'>Yazılabilir (İzin: $perms) ✅</span>";
    } else {
        echo "<span style='color: #f43f5e;'>Yazılamaz (İzin: $perms) ❌</span>";
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
echo "<strong>İşlem 4: URL ve Erişim Testi...</strong><br>";
$testFile = 'storage_test.txt';
$storageFilePath = storage_path('app/public/'.$testFile);
file_put_contents($storageFilePath, 'storage link test content');

// Direct public file test
$publicTestFile = public_path('direct_test.txt');
file_put_contents($publicTestFile, 'direct access test');

$testUrl = asset('storage/'.$testFile);
$directUrl = asset('direct_test.txt');

echo "1. <a href='$directUrl' target='_blank' style='color: #6366f1; text-decoration: underline;'>Buraya tıklayın (Doğrudan Dosya)</a> - Bu çalışıyorsa sunucu dosya sunabiliyor demektir.<br>";
echo "2. <a href='$testUrl' target='_blank' style='color: #6366f1; text-decoration: underline;'>Buraya tıklayın (Storage Linki)</a> - Bu 403 veriyorsa sorun sadece sembolik linktedir.<br><br>";

// 6. PHP Level Read Check
echo "<strong>İşlem 5: PHP Seviyesinde Okuma Kontrolü...</strong><br>";
$linkPath = public_path('storage/'.$testFile);
if (file_exists($linkPath)) {
    $content = @file_get_contents($linkPath);
    if ($content === 'storage link test content') {
        echo "<span style='color: #10b981;'>✅ PHP, link üzerinden dosyayı okuyabiliyor!</span> (Sorun kesinlikle Apache/Nginx ayarlarında).<br>";
    } else {
        echo "<span style='color: #f43f5e;'>❌ PHP, link üzerinden dosyayı OKUYAMIYOR!</span> (Dosya yolu veya sistem kısıtlaması var).<br>";
    }
} else {
    echo "<span style='color: #f43f5e;'>❌ Link yolu PHP tarafından bulunamadı.</span><br>";
}

echo "<div style='margin-top: 30px; padding: 15px; background: #f8fafc; border-radius: 12px; font-size: 14px; color: #475569;'>";
echo "<strong>Sonuç:</strong> Eğer yukarıda kırmızı renkli bir hata görmüyorsanız ve resimler hala açılmıyorsa, tarayıcı önbelleğinizi (Cache) temizleyip tekrar deneyin.<br>";
echo "Sorun devam ederse, SSH üzerinden şu komutu çalıştırın: <br>";
echo "<code style='display: block; background: #000; color: #fff; padding: 10px; border-radius: 8px; margin-top: 10px;'>chmod -R 775 storage/app/public bootstrap/cache</code>";
echo "</div>";

echo "</div>";
