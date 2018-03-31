<?php 
namespace App;

class AmfiiNavParser
{
    const location = 'https://www.amfiindia.com/spages/NAVAll.txt';
    protected $contents;
    protected $records;

    public function __construct($filename = 'nav.txt', $remote = false)
    {
        $file = $remote ? file_get_contents(self::location) : \Storage::disk('local')->get($filename);
        $this->contents = collect(
            explode( "\r\n", $file )
        )->filter(function($line) {
            return $line ? str_contains($line, ';') : false;
        })->map(function($line, $i) {
            return collect(explode(';', $line));
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
        );
        return $this;
    }

    public function records($take=null)
    {
        return $this->records->take($take);
    }

    protected function keys() {
        return [
            "scheme_code",
            "isin_div_payout_isin_growth",
            "isin_div_reinvestment",
            // "scheme_name",
            // "repurchase_price",
            // "sale_price",
            "net_asset_value",
            "date",
        ];
    }

    public function update($records = null)
    {
        $this->records($records)->filter(function($record) {
            return is_numeric($record['net_asset_value']);
        })->each(function($record){
            Scheme::where('isin', $record['isin_div_payout_isin_growth'])
            ->orWhere('isin', $record['isin_div_reinvestment'])
            ->update([
                'nav' => $record['net_asset_value'],
                'nav_date' => $record['date'],
            ]);
        });
    }
}