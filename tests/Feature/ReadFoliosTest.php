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

        $this->getJson(route('admin.folios.index'))->assertStatus(401);
    }

    /** @test */
    public function a_client_cannot_read_folios()
    {
        $this->withExceptionHandling()->signIn();

        $this->getJson(route('admin.folios.index'))->assertStatus(401);
    }

    /** @test */
    public function an_admin_can_read_every_clients_folios()
    {
        $this->signInAdmin();
        $client = create('App\Client');
        create('App\Transaction', [
            'client_id' => $client->id,
            'folio_no' => 123,
        ]);

        create('App\Transaction', [
            'folio_no' => 234,
        ],2);
        
        $response = $this->getJson(route('admin.folios.index'))->json();

        $this->assertCount(2,$response);
        $this->assertEquals([123, 234], $response);

        $response = $this->getJson(route('admin.folios.index', ['client_id' => $client->id]))->json();
        $this->assertCount(1,$response);
        $this->assertEquals([123], $response);
        

    }
}
