<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Folio;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolio = auth()->guard('web')->user()
            ->transactions()
            ->with(['scheme' => function ($query) {
                return $query->select(['scheme_code', 'scheme_name', 'scheme_type', 'nav']);
            }])->get()
            ->groupBy('scheme.scheme_type');
            
        if(request()->wantsJson()) {
            return $portfolio;
        }

        return view('portfolios.index', compact('portfolio'));
    }
}
