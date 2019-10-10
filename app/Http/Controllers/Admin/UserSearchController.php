<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = 50;

        if(request()->has('limit')) {
            if (request()->get('limit') < 0) {
                $limit = 1;
            } else if (request()->get('limit') > 500) {
                $limit = 500;
            }
            $limit = request()->get('limit');
        }

        $users = User::query();

        if (request()->has('name') && !empty(request()->get('name'))) {
            $users->where('Name', 'LIKE', '%'.request()->get('name').'%')->orderBy('LastLogin', 'DESC');
        } else {
            $users->orderBy('Id', 'DESC');
        }

        $users = $users->paginate($limit);

        return view('admin.users.search.index', ['users' => $users, 'limit' => $limit]);
    }
}
