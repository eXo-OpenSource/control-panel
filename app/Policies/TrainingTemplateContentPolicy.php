<?php

namespace App\Policies;

use App\Models\Training\TemplateContent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingTemplateContentPolicy
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

    public function edit(User $user, TemplateContent $templateContent)
    {
        $targets = $user->character->getTrainingTargetsEdit();

        if($templateContent->exercise->ElementType === 2 && $templateContent->exercise->ElementId === $user->character->FactionId &&  in_array('faction', $targets)) {
            return true;
        } elseif($templateContent->exercise->ElementType === 3 && $templateContent->exercise->ElementId === $user->character->CompanyId && in_array('company', $targets)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, TemplateContent $templateContent)
    {
        $targets = $user->character->getTrainingTargetsEdit();

        if($templateContent->exercise->ElementType === 2 && $templateContent->exercise->ElementId === $user->character->FactionId &&  in_array('faction', $targets)) {
            return true;
        } elseif($templateContent->exercise->ElementType === 3 && $templateContent->exercise->ElementId === $user->character->CompanyId && in_array('company', $targets)) {
            return true;
        }

        return false;
    }
}
