<?php

namespace Exo\TeamSpeak\Helpers;

use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Responses\ChannelGroupMembersResponse;
use Exo\TeamSpeak\Responses\ClientInfoResponse;
use Exo\TeamSpeak\Responses\DatabaseClientInfoResponse;
use Exo\TeamSpeak\Responses\DatabaseClientServerGroupsResponse;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;

class Channel
{
    /* channel_banner_gfx_url */
    /** @var string */
    public $bannerGfxUrl;

    /* channel_banner_mode */
    /** @var integer */
    public $bannerMode;

    /* channel_codec */
    /** @var integer */
    public $codec;

    /* channel_codec_quality */
    /** @var integer */
    public $codecQuality;

    /* channel_flag_default */
    /** @var boolean */
    public $flagDefault;

    /* channel_flag_password */
    /** @var boolean */
    public $flagPassword;

    /* channel_flag_permanent */
    /** @var boolean */
    public $flagPermanent;

    /* channel_flag_semi_permanent */
    /** @var boolean */
    public $flagSemiPermanent;

    /* channel_icon_id */
    /** @var integer */
    public $iconId;

    /* channel_maxclients */
    /** @var integer */
    public $maxClients;

    /* channel_maxfamilyclients */
    /** @var integer */
    public $maxFamilyClients;

    /* channel_name */
    /** @var string */
    public $name;

    /* channel_needed_subscribe_power */
    /** @var integer */
    public $neededSubscribePower;

    /* channel_needed_talk_power */
    /** @var integer */
    public $neededTalkPower;

    /* channel_order */
    /** @var integer */
    public $order;

    /* channel_topic */
    /** @var string */
    public $topic;

    /* cid */
    /** @var integer */
    public $id;

    /* pid */
    /** @var integer */
    public $parentId;

    /* seconds_empty */
    /** @var integer */
    public $secondsEmpty;

    /* total_clients */
    /** @var integer */
    public $totalClients;

    /* total_clients_family */
    /** @var integer */
    public $totalClientsFamily;


    public function __construct($channel)
    {
        $this->bannerGfxUrl = $channel->channel_banner_gfx_url;
        $this->bannerMode = intval($channel->channel_banner_mode);
        $this->codec = intval($channel->channel_codec);
        $this->codecQuality = intval($channel->channel_codec_quality);
        $this->flagDefault = intval($channel->channel_flag_default) === 1;
        $this->flagPassword = intval($channel->channel_flag_password) === 1;
        $this->flagPermanent = intval($channel->channel_flag_permanent) === 1;
        $this->flagSemiPermanent = intval($channel->channel_flag_semi_permanent) === 1;
        $this->iconId = intval($channel->channel_icon_id);
        $this->maxClients = intval($channel->channel_maxclients);
        $this->maxFamilyClients = intval($channel->channel_maxfamilyclients);
        $this->name = $channel->channel_name;
        $this->neededSubscribePower = intval($channel->channel_needed_subscribe_power);
        $this->neededTalkPower = intval($channel->channel_needed_talk_power);
        $this->order = intval($channel->channel_order);
        $this->topic = $channel->channel_topic;
        $this->id = intval($channel->cid);
        $this->parentId = intval($channel->pid);
        $this->secondsEmpty = intval($channel->seconds_empty);
        $this->totalClients = intval($channel->total_clients);
        $this->totalClientsFamily = intval($channel->total_clients_family);
    }
}
