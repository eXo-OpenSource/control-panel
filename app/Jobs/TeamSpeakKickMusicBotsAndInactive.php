<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Character;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;
use Illuminate\Bus\Queueable;
use App\Models\Shop\PremiumUser;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exo\TeamSpeak\Services\TeamSpeakService;
use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;

class TeamSpeakKickMusicBotsAndInactive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @param TeamSpeakService $teamSpeak
     * @return void
     */
    public function handle(TeamSpeakService $teamSpeak)
    {
        try {
            $clients = $teamSpeak->getClients(true);

            if($clients->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
                foreach($clients->clients as $client) {
                    if(in_array(intval(env('TEAMSPEAK_MUSICBOT_GROUP')), $client->serverGroups)) {
                        $client->kick('Slots...');
                    } elseif($client->type === 0) {
                        if($client->idleTime / 1000 > 15 * 60) {
                            $client->kick('Slots...');
                        }
                    }
                }
            }
        } catch (TeamSpeakUnreachableException $e) {
        }
    }
}
