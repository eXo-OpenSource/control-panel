<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Character;
use App\Models\Stats;

class StatisticsController extends Controller
{
    function index()
    {
        $playTime = Character::orderBy('PlayTime', 'desc')->limit(50)->with('user')->get(['Id', 'PlayTime']);
        $bankMoney = BankAccount::where('OwnerType', 1)->orderBy('Money', 'desc')->with('owner')->limit(50)->get();
        $fishes = Stats::orderBy('FishCaught', 'desc')->limit(50)->with('user')->get();
        $driven = Stats::orderBy('Driven', 'desc')->limit(50)->with('user')->get();

        $playTimeMyPosition = null;
        $bankMoneyMyPosition = null;
        $fishesMyPosition = null;
        $drivenMyPosition = null;

        if(auth()->user()) {
            $playTimeMyPosition = Character::where('PlayTime', '>', auth()->user()->character->PlayTime)->orderBy('PlayTime', 'desc')->count();
            $bankMoneyMyPosition = BankAccount::where('Money', '>', auth()->user()->character->bank->Money)->where('OwnerType', 1)->orderBy('Money', 'desc')->count();
            $fishesMyPosition = Stats::where('FishCaught', '>', auth()->user()->character->stats->FishCaught)->orderBy('FishCaught', 'desc')->count();
            $drivenMyPosition = Stats::where('Driven', '>', auth()->user()->character->stats->Driven)->orderBy('Driven', 'desc')->count();
        }

        return view('statistics.index', compact(
            'playTime', 'bankMoney', 'fishes', 'driven',
            'playTimeMyPosition', 'bankMoneyMyPosition', 'fishesMyPosition', 'drivenMyPosition'
        ));
    }
}
