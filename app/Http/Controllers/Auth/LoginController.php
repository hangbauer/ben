<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Auth;
use Session;

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
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            // Authentication passed...
            return redirect('/');
        }else{            
            Session::flash('message', 'Invalid username or password'); 
            Session::flash('alert-class', 'alert-danger'); 
            return redirect()->back();
        }
    }

    public function logout(){
        Auth::logout();

        return redirect('login');
    }

    public function username(){
        return 'username';
    }
}
