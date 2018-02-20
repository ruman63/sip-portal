<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowClientsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function an_admin_can_see_all_the_clients_registered()
    {
        $this->signInAdmin();

        $clients = create('App\Client', [], 2);

        $response = $this->getJson(route('clients.index'))->json();

        $this->assertCount(2, $response);
            
    }
}
