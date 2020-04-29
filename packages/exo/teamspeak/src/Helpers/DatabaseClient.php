<?php

namespace Exo\TeamSpeak\Helpers;

use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Responses\ChannelGroupMembersResponse;
use Exo\TeamSpeak\Responses\DatabaseClientInfoResponse;
use Exo\TeamSpeak\Responses\DatabaseClientServerGroupsResponse;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;

class DatabaseClient
{
    /* cldbid */
    /** @var integer */
    public $databaseId;

    /* cluid */
    /** @var string */
    public $uniqueId;

    public function __construct($client)
    {
        $this->databaseId = intval($client->cldbid);
        $this->uniqueId = $client->cluid;
    }

    /**
     * @throws TeamSpeakUnreachableException
     * @return DatabaseClientInfoResponse
     */
    public function info()
    {
        return app('teamspeak')->getDatabaseClientInfo($this->databaseId);
    }

    /**
     * @throws TeamSpeakUnreachableException
     * @return DatabaseClientServerGroupsResponse
     */
    public function serverGroups()
    {
        return app('teamspeak')->getServerGroupsByClientId($this->databaseId);
    }

    /**
     * @throws TeamSpeakUnreachableException
     * @return ChannelGroupMembersResponse
     */
    public function channelGroups()
    {
        return app('teamspeak')->getChannelGroupsForDatabaseClient($this->databaseId);
    }

    /**
     * @param $description
     * @throws TeamSpeakUnreachableException
     * @return TeamSpeakResponse
     */
    public function setDescription($description)
    {
        return app('teamspeak')->setDatabaseClientDescription($this->databaseId, $description);
    }

    /**
     * @param $serverGroupId
     * @throws TeamSpeakUnreachableException
     * @return TeamSpeakResponse
     */
    public function addServerGroup($serverGroupId)
    {
        return app('teamspeak')->addServerGroupToClient($this->databaseId, $serverGroupId);
    }

    /**
     * @param $serverGroupId
     * @throws TeamSpeakUnreachableException
     * @return TeamSpeakResponse
     */
    public function removeServerGroup($serverGroupId)
    {
        return app('teamspeak')->removeServerGroupFromClient($this->databaseId, $serverGroupId);
    }

    /**
     * @param $channelId
     * @param $channelGroupId
     * @throws TeamSpeakUnreachableException
     * @return TeamSpeakResponse
     */
    public function setChannelGroup($channelId, $channelGroupId)
    {
        return app('teamspeak')->setDatabaseClientChannelGroup($this->databaseId, $channelId, $channelGroupId);
    }
}
