<?php

namespace App\Http\Controllers\Event;

use App\Models\Faction;
use App\Models\SantaEvent;
use App\Services\TicketService;
use App\Models\Texture;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use TrayLabs\InfluxDB\Facades\InfluxDB;

class SantaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $head = SantaEvent::where('Part', 'head')->inRandomOrder()->first();
        $body = SantaEvent::where('Part', 'body')->inRandomOrder()->first();
        $legs = SantaEvent::where('Part', 'legs')->inRandomOrder()->first();

        return view('events.santa.index', compact('head', 'body', 'legs'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events.santa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // abort_unless(Gate::allows('admin-rank-2'), 403);

        /*
        $data = $request->validate([
            'part' => 'required',
            'svg' => 'required|in:' . implode(',', array_keys(config('constants.vehicleNames')))
        ]);
        */
        $fileName = uniqid('', true) . ".svg";

        $path = Storage::disk('santa')->put(
            $fileName, $request->get('svg')
        );

        $santa = new SantaEvent();
        $santa->UserId = auth()->user()->Id;
        $santa->Part = request()->get('part');
        $santa->Image = env('APP_URL') .  '/storage/events/santa/' . $fileName;
        $santa->save();

        Session::flash('alert-success', 'Erfolgreich hochgeladen!');
        return redirect()->route('events.santa.index');
    }
}
