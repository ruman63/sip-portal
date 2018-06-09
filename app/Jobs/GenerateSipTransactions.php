<?php

namespace App\Jobs;

use App\SipSchedule;
use App\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateSipTransactions
{
    use Dispatchable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        SipSchedule::pending()->where('due_date', '<=', Carbon::today())->get()->each->execute();
    }
}
