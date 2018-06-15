<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\GeneratePortfolios;
use App\Parsers\CSV;

class GeneratePortfolioController extends Controller
{
    public function store()
    {
        $file = request()->file('csvFile');

        $collections = CSV::read($file->getPathname())->columns([
            'amc_code', 'folio_no', 'prodcode', 'scheme', 'inv_name', 'trxntype', 'trxnno', 'traddate', 'purprice', 'units', 'amount', 'pan',
        ]);

        GeneratePortfolios::dispatch($collections);
    }
}
