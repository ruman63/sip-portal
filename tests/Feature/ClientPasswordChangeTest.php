<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class ClientPasswordChangeTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp() 
    {
        parent::setUp();
        $this->client = create('App\Client', ['password' => bcrypt('secret')]);
    }
    
    /** @test */
    public function a_guest_cannot_see_clients_change_password_page()
    {
        $this->withExceptionHandling();

        $this->get(route('password.edit'))->assertRedirect('/');
    }
    
    /** @test */
    public function a_guest_cannot_change_clients_password()
    {
        $this->withExceptionHandling();

        $this->patchJson(route('password.update'))->assertStatus(401);
    }

    /** @test */
    public function a_client_can_visit_change_password_page()
    {
        $this->signIn($this->client);

        $this->get(route('password.edit'))
            ->assertStatus(200)
            ->assertSee('Change Password');
    }

    /** @test */
    public function change_password_requires_current_password()
    {
        $this->withExceptionHandling()->signIn($this->client);

        $response = $this->patchJson(route('password.update'))->assertStatus(422)->json();

        $this->assertArrayHasKey('current_password', $response['errors']);
        $this->assertContains('is required', $response['errors']['current_password'][0]);
    }

    /** @test */
    public function change_password_requires_current_password_min_6_characters()
    {
        $this->withExceptionHandling()->signIn($this->client);

        $response = $this->patchJson(route('password.update'), [
            'current_password' => 'short'
        ])->assertStatus(422)->json();

        $this->assertArrayHasKey('current_password', $response['errors']);
        $this->assertContains('at least 6', $response['errors']['current_password'][0]);
    }

    /** @test */
    public function change_password_requires_current_password_to_match_current_password()
    {
        $this->withExceptionHandling()->signIn($this->client);

        $response = $this->patchJson(route('password.update'), [
            'current_password' => 'invalid'
        ])->assertStatus(422)->json();

        $this->assertArrayHasKey('current_password', $response['errors']);
        $this->assertContains('invalid', $response['errors']['current_password'][0]);
    }

    /** @test */
    public function change_password_requires_new_password()
    {
        $this->withExceptionHandling()->signIn($this->client);

        $response = $this->patchJson(route('password.update'), [
            'current_password' => 'secret',
        ])->assertStatus(422)->json();

        $this->assertArrayHasKey('new_password', $response['errors']);
        $this->assertContains('is required', $response['errors']['new_password'][0]);
    }
    
    /** @test */
    public function change_password_requires_new_password_min_6_characters()
    {
        $this->withExceptionHandling()->signIn($this->client);

        $response = $this->patchJson(route('password.update'), [
            'new_password' => 'short'
        ])->assertStatus(422)->json();

        $this->assertArrayHasKey('new_password', $response['errors']);
        $this->assertContains('at least 6', $response['errors']['new_password'][0]);
    }

    /** @test */
    public function change_password_requires_new_passwords_to_match()
    {
        $this->withExceptionHandling()->signIn($this->client);

        $response = $this->patchJson(route('password.update'), [
            'new_password' => 'newsecret',
            'new_password_confirmation' => 'othersecret',
        ])->assertStatus(422)->json();

        $this->assertArrayHasKey('new_password', $response['errors']);
        $this->assertContains('not match', $response['errors']['new_password'][0]);
    }

    /** @test */
    public function a_client_can_change_his_password()
    {
        $this->signIn($this->client);

        $response = $this->patchJson(route('password.update'), [
            'current_password' => 'secret',
            'new_password' => 'newsecret',
            'new_password_confirmation' => 'newsecret',
        ])->assertStatus(200)->json();

        $this->assertTrue(Hash::check('newsecret', $this->client->fresh()->password));
    }
}
