<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationStatusNotification extends Notification
{
    use Queueable;

    public $reservation;

    public $status;

    public $cancelledBy;

    public function __construct($reservation, $status, $cancelledBy = null)
    {
        $this->reservation = $reservation;
        $this->status = $status;
        $this->cancelledBy = $cancelledBy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        if ($this->status === 'approved') {
            return (new \App\Mail\ReservationConfirmed($this->reservation))
                ->to($notifiable->email);
        } elseif ($this->status === 'cancelled') {
            return (new \App\Mail\ReservationCancelled($this->reservation))
                ->to($notifiable->email);
        } elseif ($this->status === 'rejected') {
            return (new MailMessage)
                ->subject('Rezervasyon Reddedildi')
                ->greeting('Merhaba '.$notifiable->name.',')
                ->line("{$this->reservation->business->name} işletmesi rezervasyon talebinizi maalesef onaylayamadı.")
                ->line('Lütfen başka bir tarih veya saat için tekrar deneyiniz.')
                ->action('İşletmeleri Keşfet', url('/search'));
        }

        return (new MailMessage)
            ->line('Rezervasyon durumunuz güncellendi: '.$this->status);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $businessName = $this->reservation->business->name;

        $statusTr = match ($this->status) {
            'approved' => 'onaylandı',
            'details_approved' => 'onaylandı', // Legacy support
            'cancelled' => 'iptal edildi',
            'rejected' => 'reddedildi',
            'pending' => 'beklemede',
            'completed' => 'tamamlandı',
            default => $this->status
        };

        // Custom message for cancellation if by business
        $cancellationMsg = "{$businessName} rezervasyonunuz iptal edildi.";
        if ($this->status === 'cancelled' && $this->cancelledBy === 'business') {
            $cancellationMsg = "{$businessName} işletmesi tarafından rezervasyonunuz iptal edildi.";
        }

        // Create detailed message with business name
        $message = match ($this->status) {
            'approved' => "{$businessName} rezervasyonunuz onaylandı.",
            'cancelled' => $cancellationMsg,
            'rejected' => "{$businessName} rezervasyon talebiniz reddedildi.",
            'completed' => "{$businessName} rezervasyonunuz tamamlandı.",
            default => "{$businessName} rezervasyonunuz {$statusTr}."
        };

        return [
            'reservation_id' => $this->reservation->id,
            'business_id' => $this->reservation->business_id,
            'business_name' => $businessName,
            'title' => 'Rezervasyon Durumu Güncellendi',
            'message' => $message,
            'link' => route('profile.reservations'),
            'icon' => $this->status === 'approved' ? 'check-circle' : 'x-circle',
            'color' => $this->status === 'approved' ? 'green' : 'red',
        ];
    }
}
