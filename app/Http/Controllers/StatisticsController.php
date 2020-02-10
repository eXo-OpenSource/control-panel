<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Character;
use App\Stats;

class StatisticsController extends Controller
{
    function index()
    {
        $playTime = Character::orderBy('PlayTime', 'desc')->limit(50)->with('user')->get(['Id', 'PlayTime']);
        $bankMoney = BankAccount::where('OwnerType', 1)->orderBy('Money', 'desc')->limit(50)->get();
        $fishes = Stats::orderBy('FishCaught', 'desc')->limit(50)->with('user')->get();
        $driven = Stats::orderBy('Driven', 'desc')->limit(50)->with('user')->get();

        return view('statistics.index', compact(['playTime', 'bankMoney', 'fishes', 'driven']));
    }
}
