<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Warn;
use App\Models\User;
use App\Services\MTAService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class UserWarnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    public function index(User $user)
    {
        $warns = $user->warns;

        $result = [];

        foreach($warns as $warn) {
            array_push($result, [
                'Id' => $warn->Id,
                'UserId' => $warn->userId,
                'Reason' => $warn->reason,
                'AdminId' => $warn->adminId,
                'Admin' => $warn->admin->Name,
                'Created' => Carbon::createFromTimestamp($warn->created)->format('d.m.Y H:i:s'),
                'Expires' => Carbon::createFromTimestamp($warn->expires)->format('d.m.Y H:i:s'),
            ]);
        }

        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return array
     */
    public function store(Request $request, User $user)
    {
        if (Gate::allows('admin-rank-3')) {
            $reason = $request->get('reason');
            $duration = $request->get('duration');

            if (empty($reason)) {
                return ['status' => 'Error', 'message' => __('Bitte gib einen Grund an!')];
            }

            if (empty($duration) && intval('duration') <= 0) {
                return ['status' => 'Error', 'message' => __('Bitte gib eine gültige Dauer ein!')];
            }

            $mtaService = new MTAService();
            $response = $mtaService->addWarn(auth()->user()->Id, $user->Id, $duration, $reason);

            if (!empty($response)) {
                $data = json_decode($response[0]);
                if ($data->status === 'SUCCESS') {
                    return ['status' => 'Success', 'message' => __('Die Verwarnung wurde erfolgreich angelegt.')];
                } else {
                    return ['status' => 'Error', 'message' => __('Die Verwarnung konnte nicht angelegt werden.')];
                }
            }

            return ['status' => 'Error', 'message' => __('Es ist ein interner Fehler aufgetreten.')];
        }

        return ['status' => 'Error', 'message' => __('Zugriff verweigert')];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warn  $warn
     * @return array
     */
    public function destroy(User $user, Warn $warn)
    {
        if (Gate::allows('admin-rank-5')) {
            $mtaService = new MTAService();
            $response = $mtaService->removeWarn(auth()->user()->Id, $user->Id, $warn->Id);

            if (!empty($response)) {
                $data = json_decode($response[0]);
                if ($data->status === 'SUCCESS') {
                    return ['status' => 'Success', 'message' => __('Die Verwarnung wurde erfolgreich gelöscht.')];
                } else {
                    return ['status' => 'Error', 'message' => __('Die Verwarnung konnte nicht gelöscht werden.')];
                }
            }

            return ['status' => 'Error', 'message' => __('Es ist ein interner Fehler aufgetreten.')];
        }

        return ['status' => 'Error', 'message' => __('Zugriff verweigert')];
    }
}
