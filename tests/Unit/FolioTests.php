<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FolioTests extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_folio_has_a_associated_client()
    {
        $folio = create('App\Folio');
        $this->assertInstanceOf('App\Client', $folio->client);
    }

    /** @test */
    public function a_folio_has_a_associated_scheme()
    {
        $scheme = create('App\Scheme');
        
        $folio = create('App\Folio', ['scheme_code' => $scheme->scheme_code]);
        
        $this->assertInstanceOf('App\Scheme', $folio->scheme);
    }

    /** @test */
    public function a_folio_has_many_transactions()
    {
        $folio = create('App\Folio');
        
        $txn = create('App\Transaction', ['folio_id' => $folio->id]);

        $this->assertInstanceOf(Collection::class, $folio->transactions);
    }

    /** @test */
    public function a_folio_knows_the_total_amount_invested()
    {
        
        $folios = create('App\Folio', [], 2);

        $txns = create('App\Transaction', [
            'folio_id' => $folios[0]->id
        ], 3);

        $this->assertEquals($txns->sum('amount'), $folios[0]->totalAmount);
        $this->assertEquals(0, $folios[1]->totalAmount);
        
        $this->assertArrayHasKey('totalAmount', $folios[0]->toArray());
    }

    /** @test */
    public function a_folio_knows_the_total_units_hold()
    {
        
        $folios = create('App\Folio', [], 2);

        $txns = create('App\Transaction', [
            'folio_id' => $folios[0]->id
        ], 3);

        $this->assertApproximatelyEquals($txns->sum('units'), $folios[0]->totalUnits);
        
        tap($folios[0]->toArray(), function($array) use ($txns) {
            $this->assertArrayHasKey('totalUnits', $array);
            $this->assertApproximatelyEquals($txns->sum('units'), $array['totalUnits']);
        });
        
        $this->assertEquals(0, $folios[1]->totalUnits);
    }

    /** @test */
    public function a_folio_knows_the_average_rate_of_purchase()
    {
        
        $folios = create('App\Folio', [], 2);

        $txns = create('App\Transaction', [
            'folio_id' => $folios[0]->id
        ], 3);

        $this->assertApproximatelyEquals($folios[0]->totalAmount/ $folios[0]->totalUnits, $folios[0]->averageRate);
        
        tap($folios[0]->toArray(), function($array) use ($txns) {
            $this->assertArrayHasKey('averageRate', $array);
            $this->assertApproximatelyEquals($array['totalAmount']/$array['totalUnits'], $array['averageRate']);
        });
    }
}
