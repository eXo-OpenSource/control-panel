<?php

namespace App\Policies;

use App\Models\TeamspeakIdentity;
use App\Models\Training\Template;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamspeakIdentityPolicy
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

        if($user->Rank === 1 && $ability === 'create') {
            return true;
        }
    }

    public function create(User $user)
    {
        return $user->Rank >= 3;
    }

    public function update(User $user, TeamspeakIdentity $teamspeakIdentity)
    {
        return $user->Rank >= 3;
    }

    public function delete(User $user, TeamspeakIdentity $teamspeakIdentity)
    {
        return $user->Rank >= 3;
    }
}
