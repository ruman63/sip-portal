<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AllocationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_allocations()
    {
        $this->withExceptionHandling();
        $this->getJson(route('allocations.index'))->assertStatus(401);
    }

    /** @test */
    public function a_logged_in_client_can_access_his_allocations()
    {
        $this->withExceptionHandling();
        $this->signIn($client = create('App\Client'));

        $debtScheme = create('App\Scheme', ['scheme_type' => 'DEBT']);
        $equityScheme = create('App\Scheme', ['scheme_type' => 'EQUITY']);
        $otherScheme = create('App\Scheme', ['scheme_type' => 'LIQUID']);

        create('App\Folio', [
            'client_id' => $client->id,
            'amount' => 1200, 
            'scheme_code' => $debtScheme->scheme_code
        ], 2);
        create('App\Folio', [
            'client_id' => $client->id,
            'amount' => 1200, 
            'scheme_code' => $equityScheme->scheme_code
        ], 2);
        create('App\Folio', [
            'client_id' => $client->id,
            'amount' => 1200, 
            'scheme_code' => $otherScheme->scheme_code
        ], 1);

        $response = $this->getJson(
                route('allocations.index') . '?group=type'
            )->assertStatus(200)->json();

        $this->assertCount(3, $response);

        $this->assertArraySubset([
            'type' => 'DEBT',
            'amount' => 2400,
            'percent' => 40,
        ], $response[0]);

    }
}
