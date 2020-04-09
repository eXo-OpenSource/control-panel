<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountTeamspeak;
use App\Models\TeamspeakIdentity;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class TeamspeakController extends Controller
{
    public function index()
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $limit = 50;

        if(request()->has('limit')) {
            if (request()->get('limit') < 0) {
                $limit = 1;
            } else if (request()->get('limit') > 500) {
                $limit = 500;
            }
            $limit = request()->get('limit');
        }

        $teamspeak = TeamspeakIdentity::query();

        if (request()->has('id') && !empty(request()->get('id'))) {
            $teamspeak->where('TeamSpeakId', 'LIKE', '%'.request()->get('id').'%')->orderBy('Id', 'DESC');
        } else {
            $teamspeak->orderBy('Id', 'DESC');
        }

        $teamspeak = $teamspeak->paginate($limit);

        return view('admin.teamspeak.index', ['teamspeak' => $teamspeak, 'limit' => $limit]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(User $user)
    {
        return view('admin.teamspeak.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*
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
        */
        return '';
    }
}
