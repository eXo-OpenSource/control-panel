<?php

namespace App\Http\Controllers;

use App\Models\Faction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FactionStatisticController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Faction  $faction
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Faction $faction, $statistic = '')
    {
        if (Gate::denies('admin-rank-3')) {
            abort_unless($faction->active === 1, 403);
        }

        $page = 'statistics';

        if($statistic === '') {
            $statistic = 'money';
        }

        return view('factions.show', compact('faction', 'page', 'statistic'));
    }
}
