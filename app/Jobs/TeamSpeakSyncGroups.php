<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Character;
use Illuminate\Bus\Queueable;
use App\Models\Shop\PremiumUser;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exo\TeamSpeak\Services\TeamSpeakService;
use Exo\TeamSpeak\Exceptions\TeamSpeakUnreachableException;

class TeamSpeakSyncGroups implements ShouldQueue
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
        $characters = Character::query()
            ->where('FactionId', '<>', 0)
            ->orWhere('CompanyId', '<>', 0)
            ->with('teamSpeakIdentities')
            ->get();

        // dd($characters[0]);
        $serverGroups = config('tsgroups.serverGroups');

        try {
            foreach($serverGroups['factions'] as $factionId => $group)
            {
                if($group) {
                    $addDatabaseId = [];
                    $removeDatabaseId = [];
                    $shouldDatabaseId = [];


                    foreach($characters as $character) {
                        if($character->FactionId === $factionId) {
                            foreach($character->teamSpeakIdentities as $identity) {
                                if($identity->Type === 1 && $identity->TeamspeakDbId !== null) {
                                    array_push($shouldDatabaseId, $identity->TeamspeakDbId);
                                    array_push($addDatabaseId, $identity->TeamspeakDbId);
                                }
                            }
                        }
                    }

                    $clients = $teamSpeak->getServerGroupMembers($group)->members;

                    foreach($clients as $client) {
                        if(!in_array($client->databaseId, $shouldDatabaseId)) {
                            array_push($removeDatabaseId, $client->databaseId);
                            if (($key = array_search($client->databaseId, $addDatabaseId)) !== false) {
                                unset($addDatabaseId[$key]);
                            }
                        }
                    }

                    foreach($removeDatabaseId as $databaseId) {
                        $teamSpeak->removeServerGroupFromClient($databaseId, $group);
                    }

                    foreach($addDatabaseId as $databaseId) {
                        $teamSpeak->addServerGroupToClient($databaseId, $group);
                    }
                }
            }


            foreach($serverGroups['companies'] as $companyId => $group)
            {
                if($group) {
                    $addDatabaseId = [];
                    $removeDatabaseId = [];
                    $shouldDatabaseId = [];


                    foreach($characters as $character) {
                        if($character->CompanyId === $companyId) {
                            foreach($character->teamSpeakIdentities as $identity) {
                                if($identity->Type === 1) {
                                    array_push($shouldDatabaseId, $identity->TeamspeakDbId);
                                    array_push($addDatabaseId, $identity->TeamspeakDbId);
                                }
                            }
                        }
                    }

                    $clients = $teamSpeak->getServerGroupMembers($group)->members;

                    foreach($clients as $client) {
                        if(!in_array($client->databaseId, $shouldDatabaseId)) {
                            array_push($removeDatabaseId, $client->databaseId);
                            if (($key = array_search($client->databaseId, $addDatabaseId)) !== false) {
                                unset($addDatabaseId[$key]);
                            }
                        }
                    }

                    foreach($removeDatabaseId as $databaseId) {
                        $teamSpeak->removeServerGroupFromClient($databaseId, $group);
                    }

                    foreach($addDatabaseId as $databaseId) {
                        $teamSpeak->addServerGroupToClient($databaseId, $group);
                    }
                }
            }

            $users = PremiumUser::query()->where('premium_bis', '>', Carbon::now()->timestamp)->with('teamSpeakIdentities')->get();

            $addDatabaseId = [];
            $removeDatabaseId = [];
            $shouldDatabaseId = [];

            foreach($users as $user) {
                foreach($user->teamSpeakIdentities as $identity) {
                    if($identity->Type === 1) {
                        array_push($shouldDatabaseId, $identity->TeamspeakDbId);
                        array_push($addDatabaseId, $identity->TeamspeakDbId);
                    }
                }
            }


            $clients = $teamSpeak->getServerGroupMembers($serverGroups['premium'])->members;

            foreach($clients as $client) {
                if(!in_array($client->databaseId, $shouldDatabaseId)) {
                    array_push($removeDatabaseId, $client->databaseId);
                    if (($key = array_search($client->databaseId, $addDatabaseId)) !== false) {
                        unset($addDatabaseId[$key]);
                    }
                }
            }

            foreach($removeDatabaseId as $databaseId) {
                $teamSpeak->removeServerGroupFromClient($databaseId, $serverGroups['premium']);
            }

            foreach($addDatabaseId as $databaseId) {
                $teamSpeak->addServerGroupToClient($databaseId, $serverGroups['premium']);
            }

        } catch (TeamSpeakUnreachableException $e) {
        }
    }
}
