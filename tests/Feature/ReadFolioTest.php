<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadFolioTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_guest_cannot_see_folios()
    {
        $this->withExceptionHandling();

        $this->getJson(route('folios.index'))->assertStatus(401);
    }

    /** @test */
    public function a_client_can_see_only_his_folios()
    {
        $this->signIn();

        $ownFolio = create('App\Folio', ['client_id' => auth()->guard('web')->id()]);
        create('App\Folio');

        $response = $this->getJson(route('folios.index'))->assertStatus(200)->json();

        $this->assertCount(1, $response);
        $this->assertEquals($response[0]['folio_no'], $ownFolio->folio_no);
    }
}
