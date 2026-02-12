<?php

namespace App\Console\Commands;

use App\Services\SmsService;
use Illuminate\Console\Command;

class TestSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:test {number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'VatanSMS test komutu';

    /**
     * Execute the console command.
     */
    public function handle(SmsService $sms)
    {
        $number = $this->argument('number');
        $this->info("Numaraya test SMS gönderiliyor: $number");

        $result = $sms->send($number, 'RezerVist Test Mesaji: '.now()->format('H:i:s'));

        if ($result) {
            $this->info('SMS Başarıyla Gönderildi! Lütfen telefonunuzu kontrol edin.');
        } else {
            $this->error('SMS Gönderimi Başarısız! Lütfen storage/logs/laravel.log dosyasını kontrol edin.');
        }
    }
}
