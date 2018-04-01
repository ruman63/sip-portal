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
        $this->signIn($client = create('App\Client'));

        $debtFolios = create('App\Folio', [
            'client_id' => $client->id,
            'scheme_code' => function() {
                return create('App\Scheme', ['scheme_type' => 'DEBT'])->scheme_code;
            }
        ], 2);
        $equityFolio = create('App\Folio', [
            'client_id' => $client->id,
            'scheme_code' => function() {
                return create('App\Scheme', ['scheme_type' => 'EQUITY'])->scheme_code;
            }
        ]);

        $debtFolios->each(function($folio) {
            create('App\Transaction', [
                'amount' => 2000,
                'type' => 'ADD',
                'folio_id' => $folio->id
            ], 2);
        });

        $equityTransactions = create('App\Transaction', [
            'amount' => 1000,
            'type' => 'ADD',
            'folio_id' => $equityFolio->id
        ], 2);

        $response = $this->getJson(
            route('allocations.index') . '?group=type'
        )->assertStatus(200)->json();

        $this->assertCount(2, $response);

        $this->assertArraySubset([
            [
                'type' => 'DEBT',
                'amount' => 8000,
                'percent' => 80,
            ],
            [
                'type' => 'EQUITY',
                'amount' => 2000,
                'percent' => 20,
            ],
        ], $response);

    }
}
