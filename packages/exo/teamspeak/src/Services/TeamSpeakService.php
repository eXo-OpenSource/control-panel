<?php

namespace Exo\TeamSpeak\Services;

use Carbon\Carbon;
use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Responses\BanResponse;
use Exo\TeamSpeak\Responses\BansResponse;
use Exo\TeamSpeak\Responses\ChannelGroupMembersResponse;
use Exo\TeamSpeak\Responses\ChannelGroupsResponse;
use Exo\TeamSpeak\Responses\ChannelsResponse;
use Exo\TeamSpeak\Responses\ClientInfoResponse;
use Exo\TeamSpeak\Responses\ClientResponse;
use Exo\TeamSpeak\Responses\ClientsResponse;
use Exo\TeamSpeak\Responses\DatabaseClientInfoResponse;
use Exo\TeamSpeak\Responses\DatabaseClientResponse;
use Exo\TeamSpeak\Responses\DatabaseClientServerGroupsResponse;
use Exo\TeamSpeak\Responses\ServerGroupMembersResponse;
use Exo\TeamSpeak\Responses\ServerGroupsResponse;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class TeamSpeakService
{
    /** var Client $client **/
    private $client;

    /** var string $baseUri **/
    private $baseUri;

    /** var string $secret **/
    private $secret;

    /** var string $server **/
    private $server;

    private $failedToConnectError;

    public function __construct($baseUri, $secret, $server = null)
    {
        $this->client = new Client();
        $this->baseUri = $baseUri;
        $this->secret = $secret;
        $this->server = $server;

        $this->failedToConnectError = (object)[
            'status' => 'Error',
            'message' => 'Failed to connect',
            'message_user' => 'Kommunikation mit dem TeamSpeak Server ist derzeit nicht möglich!',
        ];
    }

    /**
     * @param $function
     * @param null $query
     * @return mixed
     * @throws TeamSpeakUnreachableException
     */
    private function request($function, $query = null)
    {
        $queryParam = [];

        if($query) {
            foreach($query as $key => $value) {
                if(is_numeric($key)) {
                    $queryParam[$value] = '';
                } else {
                    $queryParam[$key] = $value;
                }
            }
        }

        try {
            $result = $this->client->get($this->baseUri . '/' . $this->server . '/'. $function, [
                'headers' => [
                    'x-api-key' => $this->secret
                ],
                'query' => $queryParam
            ]);

            return \GuzzleHttp\json_decode($result->getBody()->getContents());
        }
        catch (GuzzleException $exception)
        {
            throw new TeamSpeakUnreachableException();
        }
    }

    /**
     * @param integer $databaseId
     * @param bool $byPassCache
     * @throws TeamSpeakUnreachableException
     * @return ClientResponse
     */
    public function getClient($databaseId, $byPassCache = false)
    {
        $clients = $this->getClients($byPassCache);

        if($clients->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
            foreach ($clients->clients as $client) {
                if($client->databaseId === $databaseId) {
                    return new ClientResponse($clients->status, $clients->message, $clients->originalResponse, $client);
                }
            }
        }
        return new ClientResponse(TeamSpeakResponse::RESPONSE_FAILED, $clients->message, $clients->originalResponse);
    }

    /**
     * @param string $databaseId
     * @param bool $byPassCache
     * @throws TeamSpeakUnreachableException
     * @return ClientResponse
     */
    public function getClientByUniqueId($uniqueId, $byPassCache = false)
    {
        $clients = $this->getClients($byPassCache);

        if($clients->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
            foreach ($clients->clients as $client) {
                if($client->uniqueId === $uniqueId) {
                    return new ClientResponse($clients->status, $clients->message, $clients->originalResponse, $client);
                }
            }
        }
        return new ClientResponse($clients->status, $clients->message, $clients->originalResponse);
    }

    /**
     * @throws TeamSpeakUnreachableException
     * @return BansResponse
     */
    public function getBans()
    {
        $result = $this->request('banlist');

        if($result->status->code === 0) {
            return new BansResponse(TeamSpeakResponse::RESPONSE_SUCCESS,
                $result->status->message,
                $result,
                $result->body
            );
        } else {
            return new BansResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /**
     * @param $banId
     * @return BanResponse
     * @throws TeamSpeakUnreachableException
     */
    public function getBan($banId)
    {
        $bans = $this->getBans();

        if($bans->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
            foreach ($bans->bans as $ban) {
                if($ban->id === $banId) {
                    return new BanResponse($bans->status, $bans->message, $bans->originalResponse, $ban);
                }
            }
        }
        return new BanResponse(TeamSpeakResponse::RESPONSE_FAILED, $bans->message, $bans->originalResponse);
    }


    /**
     * @param $uniqueId
     * @return BansResponse
     * @throws TeamSpeakUnreachableException
     */
    public function getClientBans($uniqueId)
    {
        $bans = $this->getBans();

        if($bans->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
            $userBans = [];
            foreach ($bans->bans as $ban) {
                if($ban->uniqueId === $uniqueId) {
                    array_push($userBans, $ban);
                }
            }
            return new BansResponse($bans->status, $bans->message, $bans->originalResponse, $userBans);
        }
        return new BansResponse(TeamSpeakResponse::RESPONSE_FAILED, $bans->message, $bans->originalResponse);
    }

    /**
     * @param integer $databaseId
     * @param bool $byPassCache
     * @throws TeamSpeakUnreachableException
     * @return BanResponse
     */
    public function getBanByUniqueId($uniqueId)
    {
        $bans = $this->getBans();

        if($bans->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
            foreach ($bans->bans as $ban) {
                if($ban->uniqueId === $uniqueId) {
                    return new BanResponse($bans->status, $bans->message, $bans->originalResponse, $ban);
                }
            }
        }
        return new BanResponse(TeamSpeakResponse::RESPONSE_FAILED, $bans->message, $bans->originalResponse);
    }


    /***
     * @param bool $byPassCache
     * @return ClientsResponse
     * @throws TeamSpeakUnreachableException
     */
    public function getClients($byPassCache = false)
    {
        if(Cache::has('teamspeak:clientlist') && !$byPassCache) {
            return Cache::get('teamspeak:clientlist');
        }

        $result = $this->request('clientlist', ['-uid', '-away', '-voice', '-times', '-groups',
            '-info', '-country', '-ip', '-badges']);
        if($result->status->code === 0) {
            $response = new ClientsResponse(TeamSpeakResponse::RESPONSE_SUCCESS,
                $result->status->message,
                $result,
                $result->body
            );

            Cache::put('teamspeak:clientlist', $response, Carbon::now()->addMinutes(2));
            return $response;
        } else {
            return new ClientsResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /***
     * @param bool $byPassCache
     * @return ChannelsResponse
     * @throws TeamSpeakUnreachableException
     */
    public function getChannels($byPassCache = false)
    {
        if(Cache::has('teamspeak:channellist') && !$byPassCache) {
            return Cache::get('teamspeak:channellist');
        }

        $result = $this->request('channellist', ['-topic', '-flags', '-voice', '-limits', '-icon',
            '-secondsempty', '-banners']);
        if($result->status->code === 0) {
            $response = new ChannelsResponse(TeamSpeakResponse::RESPONSE_SUCCESS,
                $result->status->message,
                $result,
                $result->body
            );

            Cache::put('teamspeak:channellist', $response, Carbon::now()->addMinutes(2));
            return $response;
        } else {
            return new ChannelsResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /***
     * @param bool $byPassCache
     * @return ServerGroupsResponse
     * @throws TeamSpeakUnreachableException
     */
    public function getServerGroups($byPassCache = false)
    {
        if(Cache::has('teamspeak:servergroups') && !$byPassCache) {
            return Cache::get('teamspeak:servergroups');
        }

        $result = $this->request('servergrouplist');
        if($result->status->code === 0) {
            $response = new ServerGroupsResponse(TeamSpeakResponse::RESPONSE_SUCCESS,
                $result->status->message,
                $result,
                $result->body
            );

            Cache::put('teamspeak:servergroups', $response, Carbon::now()->addMinutes(15));
            return $response;
        } else {
            return new ServerGroupsResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /***
     * @param bool $byPassCache
     * @return ChannelGroupsResponse
     * @throws TeamSpeakUnreachableException
     */
    public function getChannelGroups($byPassCache = false)
    {
        if(Cache::has('teamspeak:channelgroups') && !$byPassCache) {
            return Cache::get('teamspeak:channelgroups');
        }

        $result = $this->request('channelgrouplist');
        if($result->status->code === 0) {
            $response = new ChannelGroupsResponse(TeamSpeakResponse::RESPONSE_SUCCESS,
                $result->status->message,
                $result,
                $result->body
            );

            Cache::put('teamspeak:channelgroups', $response, Carbon::now()->addMinutes(15));
            return $response;
        } else {
            return new ChannelGroupsResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /***
     * @param $clientId
     * @param $message
     * @return TeamSpeakResponse
     * @throws TeamSpeakUnreachableException
     */
    public function messageClient($clientId, $message)
    {
        $result = $this->request('sendtextmessage', ['target' => $clientId, 'targetmode' => 1, 'msg' => $message]);

        if($result->status->code === 0) {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result);
        } else {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /***
     * @param $clientId
     * @param $message
     * @return TeamSpeakResponse
     * @throws TeamSpeakUnreachableException
     */
    public function pokeClient($clientId, $message)
    {
        $result = $this->request('clientpoke', ['clid' => $clientId, 'msg' => $message]);

        if($result->status->code === 0) {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result);
        } else {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /***
     * @param $clientId
     * @param $reason
     * @return TeamSpeakResponse
     * @throws TeamSpeakUnreachableException
     */
    public function kickClient($clientId, $reason)
    {
        $result = $this->request('clientkick', ['clid' => $clientId, 'reasonid' => 5, 'reasonmsg' => $reason]);

        if($result->status->code === 0) {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result);
        } else {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /***
     * @param $clientId
     * @param $reason
     * @return TeamSpeakResponse
     * @throws TeamSpeakUnreachableException
     */
    public function kickChannelClient($clientId, $reason)
    {
        $result = $this->request('clientkick', ['clid' => $clientId, 'reasonid' => 4, 'reasonmsg' => $reason]);

        if($result->status->code === 0) {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result);
        } else {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /**
     * @param $clientId
     * @return ClientInfoResponse
     * @throws TeamSpeakUnreachableException
     */
    public function getClientInfo($clientId)
    {
        $result = $this->request('clientinfo', ['clid' => $clientId]);

        if ($result->status->code === 0 && count($result->body) === 1) {
            return new ClientInfoResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result, $result->body[0]);
        } else {
            return new ClientInfoResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /**
     * @param $databaseId
     * @return DatabaseClientResponse
     * @throws TeamSpeakUnreachableException
     */
    public function getDatabaseClient($databaseId)
    {
        $result = $this->request('clientgetnamefromdbid', ['cldbid' => $databaseId]);

        if($result->status->code === 0 && count($result->body) === 1) {
            return new DatabaseClientResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result, $result->body[0]);
        } else {
            return new DatabaseClientResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /**
     * @param $uniqueID
     * @return DatabaseClientResponse
     * @throws TeamSpeakUnreachableException
     */
    public function getDatabaseIdFromUniqueId($uniqueID)
    {
        $result = $this->request('clientgetdbidfromuid', ['cluid' => $uniqueID]);

        if($result->status->code === 0 && count($result->body) === 1) {
            return new DatabaseClientResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result, $result->body[0]);
        } else {
            return new DatabaseClientResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /**
     * @param $databaseId
     * @return DatabaseClientInfoResponse
     * @throws TeamSpeakUnreachableException
     */
    public function getDatabaseClientInfo($databaseId)
    {
        $result = $this->request('clientdbinfo', ['cldbid' => $databaseId]);

        if($result->status->code === 0 && count($result->body) === 1) {
            return new DatabaseClientInfoResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result, $result->body[0]);
        } else {
            return new DatabaseClientInfoResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /**
     * @param $clientDbId
     * @param $description
     * @return TeamSpeakResponse
     * @throws TeamSpeakUnreachableException
     */
    public function setDatabaseClientDescription($clientDbId, $description)
    {
        $result = $this->request('clientdbedit', ['cldbid' => $clientDbId, 'client_description' => $description]);

        if($result->status->code === 0) {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result);
        } else {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /**
     * @param $clientDbId
     * @param $serverGroupId
     * @return TeamSpeakResponse
     * @throws TeamSpeakUnreachableException
     */
    public function addServerGroupToClient($clientDbId, $serverGroupId)
    {
        $result = $this->request('servergroupaddclient', ['sgid' => $serverGroupId, 'cldbid' => $clientDbId]);

        if($result->status->code === 0) {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result);
        } else {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /**
     * @param $clientDbId
     * @param $serverGroupId
     * @return TeamSpeakResponse
     * @throws TeamSpeakUnreachableException
     */
    public function removeServerGroupFromClient($clientDbId, $serverGroupId)
    {
        $result = $this->request('servergroupdelclient', ['sgid' => $serverGroupId, 'cldbid' => $clientDbId]);

        if($result->status->code === 0) {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result);
        } else {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /**
     * @param $serverGroupId
     * @return ServerGroupMembersResponse
     * @throws TeamSpeakUnreachableException
     */
    public function getServerGroupMembers($serverGroupId)
    {
        $result = $this->request('servergroupclientlist', ['sgid' => $serverGroupId, '-names']);

        if($result->status->code === 0) {
            return new ServerGroupMembersResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result, $result->body);
        } else {
            return new ServerGroupMembersResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /**
     * @param $channelGroupId
     * @return ChannelGroupMembersResponse
     * @throws TeamSpeakUnreachableException
     */
    public function getChannelGroupMembers($channelGroupId)
    {
        $result = $this->request('channelgroupclientlist', ['cgid' => $channelGroupId]);

        if($result->status->code === 0) {
            return new ChannelGroupMembersResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result, $result->body);
        } else {
            return new ChannelGroupMembersResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /**
     * @param $databaseId
     * @return ChannelGroupMembersResponse
     * @throws TeamSpeakUnreachableException
     */
    public function getChannelGroupsForDatabaseClient($databaseId)
    {
        $result = $this->request('channelgroupclientlist', ['cldbid' => $databaseId]);

        if($result->status->code === 0) {
            return new ChannelGroupMembersResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result, $result->body);
        } else {
            return new ChannelGroupMembersResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /**
     * @param $databaseId
     * @return DatabaseClientServerGroupsResponse
     * @throws TeamSpeakUnreachableException
     */
    public function getServerGroupsByClientId($databaseId)
    {
        $result = $this->request('servergroupsbyclientid', ['cldbid' => $databaseId]);

        if($result->status->code === 0) {
            return new DatabaseClientServerGroupsResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result, $result->body);
        } else {
            return new DatabaseClientServerGroupsResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /**
     * @param $databaseId
     * @return TeamSpeakResponse
     * @throws TeamSpeakUnreachableException
     */
    public function setDatabaseClientChannelGroup($databaseId, $channelId, $channelGroupid)
    {
        $result = $this->request('setclientchannelgroup', ['cldbid' => $databaseId, 'cid' => $channelId, 'cgid' => $channelGroupid]);

        if($result->status->code === 0) {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result);
        } else {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /**
     * @param $banId
     * @return TeamSpeakResponse
     * @throws TeamSpeakUnreachableException
     */
    public function removeBan($banId)
    {
        $result = $this->request('bandel', ['banid' => $banId]);

        if($result->status->code === 0) {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result);
        } else {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }

    /**
     * @param $uniqueId
     * @param $reason
     * @param int $duration
     * @return TeamSpeakResponse
     * @throws TeamSpeakUnreachableException
     */
    public function addBan($uniqueId, $reason, $duration = 0)
    {
        $result = $this->request('banadd', ['uid' => $uniqueId, 'banreason' => $reason, 'time' => $duration]);

        if($result->status->code === 0) {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_SUCCESS, $result->status->message, $result);
        } else {
            return new TeamSpeakResponse(TeamSpeakResponse::RESPONSE_FAILED, $result->status->message, $result);
        }
    }
}
