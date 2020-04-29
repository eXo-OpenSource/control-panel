<?php


namespace Exo\TeamSpeak;

use Illuminate\Support\Facades\Facade;

class TeamSpeakFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'teamspeak';
    }
}
