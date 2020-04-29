<?php

namespace Exo\TeamSpeak\Responses;

use Exo\TeamSpeak\Helpers\ChannelGroup;

class ChannelGroupsResponse extends TeamSpeakResponse
{
    /** @var ChannelGroup[] */
    public $groups = [];

    public function __construct($status, $message, $originalResponse, $groups = null)
    {
        parent::__construct($status, $message, $originalResponse);

        if($groups) {
            foreach($groups as $group) {
                array_push($this->groups, new ChannelGroup($group));
            }
        }
    }
}
