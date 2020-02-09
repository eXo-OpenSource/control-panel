<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Character;

class StatisticsController extends Controller
{
    function index()
    {
        $playTime = Character::orderBy('PlayTime', 'desc')->limit(50)->with('user')->get(['Id', 'PlayTime']);
        $bankMoney = BankAccount::where('OwnerType', 1)->with('ownerUser')->orderBy('Money', 'desc')->limit(50)->get();
        return view('statistics.index', compact(['playTime', 'bankMoney']));
    }
}
