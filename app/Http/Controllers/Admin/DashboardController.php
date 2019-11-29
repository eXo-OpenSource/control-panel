<?php

namespace App\Http\Controllers\Admin;

use App\Faction;
use App\Services\TicketService;
use App\Texture;
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

        $factions = Faction::where('active', 1)->get();

        $factionData = ['datasets' => []];

        foreach ($factions as $faction) {
            $activity = $faction->getActivity(true);
            if (!isset($factionData['labels'])) {
                $factionData['labels'] = $activity['labels'];
            }

            array_push($factionData['datasets'], $activity['datasets'][0]);
        }



        $playerCount = InfluxDB::query('select mean("loggedIn") from user_total WHERE ("branch" = \'release/production\') AND time > now() - 200m GROUP BY time(10m)');
        $points = $playerCount->getPoints();

        $playerCountData = ['datasets' => [['data' => [], 'backgroundColor' => 'transparent', 'borderColor' => 'rgba(255,255,255,.55)', 'pointBackgroundColor' => '#39f']], 'labels' => []];
        $lastPlayerCount = 0;
        foreach ($points as $point) {
            array_push($playerCountData['labels'], $point['time']);
            array_push($playerCountData['datasets'][0]['data'], $point['mean']);
            $lastPlayerCount = floor($point['mean']);
        }

        return view('admin.dashboard.index', compact('textures', 'tickets', 'factionData', 'totalTickets', 'playerCountData', 'lastPlayerCount'));
    }
}
