<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\Training\Exercise;
use App\Models\Training\Practice;
use App\Models\Training\Training;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        /** @var Character $character */
        $character = auth()->user()->character;
        $targets = $character->getTrainingTargets();

        abort_if(count($targets) === 0, 403);

        $trainings = Training::query();

        $trainings->where(function (Builder $query) use ($targets, $character) {
            if(in_array('faction', $targets)) {
                $query->where(function (Builder $query) use ($character) {
                    return $query->where('ElementType', 2)->where('ElementId', $character->FactionId);
                });
            }

            if(in_array('company', $targets)) {
                $query->orWhere(function (Builder $query) use ($character) {
                    return $query->where('ElementType', 3)->where('ElementId', $character->CompanyId);
                });
            }
        });

        $state = request()->get('state');
        if(!in_array($state, ['progress', 'both', 'finished'])) {
            $state = 'progress';
        }

        switch($state)
        {
            case 'progress':
                $trainings->where('State', Training::TRAINING_STATE_IN_PROGRESS);
                break;
            case 'finished':
                $trainings->where('State', Training::TRAINING_STATE_FINISHED);
                break;
        }

        $trainings->orderBy('Id', 'DESC');
        $trainings = $trainings->with('user')->get();

        $response = [];

        foreach($trainings as $training) {
            $users = $training->users;
            $participantsCount = 0;
            $participants = [];
            foreach($users as $user) {
                if($user->pivot->Role === 0) {
                    $participantsCount++;
                    array_push($participants, $user->Name);
                }
            }


            $entry = [
                'Id' => $training->Id,
                'UserId' => $training->UserId,
                'User' => $training->user->Name,
                'ElementId' => $training->ElementId,
                'ElementType' => $training->ElementType,
                'Name' => $training->Name,
                'Notes' => $training->Notes,
                'State' => $training->State,
                'StateText' => $training->State === Training::TRAINING_STATE_IN_PROGRESS ? 'Offen' : 'Abgeschlossen',
                'CreatedAt' => $training->CreatedAt->format('d.m.Y H:i:s'),
                'ParticipantsCount' => $participantsCount,
                'Participants' => $participants,
            ];

            array_push($response, (object)$entry);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param Training $training
     * @return array
     */
    public function show(Training $training)
    {
        abort_unless(auth()->user()->can('show', $training), 403);

        $result = [
            'Id' => $training->Id,
            'UserId' => $training->UserId,
            'User' => $training->user->Name,
            'ElementId' => $training->ElementId,
            'ElementType' => $training->ElementType,
            'Name' => $training->Name,
            'Notes' => $training->Notes,
            'State' => $training->State,
            'StateText' => $training->State === Training::TRAINING_STATE_IN_PROGRESS ? 'Offen' : 'Abgeschlossen',
            'CreatedAt' => $training->CreatedAt->format('d.m.Y H:i:s'),
            'contents' => [],
            'users' => [],
        ];

        foreach ($training->contents()->orderBy('Order')->orderBy('Id')->get() as $content)
        {
            array_push($result['contents'], [
                'Id' => $content->Id,
                'TrainingContentId' => $content->TrainingContentId,
                'UserId' => $content->UserId,
                'User' => $content->user->Name,
                'Order' => $content->Order,
                'Name' => $content->Name,
                'Description' => $content->Description,
                'State' => (bool)$content->State,
                'Notes' => $content->Notes,
            ]);
        }

        foreach ($training->users()->orderBy('pivot_Role', 'DESC')->orderBy('Name', 'ASC')->get() as $user)
        {
            array_push($result['users'], [
                'UserId' => $user->Id,
                'User' => $user->Name,
                'Role' => $user->pivot->Role,
            ]);
        }

        return $result;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Training $training
     * @return array|void
     */
    public function update(Request $request, Training $training)
    {
        abort_unless(auth()->user()->can('update', $training), 403);

        $type = $request->get('type');
        $userId = auth()->user()->Id;

        if($training->State == Training::TRAINING_STATE_FINISHED)
        {
            abort(400);
        }

        switch($type)
        {
            case 'close':
                break;
            case 'addUser':
                $addUserIds = $request->get('userIds');

                if(is_numeric($addUserIds)) {
                    $addUserIds = [$addUserIds];
                }

                foreach($addUserIds as $userId) {
                    if(!$training->users->contains($addUserIds) && User::find($addUserIds)) {
                        try {
                            $training->users()->attach($userId, ['Role' => 0]);
                            $training->save();
                        } catch(\Exception $exception) {

                        }
                    }
                }

                return $this->show($training);
                break;
            case 'removeUser':
                $removeUserId = $request->get('userId');
                if (!$training->users->contains($removeUserId)) {
                    abort(400);
                }

                $training->users()->detach($removeUserId);
                $training->save();

                return $this->show($training);
                break;
            case 'toggleRole':
                $toggleUserId = $request->get('userId');
                if (!$training->users->contains($toggleUserId)) {
                    abort(400);
                }

                $role = $training->users()->find($toggleUserId)->pivot->Role;

                $role = $role === 0 ? 1 : 0;

                $training->users()->updateExistingPivot($toggleUserId, ['Role' => $role]);
                $training->save();
                return $this->show($training);
                break;
            case 'toggleState':
                $contentId = $request->get('contentId');
                if (!$training->contents->contains($contentId)) {
                    abort(400);
                }

                $content = $training->contents()->find($contentId);

                $content->State = !$content->State;
                $content->save();

                return $this->show($training);
                break;
            case 'toggleAllState':
                foreach($training->contents as $content) {
                    $content->State = true;
                    $content->save();
                }

                return $this->show($training);
                break;
            case 'updateContentNotes':
                $notes = $request->get('contentNotes');
                $contentId = $request->get('contentId');
                if (!$training->contents->contains($contentId)) {
                    abort(400);
                }

                $content = $training->contents()->find($contentId);

                $content->Notes = $notes ?? '';
                $content->save();

                return $this->show($training);
                break;
            case 'updateNotes':
                $notes = $request->get('notes');

                $training->Notes = $notes;
                $training->save();

                return $this->show($training);
                break;
            case 'finish':
                $training->State = Training::TRAINING_STATE_FINISHED;
                $training->save();

                return $this->show($training);
                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Training $training
     * @return void
     */
    public function destroy(Training $training)
    {
        //
    }
}
