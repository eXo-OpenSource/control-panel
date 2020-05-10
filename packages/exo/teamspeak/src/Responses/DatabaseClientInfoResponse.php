<?php

namespace Exo\TeamSpeak\Responses;

use Exo\TeamSpeak\Helpers\DatabaseClient;
use Exo\TeamSpeak\Helpers\DatabaseClientInfo;

class DatabaseClientInfoResponse extends TeamSpeakResponse
{
    /** @var DatabaseClientInfo */
    public $info = [];

    public function __construct($status, $message, $originalResponse, $info = null)
    {
        parent::__construct($status, $message, $originalResponse);

        if($info) {
            if($info instanceof DatabaseClientInfo) {
                $this->info = $info;
            } else {
                $this->info = new DatabaseClientInfo($info);
            }
        }
    }
}
