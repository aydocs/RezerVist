<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WaitlistAvailableNotification extends Notification
{
    use Queueable;

    protected $waitlist;

    /**
     * Create a new notification instance.
     */
    public function __construct($waitlist)
    {
        $this->waitlist = $waitlist;
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
        $businessName = $this->waitlist->business->name;
        $date = $this->waitlist->reservation_date->format('d.m.Y');

        return (new MailMessage)
            ->subject("Sevindirici Haber: {$businessName} için yer açıldı!")
            ->greeting("Merhaba {$notifiable->name},")
            ->line("{$businessName} işletmesinde {$date} tarihinde beklediğiniz liste için boş bir yer açıldı.")
            ->line('Hemen rezervasyonunuzu tamamlayarak yerinizi ayırtabilirsiniz.')
            ->action('Hemen Rezerve Et', route('business.show', $this->waitlist->business->slug))
            ->line('Bizi tercih ettiğiniz için teşekkür ederiz!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'waitlist_available',
            'business_name' => $this->waitlist->business->name,
            'reservation_date' => $this->waitlist->reservation_date->format('Y-m-d'),
            'message' => "{$this->waitlist->business->name} için yer açıldı! Rezervasyonunuzu tamamlayabilirsiniz.",
            'action_url' => route('business.show', $this->waitlist->business->slug),
            'icon' => 'calendar-check',
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
            ->title('Sevindirici Haber!')
            ->icon('/icon.png')
            ->body("{$this->waitlist->business->name} için yer açıldı! Rezervasyonunuzu tamamlayabilirsiniz.")
            ->action('Hemen Rezerve Et', 'view_details')
            ->data(['action_url' => route('business.show', $this->waitlist->business->slug)]);
    }
}
