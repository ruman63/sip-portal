<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Address;
use Illuminate\Database\QueryException;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function addressFactoryExists()
    {
        $address = create(Address::class);
        $this->assertInstanceOf(Address::class, $address);
    }

    /** @test */
    public function addressFactoryCreatesRequiredAttributes()
    {
        $address = create(Address::class);

        $this->assertNotNull($address->first_line);
        $this->assertNotNull($address->city);
        $this->assertNotNull($address->pincode);
        $this->assertNotNull($address->state);
        $this->assertNotNull($address->country);
        $this->assertNotNull($address->client_id);
    }

    /** @test */
    public function addressHaveAllOtherNecessaryAttributes()
    {
        $address = create(Address::class, [
            'second_line' => 'Opp Some Landmark',
            'third_line' => 'East of Kailash',
            'residence_phone' => '9876543210',
            'residence_fax' => '6543210',
            'office_phone' => '9876543210',
            'office_fax' => '6543210',
        ]);

        $this->assertNotNull($address->second_line);
        $this->assertEquals('Opp Some Landmark', $address->second_line);

        $this->assertNotNull($address->third_line);
        $this->assertEquals('East of Kailash', $address->third_line);

        $this->assertNotNull($address->residence_phone);
        $this->assertEquals('9876543210', $address->residence_phone);

        $this->assertNotNull($address->residence_fax);
        $this->assertEquals('6543210', $address->residence_fax);

        $this->assertNotNull($address->office_phone);
        $this->assertEquals('9876543210', $address->office_phone);

        $this->assertNotNull($address->office_fax);
        $this->assertEquals('6543210', $address->office_fax);
    }

    /** @test */
    public function addresses_have_unique_client_id()
    {
        $this->expectException(QueryException::class);

        create(Address::class, ['client_id' => 1]);

        create(Address::class, ['client_id' => 1]);

        $this->assertEquals(1, Address::count());
    }

    /** @test */
    public function an_empty_address_can_be_created_for_a_client_with_default_country_India()
    {
        $client = create('App\Client');
        $address = Address::create(['client_id' => $client->id]);
        $this->assertTrue($address->exists());
        $this->assertEquals('India', $address->country);
    }
}
