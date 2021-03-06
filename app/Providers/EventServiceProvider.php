<?php

namespace App\Providers;

use App\Events\TaskCompletedEvent;
use App\Events\TaskCreateEvent;
use App\Events\TaskDestroyEvent;
use App\Events\TaskUncompletedEvent;
use App\Events\TaskUpdateEvent;
use App\Listeners\AddRolesToRegisterUser;
use App\Listeners\ForgetTasksCache;
use App\Listeners\LogNotification;
use App\Listeners\LogTaskCompleted;
use App\Listeners\LogTaskCreate;
use App\Listeners\LogTaskDestroy;
use App\Listeners\LogTaskUncompleted;
use App\Listeners\LogTaskUpdate;
use App\Listeners\SendMailTaskCompleted;
use App\Listeners\SendMailTaskCreate;
use App\Listeners\SendMailTaskDestroy;
use App\Listeners\SendMailTaskUncompleted;
use App\Listeners\SendMailTaskUpdate;
use App\Listeners\SendNotificationDatabaseStored;
//use App\Listeners\SendNotificationTaskCompleted;
//use App\Listeners\SendNotificationTaskUncompleted;
use App\Listeners\tasks\SendNotificationTaskCompleted;
use App\Listeners\tasks\SendNotificationTaskDestroyed;
use App\Listeners\tasks\SendNotificationTaskUncompleted;
use App\Listeners\tasks\SendNotificationTaskStored;
use App\Listeners\tasks\SendNotificationTaskUpdated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Notifications\Events\NotificationSent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            AddRolesToRegisterUser::class,
        ],
        TaskUncompletedEvent::class => [

            LogTaskUncompleted::class,
            SendMailTaskUncompleted::class,
            SendNotificationTaskUncompleted::class,
            ForgetTasksCache::class

        ],
        TaskCompletedEvent::class => [
            LogTaskCompleted::class,
            SendMailTaskCompleted::class,
            SendNotificationTaskCompleted::class,
            ForgetTasksCache::class

        ],
        TaskDestroyEvent::class => [
            LogTaskDestroy::class,
            SendMailTaskDestroy::class,
            SendNotificationTaskDestroyed::class,
            ForgetTasksCache::class
        ],
        TaskCreateEvent::class => [
            LogTaskCreate::class,
            SendMailTaskCreate::class,
            SendNotificationTaskStored::class,
            ForgetTasksCache::class
        ],
        TaskUpdateEvent::class => [
            LogTaskUpdate::class,
            SendMailTaskUpdate::class,
            SendNotificationTaskUpdated::class,
            ForgetTasksCache::class
        ],

        NotificationSent::class => [
            LogNotification::class,
            SendNotificationDatabaseStored::class
        ]

    ];
    //

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
