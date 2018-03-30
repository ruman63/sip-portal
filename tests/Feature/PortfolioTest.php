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
        $this->withExceptionHandling();
        
        $response = $this->getJson(route('portfolios.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function portfolio_summary_is_evaluated_correctly()
    {
        $this->signIn();
        $this->withExceptionHandling();

        $schemes = create('App\Scheme', [], 2);
        
        $clientFolios = create('App\Folio', [
            'client_id' => auth()->id(),
            'scheme_code' => $schemes[0]->scheme_code
        ], 2);

        $otherFolios = create('App\Folio', [], 2);
        
        $response = $this->getJson(route('portfolios.index'))->json();

        $this->assertCount(1, $response);
        $this->assertEquals($clientFolios->sum('amount'), $response[0]['amount']);
    }
}
