<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Folio;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolio = Folio::selectRaw('folios.scheme_code, MAX(folio_no) folio_no, SUM(folios.purchase_price) purchase_price, SUM(folios.amount) amount')
        ->where( 'client_id', auth()->guard('web')->id() )
        ->with('scheme')
        ->groupBy('folios.scheme_code')
        ->get();

        if(request()->wantsJson()) {
            return $portfolio;
        }

        return view('portfolios.index', compact('portfolio'));
    }
}
