<?php


namespace App\Http\Controllers\Admin\Api;


use App\Http\Controllers\Controller;
use App\Services\StatisticService;
use Carbon\Carbon;

class FactionController extends Controller
{
    public function index()
    {
        $result = StatisticService::getFactionsActivity(Carbon::now()->subDays(13), Carbon::now());
        //dd($result);
        return $result;
    }
}
