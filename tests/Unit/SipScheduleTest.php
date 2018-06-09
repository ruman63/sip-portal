<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use App\SipSchedule;
use App\Sip;
use App\Transaction;

class SipScheduleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function retrieving_pending_schedules()
    {
        create('App\SipSchedule', ['executed' => true], 2);
        $this->assertCount(0, SipSchedule::pending()->get());
        
        create('App\SipSchedule', ['executed' => false], 3);
        $this->assertCount(3, SipSchedule::pending()->get());
    }

    /** @test */
    public function belongs_to_a_sip_entry()
    {
        $schedule = create('App\SipSchedule');
        $this->assertInstanceOf(Sip::class, $schedule->sip);
    }

    /** @test */
    public function sip_schedule_when_executed_creates_transaction_and_marks_as_executed()
    {
        $sip = create('App\Sip');
        $schedule = create('App\SipSchedule', ['sip_id' => $sip->id]);

        $txn = $schedule->execute();
        $this->assertInstanceOf(Transaction::class, $txn);
        $this->assertEquals("SIP", $txn->type);
        $this->assertEquals($schedule->sip->amount, $txn->amount);
        $this->assertTrue($schedule->fresh()->executed);
        
    }
}
