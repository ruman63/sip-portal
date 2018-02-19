<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateClientsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_register_new_clients() {
        
        $this->signInAdmin();

        $client = make('App\Client');
        $bankAccount = make('App\BankAccount');

        $request = $client->toArray() + [
            'bank_account' => $bankAccount->toArray(),
        ];

        $this->postJson(route('clients.store'), $request);

        $this->assertEquals(1, \App\Client::count(), 'No clients were created in database');
        $this->assertArrayHasKey(session(), 'password');
        $this->assertDatabaseHas('clients', [
            'name' => $client->name,
            'bank_account_number' => $bankAccount->account_number,
        ]);
        
    }
}
