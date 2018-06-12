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
    public function schemes_are_paginated_to_50_per_page_by_default()
    {
        $this->signInAdmin();
        $this->withExceptionHandling();
        create('App\Scheme', [], 100);

        $response = $this->getJson(route('schemes.index'))->assertStatus(200)->json();
        $this->assertCount(50, $response['data']);
    }
    
    /** @test */
    public function a_logged_in_client_can_read_schemes()
    {
        $this->signIn();
        $this->withExceptionHandling();

        create('App\Scheme', [], 5);

        $response = $this->getJson(route('schemes.index'))->assertStatus(200)->json();
        $this->assertCount(5, $response['data']);
    }

    /** @test */
    public function an_admin_can_also_read_schemes()
    {
        $this->signInAdmin();
        $this->withExceptionHandling();
        create('App\Scheme', [], 5);

        $response = $this->getJson(route('schemes.index'))->assertStatus(200)->json();
        $this->assertCount(5, $response['data']);
    }

    /** @test */
    public function perPage_param_overrides_default_pagination_limit()
    {
        $this->signIn();
        $this->withExceptionHandling();
        
        create('App\Scheme', [], 45);
        
        $response = $this->getJson(route('schemes.index', ['perPage' => 15]))->json();

        $this->assertCount(15, $response['data']);
        $this->assertEquals(3, $response['last_page']);
    }
    
    /** @test */
    public function a_logged_in_client_can_search_schemes()
    {
        $this->signIn();
        $this->withExceptionHandling();
        
        create('App\Scheme', ['scheme_name' => 'Aditya Birla Sun Life'], 2);
        create('App\Scheme', [], 2);
        
        $response = $this->getJson(route('schemes.index').'?s=birla')->json();

        $this->assertCount(2, $response['data']);
    }
}
