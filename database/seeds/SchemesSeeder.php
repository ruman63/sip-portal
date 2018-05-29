<?php

use Illuminate\Database\Seeder;
use App\BSEParser;
use App\AmfiiNavParser;

class SchemesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Scheme', 100)->create();
    }
}
