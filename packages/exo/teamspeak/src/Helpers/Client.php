<?php

namespace Exo\TeamSpeak\Helpers;

use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Responses\ChannelGroupMembersResponse;
use Exo\TeamSpeak\Responses\ClientInfoResponse;
use Exo\TeamSpeak\Responses\DatabaseClientInfoResponse;
use Exo\TeamSpeak\Responses\DatabaseClientServerGroupsResponse;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;

class Client
{
    /* cid */
    /** @var integer */
    public $channelId;

    /* clid */
    /** @var integer */
    public $id;

    /* client_away */
    /** @var bool */
    public $away;

    /* client_away_message */
    /** @var string */
    public $awayMessage;

    /* client_badges */
    /** @var string */
    public $badges;

    /* client_channel_group_id */
    /** @var integer */
    public $channelGroupId;

    /* client_channel_group_inherited_channel_id */
    /** @var integer */
    public $channelGroupInheritedChannelId;

    /* client_country */
    /** @var string */
    public $country;

    /* client_created */
    /** @var integer */
    public $created;

    /* client_database_id */
    /** @var integer */
    public $databaseId;

    /* client_flag_talking */
    /** @var boolean */
    public $flagTalking;

    /* client_idle_time */
    /** @var integer */
    public $idleTime;

    /* client_input_hardware */
    /** @var boolean */
    public $inputHardware;

    /* client_input_muted */
    /** @var boolean */
    public $inputMuted;

    /* client_is_channel_commander */
    /** @var boolean */
    public $isChannelCommander;

    /* client_is_priority_speaker */
    /** @var boolean */
    public $isPrioritySpeaker;

    /* client_is_recording */
    /** @var boolean */
    public $isRecording;

    /* client_is_talker */
    /** @var boolean */
    public $isTalker;

    /* client_lastconnected */
    /** @var integer */
    public $lastConnected;

    /* client_nickname */
    /** @var string */
    public $nickname;

    /* client_output_hardware */
    /** @var boolean */
    public $outputHardware;

    /* client_output_muted */
    /** @var boolean */
    public $outputMuted;

    /* client_platform */
    /** @var string */
    public $platform;

    /* client_platform */
    /** @var string */
    public $serverGroups;

    /* client_platform */
    /** @var integer */
    public $talkPower;

    /* client_type */
    /** @var integer */
    public $type;

    /* client_unique_identifier */
    /** @var string */
    public $uniqueId;

    /* client_version */
    /** @var string */
    public $version;

    /* connection_client_ip */
    /** @var string */
    public $ip;

    public function __construct($client)
    {
        $this->channelId = intval($client->cid);
        $this->id = intval($client->clid);
        $this->away = intval($client->client_away) === 1;
        $this->awayMessage = $client->client_away_message;
        $this->badges = $client->client_badges;
        $this->channelGroupId = intval($client->client_channel_group_id);
        $this->channelGroupInheritedChannelId = intval($client->client_channel_group_inherited_channel_id);
        $this->country = $client->client_country;
        $this->created = intval($client->client_created);
        $this->databaseId = intval($client->client_database_id);
        $this->flagTalking = intval($client->client_flag_talking) === 1;
        $this->idleTime = intval($client->client_idle_time);
        $this->inputHardware = intval($client->client_input_hardware) === 1;
        $this->inputMuted = intval($client->client_input_muted) === 1;
        $this->isChannelCommander = intval($client->client_is_channel_commander) === 1;
        $this->isPrioritySpeaker = intval($client->client_is_priority_speaker) === 1;
        $this->isRecording = intval($client->client_is_recording) === 1;
        $this->isTalker = intval($client->client_is_talker) === 1;
        $this->lastConnected = intval($client->client_lastconnected);
        $this->nickname = $client->client_nickname;
        $this->outputHardware = intval($client->client_output_hardware) === 1;
        $this->outputMuted = intval($client->client_output_muted) === 1;
        $this->platform = $client->client_platform;

        if($client->client_servergroups !== '') {
            $serverGroups = explode(',', $client->client_servergroups);
            foreach($serverGroups as $key => $value) {
                $serverGroups[$key] = intval($value);
            }
            $this->serverGroups = $serverGroups;
        } else {
            $this->serverGroups = [];
        }

        $this->talkPower = intval($client->client_talk_power);
        $this->type = intval($client->client_type);
        $this->uniqueId = $client->client_unique_identifier;
        $this->version = $client->client_version;
        $this->ip = $client->connection_client_ip;
    }

    /**
     * @param $message
     * @throws TeamSpeakUnreachableException
     * @return TeamSpeakResponse
     */
    public function message($message)
    {
        return app('teamspeak')->messageClient($this->id, $message);
    }

    /**
     * @param $message
     * @throws TeamSpeakUnreachableException
     * @return TeamSpeakResponse
     */
    public function poke($message)
    {
        return app('teamspeak')->pokeClient($this->id, $message);
    }

    /**
     * @param $reason
     * @throws TeamSpeakUnreachableException
     * @return TeamSpeakResponse
     */
    public function kick($reason)
    {
        return app('teamspeak')->kickClient($this->id, $reason);
    }

    /**
     * @param $reason
     * @throws TeamSpeakUnreachableException
     * @return TeamSpeakResponse
     */
    public function kickChannel($reason)
    {
        return app('teamspeak')->kickChannelClient($this->id, $reason);
    }

    /**
     * @param $reason
     * @throws TeamSpeakUnreachableException
     * @return TeamSpeakResponse
     */
    public function ban($reason, $duration)
    {
        return app('teamspeak')->addBan($this->uniqueId, $reason, $duration);
    }

    /**
     * @throws TeamSpeakUnreachableException
     * @return ClientInfoResponse
     */
    public function info()
    {
        return app('teamspeak')->getClientInfo($this->id);
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
     * @throws TeamSpeakUnreachableException
     * @return DatabaseClientInfoResponse
     */
    public function databaseInfo()
    {
        return app('teamspeak')->getDatabaseClientInfo($this->databaseId);
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
