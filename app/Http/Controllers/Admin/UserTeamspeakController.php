<?php

namespace App\Http\Controllers\Admin;

use App\Models\TeamspeakIdentity;
use App\Models\User;
use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;
use Exo\TeamSpeak\Services\TeamSpeakService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class UserTeamspeakController extends Controller
{
    protected $teamSpeak;

    public function __construct(TeamSpeakService $teamSpeak)
    {
        $this->teamSpeak = $teamSpeak;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(User $user)
    {
        Gate::authorize('create', TeamspeakIdentity::class);
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
        Gate::authorize('create', TeamspeakIdentity::class);

        $validatedData = $request->validate([
            'uniqueId' => 'required|unique:App\Models\TeamspeakIdentity,TeamspeakId,NULL,Id,DeletedAt,NULL',
            'type' => 'required|in:1,2',
            'notice' => ''
        ]);

        try {
            $client = $this->teamSpeak->getDatabaseIdFromUniqueId($validatedData['uniqueId']);

            if($client->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
                $teamspeak = new TeamspeakIdentity();
                $teamspeak->UserId = $user->Id;
                $teamspeak->AdminId = auth()->user()->Id;
                $teamspeak->TeamspeakId = $validatedData['uniqueId'];
                $teamspeak->TeamspeakDbId = $client->client->databaseId;
                $teamspeak->Notice = $validatedData['notice'];
                $teamspeak->Type = intval($validatedData['type']);
                $teamspeak->save();


                $result = $client->client->addServerGroup($teamspeak->Type === 1 ? env('TEAMSPEAK_ACTIVATED_GROUP') : env('TEAMSPEAK_MUSICBOT_GROUP'));
                $client->client->setDescription(route('users.show', $user->Id));

                if($result->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
                    Session::flash('alert-success', 'Erfolgreich verknüpft und freigeschaltet!');
                } else {
                    if($result->message === 'duplicate entry') {
                        Session::flash('alert-success', 'Erfolgreich verknüpft aber der Benutzer ist bereits freigeschaltet!');
                    } else {
                        Session::flash('alert-warning', 'Erfolgreich verknüpft aber konnte nicht freigeschaltet werden!');
                    }
                }

                return redirect()->route('users.show.page', [$user, 'teamspeak']);
            } else {
                throw ValidationException::withMessages(['uniqueId' => 'Die eindeutige ID ist dem Server unbekannt! Bitte betrete den Server einmal mit dieser ID!']);
            }
        } catch (TeamSpeakUnreachableException $e) {
            throw ValidationException::withMessages(['uniqueId' => 'Kommunikation mit dem TeamSpeak Server ist derzeit nicht möglich!']);
        }
    }
}
