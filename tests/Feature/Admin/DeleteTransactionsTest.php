<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Transaction;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTransactionsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_guest_cannot_delete_transactions()
    {
        $this->withExceptionHandling();
        $transaction = create('App\Transaction');
        $this->deleteJson(route('admin.transactions.destroy', $transaction))->assertStatus(401);
    }

    /** @test */
    public function an_admin_can_delete_all_transactions()
    {
        $this->signInAdmin();
        $transaction = create('App\Transaction');

        $this->deleteJson(route('admin.transactions.destroy', $transaction));
         
        $this->assertDatabaseMissing('transactions', $transaction->toArray());
       	
    }
    
}
