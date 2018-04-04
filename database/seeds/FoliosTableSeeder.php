<?php

use Illuminate\Database\Seeder;
use App\Client;
use App\Scheme;

class FoliosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schemes = Scheme::whereNotNull('nav')->get();
        Client::all()->each(function($client) use ($schemes) {
            factory('App\Folio', 3)->create([
                'client_id' => $client->id,
                'scheme_code' => function() use ($schemes) {
                    return $schemes->random()->scheme_code;
                },
            ]);
        });
    }
}
