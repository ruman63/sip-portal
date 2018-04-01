<?php

namespace App\Http\Controllers;

use App\Folio;
use Illuminate\Http\Request;

class AllocationController extends Controller
{
    public function index()
    {
        $total = auth()->guard('web')->user()->transactions()->sum('amount');

        $assets = auth()->guard('web')->user()
            ->transactions()->with('folio.scheme')->get()
            ->groupBy(function($txn) {
                return $txn->folio->scheme->scheme_type;
            })->map(function($txn, $index) use ($total) {
                return (object)[
                    'type' => $index,
                    'amount' => $amount = $txn->sum('amount'),
                    'percent' => ($amount/$total) * 100,
                ];
            })->values();

        if(request()->wantsJson()) {
            return $assets;
        }
            
        return view('allocations.index', compact('assets'));
    }
}
