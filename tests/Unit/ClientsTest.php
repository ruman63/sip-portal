<?php

namespace Tests\Unit;

use App\Folio;
use App\Transaction;
use Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Client;
use App\BseStar\CodesLookup;

class ClientsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function client_has_a_computed_full_name_property()
    {
        $client = create('App\Client', [
            'first_name' => 'John', 
            'last_name' => 'Doe'
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
}
