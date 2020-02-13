<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faction;
use App\Services\TicketService;
use App\Models\Texture;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use TrayLabs\InfluxDB\Facades\InfluxDB;

class ForumUserController extends Controller
{
    public function show($forumId)
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $user = User::where('ForumId', $forumId)->first();

        if (!$user) {
            Session::flash('alert-danger', 'Es existiert kein Account zum gewünschten Forenaccount!');
            return redirect()->route('admin.dashboard.index');
        }

        return redirect()->route('users.show', [$user]);
    }
}
