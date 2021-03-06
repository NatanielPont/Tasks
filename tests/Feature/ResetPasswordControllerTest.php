<?php
/**
 * Created by PhpStorm.
 * User: alumne
 * Date: 26/10/18
 * Time: 16:15
 */

namespace Tests\Feature;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function can_reset_a_password()
    {
        $this->withoutExceptionHandling();
        //
        $user=factory(User::class)->create([
            'email'=>'provaName@gmail.com',
        ]);
        $this->assertNull(Auth::user());
        //
        $response=$this->post('/login_alt',['email'=>$user->email,
            'password'=>'secret']);
//        dd("hola");
        $response->assertStatus(302);
        $response->assertRedirect('/home');
        $this->assertNotNull(Auth::user());
        $this->assertEquals('provaName@gmail.com',Auth::user()->email);

        //


    }

}
