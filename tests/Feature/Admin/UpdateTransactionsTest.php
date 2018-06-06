<?php

namespace Tests\Feature\Admin;

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
        $this->patchJson(route('admin.transactions.update', $transaction))->assertStatus(401);
    }

    /** @test */
    public function an_admin_can_update_all_transactions()
    {
        $this->signInAdmin();
        
        $scheme = create('App\Scheme');
        $transaction = create('App\Transaction');

        $modified = [
            'uid' => 'TR101',
            'folio_no' => '1234/12',
            'rate' => 130,
            'date' => '2012-12-30',
            'type' => 'REDEEM',
            'scheme_code' => $scheme->scheme_code,
            'amount' => 2000
        ];

        $this->patchJson(route('admin.transactions.update', $transaction), $modified)
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
    public function updated_transaction_json_is_returned_with_scheme()
    {
        $this->signInAdmin();
        
        $scheme = create('App\Scheme');
        $transaction = create('App\Transaction');

        $response = $this->patchJson(route('admin.transactions.update', $transaction), [
            'uid' => 'TR101',
            'folio_no' => '1234/12',
            'rate' => 13,
            'type' => "ADD",
            'date' => '2017-08-01',
            'scheme_code' => $scheme->scheme_code,
            'amount' => 2000
        ])->json();

        $this->assertSame($transaction->fresh()->load('scheme')->toJson(), json_encode($response));
    } 
}
