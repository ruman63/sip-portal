<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    public function redirectTo() {
        return route('admin.dashboard');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:cpanel')->except('logout');
    }

    public function showLoginForm() 
    {
        return view('admin.auth.login');
    }

    public function username() 
    {
        return 'username';
    }

    protected function guard()
    {
        return Auth::guard('cpanel');
    }
}
