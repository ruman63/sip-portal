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
    public function it_calculates_the_units_upto_two_decimal_places()
    {
        $up = create('App\Transaction', [
            'rate'=> 10000,
            'amount' => 123456,
        ]);

        $down = create('App\Transaction', [
            'rate'=> 10000,
            'amount' => 123416,
        ]);

        tap($up->toArray(), function($array) {
            $this->assertArrayHasKey('units', $array);
            $this->assertEquals(12.35, $array['units']);
        });

        tap($down->toArray(), function($array) {
            $this->assertArrayHasKey('units', $array);
            $this->assertEquals(12.34, $array['units']);
        });
    }

    /** @test */
    public function it_returns_the_amount_upto_two_decimal_places()
    {
        $up = create('App\Transaction', [ 'amount' => 1234.56541 ]);
        $down = create('App\Transaction', [ 'amount' => 1234.5614 ]);

        $this->assertEquals(1234.57, $up['amount']);
        $this->assertEquals(1234.56, $down['amount']);
    }

    /** @test */
    public function it_returns_the_rate_upto_two_decimal_places()
    {
        $up = create('App\Transaction', [ 'rate' => 12.56541 ]);
        $down = create('App\Transaction', [ 'rate' => 12.5614 ]);

        $this->assertEquals(12.57, $up['rate']);
        $this->assertEquals(12.56, $down['rate']);
    }
}
