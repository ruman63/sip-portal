<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;

class TransactionsController extends Controller
{
    public function index() 
    {
        return Transaction::where('client_id', auth()->guard('web')->id())
            ->with(['client', 'scheme'])
            ->get();
    }
    
    public function create() 
    {
        return view('transactions.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'folio_no' => 'required',
            'transaction_uid' => 'required',
            'type' => 'required',
            'scheme_code' => 'required',
            'date' => 'required',
            'rate' => 'required',
            'amount' => 'required',
        ]);

        $transaction = Transaction::make(
            request()->only([
                'folio_no', 
                'scheme_code', 
                'type', 
                'date', 
                'rate', 
                'amount'
            ])
        );

        $transaction->uid = request('transaction_uid');
        $transaction->client_id = auth()->guard('web')->id();
        $transaction->save();
        
        flash('Tranasaction added to your Account');

        return back();
    }
}
