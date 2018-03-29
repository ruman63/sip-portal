<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function logged_in_client_is_redirected_to_dashboard_when_goes_to_homepage()
    {
        $this->signIn();
        $this->get('/')->assertRedirect('/dashboard');
    }
}