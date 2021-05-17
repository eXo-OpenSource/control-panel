<?php

namespace App\Jobs;

use App\Models\TeamSpeakIdentity;
use App\Models\TeamSpeakBan;
use App\Services\MTAService;
use Carbon\Carbon;
use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;
use Exo\TeamSpeak\Helpers\Client;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exo\TeamSpeak\Services\TeamSpeakService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TeamSpeakSupportNotification implements ShouldQueue
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
            $adminIds = explode(',', env('TEAMSPEAK_SUPPORT_GROUPS'));

            if($clients->status === TeamSpeakResponse::RESPONSE_SUCCESS) {
                $admins = [];
                /** @var Client[] $clientsInSupport */
                $clientsInSupport = [];
                $clientsInSupportIds = [];

                foreach($clients->clients as $client) {
                    $isAdmin = false;

                    foreach($adminIds as $adminId) {
                        if (in_array(intval($adminId), $client->serverGroups)) {
                            $isAdmin = true;
                            if (!in_array(intval(env('TEAMSPEAK_SUPPORT_IGNORE')), $client->serverGroups)) {
                                array_push($admins, $client);
                            }
                            break;
                        }
                    }

                    if (!$isAdmin) {
                        if ($client->channelId === intval(env('TEAMSPEAK_SUPPORT_CHANNEL'))) {
                            array_push($clientsInSupport, $client);
                            array_push($clientsInSupportIds, $client->databaseId);
                        }
                    }
                }

                $support = Cache::get('teamspeak:support');

                if (!$support) {
                    $support = [];
                }

                foreach($support as $databaseId => $client) {
                    if (!in_array($databaseId, $clientsInSupportIds)) {
                        unset($support[$databaseId]);
                    }
                }

                foreach($clientsInSupport as $client) {

                    if (isset($support[$client->databaseId])) {
                        if (Carbon::parse($support[$client->databaseId]['lastNotification']) < Carbon::now()->subMinutes(5)) {
                            $support[$client->databaseId]['lastNotification'] = Carbon::now();
                            $duration = Carbon::parse($support[$client->databaseId]['supportSince'])->longAbsoluteDiffForHumans(Carbon::now());


                            $lines = [
                                __(':name wartet schon seit :duration im Support!', ['name' => $support[$client->databaseId]['name'], 'duration' => $duration]),
                                'Ticket: https://cp.exo-reallife.de/tickets/create?category=' . env('TEAMSPEAK_SUPPORT_TICKET_CATEGORY') . '&createFor=' . $support[$client->databaseId]['id'],
                                'User: [URL=client://' . $client->id . '/' . $client->uniqueId . '~' . str_replace(' ', '%20', $client->nickname) .']' . $support[$client->databaseId]['name'] .'[/URL]',
                                'CP: https://cp.exo-reallife.de/users/' . $support[$client->databaseId]['id'],
                            ];

                            foreach($admins as $admin) {
                                foreach($lines as $line) {
                                    $admin->message($line);
                                }
                            }

                            $mtaService = new MTAService();
                            $mtaService->sendMessage('admin', null, __('[TEAMSPEAK] :name wartet schon seit :duration im Support!', ['name' => $support[$client->databaseId]['name'], 'duration' => $duration]), ['r' => 255, 'g' => 50, 'b' => 0, 'minRank' => 1]);
                        }
                    } else {
                        $identity = TeamSpeakIdentity::query()->where('TeamspeakId', $client->uniqueId)->with('user')->first();

                        if ($identity) {
                            $support[$client->databaseId] = [
                                'lastNotification' => Carbon::now(),
                                'supportSince' => Carbon::now(),
                                'name' => $identity->user->Name,
                                'id' => $identity->user->Id
                            ];
                        } else {
                            $support[$client->databaseId] = [
                                'lastNotification' => Carbon::now(),
                                'supportSince' => Carbon::now(),
                                'name' => $client->nickname,
                                'id' => -1
                            ];
                        }

                        $lines = [
                            __(':name wartet im Support!', ['name' => $support[$client->databaseId]['name']]),
                            'Ticket: https://cp.exo-reallife.de/tickets/create?category=' . env('TEAMSPEAK_SUPPORT_TICKET_CATEGORY') . '&createFor=' . $support[$client->databaseId]['id'],
                            'User: [URL=client://' . $client->id . '/' . $client->uniqueId . '~' . $client->nickname .']' . $support[$client->databaseId]['name'] .'[/URL]',
                            'CP: https://cp.exo-reallife.de/users/' . $support[$client->databaseId]['id'],
                        ];

                        foreach($admins as $admin) {
                            foreach($lines as $line) {
                                $admin->message($line);
                            }
                        }
                        $mtaService = new MTAService();
                        $mtaService->sendMessage('admin', null, __('[TEAMSPEAK] :name wartet im Support!', ['name' => $support[$client->databaseId]['name']]), ['r' => 255, 'g' => 50, 'b' => 0, 'minRank' => 1]);

                        $client->message(__('Ein Teammitglied wurde verständigt und wird sich in Kürze bei dir melden. Alternativ kannst du auch ein Ticket erstellen. https://cp.exo-reallife.de/tickets/create'));
                    }
                }

                Cache::put('teamspeak:support', $support);
            }
        } catch (TeamSpeakUnreachableException $e) {
            dump($e);
            Log::error($e);
        }
    }
}
