<?php

namespace App\Http\Controllers\Admin;

use App\Models\TeamSpeakBan;
use App\Models\TeamSpeakIdentity;
use App\Models\User;
use Carbon\Carbon;
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
        Gate::authorize('create', TeamSpeakIdentity::class);
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
        Gate::authorize('create', TeamSpeakIdentity::class);

        $validatedData = $request->validate([
            'uniqueId' => 'required|unique:App\Models\TeamSpeakIdentity,TeamspeakId,NULL,Id,DeletedAt,NULL',
            'type' => 'required|in:1,2',
            'notice' => ''
        ]);

        try {
            $client = $this->teamSpeak->getDatabaseIdFromUniqueId($validatedData['uniqueId']);

            if($client->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
                $teamspeak = new TeamSpeakIdentity();
                $teamspeak->UserId = $user->Id;
                $teamspeak->AdminId = auth()->user()->Id;
                $teamspeak->TeamspeakId = $validatedData['uniqueId'];
                $teamspeak->TeamspeakDbId = $client->client->databaseId;
                $teamspeak->Notice = $validatedData['notice'];
                $teamspeak->Type = intval($validatedData['type']);
                $teamspeak->save();

                $result = $client->client->addServerGroup($teamspeak->Type === 1 ? env('TEAMSPEAK_ACTIVATED_GROUP') : env('TEAMSPEAK_MUSICBOT_GROUP'));
                $client->client->setDescription(route('users.show', $user->Id));
                $client->client->removeServerGroup(env('TEAMSPEAK_OLD_ACTIVATED_GROUP'));

                if($result->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
                    $banDuration = -1;
                    $banReason = '';

                    $bans = TeamSpeakBan::query()->where('UserId', $user->Id)->get();
                    foreach($bans as $ban) {
                        if($ban->Duration === 0) {
                            $banDuration = 0;
                            $banReason = $ban->Reason;
                            break;
                        }

                        if($ban->ValidUntil < Carbon::now()) {
                            $ban->delete();
                        } else {
                            $duration = $ban->ValidUntil->diffInSeconds(Carbon::now());
                            if($banDuration < $duration) {
                                $banDuration = $duration;
                                $banReason = $ban->Reason;
                            }
                        }
                    }

                    if($banDuration >= 0) {
                        $client->client->ban($banReason, $banDuration);
                        Session::flash('alert-success', 'Erfolgreich verknüpft, freigeschaltet und gesperrt!');
                    } else {
                        Session::flash('alert-success', 'Erfolgreich verknüpft und freigeschaltet!');
                    }
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
