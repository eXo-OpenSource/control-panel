<?php

namespace App\Http\Controllers;

use App\Models\Texture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class TextureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_unless(Gate::allows('admin-rank-2'), 403);

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
        abort_unless(Gate::allows('admin-rank-2'), 403);

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
        abort_unless(Gate::allows('admin-rank-2'), 403);

        $data = $request->validate([
            'name' => 'required',
            'vehicle' => 'required|in:' . implode(',', array_keys(config('constants.vehicleNames'))),
            'type' => 'required|in:0,1',
            'texture' => 'required|image|dimensions:max_width=600,max_height=600|max:200'
        ]);

        $path = Storage::disk('textures')->put(
            '', $request->file('texture')
        );

        $texture = new Texture();
        $texture->UserId = auth()->user()->Id;
        $texture->Name = $data['name'];
        $texture->Image = env('APP_URL') .  '/storage/textures/' . $path;
        $texture->Model = $data['vehicle'];
        $texture->Status = 0;
        $texture->Public = $data['type'];
        $texture->Admin = 0;
        $texture->Date = new \DateTime();
        $texture->Earnings = 0;
        $texture->save();

        Session::flash('alert-success', 'Erfolgreich hochgeladen!');
        return redirect()->route('textures.index');
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
     * @param \App\Models\Texture $texture
     * @return void
     */
    public function destroy(Texture $texture)
    {
        abort_unless(Gate::allows('admin-rank-2'), 403);

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
