<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Folio;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolio = auth()->guard('web')->user()->folios()
        ->with('transactions.scheme')
        ->get()
        ->map(function($folio) {
            $folio['absoluteReturn'] = ($folio->currentValue - $folio->totalAmount) * 100 / $folio->totalAmount;
            $earliestTxnDate = $folio->transactions->sortBy(function($txn) {
                return \Carbon\Carbon::parse($txn->date);
            })->first()->date;
            $days = \Carbon\Carbon::parse($earliestTxnDate)->diffInDays();
            $folio['xirr'] = $folio->absoluteReturn / $days * 365;
            return $folio;
        });

        if(request()->wantsJson()) {
            return $portfolio;
        }

        return view('portfolios.index', compact('portfolio'));
    }
}
