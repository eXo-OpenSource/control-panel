<?php

namespace Exo\TeamSpeak\Responses;

use Exo\TeamSpeak\Helpers\Client;

class ClientResponse extends TeamSpeakResponse
{
    /** @var Client */
    public $client = [];

    public function __construct($status, $message, $originalResponse, $client = null)
    {
        parent::__construct($status, $message, $originalResponse);

        if($client) {
            if($client instanceof Client) {
                $this->client = $client;
            } else {
                $this->client = new Client($client);
            }
        }
    }
}
