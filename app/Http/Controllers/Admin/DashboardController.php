<?php

namespace App\Http\Controllers\Admin;

use App\Services\TicketService;
use App\Texture;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_unless(Gate::allows('admin-rank-2'), 403);

        $textures = Texture::query()->orderBy('Id', 'DESC')->limit(10);
        $tickets = TicketService::getStatistics(true);

        return view('admin.dashboard.index', compact('textures', 'tickets'));
    }
}
