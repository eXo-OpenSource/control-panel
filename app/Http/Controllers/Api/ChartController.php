<?php


namespace App\Http\Controllers\Api;


use App\Faction;
use App\Http\Controllers\Controller;
use App\Services\StatisticService;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function show($name)
    {
        $from = Carbon::now()->subDays(13);
        $to = Carbon::now();


        if ($name === 'factions') {
            return StatisticService::getFactionsActivity($from, $to);
        } elseif (substr($name, 0, 7) === 'faction') {
            $faction = explode(':', $name);
            $faction = Faction::find($faction[1]);
            return StatisticService::getFactionActivity($faction, $from, $to);
        }

        return ['status' => 'Error'];
    }
}
