<?php

namespace App\Policies;

use App\Models\Training\Training;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before($user, $ability)
    {
        if ($user->Rank >= 7) {
            return true;
        }
    }

    public function show(User $user, Training $training)
    {
        $targets = $user->character->getTrainingTargets();

        if($training->ElementType === 2 && $training->ElementId === $user->character->FactionId && in_array('faction', $targets)) {
            return true;
        } elseif($training->ElementType === 3 && $training->ElementId === $user->character->CompanyId && in_array('company', $targets)) {
            return true;
        }

        return false;
    }

    public function update(User $user, Training $training)
    {
        $targets = $user->character->getTrainingTargets();

        if($training->ElementType === 2 && $training->ElementId === $user->character->FactionId && in_array('faction', $targets)) {
            return true;
        } elseif($training->ElementType === 3 && $training->ElementId === $user->character->CompanyId && in_array('company', $targets)) {
            return true;
        }

        return false;
    }
}
