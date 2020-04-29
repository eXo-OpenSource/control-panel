<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountTeamspeak;
use App\Models\TeamspeakIdentity;
use App\Models\User;
use App\Services\TeamSpeakService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class TeamspeakController extends Controller
{
    protected $teamSpeakService;

    public function __construct(TeamSpeakService $teamSpeakService)
    {
        $this->teamSpeakService = $teamSpeakService;
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

        $result = $this->teamSpeakService->removeServerGroupFromClient(
            $teamspeak->Type === 1 ? env('TEAMSPEAK_ACTIVATED_GROUP') : env('TEAMSPEAK_MUSICBOT_GROUP'),
            $teamspeak->TeamspeakDbId
        );

        if($result->status !== 'Success' && $result->message !== 'Empty result set') {
            throw ValidationException::withMessages(['uniqueId' => 'Die Gruppe konnte nicht entfernt werden!']);
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
}
