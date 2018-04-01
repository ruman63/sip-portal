<?php

namespace Tests\Unit;

use App\Folio;
use App\Transaction;
use Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
    public function client_has_many_transactions_through_folios()
    {
        $client = create('App\Client');
        $folio = create('App\Folio', ['client_id' => $client->id]);
        create('App\Transaction', ['folio_id' => $folio->id]);

        $this->assertInstanceOf(Collection::class, $client->transactions);
        $this->assertInstanceOf(Transaction::class, $client->transactions->first());
    }

    /** @test */
    public function client_has_many_folios()
    {
        $client = create('App\Client');
        $folio = create('App\Folio', ['client_id' => $client->id]);

        $this->assertInstanceOf(Collection::class, $client->folios);
        $this->assertInstanceOf(Folio::class, $client->folios->first());
    }
}