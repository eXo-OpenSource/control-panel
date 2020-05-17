<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Faction;
use App\Models\Group;
use App\Services\MTAService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WhoIsOnlineController extends Controller
{
    public static function getOnlinePlayers(){
        if (!Cache::has('players')) {
            $mtaService = new MTAService();
            $response = $mtaService->getOnlinePlayers();
            if (!empty($response)) {
                $players = $response[0];

                usort($players, function($a, $b) {
                    if ($a->Id === -1 && $b->Id === -1) {
                        return strcmp($a->Name, $b->Name);
                    } else if ($a->Id === -1) {
                        return false;
                    } else if ($b->Id === -1) {
                        return true;
                    }
                    if ($a->Faction == $b->Faction) {
                        if ($a->Company == $b->Company) {
                            if ($a->GroupId == $b->GroupId) {
                                return strcmp($a->Name, $b->Name);
                            } else {
                                return $a->GroupId > $b->GroupId;
                            }
                        } else {
                            return $a->Company > $b->Company;
                        }
                    } else {
                        return $a->Faction > $b->Faction;
                    }
                });

                $factions = [0 => '- Keine -'];
                $companies = [0 => '- Keine -'];
                $factionsCount = [1 => (object)['Name' => 'Staat', 'Count' => 0]];
                $companiesCount = [];
                $groups = [0 => '- Keine -'];
                $groupIDs = [];

                foreach($players as $player) {
                    if ($player->GroupId != 0) {
                        array_push($groupIDs, $player->GroupId);
                    }
                }
                $groupIDs = array_unique($groupIDs);

                foreach(Faction::all() as $faction) {
                    $factions[$faction->Id] = $faction->Name;

                    if ($faction->Id > 3 && $faction->active == 1) {
                        $factionsCount[$faction->Id] = (object)['Name' => $faction->Name, 'Count' => 0];
                    }
                }

                foreach(Company::all() as $company) {
                    $companies[$company->Id] = $company->Name_Short;
                    $companiesCount[$company->Id] = (object)['Name' => $company->Name_Short, 'Count' => 0];
                }

                foreach(Group::query()->whereIn('Id', $groupIDs)->get() as $group) {
                    $groups[$group->Id] = $group->Name;
                }

                $playersNew = [];

                foreach($players as $player) {
                    if ($player->Faction != 0) {
                        if ($player->Faction > 0 && $player->Faction < 4) {
                            $factionsCount[1]->Count++;
                        } else {
                            if (isset($factionsCount[$player->Faction])) {
                                $factionsCount[$player->Faction]->Count++;
                            }
                        }
                    }

                    if ($player->Company != 0) {
                        $companiesCount[$player->Company]->Count++;
                    }

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

                Cache::put('players', (object)[
                    'Players' => $playersNew,
                    'Factions' => $factionsCount,
                    'Companies' => $companiesCount,
                    'Total' => count($playersNew)
                ], now()->addMinutes(5));
            }
        }

        return Cache::get('players', []);
    }
    public static function isPlayerOnline($userId){
        $data = WhoIsOnlineController::getOnlinePlayers();
        $online = false;
        foreach($data->Players as $player) {
            if ($player->Id == $userId) {
                $online = true;
                break;
            }
        }
        return $online;
    }

    function index()
    {
        $data = WhoIsOnlineController::getOnlinePlayers();
        return view('online.index', compact('data'));
    }
}
