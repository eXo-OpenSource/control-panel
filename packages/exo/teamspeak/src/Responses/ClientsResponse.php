<?php

namespace Exo\TeamSpeak\Responses;

use Exo\TeamSpeak\Helpers\Client;

class ClientsResponse extends TeamSpeakResponse
{
    /** @var Client[] */
    public $clients = [];

    public function __construct($status, $message, $originalResponse, $clients = null)
    {
        parent::__construct($status, $message, $originalResponse);

        if($clients) {
            foreach($clients as $client) {
                array_push($this->clients, new Client($client));
            }
        }
    }
}
