<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\BankAccount;
use App\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateBankAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function client_can_add_a_bank_account()
    {
        $this->signIn($client = create(Client::class));
        $bankAccount = make(BankAccount::class, ['owner_id' => $client->id]);

        $response = $this->postJson(route('bank-account.store'), $bankAccount->toArray())
            ->assertSuccessful()
            ->json();

        tap($client->fresh()->bankAccounts, function ($accounts) use ($response) {
            $this->assertCount(1, $accounts);
            $this->assertEquals($response['data']['account_number'], $accounts->first()->account_number);
        });
    }

    /** @test */
    public function client_cannot_add_more_than_5_bank_accounts()
    {
        $this->signIn($client = create(Client::class));
        create(BankAccount::class, ['owner_id' => $client->id], 5);
        $bankAccount = make(BankAccount::class, ['owner_id' => $client->id]);

        $response = $this->postJson(route('bank-account.store'), $bankAccount->toArray())
            ->assertStatus(400)
            ->json();

        $this->assertCount(5, $client->fresh()->bankAccounts);
    }
}
