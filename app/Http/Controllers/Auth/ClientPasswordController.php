<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\CurrentPassword;

class ClientPasswordController extends Controller
{
    public function edit()
    {
        return view('auth.passwords.edit');
    }
    
    public function change()
    {
        request()->validate([
            'current_password' => ['required', 'min:6', new CurrentPassword('web') ],
            'new_password' => ['required', 'min:6', 'confirmed'],
        ]);

        auth()->guard('web')->user()->changePassword(request('new_password'));

        if(request()->wantsJson()) {
            return ['message' => 'Your Password changed Successfully!'];
        }

        flash('Your Password changed Successfully!', 'success');

        return redirect('/dashboard');
    }
}
