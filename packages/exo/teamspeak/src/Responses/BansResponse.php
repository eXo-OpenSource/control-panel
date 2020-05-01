<?php

namespace Exo\TeamSpeak\Responses;

use Exo\TeamSpeak\Helpers\Ban;

class BansResponse extends TeamSpeakResponse
{
    /** @var Ban[] */
    public $bans = [];

    public function __construct($status, $message, $originalResponse, $bans = null)
    {
        parent::__construct($status, $message, $originalResponse);

        if($bans) {
            foreach($bans as $ban) {
                if($ban instanceof Ban) {
                    array_push($this->bans, $ban);
                } else {
                    array_push($this->bans, new Ban($ban));
                }
            }
        }
    }
}
