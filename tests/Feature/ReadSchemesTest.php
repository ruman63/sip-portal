<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadSchemesTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_guest_can_not_read_schemes()
    {
        $this->withExceptionHandling();
        $this->getJson(route('schemes.index'))->assertStatus(401);
    }

    /** @test */
    public function a_logged_in_client_can_read_schemes()
    {
        $this->signIn();
        $this->withExceptionHandling();

        create('App\Scheme', [], 5);

        $response = $this->getJson(route('schemes.index'))->assertStatus(200)->json();
        $this->assertCount(5, $response);
    }

    /** @test */
    public function an_admin_can_also_read_schemes()
    {
        $this->signInAdmin();
        $this->withExceptionHandling();
        create('App\Scheme', [], 5);

        $response = $this->getJson(route('schemes.index'))->assertStatus(200)->json();
        $this->assertCount(5, $response);
    }

    /** @test */
    public function schemes_are_limited_to_50()
    {
        $this->signInAdmin();
        $this->withExceptionHandling();
        create('App\Scheme', [], 100);

        $response = $this->getJson(route('schemes.index'))->assertStatus(200)->json();
        $this->assertCount(50, $response);
    }
    
    /** @test */
    public function a_logged_in_client_can_search_schemes()
    {
        $this->signIn();
        $this->withExceptionHandling();
        
        create('App\Scheme', ['scheme_name' => 'Aditya Birla Sun Life'], 2);
        create('App\Scheme', [], 2);
        
        $response = $this->getJson(route('schemes.index').'?s=birla')->json();

        $this->assertCount(2, $response);
    }
}
