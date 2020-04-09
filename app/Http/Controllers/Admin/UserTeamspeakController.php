<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountTeamspeak;
use App\Models\TeamspeakIdentity;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class UserTeamspeakController extends Controller
{
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
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'uniqueId' => 'required|unique:App\Models\TeamspeakIdentity,TeamspeakId',
            'type' => 'required|in:1,2'
        ]);



        $client = new Client();

        try {
            $result = $client->get(env('TEAMSPEAK_URI') . '/' . env('TEAMSPEAK_SERVER') . '/clientgetdbidfromuid', [
                'headers' => [
                    'x-api-key' => env('TEAMSPEAK_SECRET')
                ],
                'query' => [
                    'cluid' => $validatedData['uniqueId']
                ]
            ]);

            $data = \GuzzleHttp\json_decode($result->getBody()->getContents());
            if($data->status->code === 0 && count($data->body) === 1) {

                $teamspeak = new TeamspeakIdentity();
                $teamspeak->UserId = $user->Id;
                $teamspeak->AdminId = auth()->user()->Id;
                $teamspeak->TeamspeakId = $validatedData['uniqueId'];
                $teamspeak->TeamspeakDbId = intval($data->body[0]->cldbid);
                $teamspeak->Type = intval($validatedData['type']);
                $teamspeak->save();


                $result = $client->get(env('TEAMSPEAK_URI') . '/' . env('TEAMSPEAK_SERVER') . '/servergroupaddclient', [
                    'headers' => [
                        'x-api-key' => env('TEAMSPEAK_SECRET')
                    ],
                    'query' => [
                        'sgid' => $teamspeak->Type === 1 ? env('TEAMSPEAK_ACTIVATED_GROUP') : env('TEAMSPEAK_MUSICBOT_GROUP'),
                        'cldbid' => $teamspeak->TeamspeakDbId
                    ]
                ]);
                $data = \GuzzleHttp\json_decode($result->getBody()->getContents());

                if($data->status->code === 0) {
                    Session::flash('alert-success', 'Erfolgreich verknüpft und freigeschaltet!');
                } else {
                    if($data->status->message === 'duplicate entry') {
                        Session::flash('alert-success', 'Erfolgreich verknüpft aber der Benutzer ist bereits freigeschaltet!');
                    } else {
                        Session::flash('alert-warning', 'Erfolgreich verknüpft aber konnte nicht freigeschaltet werden!');
                    }
                }
            } else {
                throw ValidationException::withMessages(['uniqueId' => 'Die eindeutige ID ist dem Server unbekannt! Bitte betrete den Server einmal mit dieser ID!']);
            }
        } catch (GuzzleException $exception) {
            throw ValidationException::withMessages(['uniqueId' => 'Kommunikation mit dem TeamSpeak Server ist derzeit nicht möglich!']);
        }

        return redirect()->route('users.show.page', [$user, 'teamspeak']);
    }
}
