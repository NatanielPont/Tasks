<?php
//psr-4
namespace Tests\Feature;

use App\Tag;
use App\Task;

//sufix as + alias;
use App\User;
use Tests\Feature\Traits\CanLogin;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TasquesControllerTest extends TestCase
{
    use RefreshDatabase, CanLogin;
    /**
     * @test
     */
    public function guest_user_cannot_index_tasks()
    {

        $response = $this->get('/tasques');
        $response->assertRedirect('login');
    }
    /**
     * @test
     */
    public function regular_user_cannot_index_tasks()
    {
        $this->login();
        $response = $this->get('/tasques');
        $response->assertStatus(403);
    }
    /**
     * @test
     */
    public function superadmin_can_index_tasks()
    {
//        $this->withoutExceptionHandling();
        create_example_tasks_with_tags();
        $user  = $this->loginAsSuperAdmin();
        $response = $this->get('/tasques');
        $response->assertSuccessful();
        $response->assertViewIs('tasques');
        $response->assertViewHas('tasks', function($tasks) {
            return count($tasks)===6 &&
                $tasks[0]['name']==='comprar pa' &&
                $tasks[1]['name']==='comprar llet' &&
                $tasks[2]['name']==='Estudiar PHP';
        });
        $response->assertViewHas('users', function($users) use ($user) {
            return count($users)===3 &&
                $users[2]['id']=== $user->id &&
                $users[2]['name']=== $user->name &&
                $users[2]['email']=== $user->email &&
                $users[2]['gravatar']=== $user->gravatar &&
                $users[2]['admin']=== $user->admin;
        });
        $response->assertViewHas('tags', function($tags) use ($user) {
            return count($tags)===2 &&
                $tags[0]['id']=== 1 &&
                $tags[0]['name']=== 'Tag1' &&
                $tags[0]['description']=== 'bla bla bla' &&
                $tags[0]['color']=== 'blue';
        });
    }
    /**
     * @test
     */
    public function superadmin_can_add_tagTasks()
    {
        //$this->withoutExceptionHandling();
//        create_example_tasks_with_tags();
        $user  = $this->loginAsSuperAdmin('api');
        //$user= factory(User::class)->create();


        $task=Task::create([
            'name' => 'comprar pa',
            'completed' => false,
            'description' => 'Bla bla bla',
            'user_id' => $user->id
        ]);
        $tag = Tag::create([
            'name' => 'Tag1',
            'description' => 'bla bla bla',
            'color' => 'blue'
        ]);
//        $response = $this->json('POST','/api/v1/tags/',[
//            'name' => 'tag1',
//            'color' => 'blue',
//            'description' => 'Bla bla bla'
//        ]);
//        $result = json_decode($response->getContent());
//        $response->assertSuccessful();
//        window.axios.post('/api/v1/tasks/' + this.$task.id + '/', tag).then(response => {
//        $response = $this->get('/tasques');

        $response = $this->json('POST','/api/v1/tasks/'.$task->id.'/tag',[
            'name'=>$tag->name,
            'color'=>$tag->color,
            'description'=>$tag->description
        ]);
        //dd($response);
        $result = json_decode($response->getContent());
        $response->assertSuccessful();

//        $response = $this->post('/api/v1/tasks/' + $tag->id );
        $response->assertSuccessful();
    }
    /**
     * @test
     */
    public function task_manager_can_index_tasks()
    {
        $this->withoutExceptionHandling();
        create_example_tasks();
        $this->loginAsTaskManager();
        $response = $this->get('/tasques');
        $response->assertSuccessful();
        $response->assertViewIs('tasques');
        $response->assertViewHas('tasks', function($tasks) {
            return count($tasks)===3 &&
                $tasks[0]['name']==='Comprar pa' &&
                $tasks[1]['name']==='Comprar llet' &&
                $tasks[2]['name']==='Estudiar php';
        });
    }
    /**
     * @test
     */
    public function tasks_user_can_index_tasks()
    {
        create_example_tasks();
        $user = $this->loginAsTasksUser();
        Task::create([
            'name' => 'Tasca usuari logat',
            'completed' => false,
            'description' => 'Jorl',
            'user_id' => $user->id
        ]);
        $response = $this->get('/tasques');
        $response->assertSuccessful();
        $response->assertViewIs('tasques');
        $response->assertViewHas('tasks', function($tasks) {
//            dd($tasks);
            return count($tasks)===4 &&
                $tasks[3]['name']==='Tasca usuari logat';
        });
        $response->assertViewHas('users');
        $response->assertViewHas('uri');
    }

}
