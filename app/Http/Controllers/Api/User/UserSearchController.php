<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
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
        //abort_unless(auth()->user()->can('seachUser'), 403);
        if (request()->has('name') && !empty(request()->get('name'))) {
            return User::where('Name', 'LIKE', '%'.request()->get('name').'%')->orderBy('LastLogin', 'DESC')->limit(10)->get(['Name', 'Id']);
        }
        return;
    }
}
