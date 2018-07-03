<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\BankAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Client;
use App\Exceptions\MaximumBankAccountsReachedException;

class BankAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function bank_account_factory_exists()
    {
        $bankAccount = create(BankAccount::class);
        $this->assertInstanceOf(BankAccount::class, $bankAccount);
    }

    /** @test */
    public function bank_account_factory_creates_required_attributes()
    {
        $bankAccount = create(BankAccount::class);

        $this->assertNotNull($bankAccount->account_number);
        $this->assertNotNull($bankAccount->account_type_code);
        $this->assertNotNull($bankAccount->ifsc_code);
        $this->assertNotNull($bankAccount->owner_id);
    }

    /** @test */
    public function bank_account_have_nullable_attributes()
    {
        $bankAccount = create(BankAccount::class, [
            'bank_name' => 'State Bank of India',
            'bank_branch' => 'RAMPUR',
            'bank_code' => 'SBIN',
            'bank_city' => 'RAMPUR',
            'micr' => '11232',
            'bank_address' => 'NAWAB GATE, RAMPUR (U.P), 244901',
        ]);

        $this->assertNotNull($bankAccount->bank_name);
        $this->assertEquals('State Bank of India', $bankAccount->bank_name);

        $this->assertNotNull($bankAccount->bank_branch);
        $this->assertEquals('RAMPUR', $bankAccount->bank_branch);

        $this->assertNotNull($bankAccount->bank_code);
        $this->assertEquals('SBIN', $bankAccount->bank_code);

        $this->assertNotNull($bankAccount->bank_city);
        $this->assertEquals('RAMPUR', $bankAccount->bank_city);

        $this->assertNotNull($bankAccount->micr);
        $this->assertEquals('11232', $bankAccount->micr);

        $this->assertNotNull($bankAccount->bank_address);
        $this->assertEquals('NAWAB GATE, RAMPUR (U.P), 244901', $bankAccount->bank_address);
    }

    /** @test */
    public function bank_account_belongs_to_owner_who_owns_the_account()
    {
        $bankAccount = create('App\BankAccount');
        $this->assertInstanceOf(Client::class, $bankAccount->owner);
    }

    /** @test */
    public function no_more_than_5_bank_accounts_should_be_allowed_for_a_client()
    {
        $client = create(Client::class);

        create(BankAccount::class, [
            'owner_id' => $client->id,
        ], 5);

        $this->assertCount(5, $client->bankAccounts);

        try {
            create(BankAccount::class, [
                'owner_id' => $client->id,
            ]);

            $this->fail('No Excpetion was thrown even though maximum bank accounts limit reached!');
        } catch (MaximumBankAccountsReachedException $e) {
            $this->assertCount(5, $client->fresh()->bankAccounts);
        }
    }

    /** @test */
    public function set_created_bank_as_default_bank_when_no_default_account_is_set_for_client()
    {
        $client = create(Client::class);

        $this->assertNull($client->defaultBankAccount);

        $clientBankAccount = create(BankAccount::class, ['owner_id' => $client->id]);

        tap($client->fresh()->defaultBankAccount, function ($defaultAccount) use ($clientBankAccount) {
            $this->assertInstanceOf(BankAccount::class, $defaultAccount);
            $this->assertTrue($defaultAccount->is($clientBankAccount));
        });
    }

    /** @test */
    public function setAsDefault_sets_default_bank_for_owner()
    {
        $client = create(Client::class);

        $this->assertNull($client->defaultBankAccount);

        $defaultBankAccount = create(BankAccount::class, ['owner_id' => $client->id])->setAsDefault();

        $this->assertTrue($client->fresh()->defaultBankAccount->is($defaultBankAccount));
    }
}
