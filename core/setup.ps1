# Rezervist Server Setup Script
# PowerShell'de çalıştırmak için: .\setup.ps1

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Rezervist SERVER KURULUM VE BASLATMA" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# .env dosyası kontrolü
if (-not (Test-Path .env)) {
    Write-Host "[1/6] .env dosyası oluşturuluyor..." -ForegroundColor Yellow
    if (Test-Path .env.example) {
        Copy-Item .env.example .env
        Write-Host "    .env.example kopyalandı" -ForegroundColor Gray
    } else {
        $envContent = @"
APP_NAME=Rezervist
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

IYZICO_API_KEY=
IYZICO_SECRET_KEY=
IYZICO_BASE_URL=https://sandbox-api.iyzipay.com

TWILIO_ACCOUNT_SID=
TWILIO_AUTH_TOKEN=
TWILIO_PHONE_NUMBER=
"@
        $envContent | Out-File -FilePath .env -Encoding utf8
        Write-Host "    Yeni .env dosyası oluşturuldu" -ForegroundColor Gray
    }
    Write-Host "    ✓ Tamamlandı" -ForegroundColor Green
    Write-Host ""
} else {
    Write-Host "[1/6] .env dosyası mevcut" -ForegroundColor Green
    Write-Host ""
}

# Application Key
Write-Host "[2/6] Application key oluşturuluyor..." -ForegroundColor Yellow
php artisan key:generate --force 2>&1 | Out-Null
Write-Host "    ✓ Tamamlandı" -ForegroundColor Green
Write-Host ""

# Database Migration
Write-Host "[3/6] Veritabanı migration'ları çalıştırılıyor..." -ForegroundColor Yellow
php artisan migrate --force
if ($LASTEXITCODE -ne 0) {
    Write-Host "    ✗ Hata oluştu!" -ForegroundColor Red
    exit 1
}
Write-Host "    ✓ Tamamlandı" -ForegroundColor Green
Write-Host ""

# Storage Link
Write-Host "[4/6] Storage link oluşturuluyor..." -ForegroundColor Yellow
php artisan storage:link 2>&1 | Out-Null
Write-Host "    ✓ Tamamlandı" -ForegroundColor Green
Write-Host ""

# NPM Build
Write-Host "[5/6] Frontend assets build ediliyor..." -ForegroundColor Yellow
npm run build
if ($LASTEXITCODE -ne 0) {
    Write-Host "    ⚠ Build hatası (devam ediliyor...)" -ForegroundColor Yellow
} else {
    Write-Host "    ✓ Tamamlandı" -ForegroundColor Green
}
Write-Host ""

# Server Başlatma
Write-Host "[6/6] Server başlatılıyor..." -ForegroundColor Yellow
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "    SERVER ÇALIŞIYOR!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "    🌐 Ana Sayfa: http://localhost:8000" -ForegroundColor White
Write-Host "    📱 API:       http://localhost:8000/api" -ForegroundColor White
Write-Host ""
Write-Host "    Durdurmak için: Ctrl+C" -ForegroundColor Yellow
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

php artisan serve --host=0.0.0.0 --port=8000

