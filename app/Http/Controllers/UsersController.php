<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UsersPermission;
use App\Models\UsersRole;


class UsersController extends Controller
{
  /**
   * Instantiate a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {

    $this->middleware('RolePermission');

    Cache::flush();
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header('Content-Type: text/html');

    $this->arrShowFieldIndex = [
		 'username' => 1,  'first_name' => 1,  'last_name' => 1,  'email' => 1,  'email_verified_at' => 0,  'password' => 0, 'active' => 1,  'activated' => 0,  'remember_token' => 0,  'last_login' => 0, 'user_right' => 1, 'change_password' => 0];
		$this->arrShowFieldFrom = [
		 'username' => 1,  'first_name' => 1,  'last_name' => 1,  'email' => 1,  'email_verified_at' => 0,  'password' => 0, 'active' => 1,  'activated' => 0,  'remember_token' => 0,  'last_login' => 0, 'user_right' => 1, 'change_password' => 0];
		$this->arrShowFieldView = [
     'username' => 1,  'first_name' => 1,  'last_name' => 1,  'email' => 1,  'email_verified_at' => 0,  'password' => 0, 'active' => 1,  'activated' => 0,  'remember_token' => 0,  'last_login' => 1, 'user_right' => 1, 'change_password' => 0, 'created_uid' => 0, 'updated_uid' => 0, 'created_at' => 1, 'updated_at' => 1];
  }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
		$rules = [
			'username' => 'required|string|max:255|unique:users',
			'email' => 'required|string|max:255|unique:users',
			'password' => 'required|string|max:255',
			//#Ex
			//'username' => 'required|string|max:20|unique:users,username,' . $data ['id'],
			//'email' => 'required|string|email|max:255|unique:users,email,' . $data ['id'],
			// 'password' => 'required|string|min:6|confirmed',
			//'password' => 'required|string|min:6',
		];
		
		$messages = [
			'username.required' => trans('User.username_required'),
			'email.required' => trans('User.email_required'),
			// 'password.required' => trans('User.password_required'),
			// 'active.required' => trans('User.active_required'),
			// 'activated.required' => trans('User.activated_required'),
			//#Ex
			//'email.unique'  => 'Email already taken' ,
			//'username.unique'  => 'Username "' . $data['username'] . '" already taken',
			//'email.email' =>'Email type',
		];

		return Validator::make($data,$rules,$messages);
	}

  public function index(Request $request)
  {
    $compact = (object) array();

    $select = $this->_listToSelect($this->arrShowFieldIndex);

    $results = User::select($select);

    if (!$request->user()->hasRole('developer')) {
      $results->where('id', '!=', 1);
    }
    //------ search
    if (count($request->all())) {
      $input = (object) $request->all();
      if(@$input->search){
        $results = $this->_easySearch($results, $input->search);
      }else{
        $results = $this->_advSearch($results, $input);
      }  
    }

    $compact->search = (object) $request->all();

    $this->_getDataBelongs($compact);
    //-----

    $compact->collection = $results->sortable('id','DESC')->paginate(config('theme.paginator.paginate'));

    $compact->arrShowField = $this->arrShowFieldIndex;

    return view('_users.index', (array) $compact);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request)
  {
      $compact = (object) array();
      $compact->arrShowField = $this->arrShowFieldFrom;
      $compact->password = $this->_generateStrongPassword();

      $this->_getDataBelongs($compact);


      return view('_users.form', (array) $compact);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validator($request->all())->validate();

    $input = (object) $request->except(['_token', '_method']);

    try {
      DB::beginTransaction();

      $user = new User;
      foreach ($input as $key => $v) {
        $user->$key = $v;
      }

      $user->activated = 1;
      $user->change_password = 1;
      $user->token = Str::random(80);
      $user->password = Hash::make($input->password);
      //$user->created_uid = Auth::id();
      $user->created_at = date("Y-m-d H:i:s");

      $user->save();

      DB::commit();
      Log::info('Successful: User:store : ', ['data' => $user]);


      $message = trans('core.message_insert_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: User:store :' . $e->getMessage());

      $message = trans('core.message_insert_error');
      $status = 'error';
      $title = 'Error';

    }

    session(['noit_title' => $title]);
    session(['noit_message' => $message]);
    session(['noit_status' => $status]);

    return redirect()->route('users.index');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(Request $request, $id)
  {
    if (!$request->user()->hasRole('developer') && $id == 1) {
      abort(404);
    }
    $select = $this->_listToSelect($this->arrShowFieldFrom);

    $compact = (object) array();
    $compact->arrShowField = $this->arrShowFieldFrom;
    $user = User::select($select)->findOrFail($id);

    $compact->user = $user;

    $this->_getDataBelongs($compact);


    return view('_users.form',$user, (array) $compact);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request, $id)
  {

    if (!$request->user()->hasRole('developer') && $id == 1) {
      abort(404);
    }

    $select = $this->_listToSelect($this->arrShowFieldView);

    $compact = (object) array();
    $compact->arrShowField = $this->arrShowFieldView;
    $compact->user = User::select($select)->findOrFail($id);
    $this->_getDataBelongs($compact);


    return view('_users.show', (array) $compact);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */

  public function update(Request $request, $id) {
  
    //$this->validator($request->all())->validate();

    $input = (object) $request->except(['_token', '_method']);
    try {
      DB::beginTransaction();

      $user = User::find($id);
      foreach ($input as $key => $v) {
        $user->$key = $v;
      }

      if($user->change_password == 1){
        $user->remember_token = Str::random(60);
      }

      $user->updated_at = date("Y-m-d H:i:s");
      $user->save();

      if($user->active==1){
        $request->session()->put('count_login_' . $user->username, null);
      }

      DB::commit();
      Log::info('Successful: User:update : ', ['id' => $id, 'data' => $user]);

      $message = trans('core.message_update_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: User:update :' . $e->getMessage());


      $message = trans('core.message_update_error');
      $status = 'error';
      $title = 'Error';
    }

    session(['noit_title' => $title]);
    session(['noit_message' => $message]);
    session(['noit_status' => $status]);

    return redirect()->route('users.index');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, $id) {

    $response = (object) array();

    try {
      DB::beginTransaction();

      $isDelUsersPermission = UsersPermission::where('user_id', $id)->delete();
      $isDelUsersRole = UsersRole::where('user_id', $id)->delete();

      // $dataLog = array();
      // $dataLog['request'] = array('id' => $id);
      // $dataLog['response'] = Inventory::find($id)->toArray();
      // $this->_cLog($request, $dataLog);

      User::destroy($id);

      DB::commit();
      Log::info('Successful: user:destroy : ', ['id' => $id]);
      
      $message = trans('core.message_del_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: user:destroy :' . $e->getMessage());

      $message = trans('core.message_del_error');
      $status = 'error';
      $title = 'Error';
    }

    $response->title = $title;
    $response->status = $status;
    $response->message = $message;

    return (array) $response;

  }

  /**
   * Field list To Select data form db 
   *
   * @param  array  $arrField
   * @return array select data
   */
  protected function _listToSelect($arrField)
  {
    $select[] = 'id';
    foreach ($arrField as $key => $val) {
      if ($val == 1) {
        $select[] = $key;
      }
    }
    return $select;
  }

 /**
  * This function is used to search for a specific user in the database
  * 
  * @param results The query builder object.
  * @param search The search term that is being searched for.
  * 
  * @return The query builder object.
  */
  protected function _easySearch($results, $search=""){
    
    $results->where(function ($results) use ($search) {
      
      return $results->orWhere ('Users.username', 'LIKE','%'. @$search.'%')
                    ->orWhere('Users.first_name', 'LIKE', '%' . @$search . '%')
                    ->orWhere('Users.last_name', 'LIKE', '%' . @$search . '%')
                    ->orWhere ('Users.email', 'LIKE','%'. @$search.'%');
        
    });
        return $results;
  }

  /**
   * This function is used to filter the results of the search
   * 
   * @param results The query builder object
   * @param input The input object that is passed to the controller.
   * 
   * @return The results of the query.
   */
  protected function _advSearch($results, $input){
        if(@$input->username){
          $results = $results->where('Users.username', 'LIKE', "%" .  $input->username. "%" );
        }
        if(@$input->first_name){
          $results = $results->where('Users.first_name', 'LIKE', "%" .  $input->first_name. "%" );
        }
        if(@$input->last_name){
          $results = $results->where('Users.last_name', 'LIKE', "%" .  $input->last_name. "%" );
        }
        if(@$input->email){
          $results = $results->where('Users.email', 'LIKE', "%" .  $input->email. "%" );
        }
        if(@$input->email_verified_at_start && @$input->email_verified_at_end){
          $sd = date_create(@$input->email_verified_at_start . ":00");
          $sDate = date_format($sd, "H:i:s");
          $ed = date_create(@$input->email_verified_at_end . ":59");
          $eDate = date_format($ed, "H:i:s");
          $results = $results->whereBetween('Users.email_verified_at',  [$sDate, $eDate]);
        }
        if(@$input->password){
          $results = $results->where('Users.password', 'LIKE', "%" .  $input->password. "%" );
        }
        if(@$input->active != null){
          $results = $results->where('Users.active',  $input->active);
        }
        if(@$input->activated != null){
          $results = $results->where('Users.activated',  $input->activated);
        }
        if (@$input->user_right != null) {
          $results = $results->where('Users.user_right',  $input->user_right);
        }
        if(@$input->remember_token){
          $results = $results->where('Users.remember_token', 'LIKE', "%" .  $input->remember_token. "%" );
        }
        if(@$input->last_login_start && @$input->last_login_end){
          $sd = date_create(@$input->last_login_start . ":00");
          $sDate = date_format($sd, "H:i:s");
          $ed = date_create(@$input->last_login_end . ":59");
          $eDate = date_format($ed, "H:i:s");
          $results = $results->whereBetween('Users.last_login',  [$sDate, $eDate]);
        }
      return $results;
  }

  protected function _getDataBelongs($compact)
  {
  }

  /**
   * Generate a random password that is a combination of upper case letters, lower case letters,
   * numbers, and special characters
   * 
   * @return A string of random characters.
   */
  protected function _generateStrongPassword(){

    $results = null;
    $number = '0123456789';
    $lower = 'abcdefghijklmnopqrstuvwxyz';
    $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $sChar = '!@#$';
    $results .= substr(str_shuffle($upper), 0, 1);
    $results .= substr(str_shuffle($lower), 0, 3);
    $results .= substr(str_shuffle($sChar), 0, 1);
    $results .= substr(str_shuffle($number), 0, 3);
    $results = str_shuffle($results);
    return $results;
  }

}
