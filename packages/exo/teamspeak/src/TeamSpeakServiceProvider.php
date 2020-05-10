<?php

namespace Exo\TeamSpeak;

use Exo\TeamSpeak\Services\TeamSpeakService;
use Illuminate\Support\ServiceProvider;

class TeamSpeakServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/teamspeak.php' => config_path('teamspeak.php'),
        ], 'teamspeak');

    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/teamspeak.php', 'teamspeak');

        $this->app->singleton(TeamSpeakService::class, function($app) {

            return new TeamSpeakService(config('teamspeak.uri'), config('teamspeak.secret'), config('teamspeak.server'));
        });

        $this->app->bind('teamspeak', TeamSpeakService::class);
    }
}
