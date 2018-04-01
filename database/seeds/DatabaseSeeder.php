<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SchemesSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(FoliosTableSeeder::class);
        $this->call(TransactionsTableSeeder::class);
        $this->call(AdminSeeder::class);
    }
}
