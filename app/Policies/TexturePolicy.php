<?php

namespace App\Policies;

use App\Models\Texture;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TexturePolicy
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

    public function show(User $user, Texture $texture)
    {
        return $user->Id === $texture->UserId;
    }

    public function destroy(User $user, Texture $texture)
    {
        return $user->Id === $texture->UserId;
    }
}
