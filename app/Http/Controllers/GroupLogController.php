<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupLogController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Group $group, $log = '')
    {
        $page = 'logs';

        if($log === '') {
            $log = 'group';
        }

        return view('groups.show', compact('group', 'page', 'log'));
    }
}
