<?php

namespace App\Jobs;

use App\Models\TeamSpeakIdentity;
use Carbon\Carbon;
use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exo\TeamSpeak\Services\TeamSpeakService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TeamSpeakNotifyNotActivatedUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {
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
                    if($client->type === 0 && !in_array(intval(env('TEAMSPEAK_ACTIVATED_GROUP')), $client->serverGroups) && !in_array(intval(env('TEAMSPEAK_MUSICBOT_GROUP')), $client->serverGroups)) {
                        if(!Cache::has('teamspeak:not-activated:' . $client->uniqueId)) {
                            Cache::put('teamspeak:not-activated:' . $client->uniqueId, Carbon::now()->addMinutes(15));
                            $client->message(
                                PHP_EOL .
                                'Bitte schreib im Control Panel eine Freischaltung für deine TeamSpeak Identität.'. PHP_EOL .
                                'Deine Eindeutige ID ist: ' . $client->uniqueId . PHP_EOL .
                                'https://cp.exo-reallife.de/tickets/create?category=3&fields_7=' . $client->uniqueId . PHP_EOL
                            );
                        }
                    }
                }
            }
        } catch (TeamSpeakUnreachableException $e) {
        }
    }
}
