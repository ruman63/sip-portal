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

        $this->postJson(route('folios.store'))->assertStatus(401);
    }

    /** @test */
    public function a_logged_in_client_can_add_folio()
    {
        $this->signIn(
            $client = create('App\Client')
        );

        $this->postJson(route('folios.store'), [
            'folio_no' => $folioNo = '12312432',
            'transaction_uid' => $txnId = 2312414,
            'type' => 'ADD',
            'scheme_code' => 'LT-17',
            'date' => \Carbon\Carbon::now()->subMonths(4)->toDateTimeString(),
            'rate' => $rate = 120.53,
            'amount' => $amount = 3000,
        ]);

        $this->assertDatabaseHas('transactions', [
            'folio_no' => $folioNo,
            'uid' => $txnId,
            'client_id' => $client->id,
            'units' => $amount/$rate,
        ]);
    }
}
