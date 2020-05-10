<?php

namespace Exo\TeamSpeak\Helpers;

use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;

class Ban
{
    /* banid */
    /** @var integer */
    public $id;

    /* created */
    /** @var integer */
    public $created;

    /* duration */
    /** @var integer */
    public $duration;

    /* enforcements */
    /** @var integer */
    public $enforcements;

    /* invokercldbid */
    /** @var integer */
    public $invokerDatabaseId;

    /* invokername */
    /** @var string */
    public $invokerName;

    /* invokeruid */
    /** @var string */
    public $invokerUniqueId;

    /* ip */
    /** @var string */
    public $ip;

    /* lastnickname */
    /** @var string */
    public $lastNickName;

    /* mytsid */
    /** @var string */
    public $myTsId;

    /* name */
    /** @var string */
    public $name;

    /* reason */
    /** @var string */
    public $reason;

    /* uid */
    /** @var string */
    public $uniqueId;

    public function __construct($ban)
    {
        $this->id = intval($ban->banid);
        $this->created = intval($ban->created);
        $this->duration = intval($ban->duration);
        $this->enforcements = intval($ban->enforcements);
        $this->invokerDatabaseId = intval($ban->invokercldbid);
        $this->invokerName = $ban->invokername;
        $this->invokerUniqueId = $ban->invokeruid;
        $this->ip = $ban->ip;
        $this->lastNickName = $ban->lastnickname;
        $this->myTsId = $ban->mytsid;
        $this->name = $ban->name;
        $this->reason = $ban->reason;
        $this->uniqueId = $ban->uid;
    }

    /**
     * @param $reason
     * @throws TeamSpeakUnreachableException
     * @return TeamSpeakResponse
     */
    public function unban()
    {
        return app('teamspeak')->removeBan($this->id);
    }
}
