<?php

namespace App\Http\Controllers\Admin;

use App\Faction;
use App\Services\TicketService;
use App\Texture;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

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

        $data = ['datasets' => []];

        foreach ($factions as $faction) {
            $activity = $faction->getActivity(true);
            if (!isset($data['labels'])) {
                $data['labels'] = $activity['labels'];
            }

            array_push($data['datasets'], $activity['datasets'][0]);
        }

        $faction = $factions[0];

        return view('admin.dashboard.index', compact('textures', 'tickets', 'faction', 'data', 'totalTickets'));
    }
}
