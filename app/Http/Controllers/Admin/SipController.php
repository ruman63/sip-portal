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
        $sipEntries = Sip::with('schedules')->get();

        return view('admin.sip.index', compact('sipEntries'));
    }

    public function store()
    {
        $data = request()->only([
            'client_id', 'folio_no', 'scheme_code', 
            'date', 'amount', 'installments', 'interval',
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
