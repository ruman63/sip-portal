<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SipPageTests extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_guest_cannot_access_sip_page()
    {
        $this->withExceptionHandling();
        $this->get(route('admin.sip.index'))
            ->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function a_client_cannot_access_sip_page()
    {
        $this->withExceptionHandling()->signIn();
        $this->get(route('admin.sip.index'))
            ->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function an_admin_can_access_sip_page()
    {
        $this->signInAdmin();
        $this->get(route('admin.sip.index'))
            ->assertSuccessful()
            ->assertSee('SIP');
    }
}
