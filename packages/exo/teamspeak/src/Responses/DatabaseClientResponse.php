<?php

namespace Exo\TeamSpeak\Responses;

use Exo\TeamSpeak\Helpers\DatabaseClient;

class DatabaseClientResponse extends TeamSpeakResponse
{
    /** @var DatabaseClient */
    public $client = [];

    public function __construct($status, $message, $originalResponse, $client = null)
    {
        parent::__construct($status, $message, $originalResponse);

        if($client) {
            if($client instanceof DatabaseClient) {
                $this->client = $client;
            } else {
                $this->client = new DatabaseClient($client);
            }
        }
    }
}
