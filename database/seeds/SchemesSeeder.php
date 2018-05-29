<?php

use Illuminate\Database\Seeder;

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
