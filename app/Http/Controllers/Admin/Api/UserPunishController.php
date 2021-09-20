<?php


namespace App\Http\Controllers\Admin\Api;


use App\Http\Controllers\Controller;
use App\Models\Logs\Punish;
use App\Models\User;
use App\Services\MTAService;
use App\Services\StatisticService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class UserPunishController extends Controller
{


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return array
     */
    public function store(Request $request, User $user)
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $type = $request->get('type');
        $reason = $request->get('reason');
        $internal = $request->get('internal');
        $duration = $request->get('duration');

        if(!in_array($type, ['notice', 'teamspeak', 'teamspeakNotice'])) {
            return ['status' => 'Error', 'message' => __('Ungültiger Typ')];
        }

        if(empty($reason)) {
            return ['status' => 'Error', 'message' => __('Bitte gib einen Grund an!')];
        }

        $punish = new Punish();
        $punish->UserId = $user->Id;
        $punish->AdminId = auth()->user()->Id;
        $punish->Type = $type;
        $punish->Reason = $reason;
        $punish->InternalMessage = $internal;
        $punish->Duration = $duration * 3600;
        $punish->Date = new \DateTime();
        $punish->save();

        return ['status' => 'Success', 'message' => __('Erfolgreich gespeichert')];
    }
}
