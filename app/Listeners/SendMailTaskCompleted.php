<?php
namespace App\Listeners;
use App\Events\TaskCompletedEvent;
use App\Mail\TaskCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
class SendMailTaskCompleted implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $subject = $event->task->subject();
        if ($event->task->user){
            $user=$event->task->user;
        } else{
            $user=Auth::user();
        }
        Mail::to($user)
            ->cc(config('tasks.manager_email'))
            ->send((new TaskCompleted($event->task))->subject($subject));
    }
}
