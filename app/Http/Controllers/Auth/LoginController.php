<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (Auth::attempt(array($fieldType => $input['username'], 'password' => $input['password'], 'Active' => 1, 'Activated' => 1))) {

            $user = User::find(Auth::id());
            $request->session()->put('last_login', $user->last_login);
            $user->last_login = date("Y-m-d H:i:s");
            $user->save();

            Log::channel('appsyslog')->info(
                '#log#',
                [
                    'username' => Auth::user()->username,
                    'ip' => $request->ip(),
                    'date' =>  date("Y-m-d H:i:s"),
                    'uri' => Route::current()->uri,
                    'parameters' => Route::current()->parameters(),
                    'route' => Route::currentRouteName(),
                    'request' => $request->all(),
                    'response_code' => 200,
                    'methods' => Route::current()->methods
                ]
            ); 

            

            return redirect()->route('home');

        } elseif (Auth::attempt(array($fieldType => $input['username'], 'password' => $input['password'])))   {

                $user = User::find(Auth::id());
               
                if (@$user->Active == 0 || @$user->Activated == 0) {
                    
                    Log::channel('appsyslog')->info(
                        '#log#',
                        [
                            'username' => $input['username'],
                            'ip' => $request->ip(),
                            'date' =>  date("Y-m-d H:i:s"),
                            'uri' => Route::current()->uri,
                            'parameters' => Route::current()->parameters(),
                            'route' => Route::currentRouteName(),
                            'request' => $request->all(),
                            'response_code' => 400,
                            'methods' => Route::current()->methods
                        ]
                    ); 

                    Auth::logout();
                    Session()->flush();
                    return redirect()->route('login')
                    ->with('error', 'User not Active/Activated.');
                }
 
        }else{
            Log::channel('appsyslog')->info(
                '#log#',
                [
                    'username' => $input['username'],
                    'ip' => $request->ip(),
                    'date' =>  date("Y-m-d H:i:s"),
                    'uri' => Route::current()->uri,
                    'parameters' => Route::current()->parameters(),
                    'route' => Route::currentRouteName(),
                    'request' => $request->all(),
                    'response_code' => 401,
                    'methods' => Route::current()->methods
                ]
            ); 
            return redirect()->route('login')
                ->with('error', 'Username/Email-Address And Password Are Wrong');
        }

        
    }

    public function logout()
    {
        Auth::logout();
        Session()->flush();

        return redirect()->route('login');
    }
}
