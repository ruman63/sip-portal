<?php

use Faker\Generator as Faker;

$factory->define(App\BankAccount::class, function (Faker $faker) {
    return [
        'account_number' => $faker->numerify(str_repeat('#', 14)),
        'account_type'=> $faker->randomElement(['savings', 'current']),
        'bank' => title_case($faker->words(5, true)),
        'branch' => $faker->streetAddress,
        'ifsc' => $faker->bothify('????#######'),
        'micr' => $faker->bothify('????##?####'),
    ];
});
