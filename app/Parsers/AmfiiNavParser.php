<?php 
namespace App\Parsers;

use App\Scheme;

class AmfiiNavParser
{
    const FILE_LOCATION = 'https://www.amfiindia.com/spages/NAVAll.txt';
    protected $contents;
    protected $records;
    private $output;

    public function __construct($output = null)
    {
        $this->output = $output;

        if (app()->environment('testing')) {
            $file = file(stubs_path('sample_nav.txt'));
        } else {
            $file = file(self::FILE_LOCATION);
        }

        $this->contents = collect($file)
            ->filter(function ($line) {
                return $line ? str_contains($line, ';') : false;
            })->map(function ($line, $i) {
                return collect(explode(';', $line));
            });
    }

    public function parse()
    {
        $keys = $this->contents->shift()
            ->map(function ($key) {
                return str_slug($key, '_');
            });

        $this->records = $this->contents->map(
            function ($record) use ($keys) {
                return $keys->combine($record)
                    ->only($this->keys())
                    ->toArray();
            }
        )->filter(function ($record) {
            return is_numeric($record['net_asset_value']);
        });

        return $this;
    }

    public function records($take = null)
    {
        return $this->records->take($take);
    }

    protected function keys()
    {
        return [
            'scheme_code',
            'isin_div_payout_isin_growth',
            'isin_div_reinvestment',
            'net_asset_value',
            'date',
        ];
    }

    public function update($records = null)
    {
        $navs = $this->records($records);
        $bar = $this->output ? $this->output->createProgressBar($navs->count()) : null;
        $navs->each(function ($record) use ($bar) {
            Scheme::where('isin', $record['isin_div_payout_isin_growth'])
                ->orWhere('isin', $record['isin_div_reinvestment'])
                ->update([
                    'nav' => $record['net_asset_value'],
                    'nav_date' => \Carbon\Carbon::parse($record['date'])->toDateTimeString(),
                ]);
            $bar ? $bar->advance() : '';
        });

        $bar ? $bar->finish() : '';
    }
}
