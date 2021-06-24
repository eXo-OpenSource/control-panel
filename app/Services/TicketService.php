<?php


namespace App\Services;


use Illuminate\Support\Facades\DB;

class TicketService
{

    public static function getStatistics($chart = false)
    {
        $currentDate = date("Y-m-d", strtotime("-14 days"));;
        $toDate = date("Y-m-d");

        $dates = [];

        array_push($dates, $currentDate);

        while ($currentDate !== $toDate) {
            $currentDate = date("Y-m-d", strtotime($currentDate) + strtotime("+1 day") - strtotime($toDate));
            array_push($dates, $currentDate);
        }

        $activity = DB::select('SELECT DATE(FROM_UNIXTIME(`Timestamp`)) AS `Date`, COUNT(TID) AS `Count` FROM `mtickets` WHERE DATE(FROM_UNIXTIME(`Timestamp`)) IN (\'' . join('\', \'', $dates) . '\') GROUP BY `Date`;');

        foreach ($dates as $date) {
            $found = false;

            foreach ($activity as $act) {
                if($act->Date === $date) {
                    $found = true;
                }
            }

            if (!$found) {
                array_push($activity, (object)[
                    "Date" => $date,
                    "Count" => '0'
                ]);
            }
        }

        usort($activity, function($a, $b) {
            return strtotime($a->Date) > strtotime($b->Date) ? 1 : -1;
        });

        if ($chart) {
            $chartData = [
                'labels' => [],
                'datasets' => []
            ];

            $dataset = ['label' => '# Tickets', 'data' => []];

            foreach($activity as $act) {
                array_push($chartData['labels'], $act->Date);
                array_push($dataset['data'] , $act->Count);
            }

            array_push($chartData['datasets'], $dataset);

            return $chartData;
        }

        return $activity;
    }
}
