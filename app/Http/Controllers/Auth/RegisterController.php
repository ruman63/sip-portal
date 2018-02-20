<?php

namespace App\Http\Controllers\Auth;

use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function register()
    {
        $data = request()->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'dob' => 'required|date',
            'gender' => 'required|in:m,f,M,F',
            'email' => 'required|email|unique:clients,email',
            'mobile' => 'required|digits:10',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $data['password'] = bcrypt($data['password']);
        
        $client = Client::create($data);
        
        if(auth()->guard('cpanel')->check()) {
            flash('Client Created Successfully')->success();
            return redirect()->route('clients.index');
        }

        return redirect()->route('dashboard');
    }
}
