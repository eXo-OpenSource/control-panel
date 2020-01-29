<?php

namespace App\Policies;

use App\Company;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
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

    public function show(User $user, Company $company)
    {
        return true;
    }

    public function activityTotal(User $user, Company $company)
    {
        return true;
    }

    public function activity(User $user, Company $company)
    {
        return $user->character->CompanyId === $company->Id;
    }

    public function logs(User $user, Company $company)
    {
        return $user->character->CompanyId === $company->Id;
    }

    public function vehicles(User $user, Company $company)
    {
        return true;
    }
}
