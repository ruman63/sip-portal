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
    public function describe_test_here()
    {
        $txn = create('App\Transaction', [
            'rate'=> $rate = 10,
            'amount' => $amount = 2000,
        ]);

        $this->assertEquals($amount/$rate, $txn->units);
        
        tap($txn->toArray(), function($array) use ($amount, $rate) {
            $this->assertArrayHasKey('units', $array);
            $this->assertEquals($amount/$rate, $array['units']);
        });
    }
}
