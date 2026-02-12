<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use App\Models\PhoneVerification;

class TwilioService
{
    protected $client;
    protected $fromNumber;

    public function __construct()
    {
        $accountSid = config('twilio.account_sid');
        $authToken = config('twilio.auth_token');
        $this->fromNumber = config('twilio.phone_number');

        if ($accountSid && $authToken) {
            $this->client = new Client($accountSid, $authToken);
        }
    }

    /**
     * Send SMS
     */
    public function sendSMS($to, $message)
    {
        if (!$this->client) {
            Log::error('Twilio client not configured');
            return false;
        }

        try {
            // Format phone number for Twilio (Turkish format)
            $formattedNumber = $this->formatPhoneNumber($to);

            $message = $this->client->messages->create(
                $formattedNumber,
                [
                    'from' => $this->fromNumber,
                    'body' => $message
                ]
            );

            Log::info('SMS sent successfully', ['to' => $formattedNumber, 'sid' => $message->sid]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send SMS: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send verification code
     */
    public function sendVerificationCode($phone, $type = 'registration')
    {
        // Create verification record
        $verification = PhoneVerification::createForPhone($phone, $type);

        // Prepare message based on type
        $messages = [
            'registration' => "Rezervist doğrulama kodunuz: {$verification->code}\n\nBu kod 10 dakika geçerlidir.",
            'login' => "Rezervist giriş kodunuz: {$verification->code}\n\nBu kod 10 dakika geçerlidir.",
            'reservation' => "Rezervist doğrulama kodunuz: {$verification->code}"
        ];

        $message = $messages[$type] ?? $messages['registration'];

        // Send SMS
        $sent = $this->sendSMS($phone, $message);

        if ($sent) {
            return $verification;
        }

        // Delete verification if SMS failed
        $verification->delete();
        return null;
    }

    /**
     * Verify code
     */
    public function verifyCode($phone, $code)
    {
        $verification = PhoneVerification::where('phone', $phone)
            ->where('code', $code)
            ->where('verified', false)
            ->first();

        if (!$verification) {
            return [
                'success' => false,
                'message' => 'Geçersiz doğrulama kodu'
            ];
        }

        if ($verification->isExpired()) {
            return [
                'success' => false,
                'message' => 'Doğrulama kodu süresi dolmuş'
            ];
        }

        // Mark as verified
        $verification->update(['verified' => true]);

        return [
            'success' => true,
            'message' => 'Telefon numarası doğrulandı'
        ];
    }

    /**
     * Format phone number to E.164 format
     */
    private function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 0, replace with +90 (Turkey)
        if (substr($phone, 0, 1) === '0') {
            $phone = '+90' . substr($phone, 1);
        }
        // If doesn't start with +, add +90
        elseif (substr($phone, 0, 1) !== '+') {
            $phone = '+90' . $phone;
        }

        return $phone;
    }

    /**
     * Send reservation notification SMS
     */
    public function sendReservationNotification($phone, $businessName, $dateTime)
    {
        $message = "Rezervist: {$businessName} için rezervasyonunuz onaylandı.\n\nTarih: {$dateTime}\n\nİyi eğlenceler!";
        return $this->sendSMS($phone, $message);
    }

    /**
     * Send cancellation notification
     */
    public function sendCancellationNotification($phone, $businessName)
    {
        $message = "Rezervist: {$businessName} için rezervasyonunuz iptal edildi.";
        return $this->sendSMS($phone, $message);
    }

    /**
     * Send reminder SMS
     */
    public function sendReminderSMS($phone, $businessName, $time)
    {
        $message = "Rezervist: Yarın {$time}'de {$businessName} için rezervasyonunuz var. Unutmayın!";
        return $this->sendSMS($phone, $message);
    }
}
