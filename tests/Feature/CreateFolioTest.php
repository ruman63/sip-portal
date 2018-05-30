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
            'folio_no' => $folioId = '12312432',
            'transaction_uid' => $txnId = 2312414,
            'type' => 'ADD',
            'scheme_code' => 'LT-17',
            'date' => \Carbon\Carbon::now()->subMonths(4)->toDateTimeString(),
            'rate' => $rate = 120.53,
            'amount' => $amount = 3000,
        ]);

        $this->assertDatabaseHas('folios', [
            'folio_no' => $folioId,
            'client_id' => $client->id
        ]);

        $this->assertDatabaseHas('transactions', [
            'uid' => $txnId,
            'folio_id' => 1,    //First Folio Created
            'units' => $amount/$rate,
        ]);
    }

    /** @test */
    public function a_logged_in_client_can_add_transactions_to_existing_folio()
    {
        $this->signIn(
            $client = create('App\Client')
        );

        $folio = create('App\Folio');

        create('App\Transaction', [
            'folio_id' => $folio->id
        ]);

        $this->postJson(route('folios.store'), [
            'folio_no' => $folio->folio_no,
            'transaction_uid' => $txnId = 2312414,
            'type' => 'ADD',
            'scheme_code' => 'LT-17',
            'date' => \Carbon\Carbon::now()->subMonths(4)->toDateTimeString(),
            'rate' => $rate = 120.53,
            'amount' => $amount = 3000,
        ]);

        $this->assertCount(2, $folio->fresh()->transactions);

        $this->assertDatabaseHas('transactions', [
            'uid' => $txnId,
            'folio_id' => 1,
            'units' => $amount/$rate,
        ]);
    }
}
