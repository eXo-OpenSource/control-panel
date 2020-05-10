<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
        if ($user->Rank >= 3) {
            return true;
        }

        if($user->Rank === 1 && $ability === 'teamspeak') {
            return true;
        }
    }

    public function show(User $authUser, User $user)
    {
        return true;
        /*
        if ($authUser === null)
            return false;
        return $authUser->Id == $user->Id;*/
    }

    public function privateData(User $authUser, User $user)
    {
        return $authUser->Id == $user->Id;
    }

    public function vehicles(User $authUser, User $user)
    {
        return $authUser->Id == $user->Id;
    }

    public function teamspeak(User $authUser, User $user)
    {
        return $authUser->Id == $user->Id;
    }

    public function history(User $authUser, User $user)
    {
        return $authUser->Id == $user->Id;
    }

    public function activity(User $authUser, User $user)
    {
        return $authUser->Id == $user->Id;
    }

    public function bank(User $authUser, User $user)
    {
        return $authUser->Id == $user->Id;
    }

    public function trainings(User $authUser, User $user)
    {
        $targets = $authUser->character->getTrainingTargets();

        if(count($targets) > 0) {
            return true;
        }

        return $authUser->Id == $user->Id;
    }

    public function logs(User $authUser, User $user)
    {
        return $authUser->Id == $user->Id;
    }

    public function hardware(User $authUser, User $user)
    {
        return false; // only for admins for now
    }

    public function searchUser(User $authUser)
    {
        return true;
    }
}
