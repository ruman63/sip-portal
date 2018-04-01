<?php

use Faker\Generator as Faker;

$factory->define(App\Folio::class, function (Faker $faker) {
    return [
        'folio_no' => $faker->unique()->bothify('########'),
        'scheme_code' => $faker->randomNumber(),
        'client_id' => function() {
            return factory('App\Client')->create();
        },
    ];
});
