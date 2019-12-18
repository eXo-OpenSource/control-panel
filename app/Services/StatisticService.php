<?php


namespace App\Services;


use App\Faction;
use Carbon\Carbon;

class StatisticService
{
    public static function getFactionsActivity(Carbon $from, Carbon $to)
    {
        /** @var Faction[] $factions */
        $factions = Faction::where('active', 1)->get();

        foreach ($factions as $faction) {
            $activity = $faction->getActivity2($from, $to);

        }

        return '';
    }

    public static function getFactionActivity(Faction $faction, Carbon $from, Carbon $to)
    {
        $activity = $faction->getActivity2($from, $to);

        return '';
    }
}
