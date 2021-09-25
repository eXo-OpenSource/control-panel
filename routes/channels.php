<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin.polls', function(User $user) {
    if((int) $user->Rank >= 3) {
        return ['id' => $user->Id, 'rank' => $user->Rank, 'rankName' => config('constants.rankNames')[$user->Rank], 'name' => $user->Name];
    }
});

Broadcast::channel('tickets', function ($user) {
    return (int) $user->Rank > 0;
});

Broadcast::channel('tickets.user.{userId}', function ($user, $userId) {
    return (int) $user->Id === (int) $userId;
});

Broadcast::channel('tickets.{id}', function ($user, $id) {
    if($user->Rank > 0)
        return true;

    $ticket = \App\Models\Ticket::find($id);

    if(!$ticket)
        return false;

    $ticketUser = $ticket->users()->find($user->Id);

    if(!$ticketUser)
        return false;

    return $ticketUser->pivot->LeftAt === null;
});

Broadcast::channel('screencaptures.{token}', function ($user, $token) {
    if($user->Rank >= 3)
        return true;

    return false;
});

