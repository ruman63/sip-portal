<?php

use Faker\Generator as Faker;
use App\Client;

$factory->define(App\UccAccount::class, function (Faker $faker) {
    return [
        'ucc' => $faker->bothify('1#####'),
        'owner_id' => function () {
            return factory(Client::class)->create()->id;
        },
        'holding_code' => 'SI',
        'first_applicant_name' => '',
        'second_applicant_name' => '',
        'transaction_mode' => 'D',
        'depository' => 'CDSL',
        'depository_dp_id' => '',
        'depository_client_id' => '1234567890123456',
        'tax_status_code' => '01',
        'occupation_code' => '01',
        'mapin_no' => '1234',
        'first_applicant_pan' => '',
        'second_applicant_pan' => '',
        'communication_mode' => 'P',
        'dividend_pay_mode' => '01',
    ];
});
