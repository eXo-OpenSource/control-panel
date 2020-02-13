<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserLogController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, $log = '')
    {
        $page = 'logs';

        if($log === '') {
            $log = 'money';
            if(auth()->user()->Rank >= 3) {
                $log = 'punish';
            }
        }

        abort_unless(auth()->user()->can('show', $user), 403);
        // abort_unless(array_search($log, ['', 'vehicles', 'history', 'logs']) !== false, 404);
        $banned = $user->isBanned();
        return view('users.show', compact('user', 'page', 'log', 'banned'));
    }
}
