<?php

namespace App\Policies;

use App\User;
use App\Vehicle;
use Illuminate\Auth\Access\HandlesAuthorization;

class VehiclePolicy
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
            // return true;
        }
    }

    public function show(User $user, Vehicle $vehicle)
    {
        return true;
    }

    public function private(User $user, Vehicle $vehicle)
    {
        return true;
    }
}
