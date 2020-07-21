<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        abort_unless(auth()->user()->Rank >= 3, 403);
        $weeks = 25;

        $members = User::query()->where('Rank', '>', 0)->get();
        $stats = DB::select('SELECT WEEK(CreatedAt) AS Week, AssigneeId, COUNT(Id) AS Count FROM vrp_tickets WHERE AssigneeId IS NOT NULL AND State = \'Closed\' AND CreatedAt >= DATE_SUB(SUBDATE(NOW(), WEEKDAY(NOW())), INTERVAL ? WEEK) GROUP BY Week, AssigneeId;', [$weeks]);

        $result = [];

        $firstWeek = Carbon::now()->startOfWeek()->subWeeks($weeks)->week;
        $endWeek = Carbon::now()->week;

        foreach($members as $member)
        {
            $data = [
                'Id' => $member->Id,
                'Name' => $member->Name,
                'Rank' => $member->Rank,
                'data' => []
            ];

            for($i = $firstWeek; $i <= $endWeek; $i++) {
                array_push($data['data'], [
                    'Week' => $i,
                    'Count' => 0
                ]);
            }


            foreach($stats as $stat)
            {
                if($stat->AssigneeId === $member->Id)
                {
                    foreach($data['data'] as $key => $tmp)
                    {
                        if($tmp['Week'] === $stat->Week)
                        {
                            $data['data'][$key]['Count'] = $stat->Count;
                        }
                    }
                }
            }

            array_push($result, $data);
        }

        return $result;
    }
}
