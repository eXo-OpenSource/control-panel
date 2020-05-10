<?php

namespace Exo\TeamSpeak\Responses;

use Exo\TeamSpeak\Helpers\Client;
use Exo\TeamSpeak\Helpers\ServerGroup;

class ServerGroupsResponse extends TeamSpeakResponse
{
    /** @var ServerGroup[] */
    public $groups = [];

    public function __construct($status, $message, $originalResponse, $groups = null)
    {
        parent::__construct($status, $message, $originalResponse);

        if($groups) {
            foreach($groups as $group) {
                array_push($this->groups, new ServerGroup($group));
            }
        }
    }
}
