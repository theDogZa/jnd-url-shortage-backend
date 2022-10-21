<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Models\UsersPermission;
use App\Models\UsersRole;
use App\Models\User;
use App\Models\Role;
use App\Models\RolesPermission;

class UserRolesController extends Controller
{
  /**
   * Instantiate a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    //$this->middleware('auth');
    //$this->middleware('RolePermission');
    // $this->middleware(function ($request, $next) {
    //   $this->addLogSys($request);
    //   return $next($request);
    // });
    Cache::flush();
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header('Content-Type: text/html');

    $this->arrShowFieldIndex = [
		 'user_id' => 1,  'role_id' => 1,  'active' => 1, 		];
		$this->arrShowFieldFrom = [
		 'user_id' => 1,  'role_id' => 1,  'active' => 1, 		];
		$this->arrShowFieldView = [
		 'user_id' => 1,  'role_id' => 1,  'active' => 1, 		];
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
			'user_id' => 'required|string|max:255',
			'role_id' => 'required|string|max:255',
			'active' => 'required|string|max:255',
			//#Ex
			//'username' => 'required|string|max:20|unique:users,username,' . $data ['id'],
			//'email' => 'required|string|email|max:255|unique:users,email,' . $data ['id'],
			// 'password' => 'required|string|min:6|confirmed',
			//'password' => 'required|string|min:6',
		];
		
		$messages = [
			'user_id.required' => trans('Users_role.user_id_required'),
			'role_id.required' => trans('Users_role.role_id_required'),
			'active.required' => trans('Users_role.active_required'),
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

    $results = UsersRole::select($select);

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

    $compact->collection = $results->sortable()->paginate(config('theme.paginator.paginate'));

    $compact->arrShowField = $this->arrShowFieldIndex;

    return view('_users_roles.index', (array) $compact);
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

      $this->_getDataBelongs($compact);

      return view('_users_roles.form', (array) $compact);
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

      $users_role = new UsersRole;
      foreach ($input as $key => $v) {
        $users_role->$key = $v;
      }
      $users_role->created_uid = Auth::id();
      $users_role->created_at = date("Y-m-d H:i:s");
      $users_role->save();

      DB::commit();
      Log::info('Successful: Users_role:store : ', ['data' => $users_role]);

      $message = trans('core.message_insert_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: Users_role:store :' . $e->getMessage());

      $message = trans('core.message_insert_error');
      $status = 'error';
      $title = 'Error';
    }

    session(['noit_title' => $title]);
    session(['noit_message' => $message]);
    session(['noit_status' => $status]);

    return redirect()->route('users_roles.index');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(Request $request, $id)
  {
    $select = $this->_listToSelect($this->arrShowFieldFrom);

    $compact = (object) array();
    $compact->arrShowField = $this->arrShowFieldFrom;
    $users_role = UsersRole::select($select)->findOrFail($id);

    $compact->users_role = $users_role;

    $this->_getDataBelongs($compact);

    return view('_users_roles.form',$users_role, (array) $compact);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request, $id)
  {
    $select = $this->_listToSelect($this->arrShowFieldView);

    $compact = (object) array();
    $compact->arrShowField = $this->arrShowFieldView;
    $compact->users_role = UsersRole::select($select)->findOrFail($id);
    $this->_getDataBelongs($compact);
    return view('_users_roles.show', (array) $compact);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */

  public function update(Request $request, $id) {
  
    $this->validator($request->all())->validate();

    $input = (object) $request->except(['_token', '_method']);
  
    try {
      DB::beginTransaction();

      $users_role = UsersRole::find($id);
      foreach ($input as $key => $v) {
        $users_role->$key = $v;
      }
      $users_role->updated_uid = Auth::id();
      $users_role->updated_at = date("Y-m-d H:i:s");
      $users_role->save();

      DB::commit();
      Log::info('Successful: Users_role:update : ', ['id' => $id, 'data' => $users_role]);

      $message = trans('core.message_update_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: Users_role:update :' . $e->getMessage());

      $message = trans('core.message_update_error');
      $status = 'error';
      $title = 'Error';
    }

    session(['noit_title' => $title]);
    session(['noit_message' => $message]);
    session(['noit_status' => $status]);

    return redirect()->route('users_roles.index');
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

      UsersRole::destroy($id);

      DB::commit();
      Log::info('Successful: users_role:destroy : ', ['id' => $id]);

      $message = trans('core.message_del_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: users_role:destroy :' . $e->getMessage());

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

  protected function _easySearch($results, $search=""){
	      $results = $results->orWhere ('Users_roles.user_id', 'LIKE','%'. @$search.'%') ;
	      $results = $results->orWhere ('Users_roles.role_id', 'LIKE','%'. @$search.'%') ;
	      $results = $results->orWhere ('Users_roles.active', 'LIKE','%'. @$search.'%') ;
        return $results;
  }

  protected function _advSearch($results, $input){
        if(@$input->user_id){
          $results = $results->where('Users_roles.user_id',  $input->user_id);
        }
        if(@$input->role_id){
          $results = $results->where('Users_roles.role_id',  $input->role_id);
        }
        if(@$input->active){
          $results = $results->where('Users_roles.active',  $input->active);
        }
      return $results;
  }

  protected function _getDataBelongs($compact)
  {
		  $compact->arrUser = User::where('id','!=',null)
			->orderBy('id')
			->pluck('id','id')	
      ->toArray();
		  $compact->arrRole = Role::where('id','!=',null)
			->orderBy('id')
			->pluck('id','id')	
      ->toArray();
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function listByRoles(Request $request, $id)
  {
    $compact = (object) array();
    $compact->role_id = $id;

    $compact->role = Role::select('name')->findOrFail($id);

    $Users = User::select('id', 'username', 'first_name', 'last_name')->where('active', true)->orderBy('username','ASC');
    
    if (!$request->user()->hasRole('developer')) {
      $Users->where('id','!=', 1);
    }

    $compact->collection = $Users->get();
    
    $compact->usersRole = UsersRole::where('role_id', $id)
      ->where('active', true)
      ->pluck('id', 'user_id')
      ->toArray();

    return view('_users_roles.list_users', (array) $compact);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function storeRoles(Request $request)
  {
    $RolesPermission = RolesPermission::select('permission_id')->where('role_id', $request->role_id)->pluck('permission_id')->toArray();
    //$UsersPermission = UsersPermission::select('id')->where('role_id', $request->role_id)->pluck('id')->toArray();

    $response = (object) array();

    try {
      DB::beginTransaction();

      $isDelUsersRole = UsersRole::where('role_id', $request->role_id)->delete();
      $isDelUsersPermission = UsersPermission::where('role_id', $request->role_id)->delete();
      
        foreach ($request->users as $k => $v) {
          $UsersRole = new UsersRole;
          $UsersRole->role_id = $request->role_id;
          $UsersRole->user_id = $v;
          $UsersRole->active = 1;
          $UsersRole->created_uid = Auth::id();
          $UsersRole->created_at = date("Y-m-d H:i:s");
          $UsersRole->save();
        }

        foreach ($request->users as $k => $v) {
          foreach ($RolesPermission as $k => $rp) {
            $UsersPermission = new UsersPermission;
            $UsersPermission->role_id = $request->role_id;
            $UsersPermission->user_id = $v;
            $UsersPermission->permission_id = $rp;
            $UsersPermission->active = 1;
            $UsersPermission->created_uid = Auth::id();
            $UsersPermission->created_at = date("Y-m-d H:i:s");
            $UsersPermission->save();
          }
        }
      

      DB::commit();
      Log::info('Successful: UsersRoles:storeRoles : ', ['role_id' => $request->role_id]);

      $message = trans('core.message_update_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: UsersRoles:storeRoles :' . $e->getMessage());

      $message = trans('core.message_update_error');
      $status = 'error';
      $title = 'Error';
    }

    session(['noit_title' => $title]);
    session(['noit_message' => $message]);
    session(['noit_status' => $status]);

    return redirect()->route('roles.update_user_roles', ['id' => $request->role_id]);
  }

  
}
