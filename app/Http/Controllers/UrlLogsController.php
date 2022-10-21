<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Models\UrlLog;

class UrlLogsController extends Controller
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
    Cache::flush();
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header('Content-Type: text/html');

    $this->arrShowFieldIndex = [
		 'short_url' => 1,  'original_url' => 1,  'ip' => 1, 'created_at' =>1	];
		$this->arrShowFieldFrom = [
		 'short_url' => 1,  'original_url' => 1,  'ip' => 1, 		];
		$this->arrShowFieldView = [
		 'short_url' => 1,  'original_url' => 1,  'ip' => 1, 		];
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
			'short_url' => 'required|string|max:255',
			'original_url' => 'required|string|max:255',
			'ip' => 'required|string|max:255',
			//#Ex
			//'username' => 'required|string|max:20|unique:users,username,' . $data ['id'],
			//'email' => 'required|string|email|max:255|unique:users,email,' . $data ['id'],
			// 'password' => 'required|string|min:6|confirmed',
			//'password' => 'required|string|min:6',
		];
		
		$messages = [
			'short_url.required' => trans('Url_log.short_url_required'),
			'original_url.required' => trans('Url_log.original_url_required'),
			'ip.required' => trans('Url_log.ip_required'),
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

    $results = UrlLog::select($select);

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

    return view('_url_logs.index', (array) $compact);
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

      return view('_url_logs.form', (array) $compact);
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

      $url_log = new UrlLog;
      foreach ($input as $key => $v) {
        $url_log->$key = $v;
      }
      $url_log->created_uid = Auth::id();
      $url_log->created_at = date("Y-m-d H:i:s");
      $url_log->save();

      DB::commit();
      Log::info('Successful: Url_log:store : ', ['data' => $url_log]);

      $message = trans('core.message_insert_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: Url_log:store :' . $e->getMessage());

      $message = trans('core.message_insert_error');
      $status = 'error';
      $title = 'Error';
    }

    session(['noit_title' => $title]);
    session(['noit_message' => $message]);
    session(['noit_status' => $status]);

    return redirect()->route('url_logs.index');
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
    $url_log = UrlLog::select($select)->findOrFail($id);

    $compact->url_log = $url_log;

    $this->_getDataBelongs($compact);

    return view('_url_logs.form',$url_log, (array) $compact);
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
    $compact->url_log = UrlLog::select($select)->findOrFail($id);
    $this->_getDataBelongs($compact);
    return view('_url_logs.show', (array) $compact);
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

      $url_log = UrlLog::find($id);
      foreach ($input as $key => $v) {
        $url_log->$key = $v;
      }
      $url_log->updated_uid = Auth::id();
      $url_log->updated_at = date("Y-m-d H:i:s");
      $url_log->save();

      DB::commit();
      Log::info('Successful: Url_log:update : ', ['id' => $id, 'data' => $url_log]);

      $message = trans('core.message_update_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: Url_log:update :' . $e->getMessage());

      $message = trans('core.message_update_error');
      $status = 'error';
      $title = 'Error';
    }

    session(['noit_title' => $title]);
    session(['noit_message' => $message]);
    session(['noit_status' => $status]);

    return redirect()->route('url_logs.index');
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

      UrlLog::destroy($id);

      DB::commit();
      Log::info('Successful: url_log:destroy : ', ['id' => $id]);

      $message = trans('core.message_del_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: url_log:destroy :' . $e->getMessage());

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
	      $results = $results->orWhere ('Url_logs.short_url', 'LIKE','%'. @$search.'%') ;
	      $results = $results->orWhere ('Url_logs.original_url', 'LIKE','%'. @$search.'%') ;
	      $results = $results->orWhere ('Url_logs.ip', 'LIKE','%'. @$search.'%') ;
        return $results;
  }

  protected function _advSearch($results, $input){
        if(@$input->short_url){
          $results = $results->where('Url_logs.short_url', 'LIKE', "%" .  $input->short_url. "%" );
        }
        if(@$input->original_url){
          $results = $results->where('Url_logs.original_url', 'LIKE', "%" .  $input->original_url. "%" );
        }
        if(@$input->ip){
          $results = $results->where('c.ip', 'LIKE', "%" .  $input->ip. "%" );
        }
        if(@$input->created_at_start && @$input->created_at_end){
          $sd = date_create($input->created_at_start . ":00");
          $sDate = date_format($sd, "Y-m-d H:i:s");
          $ed = date_create(@$input->created_at_end . ":59");
          $eDate = date_format($ed, "Y-m-d H:i:s");
          $results = $results->whereBetween('Url_logs.created_at',  [$sDate, $eDate]);
        }
      return $results;
  }

  protected function _getDataBelongs($compact)
  {
  }
}
