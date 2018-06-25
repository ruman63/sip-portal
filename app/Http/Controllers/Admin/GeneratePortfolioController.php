<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Parsers\CSV;
use App\Jobs\GenerateCamsPortfolio;
use Illuminate\Validation\ValidationException;

class GeneratePortfolioController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'csvFile' => 'required|file',
        ]);

        $collections = CSV::read($data['csvFile']->getPathname())->columns([
            'amc_code', 'folio_no', 'prodcode', 'scheme', 'inv_name', 'trxntype', 'trxnno', 'traddate', 'purprice', 'units', 'amount', 'pan',
        ]);

        if (
            !array_has($collections->first(), ['prodcode', 'inv_name', 'traddate', 'amount', 'pan'])
        ) {
            throw ValidationException::withMessages([
                'csvFile' => ['Invalid csv data'],
            ]);
        }

        GenerateCamsPortfolio::dispatch($collections);

        return response()->json(['Portfolios will be generated shortly!'], 201);
    }
}
