<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\Logs\Punish;
use App\Models\TeamSpeakBan;
use App\Models\TeamSpeakIdentity;
use App\Models\User;
use App\Services\MTAService;
use Carbon\Carbon;
use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;
use Exo\TeamSpeak\Services\TeamSpeakService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserTeamSpeakController extends Controller
{
    /** @var TeamSpeakService */
    protected $teamSpeak;

    public function __construct(TeamSpeakService $teamSpeak)
    {
        $this->teamSpeak = $teamSpeak;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TeamSpeakIdentity  $teamSpeakIdentity
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, TeamSpeakIdentity $teamSpeakIdentity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TeamSpeakIdentity  $teamSpeakIdentity
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, TeamSpeakIdentity $teamSpeakIdentity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return array
     */
    public function update(Request $request, User $user)
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $type = $request->get('type');

        if ($type === 'unban') {
            if (Gate::allows('admin-rank-5')) {
                $reason = $request->get('reason');

                if (empty($reason)) {
                    return ['status' => 'Error', 'message' => __('Bitte gib einen Grund an!')];
                }

                $identities = TeamSpeakIdentity::query()->where('UserId', $user->Id)->get();

                $success = true;
                $successCount = 0;

                foreach($identities as $identity) {
                    try {
                        $client = $this->teamSpeak->getDatabaseClient($identity->TeamspeakDbId);

                        if($client->client) {
                            $bans = $client->client->getBans();

                            foreach($bans->bans as $ban) {
                                if($ban->unban()->status !== TeamSpeakResponse::RESPONSE_SUCCESS) {
                                    $success = false;
                                } else {
                                    $successCount++;
                                }
                            }
                        } else {
                            $success = false;
                        }
                    } catch (TeamSpeakUnreachableException $e) {
                        return ['status' => 'Error', 'message' => __('Es ist ein interner Fehler aufgetreten.')];
                    }
                }

                $punish = new Punish();
                $punish->UserId = $user->Id;
                $punish->AdminId = auth()->user()->Id;
                $punish->Type = 'teamspeakUnban';
                $punish->Reason = $reason;
                $punish->Duration = 0;
                $punish->save();

                if ($success) {
                    $bans = TeamSpeakBan::query()->where('UserId', $user->Id)->get();
                    foreach($bans as $ban) {
                        $ban->delete();
                    }
                    return ['status' => 'Success', 'message' => __('Der Spieler wurde erfolgreich entsperrt.')];
                } else {
                    return ['status' => 'Error', 'message' => __('Spieler konnte nicht entsperrt werden. Es wurden :count/:banned Identitäten entsperrt!', [
                        'count' => $successCount,
                        'banned' => count($identities)
                    ])];
                }
            }
        } elseif ($type === 'ban') {
            if (Gate::allows('admin-rank-3')) {
                $reason = $request->get('reason');
                $duration = $request->get('duration');

                if (empty($reason)) {
                    return ['status' => 'Error', 'message' => __('Bitte gib einen Grund an!')];
                }

                if (empty($duration) && intval('duration') < 0) {
                    return ['status' => 'Error', 'message' => __('Bitte gib eine gültige Dauer ein!')];
                }

                $identities = TeamSpeakIdentity::query()->where('UserId', $user->Id)->get();

                $success = true;
                $bannedIdentities = 0;
                $failedIdentities = 0;

                foreach($identities as $identity) {
                    try {
                        $client = $this->teamSpeak->getDatabaseClient($identity->TeamspeakDbId);

                        if($client->client) {
                            if($client->client->ban($reason, $duration)->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
                                $bannedIdentities++;
                            } else {
                                $failedIdentities++;
                                $success = false;
                            }
                        } else {
                            $failedIdentities++;
                            $success = false;
                        }
                    } catch (TeamSpeakUnreachableException $e) {
                        return ['status' => 'Error', 'message' => __('Es ist ein interner Fehler aufgetreten.')];
                    }
                }

                if($bannedIdentities > 0) {
                    $bans = TeamSpeakBan::query()->where('UserId', $user->Id)->get();
                    foreach($bans as $ban) {
                        if($ban->Duration !== 0) {
                            $ban->delete();
                        }
                    }

                    $ban = new TeamSpeakBan();
                    $ban->UserId = $user->Id;
                    $ban->AdminId = auth()->user()->Id;
                    $ban->ValidUntil = Carbon::now()->addSeconds($duration);
                    $ban->Reason = $reason;
                    $ban->Duration = $duration;
                    $ban->save();

                    $punish = new Punish();
                    $punish->UserId = $user->Id;
                    $punish->AdminId = auth()->user()->Id;
                    $punish->Type = 'teamspeakBan';
                    $punish->Reason = $reason;
                    $punish->Duration = $duration;
                    $punish->save();

                    if ($success) {
                        return ['status' => 'Success', 'message' => __('Der Spieler wurde erfolgreich gesperrt.')];
                    } else {
                        return [
                            'status' => 'Error',
                            'message' => __('Es konnten nicht alle Identitäten gesperrt werden! Es wurden von :banned/:count Identitäten gesperrt!', [
                                'count' => count($identities),
                                'banned' => $bannedIdentities
                            ])
                        ];
                    }
                } else {
                    if(count($identities) === 0) {
                        return ['status' => 'Error', 'message' => __('Spieler konnte nicht gesperrt werden, da keine Identität hinterlegt ist.')];
                    }
                    return ['status' => 'Error', 'message' => __('Spieler konnte nicht gesperrt werden.')];
                }
            }
        }

        return ['status' => 'Error', 'message' => __('Zugriff verweigert')];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TeamSpeakIdentity  $teamSpeakIdentity
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, TeamSpeakIdentity $teamSpeakIdentity)
    {
        //
    }
}
