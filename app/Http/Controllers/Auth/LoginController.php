<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Where to redirect users after login.
     *
     * @return string Path
     */
    protected function redirectPath() {
        return route('dashboard');
    }

    /**
     * Guard to use for Authentication
     *
     * @return Illuminate\Auth\SessionGuard 
     */
    protected function guard()
    {
        return auth()->guard('web');
    }
}
