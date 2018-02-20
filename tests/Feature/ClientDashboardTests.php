<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientDashboardTests extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_logged_in_client_can_visit_dashboard()
    {
        $this->signIn();

        $this->get(route('dashboard'))
            ->assertStatus(200)
            ->assertSee('Dashboard');
    }

    /** @test */
    public function a_guest_cannot_visit_dashboard()
    {
        $this->withExceptionHandling();
        
        $this->get(route('dashboard'))
            ->assertRedirect(route('index'));
    }
}
