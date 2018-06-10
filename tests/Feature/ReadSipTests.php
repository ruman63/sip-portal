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
    public function sip_entries_are_passed_to_the_json_response_with_schedules_scheme_and_client_eager_loaded()
    {
        $this->signInAdmin();
        $sip = create(Sip::class);
        $schedules = $sip->generateSchedules();

        $response = $this->getJson(route('admin.sip.index'))
            ->assertSuccessful()->json();

        $this->assertCount(1, $response);
        $this->assertArrayHasKey('schedules', $response[0]);
        $this->assertCount($schedules->count(), $response[0]['schedules']);

        $this->assertArrayHasKey('client', $response[0]);
        $this->assertEquals(1, $response[0]['client']['id']);

        $this->assertArrayHasKey('scheme', $response[0]);
        $this->assertEquals($response[0]['scheme_code'], $response[0]['scheme']['scheme_code']);
    }
}
