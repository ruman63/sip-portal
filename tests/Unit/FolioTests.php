<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\BSEParser;

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
    public function a_folio_knows_the_no_of_units_purchased()
    {
        
        $folio = create('App\Folio', [
            'purchase_price' => 120,
            'amount' => 5000
        ]);

        $this->assertEquals(5000/120, $folio->units);
        $this->assertArrayHasKey('units', $folio->toArray());
    }
}
