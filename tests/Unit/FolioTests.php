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
        (new BSEParser())->parse()->save(1);
        $scheme = \App\Scheme::first();
        
        $folio = create('App\Folio', ['scheme_code' => $scheme->scheme_code]);

        $this->assertInstanceOf('App\Scheme', $folio->scheme);
    }
}
