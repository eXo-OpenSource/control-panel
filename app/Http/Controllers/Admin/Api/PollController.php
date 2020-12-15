<?php

namespace App\Http\Controllers\Admin\Api;

use App\Events\Admin\PollUpdate;
use App\Http\Controllers\Controller;
use App\Models\Admin\Poll;
use App\Models\Admin\PollVote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_unless(auth()->user()->Rank >= 3, 403);
        $active = request()->has('active');

        if ($active) {
            $poll = Poll::query()->whereNull('FinishedAt')->get();
            return $poll->first();
        }

        return Poll::query()->whereNotNull('FinishedAt')->orderBy('Id', 'DESC')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function store(Request $request)
    {
        abort_unless(auth()->user()->Rank >= 3, 403);

        $action = $request->get('action');

        if($action === 'vote')
        {
            $vote = $request->get('vote');

            $poll = Poll::query()->whereNull('FinishedAt')->get();
            $poll = $poll->first();

            DB::delete('DELETE FROM vrp_admin_poll_vote WHERE PollId = ? AND AdminId = ?', [$poll->Id, auth()->user()->Id]);

            $pollVote = new PollVote();
            $pollVote->AdminId = auth()->user()->Id;
            $pollVote->PollId = $poll->Id;
            $pollVote->Vote = $vote === 'agree' ? 1 : 0;
            $pollVote->save();

            event(new PollUpdate($poll));
        }
        elseif($action === 'finish')
        {
            $polls = Poll::query()->whereNull('FinishedAt')->get();

            foreach($polls as $poll)
            {
                $poll->FinishedAt = Carbon::now();
                $poll->save();
            }

            event(new PollUpdate(null));
        }
        elseif($action === 'create')
        {
            $polls = Poll::query()->whereNull('FinishedAt')->get();

            foreach($polls as $poll)
            {
                $poll->FinishedAt = Carbon::now();
                $poll->save();
            }

            $poll = new Poll();
            $poll->Title = request()->get('title');
            $poll->URL = request()->get('url');
            $poll->AdminId = auth()->user()->Id;
            $poll->save();

            event(new PollUpdate($poll));
        }

        return 'SUCCESS';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_unless(auth()->user()->Rank >= 3, 403);

        return Poll::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
