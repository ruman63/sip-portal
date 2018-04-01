<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Folio;

class TransactionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_transaction_belongs_to_a_folio()
    {
        $txn = create('App\Transaction');
        $this->assertInstanceOf(Folio::class, $txn->folio);
    }
}
