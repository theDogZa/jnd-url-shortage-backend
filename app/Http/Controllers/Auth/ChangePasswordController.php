<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class ChangePasswordController extends Controller
{
  /*
    |--------------------------------------------------------------------------
    | ChangePassword Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
          $this->addLogSys($request);
          return $next($request);
        });
        Cache::flush();
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: text/html');
    }

 /**
   * Show profile the form for editing the specified resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $id = Auth::id();
    $select = '*';

    $compact = (object) array();
    $user = User::select($select)->findOrFail($id);

    $compact->user = $user;

    return view('_profile.change-password',$user, (array) $compact);
  }

   /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */

  public function store(Request $request) {
  
    $input = (object) $request->except(['_token', '_method']);
  
    try {
      DB::beginTransaction();

      $user = User::find(Auth::id());
    
      $code = $user->username. "@system:". $input->password;

      $user->password = Hash::make($input->password);
      $user->auth_code = base64_encode($code);
      $user->save();

      DB::commit();
      Log::info('Successful: User:ChangePassword : ', ['id' => $user->id, 'data' => $user]);

      $message = trans('changePasswords.message_change_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: User:update :' . $e->getMessage());

      $message = trans('core.message_change_error');
      $status = 'error';
      $title = 'Error';
    }

    session(['noit_title' => $title]);
    session(['noit_message' => $message]);
    session(['noit_status' => $status]);

    return view('_profile.change-password',$user);
  }

  public function checkPassword(Request $request) {
    $response = (object)[];
    $chk = false;
    $user = User::find(Auth::id());

    if(Hash::check($request['current_password'], $user['password'])){
        $chk = true;
    }
  
    $response->code = 200;
    $response->message = $chk;

    return base64_encode(json_encode((array)$response));
   
  }
}
