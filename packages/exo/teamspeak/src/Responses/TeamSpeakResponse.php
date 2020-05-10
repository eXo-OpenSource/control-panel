<?php

namespace Exo\TeamSpeak\Responses;

class TeamSpeakResponse
{
    public const RESPONSE_SUCCESS = 'Success';
    public const RESPONSE_FAILED = 'Failed';

    /** @var string */
    public $status;

    /** @var string */
    public $message;

    /** @var object */
    public $originalResponse;

    public function __construct($status, $message, $originalResponse)
    {
        $this->status = $status;
        $this->message = $message;
        $this->originalResponse = $originalResponse;
    }
}
