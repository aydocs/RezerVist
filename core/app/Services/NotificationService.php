<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send a unified notification (Push, SMS, Email).
     * Section 23: Multi-channel communication.
     */
    public function send(User $user, string $title, string $message, array $channels = ['push', 'email'])
    {
        Log::info("Notification: Sending to User {$user->id} via " . implode(', ', $channels));

        foreach ($channels as $channel) {
            try {
                match ($channel) {
                    'push' => $this->sendPush($user, $title, $message),
                    'email' => $this->sendEmail($user, $title, $message),
                    'sms' => $this->sendSms($user, $message),
                    default => null,
                };
            } catch (\Exception $e) {
                Log::error("Notification: Failed to send via {$channel}. Error: " . $e->getMessage());
            }
        }
    }

    protected function sendPush(User $user, string $title, string $message)
    {
        // FCM / WebPush logic stub
        Log::info("Notification [PUSH]: {$title} - {$message}");
    }

    protected function sendEmail(User $user, string $title, string $message)
    {
        // Laravel Mail logic stub
        Log::info("Notification [EMAIL]: {$title}");
    }

    protected function sendSms(User $user, string $message)
    {
        // Twilio / Netgsm logic stub
        Log::info("Notification [SMS]: sent to " . $user->phone);
    }
}
