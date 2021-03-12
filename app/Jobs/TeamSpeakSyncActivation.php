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

class TeamSpeakSyncActivation implements ShouldQueue
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
                $uniqueIds = [];

                foreach($clients->clients as $client) {
                    if(!in_array(intval(env('TEAMSPEAK_ACTIVATED_GROUP')), $client->serverGroups) && !in_array(intval(env('TEAMSPEAK_MUSICBOT_GROUP')), $client->serverGroups)) {
                        array_push($uniqueIds, $client->uniqueId);
                    }
                }

                $identities = TeamSpeakIdentity::query()->whereIn('TeamspeakId', $uniqueIds)->with('user')->get();

                foreach($clients->clients as $client) {
                    if(!in_array(intval(env('TEAMSPEAK_ACTIVATED_GROUP')), $client->serverGroups) && !in_array(intval(env('TEAMSPEAK_MUSICBOT_GROUP')), $client->serverGroups)) {
                        foreach($identities as $identity) {
                            if($client->uniqueId === $identity->TeamspeakId) {

                                $result = $client->addServerGroup($identity->Type === 1 ? env('TEAMSPEAK_ACTIVATED_GROUP') : env('TEAMSPEAK_MUSICBOT_GROUP'));
                                $client->setDescription(route('users.show', $identity->user->Id));

                                if($result->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
                                    $banDuration = -1;
                                    $banReason = '';
                
                                    $bans = TeamSpeakBan::query()->where('UserId', $identity->user->Id)->get();
                                    foreach($bans as $ban) {
                                        if($ban->Duration === 0) {
                                            $banDuration = 0;
                                            $banReason = $ban->Reason;
                                            break;
                                        }
                
                                        if($ban->ValidUntil < Carbon::now()) {
                                            $ban->delete();
                                        } else {
                                            $duration = $ban->ValidUntil->diffInSeconds(Carbon::now());
                                            if($banDuration < $duration) {
                                                $banDuration = $duration;
                                                $banReason = $ban->Reason;
                                            }
                                        }
                                    }
                                    
                                    if($banDuration >= 0) {
                                        $client->ban($banReason, $banDuration);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (TeamSpeakUnreachableException $e) {
        }
    }
}
