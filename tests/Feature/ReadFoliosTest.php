<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadFoliosTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_read_folios()
    {
        $this->withExceptionHandling();

        $this->getJson(route('folios.index'))->assertStatus(401);
    }

    /** @test */
    public function a_client_can_read_only_his_folios()
    {
        $this->signIn();
        create('App\Transaction', [], 4);
        create('App\Transaction', [
            'folio_no' => 123,
            'client_id' => auth()->guard('web')->id()
        ],2);

        create('App\Transaction', [
            'folio_no' => 234,
            'client_id' => auth()->guard('web')->id()
        ],2);
        
        $response = $this->getJson(route('folios.index'))->json();

        $this->assertCount(2,$response);
        $this->assertArraySubset([123, 234], $response);

    }
}
