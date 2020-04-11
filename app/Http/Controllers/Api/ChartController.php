<?php


namespace App\Http\Controllers\Api;


use App\Models\AchievementFever;
use App\Models\Company;
use App\Models\Faction;
use App\Models\Group;
use App\Http\Controllers\Controller;
use App\Services\StatisticService;
use App\Models\User;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function show($name)
    {
        $from = Carbon::now()->subDays(13);
        $to = Carbon::now();


        $parts = explode(':', $name);


        if($parts[0] === 'activity') {
            switch($parts[1]) {
                case 'factions':
                    return StatisticService::getFactionsActivity($from, $to);
                    break;
                case 'companies':
                    return StatisticService::getCompaniesActivity($from, $to);
                    break;
                case 'faction':
                    $faction = Faction::find($parts[2]);

                    abort_unless(auth()->user()->can('activityTotal', $faction), 403);

                    return StatisticService::getFactionActivity($faction, $from, $to);
                    break;
                case 'company':
                    $company = Company::find($parts[2]);

                    abort_unless(auth()->user()->can('activityTotal', $company), 403);

                    return StatisticService::getCompanyActivity($company, $from, $to);
                    break;
                case 'group':
                    $group = Group::find($parts[2]);

                    abort_unless(auth()->user()->can('activityTotal', $group), 403);

                    return StatisticService::getGroupActivity($group, $from, $to);
                    break;
                case 'user':
                    $user = User::find($parts[2]);

                    abort_unless(auth()->user()->can('activity', $user), 403);

                    return StatisticService::getUserActivity($user, $from, $to);
                    break;
            }
        }
        elseif($parts[0] === 'money')
        {
            switch($parts[1]) {
                case 'overall':
                    abort_unless(auth()->user()->Rank >= 3, 403);

                    return StatisticService::getMoneyAdmin($from, $to);
                    break;
                case 'faction':
                    $faction = Faction::find($parts[2]);

                    abort_unless(auth()->user()->can('bank', $faction), 403);

                    return StatisticService::getMoney($faction, $from, $to);
                    break;
                case 'company':
                    $company = Company::find($parts[2]);

                    abort_unless(auth()->user()->can('bank', $company), 403);

                    return StatisticService::getMoney($company, $from, $to);
                    break;
                case 'group':
                    $group = Group::find($parts[2]);

                    abort_unless(auth()->user()->can('bank', $group), 403);

                    return StatisticService::getMoney($group, $from, $to);
                    break;
                case 'user':
                    $user = User::find($parts[2]);

                    abort_unless(auth()->user()->can('bank', $user), 403);

                    return StatisticService::getMoney($user, $from, $to);
                    break;
            }
        }
        elseif($parts[0] === 'online')
        {
            switch ($parts[1]) {
                case 'total':
                    return StatisticService::getTotalOnline($from, $to);
                    break;
                case 'statevsevil':
                    return StatisticService::getStateVsEvilOnline($from, $to);
                    break;
                case 'statevsevilrelative':
                    return StatisticService::getStateVsEvilRelativeOnline($from, $to);
                    break;
            }
        }

        return ['status' => 'Error'];
    }
}
