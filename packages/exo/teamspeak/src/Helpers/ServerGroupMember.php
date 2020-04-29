<?php

namespace Exo\TeamSpeak\Helpers;

use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Responses\DatabaseClientInfoResponse;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;

class ServerGroupMember
{
    /* cldbid */
    /** @var integer */
    public $databaseId;

    /* client_nickname */
    /** @var string */
    public $nickname;

    /* client_unique_identifier */
    /** @var string */
    public $uniqueId;

    public function __construct($member)
    {
        $this->databaseId = intval($member->cldbid);
        $this->nickname = $member->client_nickname;
        $this->uniqueId = $member->client_unique_identifier;
    }
}
