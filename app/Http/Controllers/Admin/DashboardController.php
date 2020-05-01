<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\Faction;
use App\Models\PlayerHistory;
use App\Services\StatisticService;
use App\Services\TicketService;
use App\Models\Texture;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use TrayLabs\InfluxDB\Facades\InfluxDB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_unless(Gate::allows('admin-rank-2'), 403);

        $textures = Texture::query()->orderBy('Id', 'DESC')->limit(10);
        $tickets = TicketService::getStatistics(true);
        $totalTickets = DB::selectOne('SELECT COUNT(TID) AS `Count` FROM `mtickets`')->Count;

        $tickets['datasets'][0] = array_merge($tickets['datasets'][0], ['backgroundColor' => 'transparent', 'borderColor' => 'rgba(255,255,255,.55)', 'pointBackgroundColor' => '#321fdb']);


        // $playerCount = InfluxDB::query('select mean("loggedIn") from user_total WHERE ("branch" = \'release/production\') AND time > now() - 1d GROUP BY time(1h)');
        // $points = $playerCount->getPoints();
        $points = [];

        $playerCountData = ['datasets' => [['data' => [], 'backgroundColor' => 'transparent', 'borderColor' => 'rgba(255,255,255,.55)', 'pointBackgroundColor' => '#39f']], 'labels' => []];
        $lastPlayerCount = 0;
        foreach ($points as $point) {
            array_push($playerCountData['labels'], $point['time']);
            array_push($playerCountData['datasets'][0]['data'], floor($point['mean']));
            $lastPlayerCount = floor($point['mean']);
        }

        $invites = PlayerHistory::orderBy('JoinDate', 'DESC')->with('user', 'inviter')->limit(10)->get();
        $uninvites = PlayerHistory::orderBy('LeaveDate', 'DESC')->with('user', 'uninviter')->limit(10)->get();

        return view('admin.dashboard.index', compact('textures', 'tickets', 'totalTickets', 'playerCountData', 'lastPlayerCount', 'invites', 'uninvites'));
    }
}
