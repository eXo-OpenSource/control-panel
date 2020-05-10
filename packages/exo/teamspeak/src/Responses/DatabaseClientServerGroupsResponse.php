<?php

namespace Exo\TeamSpeak\Responses;

use Exo\TeamSpeak\Helpers\DatabaseClientServerGroup;

class DatabaseClientServerGroupsResponse extends TeamSpeakResponse
{
    /** @var DatabaseClientServerGroup[] */
    public $serverGroups = [];

    public function __construct($status, $message, $originalResponse, $serverGroups = null)
    {
        parent::__construct($status, $message, $originalResponse);

        if($serverGroups) {
            foreach($serverGroups as $serverGroup) {
                array_push($this->serverGroups, new DatabaseClientServerGroup($serverGroup));
            }
        }
    }
}
