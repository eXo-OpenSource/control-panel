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
        // $stats = DB::select('SELECT WEEK(CreatedAt) + 1 AS Week, AssigneeId, COUNT(Id) AS Count FROM vrp_tickets WHERE AssigneeId IS NOT NULL AND State = \'Closed\' AND CreatedAt >= DATE_SUB(SUBDATE(NOW(), WEEKDAY(NOW())), INTERVAL ? WEEK) GROUP BY Week, AssigneeId;', [$weeks]);
        // $stats2 = DB::select('SELECT WEEK(t.CreatedAt) + 1 AS Week, tu.UserId, COUNT(tu.UserId) AS Count FROM vrp_ticket_users tu INNER JOIN vrp_tickets t ON t.Id = tu.TicketId WHERE t.AssigneeId IS NOT NULL AND t.AssigneeId <> tu.UserId AND t.State = \'Closed\' AND t.CreatedAt >= DATE_SUB(SUBDATE(NOW(), WEEKDAY(NOW())), INTERVAL ? WEEK) AND tu.IsAdmin = 1 AND tu.LeftAt IS NULL GROUP BY Week, UserId;', [$weeks]);


        $firstWeekCarbon = Carbon::now()->startOfWeek()->subWeeks($weeks);
        $endWeekCarbon = Carbon::now();

        $stats = DB::select('SELECT Id, ResolvedAt, AssigneeId FROM vrp_tickets WHERE AssigneeId IS NOT NULL AND State = \'Closed\' AND ResolvedAt >= ?', [$firstWeekCarbon]);
        $stats2 = DB::select('SELECT tu.UserId, t.ResolvedAt, tu.UserId FROM vrp_ticket_users tu INNER JOIN vrp_tickets t ON t.Id = tu.TicketId WHERE t.AssigneeId IS NOT NULL AND t.AssigneeId <> tu.UserId AND t.State = \'Closed\' AND t.ResolvedAt >= ? AND tu.IsAdmin = 1 AND tu.LeftAt IS NULL', [$firstWeekCarbon]);

        $result = [];

        $firstWeek = $firstWeekCarbon->weekOfYear;

        $lastYearTotalWeeks = -1;
        if ($firstWeekCarbon->year != $endWeekCarbon->year)
        {
            // $lastYearTotalWeeks = (new Carbon('last day of December ' . $firstWeekCarbon->year, 'Europe/Vienna'))->weekOfYear;
            $lastYearTotalWeeks = Carbon::parse('last day of December ' . $firstWeekCarbon->year, 'Europe/Vienna')->weekOfYear;
        }

        // KW 53
        // KW 31 bis KW 4
        // $endWeek = $endWeekCarbon->week;

        foreach($members as $member)
        {
            $data = [
                'Id' => $member->Id,
                'Name' => $member->Name,
                'Rank' => $member->Rank,
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
/*
            dump($firstWeekCarbon->weekOfYear);
            dump($endWeekCarbon->weekOfYear);
            dd($data['data']);
*/
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



        usort($result, function($a, $b) {
           return $a['Rank'] > $b['Rank'];
        });

        return $result;
    }
}
