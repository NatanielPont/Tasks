<?php
use App\Log;
use App\Task;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class LogTaskCompletedTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function a_task_uncompleted_log_has_been_created()
    {
        // 1 Preparar
        $user = factory(User::class)->create();
        $task = Task::create([
            'name' => 'Comprar pa',
            'user_id' => $user->id
        ]);
        // Executar
//        event(new TaskUncompleted($task));
        $listener = new \App\Listeners\LogTaskCompleted();
        $listener->handle(new \App\Events\TaskCompletedEvent($task,$user));
        // 3 ASSERT
        // Test log is inserted
        $log  = Log::find(1);
        $this->assertEquals($log->text,"s'ha completat la tasca 'Comprar pa'");
        $this->assertEquals($log->action_type,'Completar');
        $this->assertEquals($log->module_type,'Tasques');
        $this->assertEquals($log->user_id,$user->id);
        $this->assertEquals((boolean)$log->old_value,false);
        $this->assertEquals($log->new_value,true);
        $this->assertEquals($log->loggable_id,$task->id);
        $this->assertEquals($log->loggable_type,Task::class);
        $this->assertEquals($log->icon,'lock_open');
        $this->assertEquals($log->color,'green');
    }
}
