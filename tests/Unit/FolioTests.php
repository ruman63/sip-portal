<?php

namespace Tests\Unit;

use Tests\TestCase;
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
}
