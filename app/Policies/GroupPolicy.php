<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
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
        if($group->Id === 1 || $group->Id === 2) {
            if(auth()->user()->Rank < 7) {
                return false;
            }
        }

        return true;
    }

    public function activityTotal(User $user, Group $group)
    {
        return $user->character->GroupId === $group->Id;
    }

    public function activity(User $user, Group $group)
    {
        return $user->character->GroupId === $group->Id;
    }

    public function logs(User $user, Group $group)
    {
        return $user->character->GroupId === $group->Id;
    }

    public function statistics(User $user, Group $group)
    {
        return $user->character->GroupId === $group->Id;
    }

    public function vehicles(User $user, Group $group)
    {
        return $user->character->GroupId === $group->Id;
    }

    public function bank(User $user, Group $group)
    {
        return $user->character->GroupId === $group->Id;
    }

    public function bankTransactions(User $user, Group $group)
    {
        return $user->character->GroupId === $group->Id;
    }
}
