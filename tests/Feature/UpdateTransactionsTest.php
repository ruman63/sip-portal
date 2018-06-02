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
        $changes = [
            'uid' => '2000',
            'rate' => 130,
            'folio_no' => '123456/34'
        ];

        $this->patchJson(route('transactions.update'), ['id' => $otherTransaction->id] + $changes)
            ->assertStatus(401);

        $this->assertEquals($otherTransaction, $otherTransaction->fresh());
    }

    /** @test */
    public function a_client_can_update_his_own_transactions()
    {
        $this->signIn();
        
        $transaction = create('App\Transaction', ['client_id' => auth()->guard('web')->id()]);

        $changes = [
            'uid' => '2000',
            'rate' => 130,
            'folio_no' => '123456/34'
        ];

        $this->patchJson(route('transactions.update'), ['id' => $transaction->id] + $changes)
            ->assertStatus(401);

        tap($transaction->fresh()->toArray(), function($fresh) use ($changes) {
            $this->assertArraySubset($changes, $fresh);
            $this->assertEquals($fresh['amount']/$changes['rate'], $fresh['units']);
        });
    }       
}
