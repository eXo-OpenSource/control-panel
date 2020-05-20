<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Ticket */
    public $ticket;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = [
            new PrivateChannel('tickets'),
            new PrivateChannel('tickets.' . $this->ticket->Id),
        ];

        foreach($this->ticket->users as $user) {
            if($user->pivot->LeftAt === null) {
                array_push($channels, new PrivateChannel('tickets.user.' . $user->Id));
            }
        }

        return $channels;
    }

    public function broadcastWith()
    {
        return ['ticket' => $this->ticket->getApiResponse()];
    }
}
