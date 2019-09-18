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

        $dates = [];

        array_push($dates, $currentDate);

        while ($currentDate !== $toDate) {
            $currentDate = date("Y-m-d", strtotime($currentDate) + strtotime("+1 day") - strtotime($toDate));
            array_push($dates, $currentDate);
        }

        $members = $this->members->pluck('Id');

        $activity = DB::select('SELECT Date, SUM(Duration) AS Duration FROM vrp_accountActivity WHERE UserID IN (' . join(', ', $members->toArray()) . ') AND Date IN (' . join(', ', $dates) . ') GROUP BY Date;');

        $activity = (array)$activity;

        foreach ($dates as $date) {
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
