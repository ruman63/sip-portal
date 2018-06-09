<?php

use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(App\Sip::class, function (Faker $faker) {
    return [
        'folio_no' => $faker->numerify("#####/##"),
        'scheme_code' => function () {
            return create('App\Scheme')->scheme_code;
        },
        'amount' => $faker->numberBetween(100, 100000),
        'installments' => $faker->numberBetween(2, 12),
        'interval' => 'monthly',
        'date' => Carbon::today()->addDays(4)->format('Y-m-d'),
        'client_id' => function() {
            return auth()->guard('web')->id() ?? create('App\Client')->id; 
        }
    ];
});
