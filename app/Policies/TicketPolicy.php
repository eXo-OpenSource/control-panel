<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
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
        if ($user->Rank >= 1) {
            return true;
        }
    }

    public function show(User $user, Ticket $ticket)
    {
        return $ticket->users->contains($user);
    }

    public function update(User $user, Ticket $ticket)
    {
        return $ticket->users->contains($user);
    }
}
