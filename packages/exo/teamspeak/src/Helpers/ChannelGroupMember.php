<?php

namespace Exo\TeamSpeak\Helpers;

use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Responses\DatabaseClientInfoResponse;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;

class ChannelGroupMember
{
    /* cldbid */
    /** @var integer */
    public $databaseId;

    /* cid */
    /** @var integer */
    public $channelId;

    /* cgid */
    /** @var integer */
    public $channelGroupId;

    public function __construct($member)
    {
        $this->databaseId = intval($member->cldbid);
        $this->channelId = intval($member->cid);
        $this->channelGroupId = intval($member->cgid);
    }
}
