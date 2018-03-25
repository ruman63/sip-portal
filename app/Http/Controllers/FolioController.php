<?php

namespace App\Http\Controllers;

use App\Folio;
use Illuminate\Http\Request;

class FolioController extends Controller
{
    public function index() 
    {
        return Folio::with(['client', 'scheme'])
            ->where('client_id', auth()->guard('web')->id())
            ->get();
    }
    
    public function create() 
    {
        return view('folios.create');
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'folio_no' => 'required',
            'scheme_code' => 'required',
            'trade_date' => 'required',
            'purchase_price' => 'required',
            'amount' => 'required',
        ]);

        $folio = Folio::create($data + [ 'client_id' => auth()->guard('web')->id() ]);
        
        flash('Folio added to your Account');

        return back();
    }
}
