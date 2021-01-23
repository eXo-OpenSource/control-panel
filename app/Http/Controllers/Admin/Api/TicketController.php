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

        $firstWeekCarbon = Carbon::now()->startOfWeek()->subWeeks($weeks);
        $endWeekCarbon = Carbon::now();

        $stats = DB::select('SELECT Id, ResolvedAt, AssigneeId FROM vrp_tickets WHERE AssigneeId IS NOT NULL AND State = \'Closed\' AND ResolvedAt >= ?', [$firstWeekCarbon]);
        $stats2 = DB::select('SELECT tu.UserId, t.ResolvedAt, tu.UserId FROM vrp_ticket_users tu INNER JOIN vrp_tickets t ON t.Id = tu.TicketId WHERE t.AssigneeId IS NOT NULL AND t.AssigneeId <> tu.UserId AND t.State = \'Closed\' AND t.ResolvedAt >= ? AND tu.IsAdmin = 1 AND tu.LeftAt IS NULL', [$firstWeekCarbon]);
        $stats3 = DB::select('SELECT COUNT(Id) AS Count, AssigneeId FROM vrp_tickets WHERE AssigneeId IS NOT NULL AND State = \'Closed\' GROUP BY AssigneeId');

        $result = [];

        $firstWeek = $firstWeekCarbon->weekOfYear;

        $lastYearTotalWeeks = -1;
        if ($firstWeekCarbon->year != $endWeekCarbon->year)
        {
            $lastYearTotalWeeks = Carbon::parse('last day of December ' . $firstWeekCarbon->year, 'Europe/Vienna')->weekOfYear;
        }

        foreach($members as $member)
        {
            $data = [
                'Id' => $member->Id,
                'Name' => $member->Name,
                'Rank' => $member->Rank,
                'Total' => 0,
                'data' => []
            ];

            for($i = 0; $i <= $weeks; $i++) {
                $week = $firstWeek + $i;
                if ($week > $lastYearTotalWeeks)
                    $week -= $lastYearTotalWeeks;
                array_push($data['data'], [
                    'Week' => $week,
                    'ResolvedCount' => 0,
                    'ConsultedCount' => 0
                ]);
            }
            array_push($result, $data);
        }

        foreach($stats as $stat)
        {
            $week = Carbon::parse($stat->ResolvedAt)->weekOfYear;

            foreach($result as $key => $entry)
            {
                if ($entry['Id'] === $stat->AssigneeId) {

                    foreach($result[$key]['data'] as $key2 => $tmp)
                    {
                        if($tmp['Week'] === $week)
                        {
                            $result[$key]['data'][$key2]['ResolvedCount'] += 1;
                            break;
                        }
                    }
                    break;
                }
            }
        }

        foreach($stats2 as $stat)
        {
            $week = Carbon::parse($stat->ResolvedAt)->weekOfYear;

            foreach($result as $key => $entry)
            {
                if ($entry['Id'] === $stat->UserId) {

                    foreach($result[$key]['data'] as $key2 => $tmp)
                    {
                        if($tmp['Week'] === $week)
                        {
                            $result[$key]['data'][$key2]['ConsultedCount'] += 1;
                            break;
                        }
                    }
                    break;
                }
            }
        }

        foreach($stats3 as $stat)
        {
            foreach($result as $key => $entry)
            {
                if ($entry['Id'] === $stat->AssigneeId) {
                    $result[$key]['Total'] = $stat->Count;
                    break;
                }
            }
        }



        usort($result, function($a, $b) {
           return $a['Rank'] > $b['Rank'];
        });

        return $result;
    }
}
