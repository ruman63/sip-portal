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
        $data = request()->validate([
            'csvFile' => 'required|file|mimes:csv',
        ]);

        $collections = CSV::read($data['csvFile']->getPathname())->columns([
            'amc_code', 'folio_no', 'prodcode', 'scheme', 'inv_name', 'trxntype', 'trxnno', 'traddate', 'purprice', 'units', 'amount', 'pan',
        ]);

        GeneratePortfolios::dispatch($collections);

        return response()->json(['Portfolios will be generated shortly!'], 201);
    }
}
