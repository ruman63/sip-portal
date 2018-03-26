<?php

namespace App\Http\Controllers;

use App\Folio;
use Illuminate\Http\Request;

class AllocationController extends Controller
{
    public function index()
    {
        $total = Folio::where('client_id', auth()->guard('web')->id())->sum('amount');

        $assets =  Folio::selectRaw('schemes.scheme_type type, SUM(folios.amount) amount')
            ->where('client_id', auth()->guard('web')->id())
            ->join('schemes', 'folios.scheme_code', '=', 'schemes.scheme_code')
            ->groupBy('type')
            ->get()
            ->map(function($item) use ($total) {
                $item['percent'] = ($item->amount / $total) * 100;
                return $item;  
            });

        if(request()->wantsJson()) {
            return $assets;
        }
            
        return view('allocations.index', compact('assets'));
    }
}
