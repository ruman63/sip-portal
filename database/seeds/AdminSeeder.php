<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Admin')->create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
        ]);
    }
}
