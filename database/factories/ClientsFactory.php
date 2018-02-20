<?php

use Faker\Generator as Faker;

$factory->define(App\Client::class, function (Faker $faker) {
    return [
        //personal
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        // 'pan' => $faker->bothify('??????####?'),
        // 'mapin' => $faker->numerify('########'),
        'dob' => $faker->date,
        'gender' => $faker->randomElement(['M','F']),
        // 'occupation' => $faker->words(3, true),
        // 'tax_status' => $faker->words(3, true),
        // 'father_name' => $faker->name,
        // 'guardian_name' => $faker->name,
        // 'guardian_pan' => $faker->bothify('??????####?'),
        
        //contact
        // 'hno' => $faker->buildingNumber ,
        // 'area' => $faker->streetName,
        // 'city' => $faker->city,
        // 'pincode' => $faker->numerify('######'),
        'mobile' => $faker->numerify('##########'),
        'email' => $faker->safeEmail,
        // 'communication_mode' => $faker->words(3, true),
        'password' => bcrypt('secret'),
        //account
        // 'account_type' => 'single',
        // 'pay_mode' => $faker->randomElement(['cash', 'cheque', 'neft']),
        // 'nominee_name' => $faker->name,
        // 'nominee_relation' => 'Son',

        // 'bank_account_number' => factory('App\BankAccount')->create()->account_number,
    ];
});
