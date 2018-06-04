<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;

class TransactionsController extends Controller
{
    public function index() 
    {
        $transactions = Transaction::where('client_id', auth()->guard('web')->id())
            ->with(['client', 'scheme'])
            ->get();

        if(request()->wantsJson()) {
            return $transactions;
        }
        
        return view('transactions.index', compact('transactions'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'folio_no' => 'required',
            'uid' => 'required',
            'type' => 'required',
            'scheme_code' => 'required',
            'date' => 'required',
            'rate' => 'required',
            'amount' => 'required',
        ]);

        $transaction = auth()->guard('web')->user()
            ->transactions()->create(
                request()->only([
                    'uid',
                    'folio_no', 
                    'scheme_code', 
                    'type', 
                    'date', 
                    'rate', 
                    'amount'
                ])
            );

        if(request()->wantsJson()) {
            return $transaction->load('scheme');
        }
        
        flash('Transaction added to your Account');

        return back();
    }

    public function update(Transaction $transaction)
    {

        $data = request()->only([
                    'uid',
                    'folio_no',
                    'rate', 
                    'amount'
                ]);
        if($transaction->client_id==auth()->guard('web')->id()) {
            $transaction->update($data); 

            return $transaction;
          
        }
        else{
            return response()->json(['message' => 'You are not authorized to update this transaction!'], 403);
        }
            
        
        
        
        // flash('Transaction updated to your Account');

        // return back();
    }
}
