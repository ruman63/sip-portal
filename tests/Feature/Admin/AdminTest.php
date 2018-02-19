<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_visit_admin_panel()
    {
        $this->withExceptionHandling();

        $this->signInAdmin();

        $this->getJson(route('admin.dashboard'))
            ->assertStatus(200);
    }

    /** @test */
    public function a_non_admin_cannot_visit_admin_panel()
    {
        $this->withExceptionHandling();

        $this->getJson(route('admin.dashboard'))
            ->assertStatus(401);

        $this->signIn();

        $this->getJson(route('admin.dashboard'))
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_with_valid_credentials_can_successfully_login_to_admin_panel()
    {
        $this->withExceptionHandling();
        
        $username = 'john';
        $password = 'secret';
        $admin = create('App\Admin', compact('username', 'passwod'));

        $this->post(route('admin.login'), compact('username', 'password'))
            ->assertRedirect(route('admin.dashboard'));
    }
}
