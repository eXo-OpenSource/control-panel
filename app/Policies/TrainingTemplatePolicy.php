<?php

namespace App\Policies;

use App\Models\Training\Template;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingTemplatePolicy
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
        if ($user->Rank >= 7) {
            return true;
        }
    }

    public function create(User $user)
    {
        $targets = $user->character->getTrainingTargets();

        return count($targets) !== 0;
    }

    public function show(User $user, Template $template)
    {
        $targets = $user->character->getTrainingTargets();

        if($template->ElementType === 2 && $template->ElementId === $user->character->FactionId && in_array('faction', $targets)) {
            return true;
        } elseif($template->ElementType === 3 && $template->ElementId === $user->character->CompanyId && in_array('company', $targets)) {
            return true;
        }

        return false;
    }

    public function edit(User $user, Template $template)
    {
        $targets = $user->character->getTrainingTargetsEdit();

        if($template->ElementType === 2 && $template->ElementId === $user->character->FactionId && in_array('faction', $targets)) {
            return true;
        } elseif($template->ElementType === 3 && $template->ElementId === $user->character->CompanyId && in_array('company', $targets)) {
            return true;
        }

        return false;
    }

    public function update(User $user, Template $template)
    {
        $targets = $user->character->getTrainingTargetsEdit();

        if($template->ElementType === 2 && $template->ElementId === $user->character->FactionId && in_array('faction', $targets)) {
            return true;
        } elseif($template->ElementType === 3 && $template->ElementId === $user->character->CompanyId && in_array('company', $targets)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Template $template)
    {
        $targets = $user->character->getTrainingTargetsEdit();

        if($template->ElementType === 2 && $template->ElementId === $user->character->FactionId && in_array('faction', $targets)) {
            return true;
        } elseif($template->ElementType === 3 && $template->ElementId === $user->character->CompanyId && in_array('company', $targets)) {
            return true;
        }

        return false;
    }
}
