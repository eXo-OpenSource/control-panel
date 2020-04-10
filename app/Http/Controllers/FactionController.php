<?php

namespace App\Http\Controllers;

use App\Models\Faction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $factions = Faction::query();

        if (Gate::denies('admin-rank-3')) {
            $factions->where('active', 1);
        }

        $factions = $factions->get();

        return view('factions.index', compact('factions'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Faction  $faction
     * @return \Illuminate\Http\Response
     */
    public function show(Faction $faction, $page = '')
    {
        if (Gate::denies('admin-rank-3')) {
            abort_unless($faction->active === 1, 403);
        }

        abort_unless(array_search($page, ['', 'vehicles', 'logs']) !== false, 404);
        return view('factions.show', compact('faction', 'page'));
    }
}
