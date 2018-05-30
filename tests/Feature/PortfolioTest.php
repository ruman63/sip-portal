<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_access_portfolio_section()
    {
        $this->withExceptionHandling();

        $this->getJson(route('portfolios.index'))->assertStatus(401);
    }

    /** @test */
    public function a_client_can_access_his_portfolio_section()
    {
        $this->signIn();
        
        $response = $this->getJson(route('portfolios.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function portfolio_summary_is_evaluated_correctly()
    {
        $this->signIn();

        $debt = create('App\Scheme', [ 'scheme_type' => "DEBT" ]);
        $elss = create('App\Scheme', [ 'scheme_type' => "ELSS" ]);

        $debtTxns = create('App\Transaction', [
            'client_id' => auth()->guard('web')->id(),
            'scheme_code' => $debt->scheme_code
        ], 3);
        $elssTxns = create('App\Transaction', [
            'client_id' => auth()->guard('web')->id(),
            'scheme_code' => $elss->scheme_code
        ], 3);

        $response = $this->getJson(route('portfolios.index'))->json();

        $this->assertCount(2, $response);
        $this->assertArrayHasKey('ELSS', $response);
        $this->assertArrayHasKey('DEBT', $response);
        
        $this->assertCount(3, $response["ELSS"]);
        
    }

    /** @test */
    // public function portfolio_summary_gives_abslute_return_correctly()
    // {
    //     $this->signIn();

    //     $scheme = create('App\Scheme', [ 'scheme_type' => "DEBT" ]);

    //     $txns = create('App\Transaction', [
    //         'client_id' => auth()->guard('web')->id(),
    //         'scheme_code' => $scheme->scheme_code
    //     ], 3);

    //     $response = $this->getJson(route('portfolios.index'))->json();

    //     dd($response["DEBT"][0]);
        
    //     // $expectedReturn = ($clientFolio->currentValue - $clientFolio->totalAmount) * 100 / $clientFolio->totalAmount;
    //     // $this->assertEquals($expectedReturn, $response[0]['absoluteReturn']);
    // }

    // /** @test */
    // public function portfolio_summary_gives_xirr_correctly()
    // {
    //     $this->signIn();

    //     $clientFolio = create('App\Folio', [
    //         'client_id' => auth()->guard('web')->id(),
    //     ]);
    //     $days = 300;
    //     $folioTxns = create('App\Transaction', [
    //         'folio_id' => $clientFolio->id,
    //         'date' => \Carbon\Carbon::now()->subDays($days),
    //     ], 3);

    //     $response = $this->getJson(route('portfolios.index'))->json();

    //     $this->assertCount(1, $response);

    //     $this->assertArrayHasKey('xirr', $response[0]);
    //     $expectedXIRR = $response[0]['absoluteReturn'] / $days * 365;
    //     $this->assertEquals($expectedXIRR, $response[0]['xirr']);
    // }
}
