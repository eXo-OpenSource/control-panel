<?php

namespace App\Policies;

use App\User;
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
        if ($user->Rank >= 2) {
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

    public function history(User $authUser, User $user)
    {
        return $authUser->Id == $user->Id;
    }

    public function activity(User $authUser, User $user)
    {
        return false; // currently broken
        return $authUser->Id == $user->Id;
    }

    public function hardware(User $authUser, User $user)
    {
        return false; // only for admins for now
    }
}
