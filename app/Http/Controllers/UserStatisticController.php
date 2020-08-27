<?php

namespace App\Http\Controllers;

use App\Models\Faction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserStatisticController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user, $statistic = '')
    {
        abort_unless(auth()->user()->can('statistics', $user), 403);

        $page = 'statistics';

        if($statistic === '') {
            $statistic = 'money';
        }

        return view('users.show', compact('user', 'page', 'statistic'));
    }
}
