<?php

namespace Exo\TeamSpeak\Helpers;

use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Responses\ServerGroupMembersResponse;

class ServerGroup
{
    /* iconid */
    /** @var integer */
    public $iconId;

    /* n_member_addp */
    /** @var integer */
    public $memberAddPower;

    /* n_member_removep */
    /** @var integer */
    public $memberRemovePower;

    /* n_modifyp */
    /** @var integer */
    public $modifyPower;

    /* name */
    /** @var string */
    public $name;

    /* namemode */
    /** @var integer */
    public $nameMode;

    /* savedb */
    /** @var boolean */
    public $saveDb;

    /* sgid */
    /** @var integer */
    public $id;

    /* sortid */
    /** @var integer */
    public $sortId;

    /* type */
    /** @var integer */
    public $type;

    public function __construct($group)
    {
        $this->iconId = intval($group->iconid);
        $this->memberAddPower = intval($group->n_member_addp);
        $this->memberRemovePower = intval($group->n_member_removep);
        $this->modifyPower = intval($group->n_modifyp);
        $this->name = $group->name;
        $this->nameMode = intval($group->namemode);
        $this->saveDb = intval($group->savedb) === 1;
        $this->id = intval($group->sgid);
        $this->sortId = intval($group->sortid);
        $this->type = intval($group->type);
    }


    /**
     * @throws TeamSpeakUnreachableException
     * @return ServerGroupMembersResponse
     */
    public function members()
    {
        return app('teamspeak')->getServerGroupMembers($this->id);
    }
}
