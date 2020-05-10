<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class VehicleController extends Controller
{
    public function index()
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $vehicles = DB::select('SELECT Model,
            COUNT(Id) AS Count,
            SUM(OwnerType = 1) AS PlayerCount,
            SUM(OwnerType = 2) AS FactionCount,
            SUM(OwnerType = 3) AS CompanyCount,
            SUM(OwnerType = 4) AS GroupCount FROM vrp_vehicles GROUP BY Model ORDER BY Model');

        return view('admin.vehicles.index', compact('vehicles'));
    }
}
