<?php

namespace App\Http\Controllers\Admin;

use App\Sip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SipController extends Controller
{
    public function index()
    {
        return view('admin.sip.index');
    }

    public function store()
    {
        $data = request()->only([
            'client_id', 'folio_no', 'scheme_code', 
            'date', 'amount', 'installments', 'interval',
        ]);

        $sip = Sip::create($data);

        if(request()->wantsJson()) {
            return $sip;
        }

        flash('Sip Created Successfully!');
        
        return back();
    }
}
