<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class LogController extends Controller
{
    public function show($log = '')
    {
        if($log === '') {
            $log = 'punish';
        }
        abort_unless(Gate::allows('admin-rank-3'), 403);

        return view('admin.logs.show', compact('log'));
    }
}
