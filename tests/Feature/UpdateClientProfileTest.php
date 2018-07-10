<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateClientProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_post_to_update_endpoint()
    {
        $this->withExceptionHandling()
            ->patchJson(route('profile.update'))
            ->assertStatus(401);
    }

    /** @test */
    public function a_guest_can_update_his_name()
    {
        $this->signIn($client = create(Client::class));

        $response = $this->patchJson(route('profile.update'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ])->assertStatus(200)->json();

        $this->assertEquals('John', $response['data']['first_name']);
        $this->assertEquals('Doe', $response['data']['last_name']);

        tap($client->fresh(), function ($newClient) {
            $this->assertEquals('John', $newClient->first_name);
            $this->assertEquals('Doe', $newClient->last_name);
        });
    }

    /** @test */
    public function a_guest_can_also_update_other_details()
    {
        $this->signIn($client = create(Client::class));

        $response = $this->patchJson(route('profile.update'), [
            'dob' => '1995-12-11',
            'gender' => 'M',
            'email' => 'john@example.com',
            'pan' => 'ABCDE1234F',
            'mobile' => '9876543210',
            'guardian' => 'Some Guardian',
            'guardian_pan' => 'ABCDE2345F',
            'nominee' => 'Some Nominee',
            'nominee_relation' => 'Uncle',
        ])->assertStatus(200)->json();

        $this->assertEquals('1995-12-11', $response['data']['dob']);
        $this->assertEquals('M', $response['data']['gender']);
        $this->assertEquals('john@example.com', $response['data']['email']);
        $this->assertEquals('ABCDE1234F', $response['data']['pan']);
        $this->assertEquals('9876543210', $response['data']['mobile']);
        $this->assertEquals('Some Guardian', $response['data']['guardian']);
        $this->assertEquals('ABCDE2345F', $response['data']['guardian_pan']);
        $this->assertEquals('Some Nominee', $response['data']['nominee']);
        $this->assertEquals('Uncle', $response['data']['nominee_relation']);

        tap($client->fresh(), function ($newClient) {
            $this->assertEquals('1995-12-11', $newClient->dob);
            $this->assertEquals('M', $newClient->gender);
            $this->assertEquals('john@example.com', $newClient->email);
            $this->assertEquals('ABCDE1234F', $newClient->pan);
            $this->assertEquals('9876543210', $newClient->mobile);
            $this->assertEquals('Some Guardian', $newClient->guardian);
            $this->assertEquals('ABCDE2345F', $newClient->guardian_pan);
            $this->assertEquals('Some Nominee', $newClient->nominee);
            $this->assertEquals('Uncle', $newClient->nominee_relation);
        });
    }
}
