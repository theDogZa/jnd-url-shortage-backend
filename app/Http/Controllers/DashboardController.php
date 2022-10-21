<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use App\Models\UrlLog;

class DashboardController extends Controller
{
  public function __construct()
  {

    Cache::flush();
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header('Content-Type: text/html');

  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function dashboard()
  {

    $compact = (object) [];

    $urlLog = UrlLog::select('*');
    
    //----- get by date
    $dateNow = date('Y-m-d');
    $sd = date_create($dateNow . "00:00:01");
    $sDate = date_format($sd, "Y-m-d H:i:s");
    $ed = date_create($dateNow . "23:59:59");
    $eDate = date_format($ed, "Y-m-d H:i:s");
    
    $dataByDate = $urlLog->whereBetween('url_logs.created_at',  [$sDate, $eDate])->orderBy('created_at','ASC')->get();

    $dTime = [];
    $label = [];

    $h = 0;
    while ($h < 24) {
        $key = date('H', strtotime(date('Y-m-d') . ' + ' . $h . ' hours'));
        $lab = date('H:i', strtotime(date('Y-m-d') . ' + ' . $h . ' hours'));
        $dTime[$key] = 0;
        $label[] = $lab;
        $h++;
    }

    foreach ($dataByDate as $d){
      $timeData = date('H',strtotime($d->created_at));
      $dTime[$timeData] += 1;
    }
    $newDTime = [];
    foreach ($dTime as $d){
      $newDTime[] = $d;
    }

    $compact->chartTime['data'] = $newDTime;
    $compact->chartTime['label'] = $label;


    return view('dashboard', (array) $compact);
  }
}
