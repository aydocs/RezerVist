<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MobilePaymentNotification extends Notification
{
    use Queueable;

    protected array $paymentData;

    public function __construct(array $paymentData)
    {
        $this->paymentData = $paymentData;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'mobile_payment',
            'title' => 'Mobil Ödeme Alındı! 💳',
            'message' => sprintf(
                '%s masasından ₺%s tutarında mobil ödeme yapıldı.',
                $this->paymentData['table_name'] ?? 'Masa',
                number_format($this->paymentData['amount'] ?? 0, 2, ',', '.')
            ),
            'table_name' => $this->paymentData['table_name'] ?? null,
            'amount' => $this->paymentData['amount'] ?? 0,
            'payment_method' => $this->paymentData['payment_method'] ?? 'wallet',
            'order_id' => $this->paymentData['order_id'] ?? null,
            'fully_paid' => $this->paymentData['fully_paid'] ?? false,
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Mobil Ödeme Alındı')
            ->line(sprintf(
                '%s masasından ₺%s tutarında mobil ödeme yapıldı.',
                $this->paymentData['table_name'] ?? 'Masa',
                number_format($this->paymentData['amount'] ?? 0, 2, ',', '.')
            ))
            ->action('Detayları Görüntüle', url('/vendor/finance'));
    }
}
