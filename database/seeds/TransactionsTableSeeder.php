<?php

use Illuminate\Database\Seeder;
use App\Folio;
use Faker\Generator;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        Folio::all()->each(function($folio) use ($faker) {
            factory('App\Transaction', 4)->create([
                'folio_id' => $folio->id,
                'rate' => $folio->scheme->nav + $faker->numberBetween(-50, 50),
            ]);
        });
    }
}
