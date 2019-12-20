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

        $result = [
            'labels' => [],
            'datasets' => [],
        ];
        /**
            label: 'My Second dataset',
            fill: false,
            lineTension: 0.1,
            backgroundColor: 'rgba(136,71,192,0.4)',
            borderColor: 'rgb(167,76,192)',
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: 'rgb(167,76,192))',
            pointBackgroundColor: 'rgb(167,76,192)',
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: 'rgb(167,76,192)',
            pointHoverBorderColor: 'rgba(220,220,220,1)',
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
         */

        foreach ($factions as $key => $faction) {
            $activity = $faction->getActivity2($from, $to);

            $data = [
                'label' => $faction->Name,
                'borderColor' => $faction->getColor(),
                'backgroundColor' => $faction->getColor(0.2),
                'pointBorderColor' => $faction->getColor(),
                'pointBackgroundColor' => $faction->getColor(),
                'pointHoverBackgroundColor' => $faction->getColor(),
                'data' => [],
            ];

            foreach ($activity as $entry) {
                if ($key === 1) {
                    array_push($result['labels'], $entry->Date);
                }

                array_push($data['data'], $entry->Duration);
            }

            array_push($result['datasets'], $data);
        }

        return $result;
    }

    public static function getFactionActivity(Faction $faction, Carbon $from, Carbon $to)
    {
        $activity = $faction->getActivity2($from, $to);

        return '';
    }
}
