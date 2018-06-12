<?php 
namespace App\Parsers;

use App\Scheme;
use Illuminate\Log\Logger;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\File;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

class BSEParser
{
    private $file;
    private $output;
    private $progressBar;
    public $records;

    public function __construct($output = null)
    {
        $this->output = $output;
        $this->file = \Storage::disk('local')->get('schemes.txt');

        if(app()->environment('testing')) {
            $this->file = file_get_contents(base_path() . '/tests/res/sample_schemes.txt');
        }

        if(!$this->file) {
            throw new FileNotFoundException("$file not found");
        }
    }
    
    public function parse()
    {
        $result = new Collection;
        $lines = $this->getLines();

        $keys = $lines->shift()->map(function($key) {
            return str_slug($key, '_');
        });

        $this->records = $lines->chunk(100)->map(function($lines) use ($keys) { 
            return $this->applyKeys($lines, $keys);
        })->flatten(1);

        return $this;
    }

    public function records($take=null)
    {
        return $this->records->take($take);
    }

    public function activeRecords($take)
    {
        return $this->records->filter(function($scheme){
            return Carbon::parse($scheme['end_date']) > today();
        })->take($take);
    }

    protected function keys() {
        return [
            "unique_no",
            "scheme_code",
            "rta_scheme_code",
            "amc_scheme_code",
            "rta_agent_code",
            "isin",
            "amc_code",
            "scheme_type",
            "scheme_plan",
            "scheme_name",
            "purchase_allowed",
            "start_date",
            "end_date",
            // "purchase_transaction_mode",
            // "minimum_purchase_amount",
            // "additional_purchase_amount",
            // "maximum_purchase_amount",
            // "purchase_amount_multiplier",
            // "purchase_cutoff_time",
            // "redemption_allowed",
            // "redemption_transaction_mode",
            // "minimum_redemption_qty",
            // "redemption_qty_multiplier",
            // "maximum_redemption_qty",
            // "redemption_amount_minimum",
            // "redemption_amount_maximum",
            // "redemption_amount_multiple",
            // "redemption_cut_off_time",
            // "amc_active_flag",
            // "dividend_reinvestment_flag",
            // "sip_flag",
            // "stp_flag",
            // "swp_flag",
            // "switch_flag",
            // "settlement_type",
            // "amc_ind",
            // "face_value",
            // "exit_load_flag",
            // "exit_load",
            // "lock_in_period_flag",
            // "lock_in_period",
            // "channel_partner_code",
        ];
    }

    public function save($take = null)
    {
        $schemes = $this->activeRecords($take);

        $this->initProgressBar($schemes->count());

        $schemes =  $schemes->map( function($scheme, $index) {
            $this->advanceProgress();
            return Scheme::create($scheme);
        });

        $this->finishProgress();

        return $schemes;
    }

    private function initProgressBar($length)
    {
        if($this->output) {
            $this->progressBar = $this->output->createProgressBar($length);
        }
    }
    private function advanceProgress() {
        if($this->progressBar) {
            $this->progressBar->advance();
        }
    }
    private function finishProgress() {
        if($this->progressBar) {
            $this->progressBar->finish();
        }
    }

    private function getLines()
    {
        return collect(
            preg_split('~\|\r?\n~', $this->file)
        )->filter()->map(function($line) {
            return collect(explode('|', $line));
        });
    }
    private function applyKeys($lines, $keys)
    {
        return $lines->map(function($line) use ($keys) {
            return $keys->combine($line)->only($this->keys())->toArray();
        });
    }
}