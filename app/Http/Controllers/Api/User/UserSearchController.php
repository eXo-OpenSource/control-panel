<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use App\Http\Controllers\Controller;

class UserSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        if (request()->has('name') && !empty(request()->get('name'))) {
            return User::where('Name', 'LIKE', '%'.request()->get('name').'%')->orderBy('LastLogin', 'DESC')->limit(10)->get(['Name', 'Id']);
        }

        if (request()->has('id') && !empty(request()->get('id'))) {
            return User::where('Id', request()->get('id'))->orderBy('LastLogin', 'DESC')->limit(10)->get(['Name', 'Id']);
        }

        return [];
    }
}
