<?php

namespace App\Http\Controllers;

use App\Transaction;

class TransactionsController extends Controller
{
    public function index() 
    {
        $transactions = auth()->guard('web')->user()->transactions()
            ->with('scheme')
            ->get();

        if(request()->wantsJson()) {
            return $transactions;
        }
        
        return view('transactions.index', compact('transactions'));
    }
}
