<?php 
namespace App\Parsers;

class BSEParser
{
    protected $contents;
    protected $records;

    public function __construct($file = 'schemes.txt')
    {
        $this->contents = collect(
            explode("|\r\n", \Storage::disk('local')->get($file))
        )->filter()->map(function($line) {
            return collect(explode('|', $line));
        });
    }

    public function parse()
    {
        $keys = $this->contents->shift();

        $keys = $keys->map(function($key) {
            return str_slug($key, '_');
        });
        
        $this->records = $this->contents->map(
            function($record) use ($keys) {
                return $keys->combine($record)->only($this->keys())->toArray();
            }
        )->filter(function($item) {
            return \Carbon\Carbon::parse($item['end_date']) > \Carbon\Carbon::now();
        });

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

    public function save($records = null)
    {
        $this->records($records)->each(function($record){
            Scheme::create($record);
        });
    }

    public function updateOrCreate($records = null)
    {
        $this->records($records)->each(function($record) {
            Scheme::updateOrCreate($record);
        });
    }
}