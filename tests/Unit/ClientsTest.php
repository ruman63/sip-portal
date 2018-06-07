<?php

namespace Tests\Unit;

use App\Folio;
use App\Transaction;
use Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

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
}