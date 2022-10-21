<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\UrlShortener;
use App\Models\UrlLog;
use App\Models\User;

class UrlGenController extends Controller
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
     * get Data Billing, Serial
     *
     * @return \Illuminate\Http\Response
     */
    public function urlGen(Request $request)
    {
        $urlShort = "";
        $reqHeader = (object)$request->header();
        $originalUrl = $request->url;
        $token = $request->token;
        $ip = $request->ip;

        $response = (object) array();
        $response->status = (object) array();

        try {
            DB::beginTransaction();

            if($originalUrl && $token){

            $user = User::select('id')->where('token', $token)->first();

                $urlShort = $this->_genAndCheckDuplicateUrl();
                
                $urlShortener = new UrlShortener;
                $urlShortener->original_url = $originalUrl;
                $urlShortener->short_url = $urlShort;
                $urlShortener->ip = $ip;
                $urlShortener->created_uid = $user->id;
                $urlShortener->created_at = date("Y-m-d H:i:s");
                $urlShortener->save();

                DB::commit();

                $response->status->code = 200;
                $response->status->message = 'Success';
                $response->data = $urlShort;

                Log::info('Successful: UrlShortener:urlGen : ', ['response' => $response]);

            }else{
                $response->status->code = 403;
                $response->status->message = 'error : original url not found';
                $response->data = [];
            }

        } catch (\Exception $e) {

            DB::rollback();

            $response->status->code = 403;
            $response->status->message = 'error : '.$e->getMessage();
            $response->data = [];

            Log::error('Error: UrlShortener:urlGen :' . $e->getMessage());
        }

        return response()->json($response);

    }

    public function urlGenBackEnd(Request $request)
    {
        $urlShort = "";
        $reqHeader = (object)$request->header();
        $originalUrl = $request->url;

        $response = (object) array();
        $response->status = (object) array();

        try {
            DB::beginTransaction();

            if($originalUrl){

                $urlShort = $this->_genAndCheckDuplicateUrl();
                

                $response->status->code = 200;
                $response->status->message = 'Success';
                $response->data = $urlShort;

                Log::info('Successful: UrlShortener:urlGen : ', ['response' => $response]);

            }else{
                $response->status->code = 403;
                $response->status->message = 'error : original url not found';
                $response->data = [];
            }

        } catch (\Exception $e) {

            DB::rollback();

            $response->status->code = 403;
            $response->status->message = 'error : '.$e->getMessage();
            $response->data = [];

            Log::error('Error: UrlShortener:urlGen :' . $e->getMessage());
        }

        return response()->json($response);

    }

    public function getOriginalUrl(Request $request){

        $response = (object) array();
        $response->status = (object) array();

        //dd($request->all());
        try {

            DB::beginTransaction();

            $shortUrl = $request->url;
            $ip = $request->ip;

            $UrlShortener = UrlShortener::select('original_url','id','count')->where('short_url',$shortUrl)->where('active',1);
            
            $chk = $UrlShortener->count();

            if($chk){

                $data = $UrlShortener->first();
                $UrlShortener->increment('count');

                $urlShortener = new UrlLog;
                $urlShortener->original_url = $data->original_url;
                $urlShortener->short_url = $shortUrl;
                $urlShortener->ip = $ip;
                $urlShortener->created_at = date("Y-m-d H:i:s");
                $urlShortener->save();

                DB::commit();

                $response->status->code = 200;
                $response->status->message = 'Success';
                $response->data = $data->original_url;

                Log::info('Successful: UrlShortener:getOriginalUrl : ', ['data' => $response]);
                
            }else{

                $response->status->code = 403;
                $response->status->message = 'url not found';
                $response->data = [];

                Log::info('Successful: UrlShortener:getOriginalUrl : ', ['data' => $response]);

            }

        } catch (\Exception $e) {
            DB::rollback();

            $response->status->code = 403;
            $response->status->message = 'error : '.$e->getMessage();
            $response->data = [];

            Log::error('Error: UrlShortener:getOriginalUrl :' . $e->getMessage());
        }

        return response()->json($response);
    }

    private function _genAndCheckDuplicateUrl($url = null){
        
        if(!$url){
           // $url = $this->StrService->genStr(9);
            $url = $this->genStr(9);
        }

        $isChkUrl = UrlShortener::select('id')->where('short_url',$url)->count();

        Log::error('info: UrlShortener:_genAndCheckDuplicateUrl :' ,['isChkUrl'=>$isChkUrl]);

        if($isChkUrl){
            $this->_genAndCheckDuplicateUrl();
        }
        return $url;
    }

    private function genStr($length=1)
    {

        $srt = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";

        for($i=0;$i<$length;$i++){
            $srt .= $codeAlphabet[mt_rand(0,strlen($codeAlphabet)-1)];
        }

        return $srt;
    }

}