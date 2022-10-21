<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function addLogSys($request,$data = [], $req = [])
    {
        
        Log::channel('appsyslog')->info('#log#',
            [
                'username' =>Auth::user()->username,
                'ip'=> $request->ip(),
                'date' =>  date("Y-m-d H:i:s"),
                'uri' => Route::current()->uri,
                'action' => Route::current()->action['as'],
                'parameters' => Route::current()->parameters(),
                'route' => Route::currentRouteName(),
                'methods' => Route::current()->methods,
                'request' => $req,
                'response_code'=> http_response_code(),
                'data' => $data, 
            ]
        );       
    }

    function _numToStr($num) {
        switch ($num) {
          case '|':
            return $this->random_str('II');
            break;
          case 0:
            return $this->random_str('QW');
            break;
          case 1:
            return $this->random_str('ER');
            break;
          case 2:
            return $this->random_str('TY');
            break;
          case 3:
            return $this->random_str('UD');
            break;
          case 4:
            return $this->random_str('OP');
            break;
          case 5:
            return $this->random_str('AS');
            break;
          case 6:
            return $this->random_str('CV');
            break;
          case 7:
            return $this->random_str('BN');
            break;
          case 8:
            return $this->random_str('JM');
            break;
          case 9:
            return $this->random_str('KL');
            break;
        }
      }
    
    function random_str($str){
      return substr(str_shuffle($str),1,1);
    }

    function _StrToNum($str) {

        if($str == 'Q' || $str == 'W'){
          return 0;
        }elseif($str == 'E' || $str == 'R'){
          return 1;
        }elseif($str == 'T' || $str == 'Y'){
          return 2;
        }elseif($str == 'U' || $str == 'D'){
          return 3;
        }elseif($str == 'O' || $str == 'P'){
          return 4;
        }elseif($str == 'A' || $str == 'S'){
          return 5;
        }elseif($str == 'C' || $str == 'V'){
          return 6;
        }elseif($str == 'B' || $str == 'N'){
          return 7;
        }elseif($str == 'J' || $str == 'M'){
          return 8;
        }elseif($str == 'K' || $str == 'L'){
          return 9;
        }elseif($str == 'I'){
            return '|';
        }else{
          return null;
        }
      }

    function rand_uniqid($in, $to_num = false, $pad_up = false, $passKey = null)
    {
      $passKey = Config('app.key');
      $index = "1234567890";
      if ($passKey !== null) {
          // Although this function's purpose is to just make the
          // ID short - and not so much secure,
          // you can optionally supply a password to make it harder
          // to calculate the corresponding numeric ID

          for ($n = 0; $n<strlen($index); $n++) {
              $i[] = substr( $index,$n ,1);
          }

          $passhash = hash('sha256',$passKey);
          $passhash = (strlen($passhash) < strlen($index))
              ? hash('sha512',$passKey)
              : $passhash;

          for ($n=0; $n < strlen($index); $n++) {
              $p[] =  substr($passhash, $n ,1);
          }

          array_multisort($p,  SORT_DESC, $i);
          $index = implode($i);
      }

    $base  = strlen($index);

      if ($to_num) {
        // Digital number  <<--  alphabet letter code
        $in  = strrev($in);
        $out = 0;
        $len = strlen($in) - 1;
        for ($t = 0; $t <= $len; $t++) {
            $bcpow = bcpow($base, $len - $t);
            $out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
        }

        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $out -= pow($base, $pad_up);
            }
        }
        $out = sprintf('%F', $out);
        $out = substr($out, 0, strpos($out, '.'));
      } else {
        // Digital number  -->>  alphabet letter code
        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $in += pow($base, $pad_up);
            }
        }

        $out = "";
        
        for ($t = floor(log($in, $base)); $t >= 0; $t--) {
            $bcp = bcpow($base, $t);
            $a   = floor($in / $bcp) % $base;
            $out = $out . substr($index, $a, 1);
            $in  = $in - ($a * $bcp);
        }
        $out = strrev($out); // reverse
      }
    return $out;
  }

  function format_uuid($uuid="",$type="",$decode = false)
  {
    $out = "";

    if(!$decode){
      // $arrUUID = str_split($uuid, 4);
      // $out = sprintf('%s-%s-%s',$arrUUID[0],$arrUUID[1],$arrUUID[2]);
      $out = $type.'.'.$uuid;
    }else{
      $out = str_replace('-','',$uuid);
    }

    return $out;
  }

  protected function _decode_url($string,$num=false){

    $arrUrl = explode(".",$string);
    $arrUrl[1] = $this->rand_uniqid($arrUrl[1],true,$num);
    return $arrUrl;
  }



  protected function _encUrlCode($string){
    $code = "";
    $arrStr = str_split($string);

    foreach($arrStr AS $k => $v){
      $code .= $this->_numToStr($v);
    }
    
    $c = strlen($code);
    if($c < 6){
      for($i = $c; $i<6 ; $i++){
        $code .= $this->random_str('ZGXH');
      }
    }
    return $code;
  }

  protected function _decUrlCode($string){
    $code = "";
    $arrStr = str_split($string);
  
    foreach($arrStr AS $k => $v){
      $code .= $this->_StrToNum($v);
    }
  
    return $code;
  }
}