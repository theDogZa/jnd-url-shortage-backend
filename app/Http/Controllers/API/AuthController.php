<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * register
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $reqHeader = (object)$request->header();
        $req = (object)$request->all();

        $response = (object) array();
        $response->status = (object) array();

        try {
            DB::beginTransaction();

            if(@$req->username && @$req->password){

                $chk = User::select('id')->where('username', $req->username)->orWhere('email', $req->email)->first();

                if(!$chk){

                    User::create([
                        'username' => $req->username,
                        'email' => @$req->email,
                        'first_name' => @$req->first_name,
                        'last_name' => @$req->last_name,
                        'activated' => 1,
                        'active' => 1,
                        'user_right' => 1, //---frontend
                        'password' => Hash::make($req->password),
                        'token' => Str::random(80),
                    ]);

                    DB::commit();

                    $response->status->code = 200;
                    $response->status->message = 'Success';
                    $response->data = [];

                    Log::info('Successful: API/AuthController:register : ', ['response' => $response]);

                }else{
                    $response->status->code = 403;
                    $response->status->message = 'error : username or email already in use!';
                    $response->data = [];
                    }

            }else{

                $response->status->code = 403;
                $response->status->message = 'error : username or password not found';
                $response->data = [];

                Log::error('Error: API/AuthController:register : username or password not found',['h'=>$reqHeader,'d'=>$req] );
            }

        } catch (\Exception $e) {

            DB::rollback();

            $response->status->code = 403;
            $response->status->message = 'error : '.$e->getMessage();
            $response->data = [];

            Log::error('Error: API/AuthController:register :' . $e->getMessage());
        }
        return response()->json($response);

    }

    /**
     * register
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $response = (object) array();
        $response->status = (object) array();

        $input = $request->all();

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        try {
            DB::beginTransaction();

            $auth = Auth::attempt(
                array(
                    $fieldType => $input['username'],
                    'password' => $input['password'],
                    'active' => 1,
                    'activated' => 1
                )
            );

            if ($auth) {

                $user = User::select('username','token','last_login')->find(Auth::id());
                $user->last_login = date("Y-m-d H:i:s");
                $user->save();
                
                DB::commit();

                $response->status->code = 200;
                $response->status->message = 'Success';
                $response->data = $user;

                Log::info('Successful: API/AuthController:login : ', ['response' => $response]);

            }else{

                $response->status->code = 403;
                $response->status->message = 'Username/Email or Password is incorrect';
                $response->data = [];
            }
            

        } catch (\Exception $e) {

            DB::rollback();

            $response->status->code = 403;
            $response->status->message = 'error : '.$e->getMessage();
            $response->data = [];

            Log::error('Error: API/AuthController:login :' . $e->getMessage());
        }

        
        return response()->json($response);
    }
}