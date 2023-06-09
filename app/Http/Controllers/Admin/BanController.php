<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ban;
use App\Models\Warn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $time = (new \DateTime())->getTimestamp();

        $banList = [];
        $bans = Ban::where('expires', '>=', $time)->orWhere('expires', 0)->with('user')->with('admin')->get();

        foreach($bans as $ban)
        {
            if($ban->user)
            {
                array_push($banList, (object)[
                    'Expires' => $ban->expires,
                    'UserId' => $ban->player_id,
                    'User' => $ban->user->Name,
                    'AdminId' => $ban->author,
                    'Admin' => $ban->admin ? $ban->admin->Name : null,
                    'Reason' => $ban->reason
                ]);
            }
        }

        $warnsByUserId = [];
        $warns = Warn::where('expires', '>=', $time)->with('user')->with('admin')->get();

        foreach($warns as $warn)
        {
            if(!isset($warnsByUserId[$warn->userId]))
            {
                $warnsByUserId[$warn->userId] = [];
            }
            array_push($warnsByUserId[$warn->userId], $warn);
        }

        foreach($warnsByUserId as $userId => $warns)
        {
            if(count($warns) >= 3)
            {
                $data = [
                    'UserId' => $userId,
                    'Reason' => '~ 3+ Warns ~'
                ];

                usort($warns, function($a, $b) {
                    return $a->expires < $b->expires ? -1 : 1;
                });

                if($warns[2]->user)
                {
                    $data['Expires'] = $warns[2]->expires;
                    $data['User'] = $warns[2]->user->Name;

                    usort($warns, function($a, $b) {
                        return $a->created < $b->created ? -1 : 1;
                    });

                    $data['AdminId'] = $warns[0]->adminId;
                    $data['Admin'] = $warns[0]->admin ? $warns[0]->admin->Name : null;


                    array_push($banList, (object)$data);
                }
            }
        }



        usort($banList, function($a, $b) {
            if($a->Expires === 0)
                return 1;

            if($b->Expires === 0)
                return -1;

            return $a->Expires > $b->Expires ? 1 : -1;
        });

        return view('admin.bans.index', compact('banList'));
    }
}
