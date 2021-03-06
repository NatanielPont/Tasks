<?php
namespace App\Notifications;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;
/**
 * Class SimpleNotification.
 *
 * @package App\Notifications
 */
class HelloNotification extends Notification
{
    use Queueable;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast', WebPushChannel::class];
    }
    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Hello from Laravel!',
            'body' => 'Thank you for using our application.',
            'action_url' => 'https://laravel.com',
            'created' => Carbon::now()->toIso8601String()
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Hello from Laravel!')
            ->icon('/notification-icon.png')
            ->body('Thank you for using our application.')
            ->action('View app', 'view_app')
            ->data(['id' => $notification->id]);
    }
}
