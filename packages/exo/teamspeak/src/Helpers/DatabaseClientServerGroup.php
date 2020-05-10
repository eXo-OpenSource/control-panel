<?php

namespace Exo\TeamSpeak\Helpers;

use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;

class DatabaseClientServerGroup
{
    /* cldbid */
    /** @var integer */
    public $databaseId;

    /* name */
    /** @var string */
    public $name;

    /* sgid */
    /** @var integer */
    public $serverGroupId;

    public function __construct($group)
    {
        $this->databaseId = intval($group->cldbid);
        $this->serverGroupId = intval($group->sgid);
        $this->name = $group->name;
    }
}
