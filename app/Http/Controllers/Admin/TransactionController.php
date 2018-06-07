<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Client;

class TransactionController extends Controller
{
    public function index() 
    {
        if(request()->wantsJson()) {
            return Transaction::with(['client', 'scheme'])->latest()->get();
        }
        
        return view('admin.transactions.index');
    }
    
    public function store()
    {
        $data = request()->validate([
            'type' => 'required',
            'rate' => 'required',
            'amount' => 'required',
            'folio_no' => 'required',
            'client_id' => 'required|exists:clients,id',
            'uid' => 'required|unique:transactions,uid',
            'date' => 'required|date_format:Y-m-d|before:tomorrow',
            'scheme_code' => 'required|exists:schemes,scheme_code',
        ]);

        $transaction = Transaction::create($data);

        if(request()->wantsJson()) {
            return $transaction->load('scheme');
        }
        
        flash('Transaction created successfully');

        return back();
    }

    public function update(Transaction $transaction)
    {
        $data = request()->validate([
            'uid' => 'required' . ($transaction->uid != request('uid') ? '|unique:transactions,uid' : ''),
            'rate' => 'required',
            'type' => 'required',
            'amount' => 'required',
            'folio_no' => 'required',
            'date' => 'required|date_format:Y-m-d|before:tomorrow',
            'scheme_code' => 'required|exists:schemes,scheme_code',
        ]);

        $transaction->update($data); 

        if(request()->wantsJson()) {
            return $transaction->load('scheme');
        }
            
        flash('Transaction updated successfully!');
        return back();
    }

    public function destroy(Transaction $transaction)
    {
        
        $transaction->delete();

        if(request()->wantsJson()) {
            return response()->json(['message'=>'Transaction deleted']);
        }

        flash('Transaction deleted successfully!');
        return back();
    }
}
