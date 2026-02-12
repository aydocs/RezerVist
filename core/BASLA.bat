@echo off
chcp 65001 >nul
title Rezervist Server
color 0A

echo.
echo ========================================
echo    Rezervist SERVER KURULUM VE BASLATMA
echo ========================================
echo.

cd /d "%~dp0"

REM .env dosyası kontrolü
if not exist .env (
    echo [1/6] .env dosyası oluşturuluyor...
    if exist .env.example (
        copy .env.example .env >nul
        echo    .env.example kopyalandı
    ) else (
        (
            echo APP_NAME=Rezervist
            echo APP_ENV=local
            echo APP_KEY=
            echo APP_DEBUG=true
            echo APP_TIMEZONE=UTC
            echo APP_URL=http://localhost:8000
            echo.
            echo DB_CONNECTION=sqlite
            echo DB_DATABASE=database/database.sqlite
            echo.
            echo SESSION_DRIVER=database
            echo QUEUE_CONNECTION=database
            echo CACHE_STORE=database
            echo.
            echo IYZICO_API_KEY=
            echo IYZICO_SECRET_KEY=
            echo IYZICO_BASE_URL=https://sandbox-api.iyzipay.com
            echo.
            echo TWILIO_ACCOUNT_SID=
            echo TWILIO_AUTH_TOKEN=
            echo TWILIO_PHONE_NUMBER=
        ) > .env
        echo    Yeni .env dosyası oluşturuldu
    )
    echo    ✓ Tamamlandı
    echo.
) else (
    echo [1/6] .env dosyası mevcut
    echo.
)

REM Application Key
echo [2/6] Application key oluşturuluyor...
php artisan key:generate --force >nul 2>&1
echo    ✓ Tamamlandı
echo.

REM Database Migration
echo [3/6] Veritabanı migration'ları çalıştırılıyor...
php artisan migrate --force
if errorlevel 1 (
    echo    ✗ Hata oluştu!
    pause
    exit /b 1
)
echo    ✓ Tamamlandı
echo.

REM Storage Link
echo [4/6] Storage link oluşturuluyor...
php artisan storage:link >nul 2>&1
echo    ✓ Tamamlandı
echo.

REM NPM Build
echo [5/6] Frontend assets build ediliyor...
call npm run build
if errorlevel 1 (
    echo    ⚠ Build hatası (devam ediliyor...)
) else (
    echo    ✓ Tamamlandı
)
echo.

REM Server Başlatma
echo [6/6] Server başlatılıyor...
echo.
echo ========================================
echo    SERVER ÇALIŞIYOR!
echo ========================================
echo.
echo    🌐 Ana Sayfa: http://localhost:8000
echo    📱 API:       http://localhost:8000/api
echo.
echo    Durdurmak için: Ctrl+C
echo.
echo ========================================
echo.

php artisan serve --host=0.0.0.0 --port=8000

pause

