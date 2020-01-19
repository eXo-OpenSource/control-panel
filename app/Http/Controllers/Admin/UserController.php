<?php

namespace App\Http\Controllers\Admin;

use App\Services\MTAService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            $mtaService->kickPlayer(auth()->user()->Id, $user->Id, $reason);

            return redirect()->route('users.show', [$user->Id]);
        }
    }
}
