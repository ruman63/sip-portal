<?php

use Faker\Generator as Faker;

$factory->define(App\Scheme::class, function (Faker $faker) {
    return [
        'scheme_code' => strtoupper($faker->unique()->bothify('?##-???')),
        'unique_no' => $faker->unique()->numerify('#####'),
        'scheme_name' => strtoupper($faker->sentence),
        'rta_scheme_code' => strtoupper($faker->bothify('#????#')),
        'amc_scheme_code' => strtoupper($faker->bothify('#????#')),
        'isin' => strtoupper($faker->bothify('INF###?##??#')),
        'amc_code' => strtoupper(str_replace(' ', '', $faker->words(3, true))). '_MF',
        'scheme_plan' => $faker->randomElement(['NORMAL', 'DIRECT']),
        'scheme_type' => $faker->randomElement(['ELSS', 'DEBT', 'EQUITY', 'LIQUID']),
        'purchase_allowed' => $faker->randomElement(['Y', 'N']),
        'start_date' => $start = $faker->date('M d Y'),
        'end_date' => $end = $faker->date('M d Y', '2099-01-01'),
    ];
});