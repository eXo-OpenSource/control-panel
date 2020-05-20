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

use Illuminate\Support\Facades\Broadcast;

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

    return $ticket->users->pluck('Id')->contains($user->Id);
});

