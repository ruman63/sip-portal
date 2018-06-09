<?php

use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(App\SipSchedule::class, function (Faker $faker) {
    return [
        'sip_id' => function() {
            return create('App\Sip')->id;
        },
        'due_date' => Carbon::today()->addDays(4)
    ];
});
