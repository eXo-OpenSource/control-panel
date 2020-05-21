<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserSearchController extends Controller
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
        $appends = [];

        $limit = 50;

        if(request()->has('limit') && is_numeric(request()->get('limit'))) {
            if (request()->get('limit') < 0) {
                $limit = 1;
            } else if (request()->get('limit') > 500) {
                $limit = 500;
            } else {
                $limit = request()->get('limit');
            }
            $appends['limit'] = $limit;
        }


        $users = User::join('character', 'account.Id', '=', 'character.Id'); // ->with('character');
        $hasFilter = false;

        if (!empty(request()->get('name')) ||
            (auth()->user()->Rank >= 3 && (!empty(request()->get('serial')) || !empty(request()->get('ip'))))
           ) {

            if(!empty(request()->get('name'))) {
                $users->where('account.Name', 'LIKE', '%'.request()->get('name').'%');
                $appends['name'] = request()->get('name');
            }

            if(auth()->user()->Rank >= 3) {
                if(!empty(request()->get('serial'))) {
                    $users->where('account.LastSerial', 'LIKE', '%'.request()->get('serial').'%');
                    $appends['serial'] = request()->get('serial');
                }

                if(!empty(request()->get('ip'))) {
                    $users->where('account.LastIP', 'LIKE', '%'.request()->get('ip').'%');
                    $appends['ip'] = request()->get('ip');
                }
            }

            $hasFilter = true;
        }

        if($sortBy && in_array($sortBy, ['name', 'playTime'])) {
            if($sortBy === 'name') {
                $users->orderBy('account.Name', $direction === 'desc' ? 'DESC' : 'ASC');
            } elseif($sortBy === 'playTime') {
                $users->orderBy('character.PlayTime', $direction === 'desc' ? 'DESC' : 'ASC');
            } elseif($hasFilter) {
                $users->orderBy('account.LastLogin', 'DESC');
            } else {
                $users->orderBy('account.Id', 'DESC');
            }
        } else {
            if($hasFilter) {
                $users->orderBy('account.LastLogin', 'DESC');
            } else {
                $users->orderBy('account.Id', 'DESC');
            }
        }


        $users = $users->paginate($limit);

        return view('users.search.index', ['users' => $users, 'limit' => $limit, 'appends' => $appends, 'sortBy' => $sortBy, 'direction' => $direction]);
    }
}
