<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $sortBy = request()->has('sortBy') ? request()->get('sortBy') : null;
        $direction = request()->has('direction') ? request()->get('direction') : null;

        $limit = 50;

        if(request()->has('limit')) {
            if (request()->get('limit') < 0) {
                $limit = 1;
            } else if (request()->get('limit') > 500) {
                $limit = 500;
            } else {
                $limit = request()->get('limit');
            }
        }

        $groups = Group::query();

        if (request()->has('name') && !empty(request()->get('name'))) {
            $groups->where('Name', 'LIKE', '%'.request()->get('name').'%');
        }

        if(!auth()->user() || auth()->user()->Rank < 7) {
            $groups->where('Id', '<>', 1);
            $groups->where('Id', '<>', 2);
        }
        $groups->withCount('members');

        if($sortBy && in_array($sortBy, ['name', 'type', 'members'])) {
            if($sortBy === 'name') {
                $groups->orderBy('Name', $direction === 'desc' ? 'DESC' : 'ASC');
            } elseif($sortBy === 'type') {
                $groups->orderBy('Type', $direction === 'desc' ? 'DESC' : 'ASC');
            } elseif($sortBy === 'members') {
                $groups->orderBy('members_count', $direction === 'desc' ? 'DESC' : 'ASC');
            }
        }

        $groups = $groups->paginate($limit);

        return view('groups.index', compact('groups', 'limit', 'sortBy', 'direction'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Group $group, $page = '')
    {
        abort_unless(array_search($page, ['', 'vehicles', 'logs']) !== false, 404);

        return view('groups.show', compact('group', 'page'));
    }
}
