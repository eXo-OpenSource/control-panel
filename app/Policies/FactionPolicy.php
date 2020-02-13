<?php

namespace App\Policies;

use App\Models\Faction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FactionPolicy
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

    public function show(User $user, Faction $faction)
    {
        return true;
    }

    public function activityTotal(User $user, Faction $faction)
    {
        return true;
    }

    public function activity(User $user, Faction $faction)
    {
        return $user->character->FactionId === $faction->Id;
    }

    public function logs(User $user, Faction $faction)
    {
        return $user->character->FactionId === $faction->Id;
    }

    public function vehicles(User $user, Faction $faction)
    {
        return true;
    }
}
