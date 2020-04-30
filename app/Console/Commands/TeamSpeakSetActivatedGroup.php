<?php

namespace App\Console\Commands;

use App\Models\TeamspeakIdentity;
use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Services\TeamSpeakService;
use Illuminate\Console\Command;

class TeamSpeakSetActivatedGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teamspeak:set-activated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $teamSpeak;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TeamSpeakService $teamSpeak)
    {
        parent::__construct();
        $this->teamSpeak = $teamSpeak;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $identities = TeamspeakIdentity::query()->where('Type', 1)->get();

        foreach($identities as $identity) {
            try {
                $client = $this->teamSpeak->getDatabaseClient($identity->TeamspeakDbId);
                $response = $client->client->serverGroups();
                $groups = [];

                foreach($response->serverGroups as $group) {
                    array_push($groups, $group->serverGroupId);
                }

                if(in_array(env('TEAMSPEAK_OLD_ACTIVATED_GROUP'), $groups)) {
                    $client->client->removeServerGroup(env('TEAMSPEAK_OLD_ACTIVATED_GROUP'));
                }

                if(!in_array(env('TEAMSPEAK_ACTIVATED_GROUP'), $groups)) {
                    $client->client->addServerGroup(env('TEAMSPEAK_ACTIVATED_GROUP'));
                }
            } catch (TeamSpeakUnreachableException $e) {
            }
        }
    }
}
