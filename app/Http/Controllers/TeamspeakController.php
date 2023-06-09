<?php

namespace App\Http\Controllers;

use App\Models\TeamSpeakIdentity;
use Illuminate\Http\Request;

class TeamspeakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teamspeakIdentities = auth()->user()->teamspeakIdentities;

        return view('teamspeak.index', compact('teamspeakIdentities'));
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
     * @param  \App\Models\TeamSpeakIdentity  $teamspeakIdentity
     * @return \Illuminate\Http\Response
     */
    public function show(TeamSpeakIdentity $teamspeakIdentity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TeamSpeakIdentity  $teamspeakIdentity
     * @return \Illuminate\Http\Response
     */
    public function edit(TeamSpeakIdentity $teamspeakIdentity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TeamSpeakIdentity  $teamspeakIdentity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeamSpeakIdentity $teamspeakIdentity) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeamSpeakIdentity  $teamspeakIdentity
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeamSpeakIdentity $teamspeakIdentity)
    {
        //
    }
}
