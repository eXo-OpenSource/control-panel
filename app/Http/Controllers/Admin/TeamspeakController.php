<?php

namespace App\Http\Controllers\Admin;

use App\Models\TeamSpeakIdentity;
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

        $teamspeak = TeamSpeakIdentity::query();

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
     * Display the specified resource.
     *
     * @param TeamSpeakIdentity $teamspeak
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(TeamSpeakIdentity $teamspeak)
    {
        Gate::authorize('show', $teamspeak);


        try {
            $channels = $this->teamSpeak->getChannels()->channels;
            $channelGroups = $this->teamSpeak->getChannelGroups()->groups;
            $client = $this->teamSpeak->getDatabaseClient($teamspeak->TeamspeakDbId)->client;
            $info = $client->info()->info;
            $serverGroups = $client->serverGroups()->serverGroups;
            $channelGroupMembers = $client->channelGroups()->members;

            return view('admin.teamspeak.show', compact('teamspeak', 'serverGroups', 'channelGroups', 'channelGroupMembers', 'channels', 'client', 'info'));
        } catch (TeamSpeakUnreachableException $e) {
        }

        abort(500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TeamSpeakIdentity $teamspeak
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(TeamSpeakIdentity $teamspeak)
    {
        Gate::authorize('delete', $teamspeak);

        return view('admin.teamspeak.delete', compact('teamspeak'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TeamSpeakIdentity $teamspeak
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function destroy(TeamSpeakIdentity $teamspeak)
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
                Session::flash('alert-success', 'Erfolgreich gelöscht!');

                return redirect()->route('admin.teamspeak.index');
            }
            else
            {
                $teamspeak->delete();
                Session::flash('alert-success', 'Erfolgreich gelöscht!');
            }

        } catch (TeamSpeakUnreachableException $e) {
            throw ValidationException::withMessages(['uniqueId' => 'TeamSpeak ist nicht erreichbar!']);
        }
    }
}
