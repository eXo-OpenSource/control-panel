<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class TeamSpeakService
{
    /** var Client $client **/
    private $client;

    /** var string $baseUri **/
    private $baseUri;

    /** var string $secret **/
    private $secret;

    private $failedToConnectError;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUri = env('TEAMSPEAK_URI') . '/' . env('TEAMSPEAK_SERVER') . '/';
        $this->secret = env('TEAMSPEAK_SECRET');

        $this->failedToConnectError = (object)[
            'status' => 'Error',
            'message' => 'Failed to connect',
            'message_user' => 'Kommunikation mit dem TeamSpeak Server ist derzeit nicht möglich!',
        ];
    }

    private function request($function, $query = null)
    {
        try {
            $result = $this->client->get($this->baseUri . $function, [
                'headers' => [
                    'x-api-key' => $this->secret
                ],
                'query' => $query
            ]);

            return \GuzzleHttp\json_decode($result->getBody()->getContents());
        }
        catch (GuzzleException $exception)
        {
            return false;
        }
    }

    public function getClientClientDbIdFromUniqueId($uniqueID)
    {
        $result = $this->request('clientgetdbidfromuid', ['cluid' => $uniqueID]);

        if($result === false) {
            return $this->failedToConnectError;
        }

        if($result->status->code === 0 && count($result->body) === 1) {
            return (object)[
                'status' => 'Success',
                'clientDbId' => intval($result->body[0]->cldbid),
            ];
        } else {
            return (object)[
                'status' => 'Error',
                'message' => 'Unknown uniqueId',
                'message_user' => 'Die eindeutige ID ist dem Server unbekannt!',
            ];
        }
    }

    public function addServerGroupToClient($serverGroupId, $clientDbId)
    {
        $result = $this->request('servergroupaddclient', ['sgid' => $serverGroupId, 'cldbid' => $clientDbId]);

        if($result === false) {
            return $this->failedToConnectError;
        }

        if($result->status->code === 0) {
            return (object)[
                'status' => 'Success'
            ];
        } else {
            if($result->status->message === 'duplicate entry') {
                return (object)[
                    'status' => 'Error',
                    'message' => 'Duplicate entry',
                    'message_user' => 'Der Benutzer hat die Gruppe bereits!',
                    'message_ts' => $result->status->message,
                ];
            }

            return (object)[
                'status' => 'Error',
                'message' => 'Unknown error',
                'message_user' => 'Unbekannter Fehler',
                'message_ts' => $result->status->message,
            ];
        }
    }

    public function removeServerGroupFromClient($serverGroupId, $clientDbId)
    {
        $result = $this->request('servergroupdelclient', ['sgid' => $serverGroupId, 'cldbid' => $clientDbId]);

        if($result === false) {
            return $this->failedToConnectError;
        }

        if($result->status->code === 0) {
            return (object)[
                'status' => 'Success'
            ];
        } else {
            if($result->status->message === 'empty result set') {
                return (object)[
                    'status' => 'Error',
                    'message' => 'Empty result set',
                    'message_user' => 'Der Benutzer hat die Gruppe nicht!',
                    'message_ts' => $result->status->message,
                ];
            }

            return (object)[
                'status' => 'Error',
                'message' => 'Unknown error',
                'message_user' => 'Unbekannter Fehler',
                'message_ts' => $result->status->message,
            ];
        }
    }
}
