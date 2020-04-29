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
        abort_unless(Gate::allows('admin-rank-3'), 403);

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

        $users = User::query();

        if (!empty(request()->get('name')) ||
            (auth()->user()->Rank >= 3 && (!empty(request()->get('serial')) || !empty(request()->get('ip'))))
           ) {

            if(!empty(request()->get('name'))) {
                $users->where('Name', 'LIKE', '%'.request()->get('name').'%');
                $appends['name'] = request()->get('name');
            }

            if(auth()->user()->Rank >= 3) {
                if(!empty(request()->get('serial'))) {
                    $users->where('LastSerial', 'LIKE', '%'.request()->get('serial').'%');
                    $appends['serial'] = request()->get('serial');
                }

                if(!empty(request()->get('ip'))) {
                    $users->where('LastIP', 'LIKE', '%'.request()->get('ip').'%');
                    $appends['ip'] = request()->get('ip');
                }
            }

            $users->orderBy('LastLogin', 'DESC');
        } else {
            $users->orderBy('Id', 'DESC');
        }

        $users = $users->paginate($limit);

        return view('users.search.index', ['users' => $users, 'limit' => $limit, 'appends' => $appends]);
    }
}
