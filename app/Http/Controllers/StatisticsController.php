<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Character;
use App\Models\Logs\GangwarStatistic;
use App\Models\Stats;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    function index()
    {
        $playTime = Character::orderBy('PlayTime', 'desc')->limit(50)->with('user')->get(['Id', 'PlayTime']);
        $bankMoney = BankAccount::where('OwnerType', 1)->orderBy('Money', 'desc')->with('owner')->limit(50)->get();
        $fishes = Stats::orderBy('FishCaught', 'desc')->limit(50)->with('user')->get();
        $driven = Stats::orderBy('Driven', 'desc')->limit(50)->with('user')->get();

        $playTimeMyPosition = null;
        $bankMoneyMyPosition = null;
        $fishesMyPosition = null;
        $drivenMyPosition = null;

        if(auth()->user()) {
            $playTimeMyPosition = Character::where('PlayTime', '>', auth()->user()->character->PlayTime)->orderBy('PlayTime', 'desc')->count();
            $bankMoneyMyPosition = BankAccount::where('Money', '>', auth()->user()->character->bank->Money)->where('OwnerType', 1)->orderBy('Money', 'desc')->count();
            $fishesMyPosition = Stats::where('FishCaught', '>', auth()->user()->character->stats->FishCaught)->orderBy('FishCaught', 'desc')->count();
            $drivenMyPosition = Stats::where('Driven', '>', auth()->user()->character->stats->Driven)->orderBy('Driven', 'desc')->count();
        }

        $damageQuery = 'SELECT gs.UserId, ac.Name, SUM(gs.Amount) AS Amount FROM vrpLogs_GangwarStatistics gs INNER JOIN ' . config('database.connections.mysql.database') . '.vrp_account ac ON gs.UserId = ac.Id WHERE gs.Date BETWEEN ? AND ? AND gs.Type = ? GROUP BY gs.UserId ORDER BY Amount DESC LIMIT 10';
        $killsQuery = 'SELECT gs.UserId, ac.Name, COUNT(gs.Id) AS Amount FROM vrpLogs_GangwarStatistics gs INNER JOIN ' . config('database.connections.mysql.database') . '.vrp_account ac ON gs.UserId = ac.Id WHERE gs.Date BETWEEN ? AND ? AND gs.Type = ? GROUP BY gs.UserId ORDER BY Amount DESC LIMIT 10';

        $damageCurrentWeek = DB::connection('mysql_logs')->select($damageQuery, [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek(), 'Damage']);
        $killsCurrentWeek = DB::connection('mysql_logs')->select($killsQuery, [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek(), 'Kill']);

        $damageLastWeek = DB::connection('mysql_logs')->select($damageQuery, [Carbon::now()->subDays(7)->startOfWeek(), Carbon::now()->subDays(7)->endOfWeek(), 'Damage']);
        $killsLastWeek = DB::connection('mysql_logs')->select($killsQuery, [Carbon::now()->subDays(7)->startOfWeek(), Carbon::now()->subDays(7)->endOfWeek(), 'Kill']);


        return view('statistics.index', compact(
            'playTime', 'bankMoney', 'fishes', 'driven',
            'playTimeMyPosition', 'bankMoneyMyPosition', 'fishesMyPosition', 'drivenMyPosition',
            'damageCurrentWeek', 'killsCurrentWeek', 'damageLastWeek', 'killsLastWeek'
        ));
    }
}
