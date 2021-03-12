<?php

namespace App\Jobs;

use App\Models\TeamSpeakIdentity;
use App\Models\TeamSpeakBan;
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

class TeamSpeakUpdateId implements ShouldQueue
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
            $clients = $teamSpeak->getClients();                


            if($clients->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
                $identities = TeamSpeakIdentity::query()->where('TeamspeakDbId', null)->get();
                
                foreach($identities as $identity) {
                    $teamspeakDbId = null;

                    foreach($clients as $client)
                    {
                        if ($client->uniqueId === $identity->TeamspeakId) {
                            $teamspeakDbId = $client->databaseId;
                            break;
                        }
                    }

                    if ($teamspeakDbId !== null) {
                        $identity->TeamspeakDbId = $teamspeakDbId;
                        $identity->save();
                    }
                }

            }
        } catch (TeamSpeakUnreachableException $e) {
            Log::error($e);
        }
    }
}
