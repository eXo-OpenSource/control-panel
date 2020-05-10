<?php

namespace App\Jobs;

use App\Models\TeamSpeakIdentity;
use Carbon\Carbon;
use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;
use Exo\TeamSpeak\Services\TeamSpeakService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class TeamSpeakOldActivationNotification implements ShouldQueue
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
     * @return void
     */
    public function handle(TeamSpeakService $teamSpeak)
    {
        try {
            $clients = $teamSpeak->getClients(true);

            if($clients->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
                foreach($clients->clients as $client) {
                    if(in_array(intval(env('TEAMSPEAK_OLD_ACTIVATED_GROUP')), $client->serverGroups)) {
                        if(Cache::has('teamspeak:old-group:' . $client->databaseId)) {
                            continue;
                        }

                        Cache::put('teamspeak:old-group:' . $client->databaseId, true, Carbon::now()->addMinutes(15));

                        $client->message(
                            PHP_EOL .
                            'Bitte schreib im Forum eine neue Freischaltung für deine TeamSpeak Identität.'. PHP_EOL .
                            'Deine Eindeutige ID ist: ' . $client->uniqueId . PHP_EOL .
                            'https://forum.exo-reallife.de/board/122-teamspeak-freischaltungen/' . PHP_EOL . PHP_EOL .
                            'Ab 08.01.2020 ist die alte Freischaltung nicht mehr gültig.'
                        );
                    }
                }
            }
        } catch (TeamSpeakUnreachableException $e) {
        }
    }
}
