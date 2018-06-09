<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\Translation\Interval;
use App\Sip;
use Illuminate\Database\Eloquent\Collection;

class CreateSipTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_guest_or_a_client_cannot_create_sip()
    {
        $this->withExceptionHandling();
        $this->postJson(route('admin.sip.store'))->assertStatus(401);

        $this->signIn();
        $this->postJson(route('admin.sip.store'))->assertStatus(401);
    }

    /** @test */
    public function an_admin_can_create_sip()
    {
        $this->signInAdmin();
        $sip = make(Sip::class, [
            'client_id' => 1,
        ]);

        $this->postJson(route('admin.sip.store'), $sip->toArray())->assertStatus(201);

        $this->assertArraySubset($sip->toArray(), Sip::first()->toArray());
    }

    /** @test */
    public function creating_an_sip_creates_correct_sip_schedules_for_monthly_plan()
    {
        $this->signInAdmin();
        $firstDate = Carbon::today()->addDays(2);
        
        $sip = make(Sip::class, [
            'client_id' => 1,
            'installments' => 4,
            'interval' => 'monthly',
            'date' => $firstDate
        ]);

        $this->postJson(route('admin.sip.store'), $sip->toArray())->assertStatus(201);

        $expectedDates = collect([
            $firstDate->toDateTimeString(),
            $firstDate->addMonth()->toDateTimeString(),
            $firstDate->addMonth()->toDateTimeString(),
            $firstDate->addMonth()->toDateTimeString(),
        ]);
        

        $this->assertArraySubset($sip->toArray(), Sip::first()->toArray());
        $scheduled_dates = Sip::first()->schedules->pluck('due_date');
        $scheduled_dates->assertEquals($expectedDates);
    }
}
