<?php

namespace App\Http\Controllers\Admin;

use App\Sip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use App\SipSchedule;

class SipController extends Controller
{
    public function index()
    {
        $sipEntries = Sip::with(['schedules', 'client'])->get();

        if(request()->wantsJson()) {
            return $sipEntries;
        }

        return view('admin.sip.index', compact('sipEntries'));
    }

    public function store()
    {
        $data = request()->validate([
            'client_id' => 'required|exists:clients,id', 
            'folio_no' => 'required',
            'scheme_code' => 'required|exists:schemes,scheme_code', 
            'date' => 'required|date_format:Y-m-d|after:yesterday',
            'amount' => 'required', 
            'installments' => 'required|integer', 
            'interval' => 'required|in:monthly,weekly',
        ]);

        $sip = Sip::create($data);
        $schedules = $sip->generateSchedules();

        if(request()->wantsJson()) {
            return $sip->load('schedules');
        }

        flash('Sip Created Successfully!');
        
        return back();
    }
}
