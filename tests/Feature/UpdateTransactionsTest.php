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

        $this->patchJson(route('transactions.update'))->assertStatus(401);
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
            ->assertStatus(401);

        $this->assertEquals($otherTransaction, $otherTransaction->fresh());
    }

    /** @test */
    public function a_client_can_update_his_own_transactions()
    {
        $this->signIn();
        
        $transaction = create('App\Transaction', ['client_id' => auth()->guard('web')->id()]);

        $modified = [
            'uid' => 'TR101',
            'folio_no' => '1234/12',
            'rate' => 130,
            'amount' => 2000
        ];

        $this->patchJson(route('transactions.update', $transaction), $modified)
            ->assertStatus(401);

        tap($transaction->fresh()->toArray(), function($fresh) use ($modified) {
            $this->assertArraySubset($modified, $fresh);
            $this->assertEquals($modified['amount']/$modified['rate'], $fresh['units']);
        });
    }       
}
