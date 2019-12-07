<?php

namespace App\Http\Controllers\Admin;

use App\Faction;
use App\Services\TicketService;
use App\Texture;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use TrayLabs\InfluxDB\Facades\InfluxDB;

class ForumUserController extends Controller
{
    public function show($forumId)
    {
        $user = User::where('ForumId', $forumId)->first();

        return redirect()->route('users.show', [$user]);
    }
}
