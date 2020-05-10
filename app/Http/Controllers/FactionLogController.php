<?php

namespace App\Http\Controllers;

use App\Models\Faction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FactionLogController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Faction  $faction
     * @return \Illuminate\Http\Response
     */
    public function show(Faction $faction, $log = '')
    {
        if (Gate::denies('admin-rank-3')) {
            abort_unless($faction->active === 1, 403);
        }

        $page = 'logs';

        if($log === '') {
            $log = 'faction';
        }

        return view('factions.show', compact('faction', 'page', 'log'));
    }
}
