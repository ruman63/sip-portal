<?php

namespace App\Http\Controllers;

use App\Client;

class ProfileController extends Controller
{
    public function show()
    {
        $client = Client::where('id', auth()->guard('web')->id())
            ->with(['address', 'bankAccounts', 'defaultBankAccount'])
            ->firstOrFail();
        return view('profile.show', compact('client'));
    }
}
