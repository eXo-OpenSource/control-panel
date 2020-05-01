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

class TeamSpeakCheckNames implements ShouldQueue
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
                    if(in_array(intval(env('TEAMSPEAK_ACTIVATED_GROUP')), $client->serverGroups)) {
                        array_push($uniqueIds, $client->uniqueId);
                    }
                }

                $identities = TeamSpeakIdentity::query()->whereIn('TeamspeakId', $uniqueIds)->with('user')->get();

                foreach($clients->clients as $client) {
                    if(in_array(intval(env('TEAMSPEAK_ACTIVATED_GROUP')), $client->serverGroups)) {
                        foreach($identities as $identity) {
                            if($client->uniqueId === $identity->TeamspeakId) {
                                if(substr(strtolower($client->nickname), 0, strlen($identity->user->Name))
                                    !== strtolower($identity->user->Name)) {
                                    $count = intval(Cache::get('teamspeak:names:' . $identity->UserId, 0));

                                    $message = 'Bitte ändere deinen Namen im TeamSpeak auf ' . $identity->user->Name . '! (' . ($count + 1) . '/5)';
                                    $suffix = PHP_EOL . 'Mehr Informationen im Regelwerk: https://forum.exo-reallife.de/thread/22539-serverregeln/?postID=212594#post212594';
                                    if($count === 0) {
                                        $client->message('Mir ist aufgefallen, dass dein Name im TeamSpeak nicht mit dem Namen im Spiel übereinstimmt. ' . $message . $suffix);
                                        Cache::put('teamspeak:names:' . $identity->UserId, $count + 1, Carbon::now()->addMinutes(15));
                                    } elseif($count === 1) {
                                        $client->message('Leider hast du deinen Namen immer noch nicht korrigiert. ' . $message . $suffix);
                                        Cache::put('teamspeak:names:' . $identity->UserId, $count + 1, Carbon::now()->addMinutes(15));
                                    } elseif($count === 2) {
                                        $client->message('Du hast deinen Name immer noch nicht korrigiert. Falls du den Namen nicht änderst, wirst du vom Server gekickt. ' . $message . $suffix);
                                        Cache::put('teamspeak:names:' . $identity->UserId, $count + 1, Carbon::now()->addMinutes(15));
                                    } elseif($count === 3) {
                                        $client->message('Falls du deinen Namen nicht änderst, wirst du vom Server gekickt! ' . $message . $suffix);
                                        Cache::put('teamspeak:names:' . $identity->UserId, $count + 1, Carbon::now()->addMinutes(15));
                                    } elseif($count === 4) {
                                        $client->kick($message);
                                        Cache::put('teamspeak:names:' . $identity->UserId, $count, Carbon::now()->addMinutes(15));
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
