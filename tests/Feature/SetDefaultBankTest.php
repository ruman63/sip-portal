<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\BankAccount;
use App\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SetDefaultBankTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function client_can_set_one_of_his_bank_accounts_as_default()
    {
        $this->signIn($client = create(Client::class));
        $defaultBankAccount = create(BankAccount::class, ['owner_id' => $client->id])->setAsDefault();
        $otherBankAccount = create(BankAccount::class, ['owner_id' => $client->id]);

        $this->assertTrue($client->fresh()->defaultBankAccount->is($defaultBankAccount));

        $this->postJson(route('bank-account.default', $otherBankAccount))
            ->assertSuccessful();

        $this->assertTrue($client->fresh()->defaultBankAccount->is($otherBankAccount));
    }

    /** @test */
    public function client_cannot_set_bank_accounts_as_default_which_he_dont_own()
    {
        $this->signIn($client = create(Client::class));
        $ownBankAccount = create(BankAccount::class, ['owner_id' => $client->id])->setAsDefault();
        $otherBankAccount = create(BankAccount::class);

        $this->assertTrue($client->fresh()->defaultBankAccount->is($ownBankAccount));

        $this->postJson(route('bank-account.default', $otherBankAccount))
            ->assertStatus(403);

        $this->assertTrue($client->fresh()->defaultBankAccount->is($ownBankAccount));
    }
}
