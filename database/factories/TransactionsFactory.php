<?php

use Faker\Generator as Faker;

$factory->define(App\Transaction::class, function (Faker $faker) {
    return [
        'uid' => $faker->numberBetween(100000, 1000000),
        'folio_no' => $faker->bothify("#######"),
        'scheme_code' => function() {
            return factory('App\Scheme')->create()->scheme_code;
        },
        'client_id' => function() {
            return factory('App\Client')->create()->id;
        },
        'type' => $faker ->randomElement(['ADD', 'ADDSIP', 'REDEEM']),
        'rate' => $rate = $faker->numberBetween(1, 500),
        'amount' => $faker->numberBetween(500, 100000),
        'date' => $faker->dateTimeBetween()
    ];
});
