<?php 
namespace App;

class Parser
{
    protected $contents;
    protected $schema = ['scode', 'payout', 'reinvestment', 'name', 'net_value', 'repurchase_price', 'sale_price', 'date'];
    protected $types;
    protected $schemes;
    protected $funds;

    public function __construct()
    {
        $this->contents = file_get_contents('https://www.amfiindia.com/spages/NAVAll.txt');
        // $this->contents = \Storage::get('nav.txt');
        $this->types = collect([]);
        $this->schemes = collect([]);
        $this->funds = collect([]);
    }

    public function parse()
    {
        $currentFund = '';
        $lines = explode("\n", $this->contents);
        foreach($lines as $line) {
            $line = str_replace("\r", "", $line);
            if(!!preg_match('~\bSchemes\b~', $line)){
                $this->types->push($line);
            }
            else if(!!preg_match('~^[^0-9]+.*Fund$~', $line)) {
                if (!$this->funds->contains($line)) {
                    $this->funds->push($line);
                }
                $currentFund = $line;
            }
            else if(!!preg_match('~^[0-9]+~', $line)) {
                $scheme = explode(";", $line);
                $scheme = array_combine($this->schema, $scheme);
                $scheme['scheme_category_id'] = $this->types->count();
                $index = $this->funds->search(function($fund, $key) use ($currentFund) {
                    return $fund == $currentFund;
                });
                $scheme['company_id'] = ++$index;
                $this->schemes->push($scheme);
            }
        }
        return $this;
    }

    public function persist($logger = null)
    {
        $start = time();
        \DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        if($logger!=null) {
            $logger->info('Truncating Tables');
        }
        Scheme::truncate();
        Company::truncate();
        SchemeCategory::truncate();
        if($logger!=null) {
            $logger->info('Completely Truncated Tables');
            $logger->info('Creating ' . count($this->funds) . ' Companies');
        }
        $this->funds->each(function($fund) {
            Company::create(['name' => $fund]);
        });
        if($logger!=null) {
            $logger->info('Created Companies');
            $logger->info('Creating ' . count($this->types) . ' categories...');
        }
        $this->types->each(function($category) {
            SchemeCategory::create(['name' => $category]);
        });
        if($logger!=null) {
            $logger->info('Completely created Categories');
            $logger->info('Creating ' . count($this->schemes) . ' Schemes');
            $logger->getOutput()->progressStart(count($this->schemes));
        }
        $this->schemes->each(function($scheme, $i) use ($logger) {
            Scheme::create($scheme);
            $logger->getOutput()->progressAdvance();
        });
        $taken = time() - $start;
        $mins = (int) $taken/60;
        $secs = $taken%60;
        if($logger!=null) {
            $logger->getOutput()->progressFinish();
            $logger->info('Completely created all Schemes');
            $logger->info('Success!! Data Synced in ' . $mins . 'mins and ' . $secs . 'secs.');
        }
        \DB::statement("SET FOREIGN_KEY_CHECKS=1;");

    }
}