<?php


namespace App\Http\Controllers\Admin\Api;


use App\Http\Controllers\Controller;
use App\Models\Logs\Punish;
use App\Models\Logs\PunishLog;
use App\Models\User;
use App\Services\MTAService;
use App\Services\StatisticService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class PunishController extends Controller
{

    /**
     * @param Punish $punish
     * @return array
     */
    public function show(Punish $punish)
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        return [
            'punish' => $punish,
            'rank' => auth()->user()->Rank,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return array
     */
    public function update(Request $request, Punish $punish)
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $duration = $request->get('duration');
        $internal = $request->get('internal');
        $reason = $request->get('reason');
        $deleted = $request->get('deleted');

        $punishLog = new PunishLog();
        $punishLog->PunishId = $punish->Id;
        $punishLog->AdminId = auth()->user()->Id;
        $punishLog->ReasonPrev = $punish->Reason;
        $punishLog->DurationPrev = $punish->Duration;
        $punishLog->InternalMessagePrev = $punish->InternalMessage;
        $punishLog->DeletedAtPrev = $punish->DeletedAt;
        $punishLog->Date = new \DateTime();

        $punish->InternalMessage = $internal;
        $punish->Reason = $reason;
        $punish->Duration = $duration;

        if(Gate::allows('admin-rank-5')) {
            if($deleted) {
                if($punish->DeletedAt == null) {
                    $punish->DeletedAt = new \DateTime();
                }
            } else {
                if($punish->DeletedAt != null) {
                    $punish->DeletedAt = null;
                }
            }
        }

        $punishLog->Reason = $punish->Reason;
        $punishLog->Duration = $punish->Duration;
        $punishLog->InternalMessage = $punish->InternalMessage;
        $punishLog->DeletedAt = $punish->DeletedAt;
        $punishLog->save();
        $punish->save();

        return ['status' => 'Success', 'message' => __('Erfolgreich gespeichert')];
    }
}
