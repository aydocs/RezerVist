@echo off
chcp 65001 >nul
title RezerVist Geliştirici Modu Başlatıcı
color 0B

echo.
echo ===================================================
echo    RezerVist FULL GELİŞTİRİCİ MODU BAŞLATILIYOR
echo ===================================================
echo.
echo    Şu servisler ayrı pencerelerde açılacak:
echo    1. Laravel Server (8000 portu)
echo    2. Queue Worker (Arkaplan işleri)
echo    3. Reverb Server (Anlık bildirimler)
echo    4. Vite (Frontend assetleri)
echo.
echo    Lütfen pencereleri kapatmayın!
echo.

cd /d "%~dp0"

echo [1/4] Önbellekler temizleniyor...
call php artisan optimize:clear
echo.

echo [2/4] Laravel Server başlatılıyor...
start "RezerVist - Server" cmd /c "php artisan serve --host=0.0.0.0 --port=8000"

echo [3/4] Queue Worker başlatılıyor...
start "RezerVist - Queue" cmd /c "php artisan queue:work --tries=3"

echo [4/4] Reverb Server başlatılıyor...
start "RezerVist - Reverb" cmd /c "php artisan reverb:start"

echo [5/5] Vite (Frontend) başlatılıyor...
start "RezerVist - Vite" cmd /c "npm run dev"

echo.
echo ===================================================
echo    TÜM SİSTEMLER AKTİF!
echo ===================================================
echo.
echo    Panele gitmek için tarayıcını aç: http://localhost:8000/dashboard
echo.
pause
