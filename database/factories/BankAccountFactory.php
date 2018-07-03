<?php

use Faker\Generator as Faker;

$factory->define(App\BankAccount::class, function (Faker $faker) {
    return [
        'account_number' => $faker->numerify('################'),
        'account_type_code' => $faker->randomElement(['01', '02', '03', '04']),
        'ifsc_code' => strtoupper($faker->bothify('####???????')),
        'owner_id' => function () {
            return factory('App\Client')->create()->id;
        },
    ];
});
