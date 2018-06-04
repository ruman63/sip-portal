<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use Carbon\Carbon;

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
            'type' => 'required',
            'rate' => 'required',
            'amount' => 'required',
            'folio_no' => 'required',
            'uid' => 'required|unique:transactions,uid',
            'date' => 'required|date_format:Y-m-d|before:tomorrow',
            'scheme_code' => 'required|exists:schemes,scheme_code',
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
        request()->validate([
            'uid' => 'required' . ($transaction->uid != request('uid') ? '|unique:transactions,uid' : ''),
            'rate' => 'required',
            'type' => 'required',
            'amount' => 'required',
            'folio_no' => 'required',
            'date' => 'required|date_format:Y-m-d|before:tomorrow',
            'scheme_code' => 'required|exists:schemes,scheme_code',
        ]);
        
        if($transaction->client_id!=auth()->guard('web')->id()) {
            return response()->json([
                'message' => 'Forbidden! You are not authorized to update this transaction!'
            ], 403);
        }

        $transaction->update(request()->only([
            'uid',
            'folio_no',
            'rate', 
            'type',
            'date',
            'amount',
            'scheme_code'
        ])); 

        if(request()->wantsJson()) {
            return $transaction->load('scheme');
        }
            
        flash('Transaction updated successfully!');
        return back();
    }
}
