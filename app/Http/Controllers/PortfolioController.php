<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Folio;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolio = auth()->guard('web')->user()->folios()
        ->with('transactions', 'scheme')
        ->get()
        ->map(function($folio) {
            $folio['absoluteReturn'] = ($folio->currentValue - $folio->totalAmount) * 100 / $folio->totalAmount;
            return $folio;
        });

        if(request()->wantsJson()) {
            return $portfolio;
        }

        return view('portfolios.index', compact('portfolio'));
    }
}
