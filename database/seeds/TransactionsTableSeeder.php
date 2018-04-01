<?php

use Illuminate\Database\Seeder;
use App\Folio;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Folio::all()->each(function($folio) {
            factory('App\Transaction', 4)->create(['folio_id' => $folio->id]);
        });
    }
}
