<?php

namespace App\Http\Controllers;

use App\Faction;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Faction  $faction
     * @return \Illuminate\Http\Response
     */
    public function show(Faction $faction, $page = '')
    {
        if (Gate::denies('admin-rank-3')) {
            abort_unless($faction->active === 1, 403);
        }

        return view('factions.show', compact('faction', 'page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Faction  $faction
     * @return \Illuminate\Http\Response
     */
    public function edit(Faction $faction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Faction  $faction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faction $faction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Faction  $faction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faction $faction)
    {
        //
    }
}
