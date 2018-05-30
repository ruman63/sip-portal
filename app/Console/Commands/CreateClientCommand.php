<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateClientCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "create:client {name : Name of Client} {email : Email of Client} {--password=secret : Password for Client }";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Client';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $first_name = $this->argument('name');
        $email = $this->argument('email');
        $password = bcrypt($this->option('password'));

        factory('App\Client')->create(compact('first_name', 'email', 'password'));

    }
}
