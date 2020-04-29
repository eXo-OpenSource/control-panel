<?php

namespace Exo\TeamSpeak\Responses;

use Exo\TeamSpeak\Helpers\ChannelGroupMember;

class ChannelGroupMembersResponse extends TeamSpeakResponse
{
    /** @var ChannelGroupMember[] */
    public $members = [];

    public function __construct($status, $message, $originalResponse, $members = null)
    {
        parent::__construct($status, $message, $originalResponse);

        if($members) {
            foreach($members as $member) {
                array_push($this->members, new ChannelGroupMember($member));
            }
        }
    }
}
