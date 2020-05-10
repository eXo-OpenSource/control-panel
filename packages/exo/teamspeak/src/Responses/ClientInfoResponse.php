<?php

namespace Exo\TeamSpeak\Responses;

use Exo\TeamSpeak\Helpers\ClientInfo;
use Exo\TeamSpeak\Helpers\DatabaseClientInfo;

class ClientInfoResponse extends TeamSpeakResponse
{
    /** @var ClientInfo */
    public $info = [];

    public function __construct($status, $message, $originalResponse, $info = null)
    {
        parent::__construct($status, $message, $originalResponse);

        if($info) {
            if($info instanceof ClientInfo) {
                $this->info = $info;
            } else {
                $this->info = new ClientInfo($info);
            }
        }
    }
}
