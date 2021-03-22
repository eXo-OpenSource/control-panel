<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\Company;
use App\Models\Faction;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        /** @var Character $character */
        $character = auth()->user()->character;
        $types = [];

        if($character->FactionId <> 0 && $character->FactionRank >= 5) {
            array_push($types, ['type' => 'faction', 'name' => __('Fraktion')]);
        }

        if($character->CompanyId <> 0 && $character->CompanyRank >= 4) {
            array_push($types, ['type' => 'company', 'name' => __('Unternehmen')]);
        }

        if(count($types) === 0) {
            abort(403);
        }

        if(count($types) === 1) {
            return redirect()->route('trainings.permissions.edit', [$types[0]['type']]);
        }

        return view('trainings.permissions.index', compact('types'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $permission
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($permission)
    {
        /** @var Character $character */
        $character = auth()->user()->character;
        $members = [];

        if($permission === 'faction') {
            if($character->FactionId <> 0 && $character->FactionRank >= 5) {
                foreach(Faction::find($character->FactionId)->members as $member) {
                    if($member->FactionRank < 5) {
                        array_push($members, [
                            'UserId' => $member->Id,
                            'Name' => $member->user->Name,
                            'Rank' => $member->FactionRank,
                            'Permission' => $member->FactionTraining,
                        ]);
                    }
                }
            } else {
                abort(403);
            }
        } elseif($permission === 'company') {
            if($character->CompanyId <> 0 && $character->CompanyRank >= 4) {
                foreach(Company::find($character->CompanyId)->members as $member) {
                    if($member->CompanyRank < 4) {
                        array_push($members, [
                            'UserId' => $member->Id,
                            'Name' => $member->user->Name,
                            'Rank' => $member->CompanyRank,
                            'Permission' => $member->CompanyTraining,
                        ]);
                    }
                }
            } else {
                abort(403);
            }
        }

        usort($members, function($a, $b) {
            return $a['Rank'] < $b['Rank'] ? -1 : 1;
        });

        return view('trainings.permissions.edit', ['members' => $members, 'type' => $permission]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $permission)
    {
        /** @var Character $character */
        $character = auth()->user()->character;

        if($permission === 'faction') {
            if($character->FactionId <> 0 && $character->FactionRank >= 5) {
                foreach(Faction::find($character->FactionId)->members as $member) {
                    if($member->FactionRank < 5) {
                        foreach($request->get('permission') as $userId => $training) {
                            if($member->Id === $userId) {
                                $member->FactionTraining = (int)$training;
                                $member->save();
                            }
                        }
                    }
                }
            } else {
                abort(403);
            }
        } elseif($permission === 'company') {
            if($character->CompanyId <> 0 && $character->CompanyRank >= 4) {
                foreach(Company::find($character->CompanyId)->members as $member) {
                    if($member->CompanyRank < 4) {
                        foreach($request->get('permission') as $userId => $training) {
                            if($member->Id === $userId) {
                                $member->CompanyTraining = (int)$training;
                                $member->save();
                            }
                        }
                    }
                }
            } else {
                abort(403);
            }
        }

        return redirect()->route('trainings.permissions.edit', [$permission]);
    }
}
