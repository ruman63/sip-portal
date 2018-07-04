<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Client;
use App\Address;

class CreateClientAddressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_add_clients_address()
    {
        $client = create(Client::class);
        $this->withExceptionHandling()
            ->postJson(route('address.store'))->assertStatus(401);
    }

    /** @test */
    public function clients_can_add_their_address()
    {
        $this->signIn($client = create(Client::class));
        $address = make(Address::class, ['client_id' => $client->id]);
        $response = $this->postJson(route('address.store'), $address->toArray())
            ->assertSuccessful()
            ->json();
        $this->assertEquals(1, $client->address()->count());
        $this->assertArraySubset($response['data'], $client->fresh()->address->toArray());
    }

    /** @test */
    public function clients_cannot_add_more_than_one_address()
    {
        $this->signIn($client = create(Client::class));
        create(Address::class, ['client_id' => $client->id]);
        $this->assertEquals(1, $client->address()->count());

        $this->postJson(route('address.store'), make(Address::class)->toArray())->assertStatus(400);

        $this->assertEquals(1, $client->address()->count());
    }
}
