<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BusinessApplicationStatusNotification extends Notification
{
    use Queueable;

    protected $status;

    protected $adminNote;

    protected $businessName;

    protected $tempPassword;

    /**
     * Create a new notification instance.
     */
    public function __construct($status, $businessName, $adminNote = null, $tempPassword = null)
    {
        $this->status = $status;
        $this->adminNote = $adminNote;
        $this->businessName = $businessName;
        $this->tempPassword = $tempPassword;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('İşletme Başvurusu Durumu: '.ucfirst($this->status))
            ->greeting('Merhaba '.($this->businessName ?? 'İşletme').' Yetkilisi,');

        if ($this->status === 'approved') {
            $message->line('Başvurunuz ONAYLANMIŞTIR! 🎉')
                ->line('İşletme hesabınız oluşturulmuştur. Giriş yaparak işletme panelinizi yönetebilirsiniz.');

            if ($this->tempPassword) {
                $message->line('Giriş Bilgileriniz:')
                    ->line('E-posta: Hesabınızdaki e-posta adresi')
                    ->line('Şifre: '.$this->tempPassword)
                    ->line('Lütfen giriş yaptıktan sonra şifrenizi değiştiriniz.');
            }

            $message->action('Panele Git', url('/'));
        } elseif ($this->status === 'rejected') {
            $message->line('Maalesef başvurunuz reddedilmiştir.')
                ->line('Red Nedeni: '.($this->adminNote ?? 'Belirtilmedi.'));
            $message->action('Başvuruyu Düzenle', route('business.application.edit'));
        } else {
            // Received or Under Review
            $message->line('Başvurunuz sisteme alınmış ve inceleme sırasına dahil edilmiştir.')
                ->line('Mevcut Durum: '.($this->status === 'pending' ? 'Beklemede' : 'İncelemede'));
            $message->action('Durumu Takip Et', route('business.application.status'));
        }

        return $message->line('RezerVist ailesini tercih ettiğiniz için teşekkür ederiz!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'business_application',
            'status' => $this->status,
            'business_name' => $this->businessName,
            'admin_note' => $this->adminNote,
            'message' => $this->getNotificationMessage(),
            'icon' => $this->getNotificationIcon(),
            'action_url' => $this->getActionUrl(),
        ];
    }

    protected function getNotificationMessage()
    {
        return match ($this->status) {
            'approved' => "Tebrikler! '{$this->businessName}' başvurunuz onaylandı.",
            'rejected' => "'{$this->businessName}' başvurunuz maalesef reddedildi.",
            'pending' => "'{$this->businessName}' başvurunuz incelenmek üzere alındı.",
            'under_review' => "'{$this->businessName}' başvurunuz şu an yetkililerimizce inceleniyor.",
            default => "İşletme başvurunuzun durumu güncellendi: {$this->status}"
        };
    }

    protected function getNotificationIcon()
    {
        return match ($this->status) {
            'approved' => 'check-circle',
            'rejected' => 'x-circle',
            'pending' => 'clock',
            'under_review' => 'search',
            default => 'bell'
        };
    }

    protected function getActionUrl()
    {
        return match ($this->status) {
            'approved' => url('/dashboard'),
            'rejected' => route('business.application.edit'),
            default => route('business.application.status')
        };
    }
}
