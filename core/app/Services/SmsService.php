<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $apiId;
    protected $apiKey;
    protected $sender;
    protected $mode;

    public function __construct()
    {
        $this->apiId = config('services.vatansms.api_id');
        $this->apiKey = config('services.vatansms.api_key');
        $this->sender = config('services.vatansms.sender');
        $this->mode = env('SMS_MODE', 'api');
    }

    /**
     * Send SMS via VatanSMS
     *
     * @param string $to
     * @param string $message
     * @return bool
     */
    public function send($to, $message)
    {
        // Format number: VatanSMS expects '905XXXXXXXXX'
        $gsm = preg_replace('/[^0-9]/', '', $to);
        if (strlen($gsm) == 10) {
            $gsm = '90' . $gsm;
        }

        // LOG MODE: Mesajı göndermek yerine log dosyasına yazar (Bedava test için)
        if ($this->mode === 'log' || empty($this->apiId) || empty($this->apiKey)) {
            Log::info("📱 [SMS SIMÜLASYONU] Alıcı: $gsm | Mesaj: $message");
            return true;
        }

        try {
            $response = Http::timeout(10)->post('https://api.vatansms.net/api/v1/1toN', [
                'api_id' => $this->apiId,
                'api_key' => $this->apiKey,
                'sender' => $this->sender,
                'message' => $message,
                'phones' => [$gsm],
                'message_type' => 'normal'
            ]);

            $json = $response->json();

            // VatanSMS success response usually contains status 'success' or code 200
            if ($response->successful() && isset($json['status']) && $json['status'] == 'success') {
                Log::info("VatanSMS sent to $gsm via API");
                return true;
            }

            Log::error("VatanSMS API Error: " . $response->body() . " for $gsm");
            return false;

        } catch (\Exception $e) {
            Log::error("VatanSMS Connection Error: " . $e->getMessage());
            return false;
        }
    }
}
