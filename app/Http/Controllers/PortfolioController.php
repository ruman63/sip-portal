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
        ->get();

        if(request()->wantsJson()) {
            return $portfolio;
        }

        return view('portfolios.index', compact('portfolio'));
    }
}
