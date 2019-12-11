<?php

namespace App\Policies;

use App\Group;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
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

    public function show(User $user, Group $group)
    {
        return true;
    }

    public function activityTotal(User $user, Group $group)
    {
        return $user->GroupId === $group->Id;
    }

    public function activity(User $user, Group $group)
    {
        return $user->GroupId === $group->Id;
    }

    public function logs(User $user, Group $group)
    {
        return $user->GroupId === $group->Id;
    }

    public function vehicles(User $user, Group $group)
    {
        return $user->GroupId === $group->Id;
    }
}
