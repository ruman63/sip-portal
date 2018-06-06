<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaction;

class FoliosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:cpanel');
    }

    public function index() {
        $query = Transaction::query();
        
        if($id = request('client_id')) {
            $query = $query->where('client_id', $id);
        }
        
        return $query->selectRaw('DISTINCT folio_no')->get()
            ->map(function($txn) {
                return $txn->folio_no;
            });
    }
}
