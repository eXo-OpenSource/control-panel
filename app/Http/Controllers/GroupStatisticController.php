<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupStatisticController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Group $group, $statistic = '')
    {
        $page = 'statistics';

        if($statistic === '') {
            $statistic = 'money';
        }

        return view('groups.show', compact('group', 'page', 'statistic'));
    }
}
