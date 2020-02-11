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
            return true;
        }
    }

    public function show(User $user, Vehicle $vehicle)
    {
        return true;
    }

    public function private(User $user, Vehicle $vehicle)
    {
        if($vehicle->OwnerType === 1) {
            return $user->Id == $vehicle->OwnerId;
        } elseif($vehicle->OwnerType === 2) {
            return $user->FactionId == $vehicle->OwnerId;
        } elseif($vehicle->OwnerType === 3) {
            return $user->CompanyId == $vehicle->OwnerId;
        } elseif($vehicle->OwnerType === 4) {
            return $user->GroupId == $vehicle->OwnerId;
        }

        return false;
    }
}
