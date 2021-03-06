<?php

namespace App\Notifications\tasks;
use App\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

/**
 * Class TaskStored.
 *
 * @package App\Notifications
 */
class TaskUpdated extends Notification implements ShouldQueue
{
    use Queueable;
    public $task;

    /**
     * SimpleNotification constructor.
     * @param $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => "S'ha actualitzat una tasca: " . $this->task->name,
            'url' => '/tasques/' . $this->task->id,
            'icon' => 'assignment',
            'iconColor' => 'primary',
            'task' => $this->task->map()
        ];
    }


    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Tasca actualitzada!')
            ->icon('/notification-icon.png')
            ->body('Has actualitzat la tasca: ' . $this->task->name)
            ->action('Visualitza la tasca', 'open_url')
            ->data(['url' => env('APP_URL') . '/tasques/' . $this->task->id]);
    }
}
