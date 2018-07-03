<?php

use Faker\Generator as Faker;

$factory->define(App\Address::class, function (Faker $faker) {
    return [
        'first_line' => $faker->streetAddress,
        'client_id' => function () {
            return factory('App\Client')->create()->id;
        },
        'city' => $faker->city,
        'pincode' => $faker->postcode,
        'state' => $faker->state,
        'country' => 'INDIA',
    ];
});
