<?php

use Illuminate\Database\Seeder;
use App\Folio;
use Faker\Generator;
use App\Client;
use App\Scheme;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        $clients = Client::all();
        foreach($clients as $client){
            factory('App\Transaction', 4)->create([
                'client_id' => $client->id,
            ]);
        };
    }
}
