<?php

namespace App\Http\Controllers\Admin;

use App\Models\TeamspeakIdentity;
use App\Models\User;
use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Helpers\ChannelGroup;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;
use Exo\TeamSpeak\Services\TeamSpeakService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class TeamspeakController extends Controller
{
    protected $teamSpeak;

    public function __construct(TeamSpeakService $teamSpeak)
    {
        $this->teamSpeak = $teamSpeak;
    }

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
     * Remove the specified resource from storage.
     *
     * @param TeamspeakIdentity $teamspeak
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(TeamspeakIdentity $teamspeak)
    {
        Gate::authorize('delete', $teamspeak);

        return view('admin.teamspeak.delete', compact('teamspeak'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TeamspeakIdentity $teamspeak
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function destroy(TeamspeakIdentity $teamspeak)
    {
        Gate::authorize('delete', $teamspeak);

        try {
            $client = $this->teamSpeak->getDatabaseClient($teamspeak->TeamspeakDbId);

            if($client->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
                $serverGroups = $this->teamSpeak->getServerGroups();
                $groups = $client->client->serverGroups();
                foreach($groups->serverGroups as $group) {
                    foreach($serverGroups->groups as $serverGroup) {
                        if($group->serverGroupId === $serverGroup->id) {
                            if(!$serverGroup->saveDb) {
                                break;
                            }

                            $result = $client->client->removeServerGroup($group->serverGroupId);

                            if($result->status !== TeamSpeakResponse::RESPONSE_SUCCESS && $result->message !== 'empty result set') {
                                throw ValidationException::withMessages(['uniqueId' => 'Die Gruppe konnte nicht entfernt werden!']);
                            }
                            break;
                        }
                    }
                }


                $channelGroups = $this->teamSpeak->getChannelGroups();
                $channelGroupId = -1;

                foreach($channelGroups->groups as $group) {
                    if(!$group->saveDb && $group->type === 1) {
                        $channelGroupId = $group->id;
                        break;
                    }
                }

                $groups = $client->client->channelGroups();
                foreach($groups->members as $group) {
                    $result = $client->client->setChannelGroup($group->channelId, $channelGroupId);
                    if($result->status !== TeamSpeakResponse::RESPONSE_SUCCESS && $result->message !== 'empty result set') {
                        throw ValidationException::withMessages(['uniqueId' => 'Die Gruppe konnte nicht entfernt werden!']);
                    }
                }

                $teamspeak->delete();

                if($result->status === 'Success') {
                    Session::flash('alert-success', 'Erfolgreich gelöscht!');
                } else {
                    if($result->message === 'Empty result set') {
                        Session::flash('alert-success', 'Erfolgreich gelöscht aber der Benutzer ist hatte bereits die Gruppe entfernt!');
                    }
                }

                return redirect()->route('admin.teamspeak.index');
            }
            throw ValidationException::withMessages(['uniqueId' => 'TeamSpeak Client konnte nicht gefunden werden!']);

        } catch (TeamSpeakUnreachableException $e) {
            throw ValidationException::withMessages(['uniqueId' => 'TeamSpeak ist nicht erreichbar!']);
        }
    }
}
