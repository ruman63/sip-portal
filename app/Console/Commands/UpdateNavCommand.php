<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Scheme;
use App\Parsers\AmfiiNavParser;

class UpdateNavCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:nav';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize NAV value from AMFII';

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
        if(!Scheme::count()) {
            $this->warn("There are no schemes available yet. Please run 'php artisan sync:schemes' command.");
            return;
        } 

        $this->info("Updating NAVs, this may take longer depending on number of schemes. Please be patient.");

        (new AmfiiNavParser($this->output))->parse()->update();

        $this->info("\nSchemes Added successfully");
    }
}
