<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Client;
use App\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateClientAddressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_hit_update_address_endpoint()
    {
        $client = create(Client::class);
        $this->withExceptionHandling()
            ->postJson(route('address.update'))
            ->assertStatus(401);
    }

    /** @test */
    public function clients_can_update_their_address()
    {
        $this->signIn($client = create(Client::class));
        $address = create(Address::class, ['client_id' => $client->id]);
        $updatedAddress = $address->toArray();
        $updatedAddress['first_line'] = 'Updated First Line';
        $updatedAddress['second_line'] = 'Updated Second Line';
        $response = $this->patchJson(route('address.update'), $updatedAddress)
            ->assertSuccessful()
            ->json();
        tap($address->fresh(), function ($address) {
            $this->assertEquals('Updated First Line', $address->first_line);
            $this->assertEquals('Updated Second Line', $address->second_line);
        });
    }
}
