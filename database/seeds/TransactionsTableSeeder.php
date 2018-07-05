<?php

use Illuminate\Database\Seeder;
use Faker\Generator;
use App\Client;

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
        foreach ($clients as $client) {
            factory('App\Transaction', 8)->create([
                'client_id' => $client->id,
            ]);
        };
    }
}
