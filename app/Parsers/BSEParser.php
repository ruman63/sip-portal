<?php 
namespace App\Parsers;

use App\Scheme;
use Illuminate\Log\Logger;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\File;

class BSEParser
{
    private $lines;
    private $output;
    public $records;

    public function __construct($output = null)
    {
        $this->output = $output;
        $file = \Storage::disk('local')->get('schemes.txt');

        if(app()->environment('testing')) {
            $file = file_get_contents(base_path() . '/tests/res/sample_schemes.txt');
        }

        if(!$file) {
            throw new FileNotFoundException("$file not found");
        }

        $this->lines = collect(
            preg_split('~\|[\r\n]~', $file)
        )->filter();
    }
    
    public function parse()
    {
        $result = collect([]);

        $keys = collect(
            explode('|', $this->lines->shift())
        )->map(function($key) {
            return str_slug($key, '_');
        });

        $this->lines
            ->chunk(100)
            ->each(function($lines) use ($keys, $result) { 
                $schemes = $lines->map(function($line) use ($keys) {
                    $scheme = collect(explode('|', $line));
                    return $keys->combine($scheme)->only($this->keys())->toArray();
                })->filter(function($scheme) {
                    return \Carbon\Carbon::parse($scheme['end_date']) > \Carbon\Carbon::now();
                });

                $result->push($schemes);
            });

        $this->records = $result->flatten(1);
            
        return $this;
    }

    public function records($take=null)
    {
        return $this->records->take($take);
    }

    protected function keys() {
        return [
            "unique_no",
            "scheme_code",
            "rta_scheme_code",
            "amc_scheme_code",
            "isin",
            "amc_code",
            "scheme_type",
            "scheme_plan",
            "scheme_name",
            "purchase_allowed",
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
            // "rta_agent_code",
            // "amc_active_flag",
            // "dividend_reinvestment_flag",
            // "sip_flag",
            // "stp_flag",
            // "swp_flag",
            // "switch_flag",
            // "settlement_type",
            // "amc_ind",
            // "face_value",
            "start_date",
            "end_date",
            // "exit_load_flag",
            // "exit_load",
            // "lock_in_period_flag",
            // "lock_in_period",
            // "channel_partner_code",
        ];
    }

    public function save($take = null)
    {
        $schemes = $this->records($take);
        $bar = $this->output ? $this->output->createProgressBar($schemes->count()) : null;

        $schemes =  $schemes->map(function($scheme, $index) use ($bar){
            if($bar) {
                $bar->advance();
            }
            return Scheme::create($scheme);
        });

        $bar ? $bar->finish() : '';

        return $schemes;
    }
}