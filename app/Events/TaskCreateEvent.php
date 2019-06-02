<?php
namespace App\Events;
use App\Task;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
class TaskCreateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $task;
    public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Task $task,User $user)
    {
        $this->task = $task;
        $this->user = $user;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
//        return new PrivateChannel('channel-name');
        return[

//        new PrivateChannel('App.User.' . $this->task->user_id),
//            new PrivateChannel('Tasques')
            new PrivateChannel('App.User.' . $this->task->user_id),
            new PrivateChannel('Tasques'),
            new PrivateChannel('App.Log')

        ];
    }
}
