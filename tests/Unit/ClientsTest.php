<?php

namespace Tests\Unit;

use App\Transaction;
use Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Client;
use App\BseStar\CodesLookup;
use App\Address;
use App\BankAccount;

class ClientsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function client_has_a_computed_full_name_property()
    {
        $client = create('App\Client', [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals($client->name, 'John Doe');
    }

    /** @test */
    public function client_has_many_transactions()
    {
        $client = create('App\Client');
        create('App\Transaction', ['client_id' => $client->id]);

        $this->assertInstanceOf(Collection::class, $client->transactions);
        $this->assertInstanceOf(Transaction::class, $client->transactions->first());
    }

    /** @test */
    public function client_can_change_his_password()
    {
        $client = create('App\Client');
        $this->assertTrue(Hash::check('secret', $client->password));

        $client->changePassword('top-secret');
        $this->assertTrue(Hash::check('top-secret', $client->fresh()->password));
    }

    /** @test */
    public function client_has_guardian()
    {
        $client = create(Client::class, [
            'guardian' => 'Mr. XYZ',
        ]);

        $this->assertEquals('Mr. XYZ', $client->guardian);
    }

    /** @test */
    public function client_has_guardian_pan()
    {
        $client = create(Client::class, [
            'guardian_pan' => 'ABCDE1234F',
        ]);

        $this->assertEquals('ABCDE1234F', $client->guardian_pan);
    }

    /** @test */
    public function client_has_nominee()
    {
        $client = create(Client::class, [
            'nominee' => 'John Doe',
        ]);

        $this->assertEquals('John Doe', $client->nominee);
    }

    /** @test */
    public function client_has_nominee_relation()
    {
        $client = create(Client::class, [
            'nominee_relation' => 'Uncle',
        ]);

        $this->assertEquals('Uncle', $client->nominee_relation);
    }

    /** @test */
    public function client_has_tax_status_code()
    {
        $client = create(Client::class, [
            'tax_status_code' => '1',
        ]);

        $this->assertEquals('1', $client->tax_status_code);
    }

    /** @test */
    public function clients_tax_status_resolves_correctly_according_to_tax_status_code()
    {
        $client = create(Client::class, [
            'tax_status_code' => $code = CodesLookup::randomTaxStatusCode(),
        ]);

        $this->assertEquals(CodesLookup::taxStatus($code), $client->tax_status, 'Client\'s tax status code dont resolve correct tax status');
    }

    /** @test */
    public function client_has_occupation_code()
    {
        $client = create(Client::class, [
            'occupation_code' => '01',
        ]);

        $this->assertEquals('01', $client->occupation_code);
    }

    /** @test */
    public function clients_occupation_code_resolves_to_a_occupation_category()
    {
        $client = create(Client::class, [
            'occupation_code' => $code = CodesLookup::randomOccupationCode(),
        ]);

        $this->assertEquals(CodesLookup::occupation($code), $client->occupation);
    }

    /** @test */
    public function client_has_communication_mode()
    {
        $client = create(Client::class, [
            'communication_mode' => 'P',
        ]);

        $this->assertEquals('P', $client->communication_mode);
    }

    /** @test */
    public function client_has_dividend_pay_mode()
    {
        $client = create(Client::class, [
            'dividend_pay_mode' => '01',
        ]);

        $this->assertEquals('01', $client->dividend_pay_mode);
    }

    /** @test */
    public function client_has_mapin_no()
    {
        $client = create(Client::class, [
            'mapin_no' => '01',
        ]);

        $this->assertEquals('01', $client->mapin_no);
    }

    /** @test */
    public function clients_have_an_address()
    {
        $client = create(Client::class);
        $this->assertNull($client->address);

        create(Address::class, [
            'client_id' => $client->id,
        ]);

        $this->assertInstanceOf(Address::class, $client->fresh()->address);
    }

    /** @test */
    public function client_has_many_bankAccounts()
    {
        $client = create(Client::class);
        $this->assertCount(0, $client->bankAccounts);

        create(BankAccount::class, [
            'owner_id' => $client->id,
        ], 2);

        $this->assertInstanceOf(Collection::class, $accounts = $client->fresh()->bankAccounts);
        $this->assertCount(2, $accounts);
        $accounts->assertHasInstancesOf(BankAccount::class);
    }

    /** @test */
    public function client_has_default_bank_account()
    {
        $client = create(Client::class);
        $defaultBankAccount = create(BankAccount::class, ['owner_id' => $client->id]);
        $otherBankAccounts = create(BankAccount::class, ['owner_id' => $client->id], 3);

        $client->default_bank_id = $defaultBankAccount->id;
        $client->save();

        tap($client->fresh()->defaultBankAccount, function ($clientsDefaultBankAccount) use ($defaultBankAccount) {
            $this->assertInstanceOf(BankAccount::class, $clientsDefaultBankAccount);
            $this->assertTrue($clientsDefaultBankAccount->is($defaultBankAccount));
        });
    }

    /** @test */
    public function update_default_bank_on_client_sets_default_bank()
    {
        $client = create(Client::class);
        $bankAccount = create(BankAccount::class, ['owner_id' => $client->id]);

        $this->assertTrue($client->updateDefaultBank($bankAccount)->is($bankAccount));

        $this->assertTrue($client->defaultBankAccount->is($bankAccount));
    }

    /** @test */
    public function update_default_bank_on_others_bank_account_throws_exception()
    {
        $client = create(Client::class);
        $bankAccount = create(BankAccount::class);

        $this->assertFalse($client->updateDefaultBank($bankAccount));
    }
}
