<?php

use Illuminate\Database\Seeder;
use App\Parsers\BSEParser;

class SchemesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new BSEParser())->parse()->save(100);
    }
}
