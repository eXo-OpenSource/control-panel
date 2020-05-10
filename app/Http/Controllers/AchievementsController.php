<?php

namespace App\Http\Controllers;

use App\Models\AchievementCache;
use App\Models\BankAccount;
use App\Models\Character;
use App\Models\Stats;

class AchievementsController extends Controller
{
    function index()
    {
        $achievements = AchievementCache::with('achievement')->get();

        return view('achievements.index', compact('achievements'));
    }
}
