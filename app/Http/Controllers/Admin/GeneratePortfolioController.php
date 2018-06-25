<?php

namespace App\Http\Controllers\Admin;

use App\Parsers\CSV;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class GeneratePortfolioController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'csvFile' => 'required|file',
            'rta' => 'required|in:cams,karvy,franklin,sundaram',
        ]);

        $collections = CSV::read($data['csvFile']->getPathname())
                    ->columns($this->getColumns($data['rta']));

        $this->validateCsvOutput($collections, $data['rta']);

        $this->dispatchGeneratorJob($data['rta'], $collections);

        return response()->json(['Portfolios will be generated shortly!'], 201);
    }

    protected function validateCsvOutput($collections, $rta)
    {
        $isValid = $collections->every(function ($item) use ($rta) {
            return array_has($item, $this->getColumns($rta));
        });

        if (!$isValid) {
            throw ValidationException::withMessages([
                'csvFile' => ['Invalid csv data'],
            ]);
        }

        return true;
    }

    protected function camsHeaders()
    {
        return [
            'amc_code', 'folio_no', 'prodcode', 'scheme', 'inv_name', 'trxntype', 'trxnno', 'traddate', 'purprice', 'units', 'amount', 'pan',
        ];
    }

    protected function karvyHeaders()
    {
        return [
            'transaction id', 'folio number', 'transaction type', 'transaction date', 'isin', 'investor name', 'price', 'units', 'amount', 'pan1',
        ];
    }

    protected function getColumns($rta)
    {
        return $this->{$rta . 'Headers'}();
    }

    protected function dispatchGeneratorJob($rta, $data)
    {
        $rta = ucwords($rta);
        $class = "App\\Jobs\\Generate${rta}Portfolio";
        if ($job = new $class($data)) {
            return dispatch($job);
        }
    }
}
