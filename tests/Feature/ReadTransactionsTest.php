<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadTransactionsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_guest_cannot_see_transactions()
    {
        $this->withExceptionHandling();

        $this->getJson(route('transactions.index'))->assertStatus(401);
    }

    /** @test */
    public function a_client_can_see_only_his_transactions()
    {
        $this->signIn();

        $ownTransaction = create('App\Transaction', ['client_id' => auth()->guard('web')->id()]);
        create('App\Transaction');

        $response = $this->getJson(route('transactions.index'))->assertStatus(200)->json();

        $this->assertCount(1, $response);
        $this->assertEquals($response[0]['folio_no'], $ownTransaction->folio_no);
    }
}
