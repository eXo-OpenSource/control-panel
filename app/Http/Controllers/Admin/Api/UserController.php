<?php


namespace App\Http\Controllers\Admin\Api;


use App\Http\Controllers\Controller;
use App\Models\AccountToSerial;
use App\Models\Character;
use App\Models\DeletedAccount;
use App\Models\User;
use App\Services\ForumService;
use App\Services\MTAService;
use App\Services\StatisticService;
use Carbon\Carbon;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;
use Exo\TeamSpeak\Services\TeamSpeakService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    protected $teamSpeak;
    protected $forum;

    public function __construct(TeamSpeakService $teamSpeak, ForumService $forum)
    {
        $this->teamSpeak = $teamSpeak;
        $this->forum = $forum;
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

        if ($type === 'kick') {
            if (Gate::allows('admin-rank-3')) {
                $reason = $request->get('reason');

                if (empty($reason)) {
                    return ['status' => 'Error', 'message' => __('Bitte gib einen Grund an!')];
                }

                $mtaService = new MTAService();
                $response = $mtaService->kickPlayer(auth()->user()->Id, $user->Id, $reason);

                if (!empty($response)) {
                    $data = json_decode($response[0]);
                    if ($data->status === 'SUCCESS') {
                        return ['status' => 'Success', 'message' => __('Der Spieler wurde erfolgreich gekickt.')];
                    } else {
                        return ['status' => 'Error', 'message' => __('Spieler konnte nicht gekickt werden (:type).', ['type' => $data->error])];
                    }
                }

                return ['status' => 'Error', 'message' => __('Es ist ein interner Fehler aufgetreten.')];
            }
        } elseif ($type === 'unban') {
            if (Gate::allows('admin-rank-5')) {
                $reason = $request->get('reason');

                if (empty($reason)) {
                    return ['status' => 'Error', 'message' => __('Bitte gib einen Grund an!')];
                }

                $mtaService = new MTAService();
                $response = $mtaService->unbanPlayer(auth()->user()->Id, $user->Id, $reason);

                if (!empty($response)) {
                    $data = json_decode($response[0]);
                    if ($data->status === 'SUCCESS') {
                        return ['status' => 'Success', 'message' => __('Der Spieler wurde erfolgreich entsperrt.')];
                    } else {
                        return ['status' => 'Error', 'message' => __('Spieler konnte nicht entsperrt werden.')];
                    }
                }

                return ['status' => 'Error', 'message' => __('Es ist ein interner Fehler aufgetreten.')];
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

                $mtaService = new MTAService();
                $response = $mtaService->banPlayer(auth()->user()->Id, $user->Id, intval($duration), $reason);

                if (!empty($response)) {
                    $data = json_decode($response[0]);
                    if ($data->status === 'SUCCESS') {
                        return ['status' => 'Success', 'message' => __('Der Spieler wurde erfolgreich gesperrt.')];
                    } else {
                        return ['status' => 'Error', 'message' => __('Spieler konnte nicht gesperrt werden.')];
                    }
                }

                return ['status' => 'Error', 'message' => __('Es ist ein interner Fehler aufgetreten.')];
            }
        } elseif ($type === 'unprison') {
            if (Gate::allows('admin-rank-4')) {
                $reason = $request->get('reason');

                if (empty($reason)) {
                    return ['status' => 'Error', 'message' => __('Bitte gib einen Grund an!')];
                }

                $mtaService = new MTAService();
                $response = $mtaService->unprisonPlayer(auth()->user()->Id, $user->Id, $reason);

                if (!empty($response)) {
                    $data = json_decode($response[0]);
                    if ($data->status === 'SUCCESS') {
                        return ['status' => 'Success', 'message' => __('Der Spieler wurde erfolgreich aus dem Pirson entfernt.')];
                    } else {
                        return ['status' => 'Error', 'message' => __('Spieler konnte nicht aus dem Pirson entfernt werden.')];
                    }
                }

                return ['status' => 'Error', 'message' => __('Es ist ein interner Fehler aufgetreten.')];
            }
        } elseif ($type === 'prison') {
            if (Gate::allows('admin-rank-3')) {
                $reason = $request->get('reason');
                $duration = $request->get('duration');

                if (empty($reason)) {
                    return ['status' => 'Error', 'message' => __('Bitte gib einen Grund an!')];
                }

                if (empty($duration) && intval('duration') < 1) {
                    return ['status' => 'Error', 'message' => __('Bitte gib eine gültige Dauer ein!')];
                }

                $mtaService = new MTAService();
                $response = $mtaService->prisonPlayer(auth()->user()->Id, $user->Id, intval($duration), $reason);

                if (!empty($response)) {
                    $data = json_decode($response[0]);
                    if ($data->status === 'SUCCESS') {
                        return ['status' => 'Success', 'message' => __('Der Spieler wurde erfolgreich in das Prison gesteckt.')];
                    } else {
                        return ['status' => 'Error', 'message' => __('Spieler konnte nicht in das Prison gesteckt werden.')];
                    }
                }

                return ['status' => 'Error', 'message' => __('Es ist ein interner Fehler aufgetreten.')];
            }
        } elseif ($type === 'delete') {
            if(in_array($user->Id, [1, 2, 13, 20, 37, 220, 404, 1194, 4123]))
                return ['status' => 'Error', 'message' => __('Sir are you drunk?')];
            // TODO: Delete em
            if(auth()->user()->Id !== 4123)
                return ['status' => 'Error', 'message' => __('Es ist ein interner Fehler aufgetreten.')];

            $mtaService = new MTAService();
            $mtaService->kickPlayer(auth()->user()->Id, $user->Id, 'Account deletion');

            $userId = $user->Id;
            $forumId = $user->ForumId;

            $serials = AccountToSerial::query()->where('PlayerId', $userId);

            // Adding the serial to banned users

            foreach($serials as $serial)
            {
                if(DeletedAccount::query()->where('Serial', $serial->Serial)->exists())
                {
                    $deletedAccount = new DeletedAccount();
                    $deletedAccount->Serial = $serial->Serial;
                    $deletedAccount->save();
                }
            }

            // Delete teamspeak identities

            foreach($user->teamSpeakIdentities as $teamSpeakIdentity)
            {
                $client = $this->teamSpeak->getDatabaseClient($teamSpeakIdentity->TeamspeakDbId);

                if($client->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
                    $serverGroups = $this->teamSpeak->getServerGroups();
                    $groups = $client->client->serverGroups();
                    foreach ($groups->serverGroups as $group) {
                        foreach ($serverGroups->groups as $serverGroup) {
                            if ($group->serverGroupId === $serverGroup->id) {
                                if (!$serverGroup->saveDb) {
                                    break;
                                }

                                $client->client->removeServerGroup($group->serverGroupId);
                                break;
                            }
                        }
                    }


                    $channelGroups = $this->teamSpeak->getChannelGroups();
                    $channelGroupId = -1;

                    foreach ($channelGroups->groups as $group) {
                        if (!$group->saveDb && $group->type === 1) {
                            $channelGroupId = $group->id;
                            break;
                        }
                    }

                    $groups = $client->client->channelGroups();
                    foreach ($groups->members as $group) {
                        $client->client->setChannelGroup($group->channelId, $channelGroupId);
                    }
                }

                $teamSpeakIdentity->delete();
            }

            // Delete forum account
            $this->forum->deleteUser($forumId);

            // Delete ingame account
            $user->delete();

            // Cleanup reamining bits from the user in the database
            $character = Character::find($userId);

            if($character)
                $character->delete();

            return ['status' => 'Success', 'message' => __('Brudi der ist jetzt weg.')];
        }

        return ['status' => 'Error', 'message' => __('Zugriff verweigert')];
    }
}
