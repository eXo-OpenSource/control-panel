<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccountActivity extends Model
{
    protected $table = "account_activity";

    protected $dates = [
        'Date'
    ];

    public static function getActivity(User $user, Carbon $from, Carbon $to)
    {
        $days = $from->diffInDays($to);

        $activity = DB::select('SELECT Date, SUM(Duration) AS Duration FROM vrp_account_activity WHERE UserId = ? AND Date >= ? AND Date <= ? GROUP BY Date;', [$user->Id, $from->format('Y-m-d'), $to->format('Y-m-d')]);


        for ($i = 0; $i < $days; $i++) {
            $date = $from->copy()->addDays($i)->format('Y-m-d');
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

        return $activity;
    }

    /*
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


        $activity = [];

        if (count($users) > 0) {
            $activity = DB::select('SELECT Date, SUM(Duration) AS Duration FROM vrp_account_activity WHERE UserId IN (' . join(', ', $users) . ') AND Date IN (\'' . join('\', \'', $dates) . '\') GROUP BY Date;');
        }

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
    */
}

