<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use App\Client;

class ClientsRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function any_user_can_register_new_clients() 
    {
        $this->post(
            route('register'), 
            $client = make('App\Client')->toArray() + [
                'password' => 'secret',
                'password_confirmation' => 'secret',
            ]
        );

        $this->assertEquals(1, \App\Client::count(), 'No clients were created in database');

        $this->assertDatabaseHas('clients', [
            'id' => 1,
            'first_name' => $client['first_name'],
        ]);
    }

    /** @test */
    public function a_client_requires_pan_no()
    {
        $this->withExceptionHandling();

        $this->postJson(
            route('register'),
            $client = make('App\Client', [ 'pan' => '' ])->toArray() + [
                'password' => 'secret',
                'password_confirmation' => 'secret',
            ]
        )->assertStatus(422);
    }

    /** @test */
    public function a_client_requires_a_valid_formatted_pan_no()
    {
        $this->withExceptionHandling();
        $this->postJson(
            route('register'),
            make('App\Client', [ 'pan' => 'dasfddfa' ])->toArray() + [
                'password' => 'secret',
                'password_confirmation' => 'secret',
            ]
        )->assertStatus(422);

        $this->post(
            route('register'),
            make('App\Client', [ 'pan' => 'ABCDE1234X' ])->toArray() + [
                'password' => 'secret',
                'password_confirmation' => 'secret',
            ]
        )->assertRedirect();
    }

    /** @test */
    public function when_a_client_registers_himself_he_is_logged_in_to_clients_dashboard() 
    {
        $this->post( route('register'), 
            $client = make('App\Client')->toArray() + [
                'password' => 'secret',
                'password_confirmation' => 'secret',
            ])->assertRedirect(route('dashboard'));

        $this->assertTrue(\Auth::check(), 'User is not logged in');
    }

    /** @test */
    public function if_passwords_do_not_match_a_admin_is_redirected_back_with_error_message()
    {
        $this->withExceptionHandling();
        $this->signInAdmin();

        $client = make('App\Client')->toArray() 
            + [
                'password' => 'secret',
                'password_confirmation' => 'secret2',
            ];

        $this->post(route('register'), $client)
            ->assertSessionHasErrors('password');

    }

    /** @test */
    public function each_client_created_must_have_unique_emails()
    {
        $this->withExceptionHandling();
        $this->signInAdmin();
        $client1 = create('App\Client');
        $client = make('App\Client', ['email' => $client1->email])->toArray() 
            + [
                'password' => 'secret',
                'password_confirmation' => 'secret',
            ];

        $this->post(route('register'), $client)
            ->assertSessionHasErrors('email');

    }

    /** @test */
    public function makes_sure_password_is_hashed()
    {
        $this->signInAdmin();
        
        $client = make('App\Client');
            

        $this->post(route('register'), $client->toArray() + [
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $this->assertTrue(\Hash::check('secret', Client::find(1)->password));
    }
}
