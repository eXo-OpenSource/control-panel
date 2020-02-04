<?php

namespace App\Http\Controllers;

use App\Company;
use App\Faction;
use App\Group;
use App\Services\MTAService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WhoIsOnlineController extends Controller
{
    function index()
    {
        if (!Cache::has('players')) {
            $mtaService = new MTAService();
            $response = $mtaService->getOnlinePlayers();
            if (!empty($response)) {
                $players = $response[0];

                usort($players, function($a, $b) {
                    if ($a->Faction == $b->Faction) {
                        if ($a->Company == $b->Company) {
                            return strcmp($a->Name, $b->Name);
                        } else {
                            return $a->Company > $b->Company;
                        }
                    } else {
                        return $a->Faction > $b->Faction;
                    }
                });

                $factions = [0 => '- Keine -'];
                $companies = [0 => '- Keine -'];
                $groups = [0 => '- Keine -'];
                $groupIDs = [];

                foreach($players as $player) {
                    if ($player->GroupId != 0) {
                        array_push($groupIDs, $player->GroupId);
                    }
                }
                $groupIDs = array_unique($groupIDs);

                foreach(Faction::all() as $faction) {
                    $factions[$faction->Id] = $faction->Name_Short;
                }

                foreach(Company::all() as $company) {
                    $companies[$company->Id] = $company->Name_Short;
                }

                foreach(Group::query()->whereIn('Id', $groupIDs)->get() as $group) {
                    $groups[$group->Id] = $group->Name;
                }

                $playersNew = [];

                foreach($players as $player) {
                    array_push($playersNew, (object)[
                        'Id' => $player->Id,
                        'Name' => $player->Name,
                        'FactionId' => $player->Faction,
                        'FactionName' => $factions[$player->Faction],
                        'CompanyId' => $player->Company,
                        'CompanyName' => $companies[$player->Company],
                        'GroupId' => $player->GroupId,
                        'GroupName' => $groups[$player->GroupId] ?? '- Keine -',
                        'PlayTime' => $player->PlayTime
                    ]);
                }

                Cache::put('players', $playersNew, now()->addMinutes(5));
            }
        }

        $players = Cache::get('players', []);

        return view('online.index', compact('players'));
    }
}
