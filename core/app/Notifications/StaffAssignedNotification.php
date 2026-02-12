<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $reservation;

    public $staff;

    /**
     * Create a new notification instance.
     */
    public function __construct($reservation, $staff)
    {
        $this->reservation = $reservation;
        $this->staff = $staff;
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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Rezervasyonunuza Personel Atandı - '.$this->reservation->business->name)
            ->greeting('Merhaba '.$notifiable->name.',')
            ->line('**'.$this->reservation->business->name.'** işletmesindeki rezervasyonunuza personel atandı!')
            ->line('**Personel:** '.$this->staff->name)
            ->line('**Pozisyon:** '.($this->staff->position ?? 'Personel'))
            ->line('**Rezervasyon Tarihi:** '.$this->reservation->start_time->format('d.m.Y'))
            ->line('**Rezervasyon Saati:** '.$this->reservation->start_time->format('H:i'))
            ->line('**Kişi Sayısı:** '.$this->reservation->guest_count.' kişi')
            ->line($this->staff->name.' sizinle ilgilenecek ve en iyi hizmeti sunmak için hazır olacak.')
            ->action('Rezervasyon Detaylarını Görüntüle', route('profile.reservations'))
            ->line('Keyifli bir deneyim geçirmenizi dileriz!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $position = $this->staff->position ?? 'Garson';
        $businessName = $this->reservation->business->name;

        return [
            'reservation_id' => $this->reservation->id,
            'business_id' => $this->reservation->business_id,
            'business_name' => $businessName,
            'staff_id' => $this->staff->id,
            'staff_name' => $this->staff->name,
            'staff_position' => $position,
            'title' => 'Personel Atandı',
            'message' => "{$businessName} - {$this->staff->name} isimli ".strtolower($position).' masanıza atandı.',
            'link' => route('profile.reservations'),
            'icon' => 'user-check',
            'color' => 'blue',
        ];
    }
}
