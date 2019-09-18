<?php

namespace App\Http\Controllers;

use App\Texture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TextureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $textures = auth()->user()->textures;
        return view('textures.index', compact('textures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehicles = [];

        foreach(config('constants.vehicleNames') as $id => $name)
        {
            array_push($vehicles, [
                'Id' => $id,
                'Name' => $name,
            ]);
        }

        usort($vehicles, function($a, $b) {
            return strcmp($a['Name'], $b['Name']);
        });

        return view('textures.create', compact('vehicles'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Texture $texture
     * @return void
     */
    public function destroy(Texture $texture)
    {
        abort_unless(auth()->user()->can('destroy', $texture), 403);

        if ($texture->isDeleteable()) {
            $texture->delete();
            Session::flash('alert-success', 'Erfolgreich gelöscht!');
            return redirect()->route('textures.index');
        } else {
            Session::flash('alert-danger', 'Diese Textur kann nicht gelöscht werden!');
            return redirect()->route('textures.index');
        }
    }
}
