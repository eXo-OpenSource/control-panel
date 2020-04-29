<?php

namespace Exo\TeamSpeak\Helpers;

use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;

class DatabaseClientInfo
{
    /* client_database_id */
    /** @var integer */
    public $databaseId;

    /* client_unique_identifier */
    /** @var string */
    public $uniqueId;

    /* client_base64HashClientUID */
    /** @var string */
    public $base64HashClientUID;

    /* client_created */
    /** @var integer */
    public $created;

    /* client_description */
    /** @var string */
    public $description;

    /* client_flag_avatar */
    /** @var string */
    public $flagAvatar;

    /* client_lastconnected */
    /** @var integer */
    public $lastConnected;

    /* client_lastip */
    /** @var string */
    public $lastIp;

    /* client_month_bytes_downloaded */
    /** @var integer */
    public $monthBytesDownloaded;

    /* client_month_bytes_downloaded */
    /** @var integer */
    public $monthBytesUploaded;

    /* client_nickname */
    /** @var string */
    public $nickname;

    /* client_total_bytes_downloaded */
    /** @var integer */
    public $totalBytesDownloaded;

    /* client_total_bytes_uploaded */
    /** @var integer */
    public $totalBytesUploaded;

    /* client_totalconnections */
    /** @var integer */
    public $totalConnections;

    public function __construct($client)
    {
        $this->databaseId = intval($client->client_database_id);
        $this->uniqueId = $client->client_unique_identifier;
        $this->base64HashClientUID = $client->client_base64HashClientUID;
        $this->base64HashClientUID = $client->client_base64HashClientUID;
        $this->created = intval($client->client_created);
        $this->description = $client->client_description;
        $this->flagAvatar = $client->client_flag_avatar;
        $this->lastConnected = intval($client->client_lastconnected);
        $this->lastIp = $client->client_lastip;
        $this->lastIp = $client->client_lastip;
        $this->monthBytesDownloaded = intval($client->client_month_bytes_downloaded);
        $this->monthBytesUploaded = intval($client->client_month_bytes_uploaded);
        $this->nickname = $client->client_nickname;
        $this->totalBytesDownloaded = intval($client->client_total_bytes_downloaded);
        $this->totalBytesUploaded = intval($client->client_total_bytes_uploaded);
        $this->totalConnections = intval($client->client_totalconnections);
    }
}
