<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Models\UrlShortener;
use App\Services\NetworkService;

class UrlShortenerController extends Controller
{
  /**
   * Instantiate a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    //$this->middleware('auth');
    $this->middleware('RolePermission');
    
    Cache::flush();
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header('Content-Type: text/html');

    $this->arrShowFieldIndex = ['short_url' => 1,  'original_url' => 1,  'ip' => 1,  'count' => 1,  'active' => 1, 'created_at' =>1 ,'created_uid' =>1 		];
		$this->arrShowFieldFrom = ['short_url' => 1,  'original_url' => 1,  'ip' => 1,  'count' => 1,  'active' => 1, 	'created_at' =>1 ,'created_uid' =>1 	];
		$this->arrShowFieldView = ['short_url' => 1,  'original_url' => 1,  'ip' => 1,  'count' => 1,  'active' => 1, 'created_at' =>1 ,'created_uid' =>1 		];
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
			'count' => 'required|string|max:255',
			'active' => 'required|string|max:255',
			//#Ex
			//'username' => 'required|string|max:20|unique:users,username,' . $data ['id'],
			//'email' => 'required|string|email|max:255|unique:users,email,' . $data ['id'],
			// 'password' => 'required|string|min:6|confirmed',
			//'password' => 'required|string|min:6',
		];
		
		$messages = [
			'short_url.required' => trans('UrlShortener.short_url_required'),
			'original_url.required' => trans('UrlShortener.original_url_required'),
			'ip.required' => trans('UrlShortener.ip_required'),
			'count.required' => trans('UrlShortener.count_required'),
			'active.required' => trans('UrlShortener.active_required'),
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

    $results = UrlShortener::select($select);

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

    if(@$input->sort == null){
      $compact->collection = $results->sortable(['id'=>'desc'])->paginate(config('theme.paginator.paginate'));
  
    }else{
      $compact->collection = $results->sortable()->paginate(config('theme.paginator.paginate'));
    }

    $compact->arrShowField = $this->arrShowFieldIndex;

    return view('_url_shortener.index', (array) $compact);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request)
  {
      $NetworkService = new NetworkService();

      $compact = (object) array();
      $compact->arrShowField = $this->arrShowFieldFrom;

      $compact->ip = $NetworkService->getClientIp();

      return view('_url_shortener.form', (array) $compact);
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

      $urlShortener = new UrlShortener;
      foreach ($input as $key => $v) {
        $urlShortener->$key = $v;
      }
      $urlShortener->created_uid = Auth::id();
      $urlShortener->created_at = date("Y-m-d H:i:s");
      $urlShortener->save();

      DB::commit();
      Log::info('Successful: UrlShortener:store : ', ['data' => $urlShortener]);

      $message = trans('core.message_insert_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: UrlShortener:store :' . $e->getMessage());

      $message = trans('core.message_insert_error');
      $status = 'error';
      $title = 'Error';
    }

    session(['noit_title' => $title]);
    session(['noit_message' => $message]);
    session(['noit_status' => $status]);

    return redirect()->route('url_shortener.index');
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
    $urlshortener = UrlShortener::select($select)->findOrFail($id);

    $compact->urlshortener = $urlshortener;

    return view('_url_shortener.form',$urlshortener, (array) $compact);
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
    $compact->urlshortener = UrlShortener::select($select)->findOrFail($id);

    return view('_url_shortener.show', (array) $compact);
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

      $urlshortener = UrlShortener::find($id);
      foreach ($input as $key => $v) {
        $urlshortener->$key = $v;
      }
      $urlshortener->updated_uid = Auth::id();
      $urlshortener->updated_at = date("Y-m-d H:i:s");
      $urlshortener->save();

      DB::commit();
      Log::info('Successful: UrlShortener:update : ', ['id' => $id, 'data' => $urlshortener]);

      $message = trans('core.message_update_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: UrlShortener:update :' . $e->getMessage());

      $message = trans('core.message_update_error');
      $status = 'error';
      $title = 'Error';
    }

    session(['noit_title' => $title]);
    session(['noit_message' => $message]);
    session(['noit_status' => $status]);

    return redirect()->route('url_shortener.index');
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

      UrlShortener::destroy($id);

      DB::commit();
      Log::info('Successful: urlshortener:destroy : ', ['id' => $id]);

      $message = trans('core.message_del_success');
      $status = 'success';
      $title = 'Success';
    } catch (\Exception $e) {

      DB::rollback();
      Log::error('Error: urlshortener:destroy :' . $e->getMessage());

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
	      $results = $results->orWhere ('url_shortener.short_url', 'LIKE','%'. @$search.'%') ;
	      $results = $results->orWhere ('url_shortener.original_url', 'LIKE','%'. @$search.'%') ;
	      $results = $results->orWhere ('url_shortener.ip', 'LIKE','%'. @$search.'%') ;
	      $results = $results->orWhere ('url_shortener.count', 'LIKE','%'. @$search.'%') ;
	      $results = $results->orWhere ('url_shortener.active', 'LIKE','%'. @$search.'%') ;
        return $results;
  }

  protected function _advSearch($results, $input){
        if(@$input->short_url){
          $results = $results->where('url_shortener.short_url', 'LIKE', "%" .  $input->short_url. "%" );
        }
        if(@$input->original_url){
          $results = $results->where('url_shortener.original_url', 'LIKE', "%" .  $input->original_url. "%" );
        }
        if(@$input->ip){
          $results = $results->where('url_shortener.ip', 'LIKE', "%" .  $input->ip. "%" );
        }
        if(@$input->count_start && @$input->count_end){
          $min = @$input->count_start;
          $max = @$input->count_end;
          $results = $results->whereBetween('url_shortener.count',  [$min, $max]);
        }
        if(@$input->active){
          $results = $results->where('url_shortener.active',  $input->active);
        }
      return $results;
  }
}
