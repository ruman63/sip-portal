<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;

class FoliosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }


    public function index() {
        return auth()->guard('web')->user()
            ->transactions()
            ->selectRaw('DISTINCT folio_no')
            ->get()
            ->map(function($txn) {
                return $txn->folio_no;
            });
    }
}
