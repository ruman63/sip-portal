<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActAsClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_login_as_any_client()
    {
        $this->signInAdmin();
        $client = create('App\Client');

        $this->postJson(route('clients.login-as', $client))
            ->assertSuccessful()
            ->assertJson([
                'redirectUrl' => route('dashboard')
            ]);

        $this->assertTrue(auth()->guard('web')->check());
        $this->assertEquals($client->first_name, auth()->guard('web')->user()->first_name);
    }

    /** @test */
    public function non_admin_cannot_login_as_any_client()
    {
        $this->withExceptionHandling();
        
        $client = create('App\Client');

        $this->postJson(route('clients.login-as', $client))
            ->assertStatus(401);
    }

    /** @test */
    public function when_admin_logs_in_as_client_and_logout_client_admin_remains_logged_in()
    {
        $this->signInAdmin();

        $this->post(route('clients.login-as', $client = create('App\Client')));

        $this->post(route('logout'));

        $this->assertFalse(auth()->guard('web')->check());

        $this->assertTrue(auth()->guard('cpanel')->check());
    }
}
