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
                return $query->select(['scheme_code', 'scheme_name', 'scheme_type', 'scheme_plan', 'nav']);
            }])->get()
            ->groupBy('scheme.scheme_type');
            // ->map(function($txns) {
            //     $txns['currentValue'] = $txns->sum('currentValue');
            //     $txns['totalAmount'] = $txns->sum('amount');
            //     $txns['absoluteReturn'] = ( $txns['currentValue'] - $txns['totalAmount']) * 100 / $txns['totalAmount'];
            //     $days = Carbon::parse($txns->max('date'))->diffInDays();
            //     $txns['xirr'] = $txns['absoluteReturn'] / $days * 365;
            //     return $txns;
            // });
        
        if(request()->wantsJson()) {
            return $portfolio;
        }

        return view('portfolios.index', compact('portfolio'));
    }
}
