<?php

namespace Tests\Feature\Admin;

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

        $this->postJson(route('admin.transactions.store'))->assertStatus(401);
    }

    /** @test */
    public function an_admin_can_create_transactions_and_recieves_created_transaction_as_response()
    {
        $this->signInAdmin();

        $client = create('App\Client');
        $scheme = create('App\Scheme');

        $response = $this->postJson(route('admin.transactions.store'), [
            'folio_no' => $folioNo = '12312432',
            'uid' => $txnId = 2312414,
            'client_id' => $client->id,
            'type' => 'ADD',
            'scheme_code' => $scheme->scheme_code,
            'date' => \Carbon\Carbon::now()->subMonths(4)->format('Y-m-d'),
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
