<?php

namespace App\Http\Controllers;

use App\Folio;
use Illuminate\Http\Request;

class AllocationController extends Controller
{
    public function index()
    {
        $client = auth()->guard('web')->user();
        $total = $client->transactions()->sum('amount');

        $assets = $client->transactions()
            ->selectRaw("schemes.scheme_type type, SUM(amount) amount, (SUM(amount)/$total)*100 percent")
            ->join('schemes', 'schemes.scheme_code', '=', 'transactions.scheme_code')
            ->groupBy('schemes.scheme_type')
            ->get();

        if(request()->wantsJson()) {
            return $assets;
        }
            
        return view('allocations.index', compact('assets'));
    }
}
