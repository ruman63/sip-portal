<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateFolioTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_guest_cannot_add_folio()
    {
        $this->withExceptionHandling();

        $this->postJson(route('folio.store'))->assertStatus(401);
    }

    /** @test */
    public function a_logged_in_client_can_add_folio()
    {
        $this->signIn(
            $client = create('App\Client')
        );

        $this->postJson(route('folio.store'), [
            'id' => $folioId = '12312432',
            'scheme_code' => 'LT-17',
            'trade_date' => \Carbon\Carbon::now()->subMonths(4)->toDateTimeString(),
            'purchase_price' => 120.53,
            'amount' => '3000',
        ]);

        $this->assertDatabaseHas('folios', [
            'id' => $folioId,
            'client_id' => $client->id
        ]);
    }
}
