<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTransactionsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_guest_cannot_update_transactions()
    {
        $this->withExceptionHandling();
        $transaction = create('App\Transaction');
        $this->patchJson(route('transactions.update', $transaction))->assertStatus(401);
    }
    
    /** @test */
    public function a_client_cannot_update_others_transactions()
    {
        $this->signIn();
        
        $otherTransaction = create('App\Transaction');

        $modified = [
            'uid' => 'TR101',
            'folio_no' => '1234/12',
            'rate' => 130,
            'amount' => 2000
        ];

        $this->patchJson(route('transactions.update', $otherTransaction), $modified)
            ->assertStatus(403);

        $this->assertEquals($otherTransaction->toArray(), $otherTransaction->fresh()->toArray());
    }

    /** @test */
    public function a_client_can_update_his_own_transactions()
    {
        $this->signIn();
        
        $scheme = create('App\Scheme');
        $transaction = create('App\Transaction', ['client_id' => auth()->guard('web')->id()]);

        $modified = [
            'uid' => 'TR101',
            'folio_no' => '1234/12',
            'rate' => 130,
            'date' => '2012-12-30',
            'type' => 'REDEEM',
            'scheme_code' => $scheme->scheme_code,
            'amount' => 2000
        ];

        $this->patchJson(route('transactions.update', $transaction), $modified)
            ->assertStatus(200);

        tap($transaction->fresh(), function($fresh) use ($modified) {
            $this->assertEquals($modified['uid'], $fresh->uid);
            $this->assertEquals($modified['type'], $fresh->type);
            $this->assertEquals($modified['date'], $fresh->date);
            $this->assertEquals($modified['folio_no'], $fresh->folio_no);
            $this->assertEquals($modified['scheme_code'], $fresh->scheme_code);
            $this->assertApproximatelyEquals($modified['rate'], $fresh->rate);
            $this->assertApproximatelyEquals($modified['amount'], $fresh->amount);
            $this->assertApproximatelyEquals($modified['amount']/$modified['rate'], $fresh->units);
        });
    } 
    
    /** @test */
    public function when_client_updates_transaction_updated_transaction_json_is_returned_with_scheme()
    {
        $this->signIn();
        
        $scheme = create('App\Scheme');
        $transaction = create('App\Transaction', ['client_id' => auth()->guard('web')->id()]);

        $response = $this->patchJson(route('transactions.update', $transaction), [
            'uid' => 'TR101',
            'folio_no' => '1234/12',
            'rate' => 13,
            'scheme_code' => $scheme->scheme_code,
            'amount' => 2000
        ])->json();

        $this->assertSame($transaction->fresh()->load('scheme')->toJson(), json_encode($response));
    } 
}
