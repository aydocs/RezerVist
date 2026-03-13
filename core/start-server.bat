@echo off
chcp 65001 >nul
echo 🚀 Rezervist Server Başlatılıyor...
echo.

cd /d "%~dp0"

if not exist .env (
    echo 📝 .env dosyası oluşturuluyor...
    if exist .env.example (
        copy .env.example .env >nul
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
    )
    echo ✅ .env dosyası oluşturuldu
    echo.
)

echo 🔑 Application key oluşturuluyor...
php artisan key:generate --force
echo.

echo 📊 Veritabanı migration'ları çalıştırılıyor...
php artisan migrate --force
echo.

echo 🔗 Storage link oluşturuluyor...
php artisan storage:link
echo.

echo 📦 Frontend assets build ediliyor...
call npm run build
echo.

echo ✅ Kurulum tamamlandı!
echo.
echo 🌐 Server başlatılıyor: http://localhost:8000
echo.
php artisan serve --host=0.0.0.0 --port=8000

