<?php

namespace Exo\TeamSpeak\Responses;

use Exo\TeamSpeak\Helpers\Client;
use Exo\TeamSpeak\Helpers\ServerGroup;
use Exo\TeamSpeak\Helpers\ServerGroupMember;

class ServerGroupMembersResponse extends TeamSpeakResponse
{
    /** @var ServerGroupMember[] */
    public $members = [];

    public function __construct($status, $message, $originalResponse, $members = null)
    {
        parent::__construct($status, $message, $originalResponse);

        if($members) {
            foreach($members as $member) {
                array_push($this->members, new ServerGroupMember($member));
            }
        }
    }
}
