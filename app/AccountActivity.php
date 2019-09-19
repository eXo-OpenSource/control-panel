<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccountActivity extends Model
{
    protected $table = "accountActivity";

    protected $dates = [
        'Date'
    ];


    public static function getActivity($users, $chart = false)
    {
        $currentDate = date("Y-m-d", strtotime("-14 days"));;
        $toDate = date("Y-m-d");

        $dates = [];

        array_push($dates, $currentDate);

        while ($currentDate !== $toDate) {
            $currentDate = date("Y-m-d", strtotime($currentDate) + strtotime("+1 day") - strtotime($toDate));
            array_push($dates, $currentDate);
        }

        $activity = DB::select('SELECT Date, SUM(Duration) AS Duration FROM vrp_accountActivity WHERE UserID IN (' . join(', ', $users) . ') AND Date IN (\'' . join('\', \'', $dates) . '\') GROUP BY Date;');

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
                    "Duration" => '0'
                ]);
            }
        }

        usort($activity, function($a, $b) {
            return strcmp($a->Date, $b->Date);
        });

        if ($chart) {
            $chartData = [
                'labels' => [],
                'datasets' => []
            ];

            $dataset = ['label' => 'Aktivität in h', 'data' => []];

            foreach($activity as $act) {
                array_push($chartData['labels'], $act->Date);
                array_push($dataset['data'] , round($act->Duration / 60, 1));
            }

            array_push($chartData['datasets'], $dataset);

            return $chartData;
        }

        return $activity;
    }
}

