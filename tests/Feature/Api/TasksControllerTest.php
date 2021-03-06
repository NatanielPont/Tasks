<?php
/**
 * Created by PhpStorm.
 * User: alumne
 * Date: 11/10/18
 * Time: 19:24
 */

namespace Tests\Feature\Api;


use App\Events\TaskCreateEvent;
use App\Events\TaskDestroyEvent;
use App\Events\TaskUpdateEvent;
use App\Tag;
use App\Task;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\Feature\Traits\CanLogin;
use Tests\TestCase;

class TasksControllerTest extends TestCase
{
    use RefreshDatabase, CanLogin;
    // *********************** SHOW *******************************************************
    /**
     * @test
     */
    public function task_manager_can_show_a_task()
    {
        $this->withoutExceptionHandling();
        $this->loginAsTaskManager('api');
        $task = factory(Task::class)->create();
        $response = $this->json('GET','/api/v1/tasks/' . $task->id);
        $result = json_decode($response->getContent());
        $response->assertSuccessful();
        $this->assertEquals($task->name, $result->name);
        $this->assertEquals($task->completed, (boolean) $result->completed);
    }
    /**
     * @test
     */
    public function superadmin_can_show_a_task()
    {
        $this->loginAsSuperAdmin('api');
        $task = factory(Task::class)->create();
        $response = $this->json('GET','/api/v1/tasks/' . $task->id);
        $result = json_decode($response->getContent());
        $response->assertSuccessful();
        $this->assertEquals($task->name, $result->name);
        $this->assertEquals($task->completed, (boolean) $result->completed);
    }
    /**
     * @test
     */
    public function regular_user_cannot_show_a_task()
    {
        $this->login('api');
        $task = factory(Task::class)->create();
        $response = $this->json('GET','/api/v1/tasks/' . $task->id);
        $response->assertStatus(403);
    }
    /**
     * @test
     */
    public function guest_user_cannot_show_a_task()
    {
        $task = factory(Task::class)->create();
        $response = $this->json('GET','/api/v1/tasks/' . $task->id);
        $response->assertStatus(401);
    }
    // *********************** DELETE *******************************************************
    /**
     * @test
     */
    public function tasks_manager_can_delete_task()
    {
        $this->loginAsTaskManager('api');
        $task = factory(Task::class)->create();
        Event::fake();

        $response = $this->json('DELETE','/api/v1/tasks/' . $task->id);
        $result = json_decode($response->getContent());
        $response->assertSuccessful();
        $this->assertEquals($result->name, $task->name);
        $this->assertNull(Task::find($task->id));
        Event::assertDispatched(TaskDestroyEvent::class, function ($event) use ($task) {
            return $event->task->is($task);
        });
    }
    /**
     * @test
     */
    public function superadmin_can_delete_task()
    {
        $this->loginAsSuperAdmin('api');
        $task = factory(Task::class)->create();
        Event::fake();

        $response = $this->json('DELETE','/api/v1/tasks/' . $task->id);
        $result = json_decode($response->getContent());
        $response->assertSuccessful();
        $this->assertEquals($result->name, $task->name);
        $this->assertNull(Task::find($task->id));
        Event::assertDispatched(TaskDestroyEvent::class, function ($event) use ($task) {
            return $event->task->is($task);
        });
    }
    /**
     * @test
     */
    public function regular_user_cannot_delete_task()
    {
        $this->login('api');
        $task = factory(Task::class)->create();
        $response = $this->json('DELETE','/api/v1/tasks/' . $task->id);
        $result = json_decode($response->getContent());
        $response->assertStatus(403);
    }
    // *********************** CREATE *******************************************************
    /**
     * @test
     */
    public function cannot_create_tasks_without_name()
    {
//        $this->loginAsTaskManager('api');
        $this->loginWithPermission('api','tasks.store');
        $response = $this->json('POST','/api/v1/tasks/',[
            'name' => ''
        ]);
        $response->assertStatus(422);
    }
    /**
     * @test
     */
    public function superadmin_can_create_task()
    {
        $this->loginAsSuperAdmin('api');
        Event::fake();

        $response = $this->json('POST','/api/v1/tasks/',[
            'name' => 'Comprar pa',
            'description' => 'Bla bla bla',
        ]);
        $result = json_decode($response->getContent());
        $response->assertSuccessful();
        $this->assertNotNull($task = Task::find($result->id));
        $this->assertEquals('Comprar pa',$result->name);
        $this->assertEquals('Bla bla bla',$result->description);
        $this->assertFalse($result->completed);
    }
    /**
     * @test
     */
    public function superadmin_can_create_fulltask()
    {
//        $this->withoutExceptionHandling();
        $this->loginAsSuperAdmin('api');
        $user = factory(User::class)->create();
        $response = $this->json('POST','/api/v1/tasks/',[
            'name' => 'Comprar pa',
            'description' => 'Bla bla bla',
            'completed' => true,
            'user_id' => $user->id
        ]);
        $result = json_decode($response->getContent());
        $response->assertSuccessful();
        $this->assertNotNull($task = Task::find($result->id));
        $this->assertEquals('Comprar pa',$result->name);
        $this->assertEquals('Bla bla bla',$result->description);
        $this->assertEquals(true,$result->completed);
        $this->assertEquals($user->id,$result->user_id);
        $this->assertEquals('Comprar pa',$task->name);
        $this->assertEquals('Bla bla bla',$task->description);
        $this->assertEquals(true,$task->completed);
        $this->assertEquals($user->id,$task->user_id);
    }
    /**
     * @test
     */
    public function superadmin_can_create_completed_task()
    {
        $this->loginAsSuperAdmin('api');
        Event::fake();

        $response = $this->json('POST','/api/v1/tasks/',[
            'name' => 'Comprar pa',
            'description' => 'Bla bla bla',
            'completed' => true,
        ]);
        $result = json_decode($response->getContent());
        $response->assertSuccessful();
        $this->assertNotNull($task = Task::find($result->id));
        $this->assertEquals('Comprar pa',$result->name);
        $this->assertEquals('Bla bla bla',$result->description);
        $this->assertTrue($result->completed);
        $this->assertEquals('Comprar pa',$task->name);
        $this->assertEquals('Bla bla bla',$task->description);
        $this->assertEquals(1,$task->completed);
        Event::assertDispatched(TaskCreateEvent::class, function ($event) use ($task) {
            return $event->task->is($task);
        });
    }
    /**
     * @test
     */
    public function task_manager_can_create_task()
    {
        $this->loginAsTaskManager('api');
        Event::fake();


        $response = $this->json('POST','/api/v1/tasks/',[
            'name' => 'Comprar pa'
        ]);
        $result = json_decode($response->getContent());
        $response->assertSuccessful();
        $this->assertNotNull($task = Task::find($result->id));
        $this->assertEquals('Comprar pa',$result->name);
        $this->assertFalse($result->completed);
        Event::assertDispatched(TaskCreateEvent::class, function ($event) use ($task) {
            return $event->task->is($task);
        });
    }
    /**
     * @test
     */
    public function regular_user_cannot_create_task()
    {
        $user = $this->login('api');
        $response = $this->json('POST','/api/v1/tasks/',[
            'name' => 'Comprar pa'
        ]);
        $result = json_decode($response->getContent());
        $response->assertStatus(403);
    }
    // *********************** INDEX *******************************************************
    /**
     * @test
     */
    public function regular_user_cannot_index_tasks()
    {
        $this->login('api');
        $response = $this->json('GET','/api/v1/tasks');
        $response->assertStatus(403);
    }
    /**
     * @test
     */
    public function superadmin_can_index_tasks()
    {
        $this->loginAsSuperAdmin('api');
        create_example_tasks();
        $response = $this->json('GET','/api/v1/tasks');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertCount(3,$result);
        $this->assertEquals('Comprar pa', $result[0]->name);
        $this->assertTrue((boolean)$result[0]->completed);
        $this->assertEquals('Comprar llet', $result[1]->name);
        $this->assertFalse((boolean) $result[1]->completed);
        $this->assertEquals('Estudiar php', $result[2]->name);
        $this->assertFalse((boolean) $result[2]->completed);
    }
    /**
     * @test
     */
    public function task_manager_can_index_tasks()
    {
        $this->loginAsTaskManager('api');
        create_example_tasks();
        $response = $this->json('GET','/api/v1/tasks');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertCount(3,$result);
        $this->assertEquals('Comprar pa', $result[0]->name);
        $this->assertTrue((boolean)$result[0]->completed);
        $this->assertEquals('Comprar llet', $result[1]->name);
        $this->assertFalse((boolean) $result[1]->completed);
        $this->assertEquals('Estudiar php', $result[2]->name);
        $this->assertFalse((boolean) $result[2]->completed);
    }
    // *********************** EDIT *******************************************************
    /**
     * @test
     */
    public function regular_user_cannot_edit_task()
    {
        $this->login('api');
        $oldTask = factory(Task::class)->create([
            'name' => 'Comprar llet'
        ]);
        $response = $this->json('PUT','/api/v1/tasks/' . $oldTask->id, [
            'name' => 'Comprar pa'
        ]);
        json_decode($response->getContent());
        $response->assertStatus(403);
    }
    /**
     * @test
     */
    public function superadmin_can_edit_task()
    {
        $this->loginAsSuperAdmin('api');
        Event::fake();
        $oldTask = factory(Task::class)->create([
            'name' => 'Comprar llet'
        ]);
        // 2
        $response = $this->json('PUT','/api/v1/tasks/' . $oldTask->id, [
            'name' => 'Comprar pa'
        ]);
        $result = json_decode($response->getContent());
        $response->assertSuccessful();
        $newTask = $oldTask->refresh();
        $this->assertNotNull($newTask);
        $this->assertEquals('Comprar pa',$result->name);
        $this->assertFalse((boolean) $newTask->completed);
//        Event::assertDispatched(TaskUpdateEvent::class, function ($event) use ($oldTask,$task,$user) {
//            return $event->task->is($task);
//        });
    }
    /**
     * @test
     */
    public function task_manager_can_edit_task()
    {
        $this->loginAsTaskManager('api');
        $oldTask = factory(Task::class)->create([
            'name' => 'Comprar llet'
        ]);
        Event::fake();

        // 2
        $response = $this->json('PUT','/api/v1/tasks/' . $oldTask->id, [
            'name' => 'Comprar pa'
        ]);
        $result = json_decode($response->getContent());
        $response->assertSuccessful();
        $newTask = $oldTask->refresh();
        $this->assertNotNull($newTask);
        $this->assertEquals('Comprar pa',$result->name);
        $this->assertFalse((boolean) $newTask->completed);
    }
    /**
     * @test
     */
    public function cannot_edit_task_without_name()
    {
//        $this->withoutExceptionHandling();
        $this->loginAsTaskManager('api');
        $oldTask = factory(Task::class)->create();
        $response = $this->json('PUT','/api/v1/tasks/' . $oldTask->id, [
            'name' => ''
        ]);
        $response->assertStatus(422);
    }
}
