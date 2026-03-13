<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class TestPushNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('RezerVist Test Bildirimi')
            ->icon('/icon.png')
            ->body('Web push bildirimleri başarıyla aktif edildi!')
            ->action('Siteyi Aç', 'open_site')
            ->data(['url' => url('/')]);
    }
}
