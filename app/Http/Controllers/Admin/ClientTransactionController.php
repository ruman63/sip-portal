<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Client;

class ClientTransactionController extends Controller
{
    public function index(Client $client)
    {
        if(request()->wantsJson()) {
            return $client->transactions()->with('scheme')->latest()->get();
        }

        return view('admin.transactions.index', compact('transactions', 'client'));
    }
}
