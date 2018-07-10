<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Client;
use App\BankAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateBankAccountsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function client_can_update_a_bank_account()
    {
        $this->signIn($client = create(Client::class));
        $bankAccount = create(BankAccount::class, ['owner_id' => $client->id, 'account_number' => '9876543210']);

        $response = $this->patchJson(
            route('bank-account.update', $bankAccount),
            ['account_number' => '1234567890']
        )->assertSuccessful()->json();

        $this->assertEquals('1234567890', $bankAccount->fresh()->account_number);
        $this->assertEquals('1234567890', $response['data']['account_number']);
    }

    /** @test */
    public function updating_a_bank_account_request_ignores_extra_values()
    {
        $this->signIn($client = create(Client::class));
        $bankAccount = create(BankAccount::class, ['owner_id' => $client->id, 'account_number' => '9876543210']);

        $response = $this->patchJson(
            route('bank-account.update', $bankAccount),
            [
                'account_number' => '1234567890',
                'some_unnecessary' => 'values',
            ]
        )->assertSuccessful()->json();

        $this->assertEquals('1234567890', $bankAccount->fresh()->account_number);
        $this->assertEquals('1234567890', $response['data']['account_number']);
    }

    /** @test */
    public function cannot_update_others_accounts()
    {
        $this->signIn($client = create(Client::class));
        $bankAccount = create(BankAccount::class, ['account_number' => '9876543210']);

        $response = $this->patchJson(
            route('bank-account.update', $bankAccount),
            [
                'account_number' => '1234567890',
                'some_unnecessary' => 'values',
            ]
        )->assertStatus(403)->json();

        $this->assertEquals('9876543210', $bankAccount->fresh()->account_number);
    }
}
