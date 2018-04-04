<?php

use Faker\Generator as Faker;

$factory->define(App\Folio::class, function (Faker $faker) {
    return [
        'folio_no' => $faker->unique()->bothify('########'),
        'scheme_code' => function() {
            return factory('App\Scheme')->create()->scheme_code;
        },
        'client_id' => function() {
            return factory('App\Client')->create()->id;
        },
    ];
});
