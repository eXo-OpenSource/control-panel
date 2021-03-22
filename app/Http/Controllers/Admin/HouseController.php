<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $houses = House::with('user')->with('bank')->get()->toArray();

        usort($houses, function($a, $b) {
            if(!isset($a['user']))
                return -1;

            if(!isset($b['user']))
                return 1;

            return $a['user']['LastLogin'] > $b['user']['LastLogin'] ? 1 : -1;
        });

        return view('admin.houses.index', compact('houses'));
    }
}
