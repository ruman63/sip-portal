<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Sip;
use Illuminate\Database\Eloquent\Collection;
use App\Client;

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

    /** @test */
    public function sip_entries_are_passed_to_the_index_view()
    {
        $this->signInAdmin();
        create(Sip::class, [], 4);

        $response = $this->get(route('admin.sip.index'))->assertSuccessful();

        $this->assertInstanceOf(Collection::class, $entries = $response->getData('sipEntries'));
        $this->assertCount(4, $entries);
    }

    /** @test */
    public function sip_entries_are_passed_to_the_view_with_schedules_scheme_and_client_eager_loaded()
    {
        $this->signInAdmin();
        $sip = create(Sip::class);
        $schedules = $sip->generateSchedules();

        $response = $this->get(route('admin.sip.index'))
            ->assertSuccessful();

        tap($response->getData('sipEntries')->first()->toArray(), function($sip) use ($schedules) {
            $this->assertArrayHasKey('schedules', $sip);
            $this->assertCount($schedules->count(), $sip['schedules']);
    
            $this->assertArrayHasKey('client', $sip);
            $this->assertEquals($sip['client_id'], $sip['client']['id']);
    
            $this->assertArrayHasKey('scheme', $sip);
            $this->assertEquals($sip['scheme_code'], $sip['scheme']['scheme_code']);
        });
        
    }
}
