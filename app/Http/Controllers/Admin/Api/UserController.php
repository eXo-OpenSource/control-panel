<?php


namespace App\Http\Controllers\Admin\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MTAService;
use App\Services\StatisticService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
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
                        return ['status' => 'Error', 'message' => __('Spieler konnte nicht gekickt werden.')];
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
        }

        return ['status' => 'Error', 'message' => __('Zugriff verweigert')];
    }
}
