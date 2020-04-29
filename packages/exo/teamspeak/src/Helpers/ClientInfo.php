<?php

namespace Exo\TeamSpeak\Helpers;

use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Responses\DatabaseClientInfoResponse;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;

class ClientInfo
{
    /* cid */
    /** @var integer */
    public $channelId;

    /* client_away */
    /** @var bool */
    public $away;

    /* client_away_message */
    /** @var string */
    public $awayMessage;

    /* client_badges */
    /** @var string */
    public $badges;

    /* client_base64HashClientUID */
    /** @var string */
    public $base64HashClientUID;

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

    /* client_default_channel */
    /** @var string */
    public $defaultChannel;

    /* client_default_token */
    /** @var string */
    public $defaultToken;

    /* client_description */
    /** @var string */
    public $description;

    /* client_flag_avatar */
    /** @var string */
    public $flagAvatar;

    /* client_icon_id */
    /** @var integer */
    public $iconId;

    /* client_idle_time */
    /** @var integer */
    public $idleTime;

    /* client_input_hardware */
    /** @var boolean */
    public $inputHardware;

    /* client_input_muted */
    /** @var boolean */
    public $inputMuted;

    /* client_integrations */
    /** @var string */
    public $integrations;

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

    /* client_login_name */
    /** @var string */
    public $loginName;

    /* client_meta_data */
    /** @var string */
    public $metaData;

    /* client_month_bytes_downloaded */
    /** @var integer */
    public $monthBytesDownloaded;

    /* client_month_bytes_uploaded */
    /** @var integer */
    public $monthBytesUploaded;

    /* client_myteamspeak_avatar */
    /** @var string */
    public $myTeamSpeakAvatar;

    /* client_myteamspeak_avatar */
    /** @var string */
    public $myTeamSpeakId;

    /* client_needed_serverquery_view_power */
    /** @var integer */
    public $neededServerQueryViewPower;

    /* client_nickname */
    /** @var string */
    public $nickname;

    /* client_nickname_phonetic */
    /** @var string */
    public $nicknamePhonetic;

    /* client_output_hardware */
    /** @var boolean */
    public $outputHardware;

    /* client_output_muted */
    /** @var boolean */
    public $outputMuted;

    /* client_outputonly_muted */
    /** @var boolean */
    public $outputOnlyMuted;

    /* client_platform */
    /** @var string */
    public $platform;

    /* client_security_hash */
    /** @var string */
    public $securityHash;

    /* client_platform */
    /** @var string */
    public $serverGroups;

    /* client_signed_badges */
    /** @var string */
    public $signedBadges;

    /* client_platform */
    /** @var integer */
    public $talkPower;

    /* client_talk_request */
    /** @var boolean */
    public $talkRequest;

    /* client_talk_request_msg */
    /** @var string */
    public $talkRequestMessage;

    /* client_total_bytes_downloaded */
    /** @var integer */
    public $totalBytesDownloaded;

    /* client_total_bytes_uploaded */
    /** @var integer */
    public $totalBytesUploaded;

    /* client_totalconnections */
    /** @var integer */
    public $totalConnections;

    /* client_type */
    /** @var integer */
    public $type;

    /* client_unique_identifier */
    /** @var string */
    public $uniqueId;

    /* client_version */
    /** @var string */
    public $version;

    /* client_version_sign */
    /** @var string */
    public $versionSign;

    /* connection_bandwidth_received_last_minute_total */
    /** @var integer */
    public $bandwidthReceivedLastMinuteTotal;

    /* connection_bandwidth_received_last_second_total */
    /** @var integer */
    public $bandwidthReceivedLastSecondTotal;

    /* connection_bandwidth_sent_last_minute_total */
    /** @var integer */
    public $bandwidthSentLastMinuteTotal;

    /* connection_bandwidth_sent_last_second_total */
    /** @var integer */
    public $bandwidthSentLastSecondTotal;

    /* connection_bytes_received_total */
    /** @var integer */
    public $bytesReceivedTotal;

    /* connection_bytes_sent_total */
    /** @var integer */
    public $bytesSentTotal;

    /* connection_client_ip */
    /** @var string */
    public $ip;

    /* connection_connected_time */
    /** @var integer */
    public $connectedTime;

    /* connection_filetransfer_bandwidth_received */
    /** @var integer */
    public $fileTransferBandwidthReceived;

    /* connection_filetransfer_bandwidth_sent */
    /** @var integer */
    public $fileTransferBandwidthSent;

    /* connection_packets_received_total */
    /** @var integer */
    public $packetsReceivedTotal;

    /* connection_packets_sent_total */
    /** @var integer */
    public $packetsSentTotal;

    public function __construct($client)
    {
        $this->channelId = intval($client->cid);
        $this->away = intval($client->client_away) === 1;
        $this->awayMessage = $client->client_away_message;
        $this->badges = $client->client_badges;
        $this->base64HashClientUID = $client->client_base64HashClientUID;
        $this->channelGroupId = intval($client->client_channel_group_id);
        $this->channelGroupInheritedChannelId = intval($client->client_channel_group_inherited_channel_id);
        $this->country = $client->client_country;
        $this->created = intval($client->client_created);
        $this->databaseId = intval($client->client_database_id);
        $this->defaultChannel = $client->client_default_channel;
        $this->defaultToken = $client->client_default_token;
        $this->description = $client->client_description;
        $this->flagAvatar = $client->client_flag_avatar;
        $this->iconId = intval($client->client_icon_id);
        $this->idleTime = intval($client->client_idle_time);
        $this->inputHardware = intval($client->client_input_hardware) === 1;
        $this->inputMuted = intval($client->client_input_muted) === 1;
        $this->integrations = $client->client_integrations;
        $this->isChannelCommander = intval($client->client_is_channel_commander) === 1;
        $this->isPrioritySpeaker = intval($client->client_is_priority_speaker) === 1;
        $this->isRecording = intval($client->client_is_recording) === 1;
        $this->isTalker = intval($client->client_is_talker) === 1;
        $this->lastConnected = intval($client->client_lastconnected);
        $this->loginName = $client->client_login_name;
        $this->metaData = $client->client_meta_data;
        $this->monthBytesDownloaded = intval($client->client_month_bytes_downloaded);
        $this->monthBytesUploaded = intval($client->client_month_bytes_uploaded);
        $this->myTeamSpeakAvatar = $client->client_myteamspeak_avatar;
        $this->myTeamSpeakId = $client->client_myteamspeak_id;
        $this->neededServerQueryViewPower = intval($client->client_needed_serverquery_view_power);
        $this->nickname = $client->client_nickname;
        $this->nicknamePhonetic = $client->client_nickname_phonetic;
        $this->outputHardware = intval($client->client_output_hardware) === 1;
        $this->outputMuted = intval($client->client_output_muted) === 1;
        $this->outputOnlyMuted = intval($client->client_outputonly_muted) === 1;
        $this->platform = $client->client_platform;
        $this->securityHash = $client->client_security_hash;

        if($client->client_servergroups !== '') {
            $serverGroups = explode(',', $client->client_servergroups);
            foreach($serverGroups as $key => $value) {
                $serverGroups[$key] = intval($value);
            }
            $this->serverGroups = $serverGroups;
        } else {
            $this->serverGroups = [];
        }

        $this->signedBadges = $client->client_signed_badges;
        $this->talkPower = intval($client->client_talk_power);
        $this->talkRequest = intval($client->client_talk_request) === 1;
        $this->talkRequestMessage = $client->client_talk_request_msg;
        $this->totalBytesDownloaded = intval($client->client_total_bytes_downloaded);
        $this->totalBytesUploaded = intval($client->client_total_bytes_uploaded);
        $this->totalConnections = intval($client->client_totalconnections);
        $this->type = intval($client->client_type);
        $this->uniqueId = $client->client_unique_identifier;
        $this->version = $client->client_version;
        $this->versionSign = $client->client_version_sign;
        $this->bandwidthReceivedLastMinuteTotal = intval($client->connection_bandwidth_received_last_minute_total);
        $this->bandwidthReceivedLastSecondTotal = intval($client->connection_bandwidth_received_last_second_total);
        $this->bandwidthSentLastMinuteTotal = intval($client->connection_bandwidth_sent_last_minute_total);
        $this->bandwidthSentLastSecondTotal = intval($client->connection_bandwidth_sent_last_second_total);
        $this->bytesReceivedTotal = intval($client->connection_bytes_received_total);
        $this->bytesSentTotal = intval($client->connection_bytes_sent_total);
        $this->ip = $client->connection_client_ip;
        $this->connectedTime = intval($client->connection_connected_time);
        $this->fileTransferBandwidthReceived = intval($client->connection_filetransfer_bandwidth_received);
        $this->fileTransferBandwidthSent = intval($client->connection_filetransfer_bandwidth_sent);
        $this->packetsReceivedTotal = intval($client->connection_packets_received_total);
        $this->packetsSentTotal = intval($client->connection_packets_sent_total);
    }
}
