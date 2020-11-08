<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\Faction;

class FactionController extends Controller
{
    public function index()
    {
        $data = [];

        foreach(Faction::where('active', 1)->get() as $faction) {
            array_push($data, [
                'name' => $faction->Name,
                'membersCount' => $faction->membersCount()
            ]);
        }

        return $data;
    }

    public function show(Faction $faction)
    {
        $data = [
            'name' => $faction->Name,
            'members' => []
        ];

        foreach($faction->members()->with('user')->orderBy('FactionRank', 'DESC')->get() as $user) {
            array_push($data['members'], [
                'name' => $user->user->Name,
                'rank' => $user->FactionRank
            ]);
        }

        return $data;
    }
}
