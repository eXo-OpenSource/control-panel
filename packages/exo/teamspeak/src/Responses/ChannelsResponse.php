<?php

namespace Exo\TeamSpeak\Responses;

use Exo\TeamSpeak\Helpers\Channel;

class ChannelsResponse extends TeamSpeakResponse
{
    /** @var Channel[] */
    public $channels = [];

    public function __construct($status, $message, $originalResponse, $channels = null)
    {
        parent::__construct($status, $message, $originalResponse);

        if($channels) {
            foreach($channels as $channel) {
                array_push($this->channels, new Channel($channel));
            }
        }
    }
}
