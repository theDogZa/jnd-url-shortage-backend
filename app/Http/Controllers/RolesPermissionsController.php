<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Models\RolesPermission;
use App\Models\Role;
use App\Models\Permission;
use App\Models\UsersRole;
use App\Models\UsersPermission;

class RolesPermissionsController extends Controller
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
		 'role_id' => 1,  'permission_id' => 1,  'active' => 1, 		];
		$this->arrShowFieldFrom = [
		 'role_id' => 1,  'permission_id' => 1,  'active' => 1, 		];
		$this->arrShowFieldView = [
		 'role_id' => 1,  'permission_id' => 1,  'active' => 1, 		];
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
			'role_id' => 'required|string|max:255',
			'permission_id' => 'required|string|max:255',
			'active' => 'required|string|max:255',
			//#Ex
			//'username' => 'required|string|max:20|unique:users,username,' . $data ['id'],
			//'email' => 'required|string|email|max:255|unique:users,email,' . $data ['id'],
			// 'password' => 'required|string|min:6|confirmed',
			//'password' => 'required|string|min:6',
		];
		
		$messages = [
			'role_id.required' => trans('RolesPermission.role_id_required'),
			'permission_id.required' => trans('RolesPermission.permission_id_required'),
			'active.required' => trans('RolesPermission.active_required'),
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

    $results = RolesPermission::select($select);

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

    return view('_roles_permissions.index', (array) $compact);
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

      return view('_roles_permissions.form', (array) $compact);
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

      $RolesPermission = new RolesPermission;
      foreach ($input as $key => $v) {
        $RolesPermission->$key = $v;
      }
      $RolesPermission->created_uid = Auth::id();
      $RolesPermission->created_at = date("Y-m-d H:i:s");
      $RolesPermission->save();

      DB::commit();
      Log::info('Successful: RolesPermission:store : ', ['data' => $RolesPermission]);

      $message = trans('core.message_insert_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: RolesPermission:store :' . $e->getMessage());

      $message = trans('core.message_insert_error');
      $status = 'error';
      $title = 'Error';
    }

    session(['noit_title' => $title]);
    session(['noit_message' => $message]);
    session(['noit_status' => $status]);

    return redirect()->route('RolesPermissions.index');
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
    $RolesPermission = RolesPermission::select($select)->findOrFail($id);

    $compact->RolesPermission = $RolesPermission;

    $this->_getDataBelongs($compact);

    return view('_roles_permissions.form',$RolesPermission, (array) $compact);
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
    $compact->RolesPermission = RolesPermission::select($select)->findOrFail($id);
    $this->_getDataBelongs($compact);
    return view('_roles_permissions.show', (array) $compact);
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

      $RolesPermission = RolesPermission::find($id);
      foreach ($input as $key => $v) {
        $RolesPermission->$key = $v;
      }
      $RolesPermission->updated_uid = Auth::id();
      $RolesPermission->updated_at = date("Y-m-d H:i:s");
      $RolesPermission->save();

      DB::commit();
      Log::info('Successful: RolesPermission:update : ', ['id' => $id, 'data' => $RolesPermission]);

      $message = trans('core.message_update_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: RolesPermission:update :' . $e->getMessage());

      $message = trans('core.message_update_error');
      $status = 'error';
      $title = 'Error';
    }

    session(['noit_title' => $title]);
    session(['noit_message' => $message]);
    session(['noit_status' => $status]);

    return redirect()->route('RolesPermissions.index');
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

      RolesPermission::destroy($id);

      DB::commit();
      Log::info('Successful: RolesPermission:destroy : ', ['id' => $id]);

      $message = trans('core.message_del_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: RolesPermission:destroy :' . $e->getMessage());

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
	      $results = $results->orWhere ('RolesPermissions.role_id', 'LIKE','%'. @$search.'%') ;
	      $results = $results->orWhere ('RolesPermissions.permission_id', 'LIKE','%'. @$search.'%') ;
	      $results = $results->orWhere ('RolesPermissions.active', 'LIKE','%'. @$search.'%') ;
        return $results;
  }

  protected function _advSearch($results, $input){
        if(@$input->role_id){
          $results = $results->where('RolesPermissions.role_id',  $input->role_id);
        }
        if(@$input->permission_id){
          $results = $results->where('RolesPermissions.permission_id',  $input->permission_id);
        }
        if(@$input->active){
          $results = $results->where('RolesPermissions.active',  $input->active);
        }
      return $results;
  }

  protected function _getDataBelongs($compact)
  {
		  $compact->arrRole = Role::where('id','!=',null)
			->orderBy('id')
			->pluck('id','id')	
      ->toArray();
		  $compact->arrPermission = Permission::where('id','!=',null)
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
    $setPermission = [];

    $compact->role_id = $id;
    $compact->role = Role::select('name')->findOrFail($id);
    $Permissions = Permission::select('id', 'name', 'group_code', 'description');

    if (!$request->user()->hasRole('developer')) {

      $Permissions = $Permissions->where('group_code', '!=', 'DEV');
    }

    $Permissions = $Permissions->where('active', true)->get();

    foreach($Permissions AS $Per){
      $setPermission[$Per->group_code][] = $Per;
    }

    $compact->collection = $setPermission;
    $compact->permissionsGuoup = config('auth.permission_group_code');

    $compact->rolesPermission = RolesPermission::where('role_id', $id)
    ->where('active', true)
    ->pluck('id', 'permission_id')
    ->toArray();

    return view('_roles_permissions.list_roles', (array) $compact);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function storeRoles(Request $request)
  {
    //$RolesPermission = RolesPermission::select('id')->where('role_id', $request->role_id)->get();
    $UsersRoles = UsersRole::select('user_id')->where('role_id', $request->role_id)->pluck('user_id')->toArray();

    $response = (object) array();

    try {
      DB::beginTransaction();

      $isDelRolesPermission = RolesPermission::where('role_id', $request->role_id)->delete();
      $isDelUsersPermission = UsersPermission::where('role_id', $request->role_id)->delete();

      //if($isDelRolesPermission && $isDelUsersPermission){

        foreach ($request->permissions as $k=>$v) {
          $RolesPermission = new RolesPermission;
          $RolesPermission->role_id = $request->role_id;
          $RolesPermission->permission_id = $v;
          $RolesPermission->active = 1;
          $RolesPermission->created_uid = Auth::id();
          $RolesPermission->created_at = date("Y-m-d H:i:s");
          $RolesPermission->save();
        }

        foreach ($request->permissions as $k => $v) {
          foreach ($UsersRoles as $k => $ur) {
              $UsersPermission = new UsersPermission;
              $UsersPermission->role_id = $request->role_id;
              $UsersPermission->user_id = $ur;
              $UsersPermission->permission_id = $v;
              $UsersPermission->active = 1;
              $UsersPermission->created_uid = Auth::id();
              $UsersPermission->created_at = date("Y-m-d H:i:s");
              $UsersPermission->save();
          }
        }
     // }

      DB::commit();
      Log::info('Successful: RolesPermission:storeRoles : ', ['role_id' => $request->role_id]);

      $message = trans('core.message_update_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: RolesPermission:storeRoles :' . $e->getMessage());

      $message = trans('core.message_update_error');
      $status = 'error';
      $title = 'Error';
    }

    session(['noit_title' => $title]);
    session(['noit_message' => $message]);
    session(['noit_status' => $status]);

    return redirect()->route('roles.update_permissions_roles', ['id' => $request->role_id]);
  }

}

/** 
 * CRUD Laravel
 * Master ฺBY Kepex  =>  https://github.com/kEpEx/laravel-crud-generator
 * Modify/Update BY PRASONG PUTICHANCHAI
 * 
 * Latest Update : 09/09/2020 10:32
 * Version : ver.1.00.00
 *
 * File Create : 2020-09-22 16:55:35 *
 */