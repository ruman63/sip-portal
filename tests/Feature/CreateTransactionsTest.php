<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Transaction;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTransactionsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_guest_cannot_add_transactions()
    {
        $this->withExceptionHandling();

        $this->postJson(route('transactions.store'))->assertStatus(401);
    }

    /** @test */
    public function a_logged_in_client_can_add_folio()
    {
        $this->signIn(
            $client = create('App\Client')
        );

        $response = $this->postJson(route('transactions.store'), [
            'folio_no' => $folioNo = '12312432',
            'uid' => $txnId = 2312414,
            'type' => 'ADD',
            'scheme_code' => 'LT-17',
            'date' => \Carbon\Carbon::now()->subMonths(4)->toDateTimeString(),
            'rate' => $rate = 120.53,
            'amount' => $amount = 3000,
        ])->json();

        $this->assertDatabaseHas('transactions', [
            'folio_no' => $folioNo,
            'uid' => $txnId,
            'client_id' => $client->id,
            'units' => $amount/$rate,
        ]);

        $this->assertEquals(Transaction::with('scheme')->first()->toArray(), $response);
    }

}
