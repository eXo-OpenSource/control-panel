<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Faction extends Model
{
    protected $primaryKey = 'Id';

    public function members()
    {
        return $this->hasMany(Character::class, 'FactionId', 'Id');
    }

    public function membersCount()
    {
        return Character::where('FactionId', $this->Id)->count();
    }

    public function getActivity($chart)
    {
        $currentDate = date("Y-m-d", strtotime("-14 days"));;
        $toDate = date("Y-m-d");

        $dates = "'" . $currentDate . "'";
        $datesCol = array();

        array_push($datesCol, $currentDate);

        while ($currentDate !== $toDate) {
            $currentDate = date("Y-m-d", strtotime($currentDate) + strtotime("+1 day") - strtotime($toDate));
            $dates = $dates . ", '" . $currentDate . "'";
            array_push($datesCol, $currentDate);
        }

        $members = $this->members->pluck('Id');

        $users = join(', ', $members->toArray());

        while ($currentDate !== $toDate) {
            $currentDate = date("Y-m-d", strtotime($currentDate) + strtotime("+1 day") - strtotime($toDate));
            $dates = $dates . ", '" . $currentDate . "'";
            array_push($datesCol, $currentDate);
        }

        $activity = DB::select('SELECT Date, SUM(Duration) AS Duration FROM vrp_accountActivity WHERE UserID IN (' . $users . ') AND Date IN (' . $dates . ') GROUP BY Date;');

        $activity = (array)$activity;

        foreach ($datesCol as $date) {
            $found = false;

            foreach ($activity as $act) {
                if(((array)$act)['Date'] === $date) {
                    $found = true;
                }
            }
            if (!$found) {
                array_push($activity, array(
                    "Date" => $date,
                    "Duration" => '0'
                ));
            }
        }

        usort($activity, function($a, $b) {
            return strcmp(((array)$a)['Date'], ((array)$b)['Date']);
        });

        if ($chart) {
            $activity = (array)$activity;
            $chartData = [
                'labels' => [],
                'datasets' => []
            ];

            $dataset = ['label' => 'Aktivität in h', 'data' => []];

            foreach((array)$activity as $act) {
                array_push($chartData['labels'], ((array)$act)['Date']);
                array_push($dataset['data'] , round(((array)$act)['Duration'] / 60, 1));
            }



            array_push($chartData['datasets'], $dataset);

            return $chartData;
        }

        return $activity;
    }
}
