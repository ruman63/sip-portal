<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientLoginTests extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_client_can_login_using_email_and_correct_password()
    {
        $client = create('App\Client', [
            'email' => 'john@example.com',
            'password' => bcrypt('secret'),
        ]);

        $this->assertFalse(auth()->guard('web')->check());

        $this->post(route('login'), [
            'user' => 'john@example.com',
            'password' => 'secret',
        ]);

        $this->assertTrue(auth()->guard('web')->check());
    }

    /** @test */
    public function a_client_can_login_using_pan_and_correct_password()
    {
        $client = create('App\Client');
        $this->assertFalse(auth()->guard('web')->check());

        $this->post(route('login'), [
            'user' => $client->pan,
            'password' => 'secret',
        ]);

        $this->assertTrue(auth()->guard('web')->check());
    }
}
