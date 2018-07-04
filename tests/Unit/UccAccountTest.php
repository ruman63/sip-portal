<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\UccAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\BseStar\CodesLookup;
use App\Client;
use Illuminate\Database\QueryException;

class UccAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ucc_account_factory_exists()
    {
        $uccAccount = create(UccAccount::class);
        $this->assertInstanceOf(UccAccount::class, $uccAccount);
    }

    /** @test */
    public function bank_account_factory_creates_required_attributes()
    {
        $uccAccount = create(UccAccount::class);

        $this->assertNotNull($uccAccount->ucc);
        $this->assertNotNull($uccAccount->owner_id);
        $this->assertNotNull($uccAccount->holding_code);
        $this->assertNotNull($uccAccount->first_applicant_name);
        $this->assertNotNull($uccAccount->second_applicant_name);
        $this->assertNotNull($uccAccount->transaction_mode);
        $this->assertNotNull($uccAccount->depository);
        $this->assertNotNull($uccAccount->depository_dp_id);
        $this->assertNotNull($uccAccount->depository_client_id);
        $this->assertNotNull($uccAccount->tax_status_code);
        $this->assertNotNull($uccAccount->occupation_code);
        $this->assertNotNull($uccAccount->mapin_no);
        $this->assertNotNull($uccAccount->first_applicant_pan);
        $this->assertNotNull($uccAccount->second_applicant_pan);
        $this->assertNotNull($uccAccount->communication_mode);
        $this->assertNotNull($uccAccount->dividend_pay_mode);
    }

    /** @test */
    public function tax_status_resolves_correctly_according_to_tax_status_code()
    {
        $uccAccount = create(UccAccount::class, [
            'tax_status_code' => $code = CodesLookup::randomTaxStatusCode(),
        ]);

        $this->assertEquals(CodesLookup::taxStatus($code), $uccAccount->tax_status, 'Tax status code dont resolve correct tax status');
    }

    /** @test */
    public function occupation_code_resolves_to_a_occupation_category()
    {
        $uccAccount = create(UccAccount::class, [
            'occupation_code' => $code = CodesLookup::randomOccupationCode(),
        ]);

        $this->assertEquals(CodesLookup::occupation($code), $uccAccount->occupation);
    }

    /** @test */
    public function ucc_account_is_owned_by_a_client()
    {
        $client = create(Client::class);
        $uccAccount = create(UccAccount::class, [
            'owner_id' => $client->id,
        ]);

        $this->assertInstanceOf(Client::class, $uccAccount->owner);
        $this->assertEquals($client->id, $uccAccount->owner->id);
    }

    /** @test */
    public function it_associated_with_unique_owners()
    {
        create(UccAccount::class, ['owner_id' => 1]);

        try {
            create(UccAccount::class, ['owner_id' => 1]);
            $this->fail('Ucc accounts with duplicate clients were recorded, QueryException was not thrown.');
        } catch (QueryException $e) {
            $this->assertEquals(1, UccAccount::count());
        }
    }
}
