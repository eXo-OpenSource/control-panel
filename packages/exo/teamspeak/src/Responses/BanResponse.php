<?php

namespace Exo\TeamSpeak\Responses;

use Exo\TeamSpeak\Helpers\Ban;

class BanResponse extends TeamSpeakResponse
{
    /** @var Ban */
    public $ban = [];

    public function __construct($status, $message, $originalResponse, $ban = null)
    {
        parent::__construct($status, $message, $originalResponse);

        if($ban) {
            if($ban instanceof Ban) {
                $this->ban = $ban;
            } else {
                $this->ban = new Ban($ban);
            }
        }
    }
}
