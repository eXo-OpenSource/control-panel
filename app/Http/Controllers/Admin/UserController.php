<?php

namespace App\Http\Controllers\Admin;

use App\Services\MTAService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $type = $request->get('type');

        if ($type === 'kick') {
            $reason = $request->get('reason');

            if (empty('reason')) {
                return redirect()->route('users.show', [$user->Id]);
            }

            $mtaService = new MTAService();
            $response = $mtaService->kickPlayer(auth()->user()->Id, $user->Id, $reason);

            if (!empty($response)) {
                $data = json_decode($response[0]);
                if ($data->status === 'SUCCESS') {
                    Session::flash('alert-success', 'Erfolgreich gekickt!');
                } else {
                    Session::flash('alert-danger', 'Spieler konnte nicht gekickt werden!');
                }
            } else {
                Session::flash('alert-danger', 'Interner Fehler!');
            }

            return redirect()->route('users.show', [$user->Id]);
        }
    }
}
