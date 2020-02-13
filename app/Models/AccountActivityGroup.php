<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccountActivityGroup extends Model
{
    protected $table = "account_activity_group";

    protected $dates = [
        'Date'
    ];

    public static function getActivity($elementId, $elementType, Carbon $from, Carbon $to)
    {
        $days = $from->diffInDays($to);

        $activity = DB::select('SELECT Date, SUM(Duration) AS Duration, SUM(DurationDuty) AS DurationDuty FROM vrp_account_activity_group WHERE ElementId = ? AND ElementType = ? AND Date >= ? AND Date <= ? GROUP BY Date;', [$elementId, $elementType, $from->format('Y-m-d'), $to->format('Y-m-d')]);

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
                    "Duration" => '0',
                    "DurationDuty" => '0'
                ]);
            }
        }

        usort($activity, function($a, $b) {
            return strcmp($a->Date, $b->Date);
        });

        return $activity;
    }
}

