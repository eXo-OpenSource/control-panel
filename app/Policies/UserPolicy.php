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

    public function show(?User $authUser, User $user)
    {
        if ($authUser === null)
            return false;
        return $authUser->Id == $user->Id;
    }
}
