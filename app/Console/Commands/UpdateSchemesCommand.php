<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Scheme;
use App\Parsers\BSEParser;

class UpdateSchemesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:schemes {--limit= : Limit the number of schemes to save.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize the schemes from BSE schemes master file';

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
        Scheme::truncate();

        $this->info("Creating schemes, this may take longer depending on 'limit'. Please be patient.");

        (new BSEParser($this->output))->parse()->save(
            $this->option('limit')
        );

        $this->info("\nSchemes Added successfully");
    }
}
