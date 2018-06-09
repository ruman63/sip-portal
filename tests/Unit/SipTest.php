<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Sip;
use App\Scheme;
use App\SipSchedule;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SipTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_returns_date_as_an_instance_of_Carbon()
    {
        $sip = create(Sip::class);
        $this->assertInstanceOf(Carbon::class, $sip->date);
    }

    /** @test */
    public function it_has_related_schedules()
    {
        $sip = create(Sip::class);
        $schedules = create(SipSchedule::class, ['sip_id' => $sip->id], 4);
        $this->assertInstanceOf(Collection::class, $sip->schedules);
        $this->assertCount(4, $sip->schedules);
    }

    /** @test */
    public function it_can_generate_schedules()
    {
        $sip = create(Sip::class, [ 'installments' => 4 ]);
        $this->assertCount(0, $sip->schedules);

        tap($sip->generateSchedules(), function($schedule) {
            $this->assertInstanceOf(Collection::class, $schedule);
            $this->assertCount(4, $schedule);
        });

        $this->assertCount(4, $sip->fresh()->schedules);
    }

    /** @test */
    public function it_has_a_related_scheme()
    {
        $scheme = create(Scheme::class);
        $sip = create(Sip::class, ['scheme_code' => $scheme->scheme_code]);
        $this->assertInstanceOf(Scheme::class, $sip->scheme);
    }
}
