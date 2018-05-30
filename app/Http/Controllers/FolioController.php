<?php

namespace App\Http\Controllers;

use App\Folio;
use Illuminate\Http\Request;
use App\Transaction;

class FolioController extends Controller
{
    public function index() 
    {
        return Folio::with(['client', 'transactions.scheme'])
            ->where('client_id', auth()->guard('web')->id())
            ->get();
    }
    
    public function create() 
    {
        return view('folios.create');
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

        $folio = Folio::firstOrCreate(
            request()->only('folio_no'),
            [ 'client_id' => auth()->guard('web')->id() ]
        );

        $transaction = Transaction::make(request()->only(['scheme_code', 'type', 'date', 'rate','amount']));
        $transaction->folio_id = $folio->id;
        $transaction->uid = request('transaction_uid');
        $transaction->save();
        
        flash('Folio added to your Account');

        return back();
    }
}
