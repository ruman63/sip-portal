<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\BankAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteBankAccountsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function client_cannot_delete_his_only_bank_account()
    {
        $this->signIn();
        $bankAccount = create(BankAccount::class, ['owner_id' => auth()->id()]);

        $this->deleteJson(route('bank-account.destroy', $bankAccount))
            ->assertStatus(400);

        $this->assertNotNull($bankAccount->fresh());
        $this->assertTrue($bankAccount->fresh()->is($bankAccount));
    }

    /** @test */
    public function client_can_delete_bank_account_if_he_owns_more_than_one_bank_account()
    {
        $this->signIn();
        $bankAccount1 = create(BankAccount::class, ['owner_id' => auth()->id()]);
        $bankAccount2 = create(BankAccount::class, ['owner_id' => auth()->id()]);
        $bankAccount3 = create(BankAccount::class, ['owner_id' => auth()->id()]);

        $response = $this->deleteJson(route('bank-account.destroy', $bankAccount3))
            ->assertSuccessful()
            ->json();

        $this->assertEquals($bankAccount3->id, $response['data']['id']);
        $this->assertNull($bankAccount3->fresh());
    }

    /** @test */
    public function client_cannot_delete_his_default_bank_account_even_if_he_owns_more_than_one_bank_account()
    {
        $this->signIn();
        $defaultBankAccount = create(BankAccount::class, ['owner_id' => auth()->id()])->setAsDefault();
        create(BankAccount::class, ['owner_id' => auth()->id()], 3);

        $response = $this->deleteJson(route('bank-account.destroy', $defaultBankAccount))
            ->assertStatus(400)
            ->json();

        $this->assertNotNull($defaultBankAccount->fresh());
        $this->assertTrue($defaultBankAccount->fresh()->is($defaultBankAccount));
    }
}
