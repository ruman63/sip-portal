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
        (new BSEParser())->parse()->save();
        (new AmfiiNavParser(null, true))->parse()->update();
    }
}
