<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadTransactionsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_guest_cannot_see_transactions()
    {
        $this->withExceptionHandling();

        $this->getJson(route('admin.transactions.index'))->assertStatus(401);
    }

    /** @test */
    public function a_client_can_not_hit_admins_transactions_endpoint()
    {
        $this->withExceptionHandling()->signIn();

        $this->getJson(route('admin.transactions.index'))->assertStatus(401);
    }

    /** @test */
    public function an_admin_can_see_all_transactions_with_related_client_and_scheme()
    {
        $this->withExceptionHandling()->signInAdmin();

        create('App\Transaction', [], 4);
        
        $response = $this->getJson(route('admin.transactions.index'))->assertStatus(200)->json();

        $this->assertCount(4, $response);
        $this->assertArrayHasKey('scheme', $response[0]);
        $this->assertArrayHasKey('client', $response[0]);
    }

    /** @test */
    public function an_admin_can_see_a_clients_transactions_with_scheme()
    {
        $this->withExceptionHandling()->signInAdmin();

        $client = create('App\Client');
        create('App\Transaction', [], 2);
        create('App\Transaction', ['client_id' => $client->id], 2);
        
        $response = $this->getJson(route('clients.transactions', $client))->assertStatus(200)->json();

        $this->assertCount(2, $response);
        $this->assertArrayHasKey('scheme', $response[0]);
    }
}
