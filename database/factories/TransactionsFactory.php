<?php

use Faker\Generator as Faker;

$factory->define(App\Transaction::class, function (Faker $faker) {
    return [
        'uid' => $faker->numberBetween(100000, 1000000),
        'type' => $faker ->randomElement(['ADD', 'ADDSIP', 'REDEEM']),
        'rate' => $rate = $faker->numberBetween(1, 1000),
        'amount' => $faker->numberBetween(500, 10000000),
        'folio_id' => function () {
            return factory('App\Folio')->create()->id;
        },
        'date' => $faker->dateTimeBetween()
    ];
});
