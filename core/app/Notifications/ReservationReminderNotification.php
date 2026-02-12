<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationReminderNotification extends Notification
{
    use Queueable;

    protected $reservation;

    /**
     * Create a new notification instance.
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast', 'webpush'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $businessName = $this->reservation->business->name;
        $date = $this->reservation->start_time->format('d.m.Y H:i');

        return (new MailMessage)
            ->subject("Rezervasyon Hatırlatması: {$businessName}")
            ->greeting("Merhaba {$notifiable->name},")
            ->line("Yarınki rezervasyonunuzu hatırlatmak istedik.")
            ->line("İşletme: {$businessName}")
            ->line("Tarih ve Saat: {$date}")
            ->line("Kişi Sayısı: {$this->reservation->guest_count}")
            ->action('Detayları Gör', route('reservations.index'))
            ->line('Keyifli vakit geçirmenizi dileriz!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'reservation_reminder',
            'business_name' => $this->reservation->business->name,
            'reservation_time' => $this->reservation->start_time->format('H:i'),
            'message' => "Yarın {$this->reservation->business->name} işletmesinde rezervasyonunuz var.",
            'action_url' => route('reservations.index'),
            'icon' => 'clock',
        ];
    }

    /**
     * Get the web push representation of the notification.
     *
     * @param  mixed  $notifiable
     * @param  mixed  $notification
     * @return \NotificationChannels\WebPush\WebPushMessage
     */
    public function toWebPush($notifiable, $notification)
    {
        return (new \NotificationChannels\WebPush\WebPushMessage)
            ->title('Rezervasyon Hatırlatması')
            ->icon('/icon.png')
            ->body("Yarın {$this->reservation->business->name} işletmesinde rezervasyonunuz var.")
            ->action('Detayları Gör', 'view_details')
            ->data(['action_url' => route('profile.reservations')]);
    }
}
