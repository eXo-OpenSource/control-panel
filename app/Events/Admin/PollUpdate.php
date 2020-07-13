<?php

namespace App\Events\Admin;

use App\Models\Admin\Poll;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PollUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Poll */
    public $poll;

    /**
     * Create a new event instance.
     *
     * @param Poll $poll
     */
    public function __construct(?Poll $poll)
    {
        $this->poll = $poll;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('admin.polls');
    }

    public function broadcastWith()
    {
        return ['poll' => $this->poll];
    }
}
