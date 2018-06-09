<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Transaction;
use Illuminate\Support\Carbon;
use App\SipSchedule;
use App\Jobs\GenerateSipTransactions;

class GenerateSipTransactionsJobTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_generates_transactions_for_all_pending_sip_transactions()
    {
        $this->assertCount(0, Transaction::where('type', 'SIP')->get());

        create('App\SipSchedule', ['due_date' => Carbon::today()->subDays(10)]);
        create('App\SipSchedule', ['due_date' => Carbon::today()->subDays(5)]);
        create('App\SipSchedule', ['due_date' => Carbon::today()]);
        create('App\SipSchedule', ['due_date' => Carbon::today()->addDays(1)]);
        create('App\SipSchedule', ['due_date' => Carbon::today()->addDays(5)]);
        $this->assertCount(5, SipSchedule::pending()->get());

        dispatch(new GenerateSipTransactions);

        $this->assertCount(2, SipSchedule::pending()->get());
        $this->assertCount(3, Transaction::where('type', 'SIP')->get());
    }
}
